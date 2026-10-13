<?php
// app/models/LikeModel.php
// Handles post likes

class LikeModel {
    private $conn;
    private $table = 'likes';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Check if a user has liked a post
    public function hasLiked($post_id, $user_id) {
        $sql = "SELECT id FROM {$this->table} 
                WHERE post_id = :post_id AND user_id = :user_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    // Add a like
    public function add($post_id, $user_id) {
        $sql = "INSERT INTO {$this->table} (post_id, user_id) 
                VALUES (:post_id, :user_id)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Remove a like
    public function remove($post_id, $user_id) {
        $sql = "DELETE FROM {$this->table} 
                WHERE post_id = :post_id AND user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Count likes for a post
    public function countByPost($post_id) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }
}