<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/LikeModel.php';
require_once __DIR__ . '/../models/PostModel.php';          
require_once __DIR__ . '/../models/MarketplaceModel.php'; 
require_once __DIR__ . '/../models/NotificationModel.php';  

class LikeController {
    private $likeModel;
    private $postModel;
    private $marketModel;
    private $notifModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->likeModel = new LikeModel($db);
        $this->postModel = new PostModel($db);
        $this->marketModel = new MarketplaceModel($db);
        $this->notifModel = new NotificationModel($db);
    }

    public function toggle() {
        if (!isset($_SESSION['user_id'])) { exit; }
        
        $post_id = (int)($_GET['id'] ?? 0);
        $type = $_GET['type'] ?? 'post'; // 'post' or 'marketplace'
        $my_id = $_SESSION['user_id'];
        $status = 'error';

        if ($post_id > 0) {
            // Find the owner of the post/item to notify them
            $owner_id = null;
            if ($type === 'marketplace') {
                $item = $this->marketModel->getItemById($post_id);
                if ($item) $owner_id = $item['user_id'];
            } else {
                $post = $this->postModel->findById($post_id);
                if ($post) $owner_id = $post['user_id'];
            }
            
            if ($owner_id) {
                if ($this->likeModel->hasLiked($post_id, $my_id, $type)) {
                    $this->likeModel->remove($post_id, $my_id, $type);
                    $status = 'unliked';
                } else {
                    $this->likeModel->add($post_id, $my_id, $type);
                    $this->notifModel->create($owner_id, $my_id, 'like', $post_id);
                    $status = 'liked';
                }
            }
        }

        // If this is an AJAX request, return JSON
        if (isset($_GET['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode(['status' => $status]);
            exit;
        }

        // Otherwise, normal redirect
        $redirect = $_SERVER['HTTP_REFERER'] ?? 'index.php?page=feed';
        header("Location: " . $redirect);
        exit;
    }
}