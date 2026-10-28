<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/NotificationModel.php';
require_once __DIR__ . '/../models/UserModel.php'; 

class NotificationController {
    private $notifModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->notifModel = new NotificationModel($db);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) { 
            header('Location: index.php?page=login'); 
            exit; 
        }
        
        // NEW: If the AJAX button requested to mark all as read
        if (isset($_GET['action']) && $_GET['action'] === 'mark_read') {
            $this->notifModel->markAllRead($_SESSION['user_id']);
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success']);
            exit;
        }

        $notifications = $this->notifModel->getUserNotifications($_SESSION['user_id']);
        $this->notifModel->markAllRead($_SESSION['user_id']);
        
        $pageTitle = 'Notifications - NEO';
        require __DIR__ . '/../views/notifications/index.php';
    }
}