<?php
class NotificationModel {
    private $conn;
    private $table = 'notifications';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Add a new notification
    public function create($user_id, $sender_id, $type, $post_id = null) {
        // Don't notify yourself!
        if ($user_id == $sender_id) return false;

        $sql = "INSERT INTO {$this->table} (user_id, sender_id, type, post_id) 
                VALUES (:user_id, :sender_id, :type, :post_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':sender_id' => $sender_id,
            ':type' => $type,
            ':post_id' => $post_id
        ]);
    }

    // Get all notifications for a user
    public function getUserNotifications($user_id) {
        $sql = "SELECT n.*, u.full_name, u.username, u.profile_image 
                FROM {$this->table} n
                JOIN users u ON n.sender_id = u.id
                WHERE n.user_id = :user_id
                ORDER BY n.created_at DESC LIMIT 50";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    // Count unread notifications
    public function countUnread($user_id) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE user_id = :user_id AND is_read = 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetch()['total'];
    }

    // Mark all as read (Only declared once now!)
    public function markAllRead($user_id) {
        $sql = "UPDATE {$this->table} SET is_read = 1 WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':user_id' => $user_id]);
    }
}