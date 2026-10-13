<?php
// app/controllers/LikeController.php
// Handles like/unlike toggling

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/LikeModel.php';

class LikeController {
    private $likeModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->likeModel = new LikeModel($db);
    }

    // Toggle like (add if not liked, remove if already liked)
    public function toggle() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $post_id = (int)($_GET['id'] ?? 0);

        if ($post_id > 0) {
            if ($this->likeModel->hasLiked($post_id, $_SESSION['user_id'])) {
                $this->likeModel->remove($post_id, $_SESSION['user_id']);
            } else {
                $this->likeModel->add($post_id, $_SESSION['user_id']);
            }
        }

        header('Location: index.php?page=feed');
        exit;
    }
}