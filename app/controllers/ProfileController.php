<?php
// app/controllers/ProfileController.php
// Handles profile viewing and editing

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/PostModel.php';

class ProfileController {
    private $userModel;
    private $postModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->userModel = new UserModel($db);
        $this->postModel = new PostModel($db);
    }

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    // -------------------------
    // VIEW PROFILE
    // -------------------------
    public function index() {
        $this->requireLogin();

        $id = (int)($_GET['id'] ?? $_SESSION['user_id']);
        $user = $this->userModel->findById($id);

        if (!$user) {
            header('Location: index.php?page=feed');
            exit;
        }

        $posts = $this->postModel->getByUser($id);
        $isOwnProfile = ($id == $_SESSION['user_id']);
        $pageTitle = htmlspecialchars($user['full_name']) . ' - Mini Social';

        require __DIR__ . '/../views/profile/index.php';
    }

    // -------------------------
    // EDIT PROFILE
    // -------------------------
    public function edit() {
        $this->requireLogin();

        $user = $this->userModel->findById($_SESSION['user_id']);
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $full_name = trim($_POST['full_name'] ?? '');
            $bio = trim($_POST['bio'] ?? '');
            $profile_image = null;

            if ($full_name === '') {
                $errors[] = 'Full name is required.';
            }

            if (strlen($bio) > 500) {
                $errors[] = 'Bio is too long (max 500 characters).';
            }

            // Handle image upload (optional)
            if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
                $file = $_FILES['profile_image'];
                $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
                $maxSize = 2 * 1024 * 1024; // 2MB

                if (!in_array($file['type'], $allowed)) {
                    $errors[] = 'Only JPG, PNG, GIF, or WEBP images allowed.';
                } elseif ($file['size'] > $maxSize) {
                    $errors[] = 'Image must be under 2MB.';
                } else {
                    // Generate safe filename
                    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
                    $filename = 'user_' . $_SESSION['user_id'] . '_' . time() . '.' . strtolower($ext);
                    $uploadPath = __DIR__ . '/../../public/assets/uploads/' . $filename;

                    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                        $profile_image = $filename;
                    } else {
                        $errors[] = 'Failed to upload image.';
                    }
                }
            }

            if (empty($errors)) {
                if ($this->userModel->update($_SESSION['user_id'], $full_name, $bio, $profile_image)) {
                    // Update session name too
                    $_SESSION['full_name'] = $full_name;
                    header('Location: index.php?page=profile&id=' . $_SESSION['user_id']);
                    exit;
                } else {
                    $errors[] = 'Failed to update profile.';
                }
            }
        }

        $pageTitle = 'Edit Profile - Mini Social';
        require __DIR__ . '/../views/profile/edit.php';
    }
}