<?php
// app/controllers/CommentController.php
// Handles comments: create, edit, delete

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/CommentModel.php';
require_once __DIR__ . '/../models/PostModel.php';

class CommentController {
    private $commentModel;
    private $postModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->commentModel = new CommentModel($db);
        $this->postModel = new PostModel($db);
    }

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    // Create comment (UPDATED for AJAX - No Refresh)
    public function create() {
        $this->requireLogin();

        $status = 'error';
        $html = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $post_id = (int)($_POST['post_id'] ?? 0);
            $type = $_POST['type'] ?? 'post'; 
            $content = trim($_POST['content'] ?? '');

            if ($post_id > 0 && $content !== '' && strlen($content) <= 500) {
                if ($this->commentModel->create($post_id, $_SESSION['user_id'], $content, $type)) {
                    $status = 'success';

                    // If AJAX request, fetch the newly created comment and render it
                    if (isset($_POST['ajax'])) {
                        $comments = $this->commentModel->getByPost($post_id, $type);
                        $newComment = end($comments); // Get the last inserted comment

                        if ($newComment) {
                            // Start output buffering to capture the HTML of _item.php
                            ob_start();
                            $comment = $newComment; // Set the variable expected by _item.php
                            require __DIR__ . '/../views/comments/_item.php';
                            $html = ob_get_clean();
                        }
                    }
                }
            }
        }

        // If this is an AJAX request, return JSON with status and HTML
        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode(['status' => $status, 'html' => $html]);
            exit;
        }

        // Otherwise, normal redirect
        $redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=feed';
        header("Location: " . $redirect);
        exit;
    }

    // Update comment
    public function edit() {
        $this->requireLogin();

        $id = (int)($_GET['id'] ?? 0);
        $comment = $this->commentModel->findById($id);

        // Not found or not owner
        if (!$comment || $comment['user_id'] != $_SESSION['user_id']) {
            header('Location: index.php?page=feed');
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = trim($_POST['content'] ?? '');

            if ($content === '') {
                $error = 'Comment cannot be empty.';
            } elseif (strlen($content) > 500) {
                $error = 'Comment is too long (max 500 characters).';
            } else {
                $this->commentModel->update($id, $content);
                header('Location: index.php?page=feed');
                exit;
            }
        }

        $pageTitle = 'Edit Comment - Mini Social';
        require __DIR__ . '/../views/comments/edit.php';
    }

    // Delete comment
    public function delete() {
        $this->requireLogin();

        $id = (int)($_GET['id'] ?? 0);
        $comment = $this->commentModel->findById($id);

        if ($comment && $comment['user_id'] == $_SESSION['user_id']) {
            $this->commentModel->delete($id);
        }

        header('Location: index.php?page=feed');
        exit;
    }
}