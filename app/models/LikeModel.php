<?php
// app/models/LikeModel.php
// Handles post likes

class LikeModel {
    private $conn;
    private $table = 'likes';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Check if a user has liked a post or marketplace item
    public function hasLiked($post_id, $user_id, $type = 'post') {
        $sql = "SELECT id FROM {$this->table} 
                WHERE post_id = :post_id AND user_id = :user_id AND item_type = :type LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    // Add a like
    public function add($post_id, $user_id, $type = 'post') {
        $sql = "INSERT INTO {$this->table} (post_id, user_id, item_type) 
                VALUES (:post_id, :user_id, :type)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type);
        return $stmt->execute();
    }

    // Remove a like
    public function remove($post_id, $user_id, $type = 'post') {
        $sql = "DELETE FROM {$this->table} 
                WHERE post_id = :post_id AND user_id = :user_id AND item_type = :type";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type);
        return $stmt->execute();
    }

    // Count likes for a post
    public function countByPost($post_id, $type = 'post') {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE post_id = :post_id AND item_type = :type";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':type', $type);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }
}