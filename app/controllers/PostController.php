<?php
// app/controllers/PostController.php

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/PostModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/MessageModel.php';

class PostController {
    private $postModel;
    private $userModel;
    private $messageModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->postModel = new PostModel($db);
        $this->userModel = new UserModel($db);
        $this->messageModel = new MessageModel($db);
    }

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    private function handleUpload($fileKey) {
        if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
            return ['filename' => null, 'error' => null];
        }

        $file = $_FILES[$fileKey];
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 3 * 1024 * 1024;

        if (!in_array($file['type'], $allowed)) {
            return ['filename' => null, 'error' => 'Only JPG, PNG, GIF, or WEBP images allowed.'];
        }
        if ($file['size'] > $maxSize) {
            return ['filename' => null, 'error' => 'Image must be under 3MB.'];
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = 'post_' . $_SESSION['user_id'] . '_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        $uploadPath = __DIR__ . '/../../public/assets/uploads/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['filename' => $filename, 'error' => null];
        }

        return ['filename' => null, 'error' => 'Failed to upload image.'];
    }

    public function feed() {
        $this->requireLogin();
        $posts = $this->postModel->getAll();
        $currentUser = $this->userModel->findById($_SESSION['user_id']);
        
        $unreadMsgs = $this->messageModel->countUnreadMessages($_SESSION['user_id']);

        $pageTitle = 'NEO';  // <--- CHANGED FROM 'Newsfeed - NEO'
        require __DIR__ . '/../views/posts/feed.php';
    }

    public function create() {
        $this->requireLogin();

        $error = '';
        $content = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = trim($_POST['content'] ?? '');
            $image = null;

            if ($content === '') {
                $error = 'Post content cannot be empty.';
            } elseif (strlen($content) > 1000) {
                $error = 'Post is too long (max 1000 characters).';
            } else {
                $upload = $this->handleUpload('image');
                if ($upload['error']) {
                    $error = $upload['error'];
                } else {
                    $image = $upload['filename'];
                }

                if (empty($error)) {
                    if ($this->postModel->create($_SESSION['user_id'], $content, $image)) {
                        header('Location: index.php?page=feed');
                        exit;
                    } else {
                        $error = 'Failed to create post. Please try again.';
                    }
                }
            }
        }

        $pageTitle = 'Newsfeed - NEO';  // <--- CHANGED FROM 'Create Post - NEO'
        require __DIR__ . '/../views/posts/create.php';
    }

    public function edit() {
        $this->requireLogin();

        $id = (int)($_GET['id'] ?? 0);
        $post = $this->postModel->findById($id);

        if (!$post || $post['user_id'] != $_SESSION['user_id']) {
            header('Location: index.php?page=feed');
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = trim($_POST['content'] ?? '');
            $image = null;

            if ($content === '') {
                $error = 'Post content cannot be empty.';
            } elseif (strlen($content) > 1000) {
                $error = 'Post is too long (max 1000 characters).';
            } else {
                $upload = $this->handleUpload('image');
                if ($upload['error']) {
                    $error = $upload['error'];
                } else {
                    $image = $upload['filename'];

                    if ($image !== null && !empty($post['image'])) {
                        $oldPath = __DIR__ . '/../../public/assets/uploads/' . $post['image'];
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }
                }

                if (empty($error)) {
                    if ($this->postModel->update($id, $content, $image)) {
                        header('Location: index.php?page=feed');
                        exit;
                    } else {
                        $error = 'Failed to update post.';
                    }
                }
            }
        }

        $pageTitle = 'NEO';  // <--- CHANGED FROM 'Edit Post - NEO'
        require __DIR__ . '/../views/posts/edit.php';
    }

    public function delete() {
        $this->requireLogin();

        $id = (int)($_GET['id'] ?? 0);
        $post = $this->postModel->findById($id);

        if ($post && $post['user_id'] == $_SESSION['user_id']) {
            if (!empty($post['image'])) {
                $imgPath = __DIR__ . '/../../public/assets/uploads/' . $post['image'];
                if (file_exists($imgPath)) {
                    unlink($imgPath);
                }
            }
            $this->postModel->delete($id);
        }

        header('Location: index.php?page=feed');
        exit;
    }
    public function share() {
        $this->requireLogin();

        $post_id = (int)($_POST['post_id'] ?? 0);
        $type = $_POST['type'] ?? 'post';
        $content = trim($_POST['caption'] ?? '');
        
        if ($content === '') {
            $content = "Shared a post!";
        }

        $status = 'error';
        if ($post_id > 0) {
            if ($this->postModel->create($_SESSION['user_id'], $content, null, $post_id, $type)) {
                $status = 'success';
            }
        }

        // If AJAX request, return JSON
        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode(['status' => $status]);
            exit;
        }
        
        header('Location: index.php?page=feed');
        exit;
    }
}