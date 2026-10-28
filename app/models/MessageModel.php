<?php
class MessageModel {
    private $conn;
    private $table = 'messages';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all messages between two users
    public function getConversation($user1, $user2) {
        $sql = "SELECT * FROM {$this->table} 
                WHERE (sender_id = :user1 AND receiver_id = :user2) 
                   OR (sender_id = :user2 AND receiver_id = :user1)
                ORDER BY created_at ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user1' => $user1, ':user2' => $user2]);
        return $stmt->fetchAll();
    }

    // Send a message
    public function send($sender_id, $receiver_id, $message) {
        $sql = "INSERT INTO {$this->table} (sender_id, receiver_id, message, is_read) 
                VALUES (:sender_id, :receiver_id, :message, 0)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':sender_id' => $sender_id,
            ':receiver_id' => $receiver_id,
            ':message' => $message
        ]);
    }

    /**
     * Get all users to chat with, sorted like Messenger/Instagram:
     * people with the most recent message (sent OR received) come first,
     * people you have never chatted with go to the bottom (A-Z).
     *
     * Same columns as before, plus:
     *   last_message    - text of the latest message in that conversation
     *   last_time       - when it was sent
     *   last_sender_id  - who sent it (to show "You: ..." in the preview)
     *   unread_count    - how many unread messages that user sent to you
     */
    public function getUsers($current_id = null) {
        $me = (int) ($current_id ?? $_SESSION['user_id']);

        $sql = "SELECT u.id, u.full_name, u.username, u.profile_image,
                       lm.message    AS last_message,
                       lm.created_at AS last_time,
                       lm.sender_id  AS last_sender_id,
                       (SELECT COUNT(*) FROM {$this->table} um
                         WHERE um.sender_id = u.id
                           AND um.receiver_id = :me3
                           AND um.is_read = 0) AS unread_count
                FROM users u
                LEFT JOIN {$this->table} lm ON lm.id = (
                    SELECT m2.id FROM {$this->table} m2
                    WHERE (m2.sender_id = u.id  AND m2.receiver_id = :me1)
                       OR (m2.sender_id = :me2  AND m2.receiver_id = u.id)
                    ORDER BY m2.created_at DESC, m2.id DESC
                    LIMIT 1
                )
                WHERE u.id != :me4
                ORDER BY (lm.id IS NULL) ASC, lm.created_at DESC, lm.id DESC, u.full_name ASC";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute([
            ':me1' => $me,
            ':me2' => $me,
            ':me3' => $me,
            ':me4' => $me
        ]);
        return $stmt->fetchAll();
    }

    // Count UNREAD messages sent to you
    public function countUnreadMessages($user_id) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} 
                WHERE receiver_id = :user_id AND is_read = 0";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetch()['total'];
    }

    // Mark messages as read when you open the chat
    public function markAsRead($sender_id, $receiver_id) {
        $sql = "UPDATE {$this->table} SET is_read = 1 
                WHERE sender_id = :sender_id AND receiver_id = :receiver_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':sender_id' => $sender_id,
            ':receiver_id' => $receiver_id
        ]);
    }
}