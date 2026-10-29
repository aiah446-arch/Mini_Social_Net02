<?php
// app/controllers/ProfileController.php
// Handles profile viewing and editing

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/NotificationModel.php';

class ProfileController {
    private $userModel;
    private $postModel;
    private $notifModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->userModel = new UserModel($db);
        $this->postModel = new PostModel($db);
        $this->notifModel = new NotificationModel($db);
    }

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    public function index() {
        $this->requireLogin();
        $id = (int) ($_GET['id'] ?? $_SESSION['user_id']);
        $user = $this->userModel->findById($id);

        if (!$user) {
            header('Location: index.php?page=feed');
            exit;
        }

        $posts = $this->postModel->attachImages($this->postModel->getByUser($id));
        $isOwnProfile = ($id == $_SESSION['user_id']);
        
        $followerCount = $this->userModel->getFollowerCount($id);
        $followingCount = $this->userModel->getFollowingCount($id);
        $followersList = $this->userModel->getFollowers($id);
        $followingList = $this->userModel->getFollowing($id);
        $isFollowing = !$isOwnProfile ? $this->userModel->isFollowing($_SESSION['user_id'], $id) : false;

        $pageTitle = htmlspecialchars($user['full_name']) . ' - Mini Social';
        require __DIR__ . '/../views/profile/index.php';
    }

    public function edit() {
        $this->requireLogin();
        $user = $this->userModel->findById($_SESSION['user_id']);

        if (!$user) {
            header('Location: index.php?page=feed');
            exit;
        }

        $errors = [];
        $pwErrors = [];
        $pwSuccess = false;

        // CHANGE PASSWORD (separate form on the same page)
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'change_password') {
            $current = $_POST['current_password'] ?? '';
            $new     = $_POST['new_password'] ?? '';
            $confirm = $_POST['confirm_password'] ?? '';

            if ($current === '' || $new === '' || $confirm === '') {
                $pwErrors[] = 'Please fill in all password fields.';
            } elseif (!password_verify($current, $user['password'])) {
                $pwErrors[] = 'Your current password is incorrect.';
            } else {
                if (strlen($new) < 8) {
                    $pwErrors[] = 'New password must be at least 8 characters.';
                }
                if ($new !== $confirm) {
                    $pwErrors[] = 'New passwords do not match.';
                }
                if ($new === $current) {
                    $pwErrors[] = 'New password must be different from your current password.';
                }
            }

            if (empty($pwErrors)) {
                if ($this->userModel->updatePassword($_SESSION['user_id'], password_hash($new, PASSWORD_DEFAULT))) {
                    session_regenerate_id(true);
                    $pwSuccess = true;
                } else {
                    $pwErrors[] = 'Failed to change password. Please try again.';
                }
            }
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $full_name = trim($_POST['full_name'] ?? '');
            $bio = trim($_POST['bio'] ?? '');
            $profile_image = null;
            $cover_image = null;

            if ($full_name === '') {
                $errors[] = 'Full name is required.';
            }

            if (strlen($bio) > 500) {
                $errors[] = 'Bio is too long (max 500 characters).';
            }

            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['profile_image'];
                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $maxSize = 2 * 1024 * 1024;

                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($file['tmp_name']);

                if (!in_array($mimeType, $allowed, true)) {
                    $errors[] = 'Only JPG, PNG, GIF, or WEBP images are allowed.';
                } elseif ($file['size'] > $maxSize) {
                    $errors[] = 'Image must be under 2MB.';
                } else {
                    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
                    $filename = 'user_' . (int) $_SESSION['user_id'] . '_' . time() . '.' . $extensions[$mimeType];
                    $uploadPath = __DIR__ . '/../../public/assets/uploads/' . $filename;

                    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                        $profile_image = $filename;
                    } else {
                        $errors[] = 'Failed to upload image.';
                    }
                }
            } elseif (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== UPLOAD_ERR_NO_FILE && $_FILES['profile_image']['error'] !== UPLOAD_ERR_OK) {
                $errors[] = 'There was a problem uploading the image.';
            }

            // Cover photo upload (same rules as the profile picture, max 4MB)
            if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['cover_image'];
                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $maxSize = 4 * 1024 * 1024;

                $finfo = new finfo(FILEINFO_MIME_TYPE);
                $mimeType = $finfo->file($file['tmp_name']);

                if (!in_array($mimeType, $allowed, true)) {
                    $errors[] = 'Cover photo must be a JPG, PNG, GIF, or WEBP image.';
                } elseif ($file['size'] > $maxSize) {
                    $errors[] = 'Cover photo must be under 4MB.';
                } else {
                    $extensions = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
                    $filename = 'cover_' . (int) $_SESSION['user_id'] . '_' . time() . '.' . $extensions[$mimeType];
                    $uploadPath = __DIR__ . '/../../public/assets/uploads/' . $filename;

                    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                        $cover_image = $filename;
                    } else {
                        $errors[] = 'Failed to upload cover photo.';
                    }
                }
            } elseif (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] !== UPLOAD_ERR_NO_FILE) {
                $errors[] = 'There was a problem uploading the cover photo.';
            }

            if ($profile_image === null && isset($_POST['remove_profile_image']) && $_POST['remove_profile_image'] === '1') {
                $profile_image = '';
            }

            if (empty($errors)) {
                $updated = $this->userModel->update($_SESSION['user_id'], $full_name, $bio, $profile_image, $cover_image);

                if ($updated) {
                    $_SESSION['full_name'] = $full_name;
                    header('Location: index.php?page=profile&id=' . (int) $_SESSION['user_id']);
                    exit;
                }
                $errors[] = 'Failed to update profile.';
            }

            $user['full_name'] = $full_name;
            $user['bio'] = $bio;
        }

        $pageTitle = 'Edit Profile - Mini Social';
        require __DIR__ . '/../views/profile/edit.php';
    }

    public function followAction() {
        $this->requireLogin();
        $target_id = (int)($_GET['id'] ?? 0);
        $my_id = $_SESSION['user_id'];
        $action = 'error';

        if ($target_id > 0 && $target_id !== $my_id) {
            if ($this->userModel->isFollowing($my_id, $target_id)) {
                $this->userModel->unfollow($my_id, $target_id);
                $action = 'unfollowed';
            } else {
                $this->userModel->follow($my_id, $target_id);
                $action = 'followed';
                $this->notifModel->create($target_id, $my_id, 'follow', null);
            }
        }   
        
        if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
            header('Content-Type: application/json');
            echo json_encode(['status' => $action]);
            exit;
        }
        
        header("Location: index.php?page=profile&id=" . $target_id);
        exit;
    }
}