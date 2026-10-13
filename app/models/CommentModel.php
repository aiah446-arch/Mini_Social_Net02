<?php
// app/models/CommentModel.php
// Handles all database operations related to comments

class CommentModel {
    private $conn;
    private $table = 'comments';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all comments for a specific post
    public function getByPost($post_id) {
        $sql = "SELECT c.*, u.username, u.full_name, u.profile_image
                FROM {$this->table} c
                JOIN users u ON c.user_id = u.id
                WHERE c.post_id = :post_id
                ORDER BY c.created_at ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Find a single comment
    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Create a comment
    public function create($post_id, $user_id, $content) {
        $sql = "INSERT INTO {$this->table} (post_id, user_id, content)
                VALUES (:post_id, :user_id, :content)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':content', $content);
        return $stmt->execute();
    }

    // Update a comment
    public function update($id, $content) {
        $sql = "UPDATE {$this->table} SET content = :content WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Delete a comment
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Count comments per post
    public function countByPost($post_id) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE post_id = :post_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':post_id', $post_id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        return $row['total'];
    }
}