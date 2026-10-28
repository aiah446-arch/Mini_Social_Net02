<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/MessageModel.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/NotificationModel.php';

class MessageController {
    private $messageModel;
    private $userModel;
    private $notifModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->messageModel = new MessageModel($db);
        $this->userModel = new UserModel($db);
        $this->notifModel = new NotificationModel($db);
    }

    public function index() {
        $isAjax = isset($_GET['ajax']);

        // 1. Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            if ($isAjax) { http_response_code(401); exit; }
            header('Location: index.php?page=login');
            exit;
        }

        $chatWith = null;
        $messages = [];

        // 2. Check if a specific user is requested (via ?with=ID from the marketplace)
        if (isset($_GET['with'])) {
            $chatWith = $this->userModel->findById((int)$_GET['with']);
            if ($chatWith) {
                $messages = $this->messageModel->getConversation($_SESSION['user_id'], $chatWith['id']);

                // Mark messages as read when you open the chat
                $this->messageModel->markAsRead($chatWith['id'], $_SESSION['user_id']);
            }
        }

        // 3. Load the chat list (sorted by recent messages)
        $users = $this->messageModel->getUsers($_SESSION['user_id']);

        // 4. Handle AJAX Polling (for real-time updates without refreshing)
        if ($isAjax) {
            header('Content-Type: application/json');
            echo json_encode([
                'me'       => (int)$_SESSION['user_id'],
                'users'    => $users,
                'messages' => $messages
            ]);
            exit;
        }

        // 5. Load the normal view
        $pageTitle = 'Chats - NEO';
        require __DIR__ . '/../views/chats/index.php';
    }

    public function send() {
        if (!isset($_SESSION['user_id'])) {
            if (isset($_POST['ajax'])) { http_response_code(401); header('Content-Type: application/json'); echo json_encode(['status' => 'error']); }
            exit;
        }

        $receiver_id = 0;
        $sent = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $receiver_id = (int)($_POST['receiver_id'] ?? 0);
            $message = trim($_POST['message'] ?? '');

            if ($receiver_id > 0 && $message !== '') {
                $this->messageModel->send($_SESSION['user_id'], $receiver_id, $message);
                $this->notifModel->create($receiver_id, $_SESSION['user_id'], 'message', null);
                $sent = true;
            }
        }

        // Sent from the chat page without reloading: answer with JSON instead of redirecting
        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode(['status' => $sent ? 'ok' : 'error']);
            exit;
        }

        header('Location: index.php?page=chats&with=' . $receiver_id);
        exit;
    }
}