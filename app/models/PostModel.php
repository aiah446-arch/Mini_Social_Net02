<?php
// app/models/PostModel.php
// Handles all database operations related to posts

class PostModel {
    private $conn;
    private $table = 'posts';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all posts with author info (for newsfeed)
    public function getAll() {
        $sql = "SELECT p.*, u.username, u.full_name, u.profile_image
                FROM {$this->table} p
                JOIN users u ON p.user_id = u.id
                ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Get a single post by ID
    public function findById($id) {
        $sql = "SELECT p.*, u.username, u.full_name, u.profile_image
                FROM {$this->table} p
                JOIN users u ON p.user_id = u.id
                WHERE p.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Get all posts by a specific user (for profile)
    public function getByUser($user_id) {
        $sql = "SELECT p.*, u.username, u.full_name, u.profile_image
                FROM {$this->table} p
                JOIN users u ON p.user_id = u.id
                WHERE p.user_id = :user_id
                ORDER BY p.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // Create a new post
    public function create($user_id, $content, $image = null) {
        $sql = "INSERT INTO {$this->table} (user_id, content, image)
                VALUES (:user_id, :content, :image)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image', $image);
        return $stmt->execute();
    }

    // Update a post (content + optional image)
    public function update($id, $content, $image = null) {
        if ($image !== null) {
            $sql = "UPDATE {$this->table}
                    SET content = :content, image = :image
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':image', $image);
        } else {
            $sql = "UPDATE {$this->table}
                    SET content = :content
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
        }
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Delete a post
    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Search posts by keyword
    public function search($keyword) {
        $sql = "SELECT p.*, u.username, u.full_name, u.profile_image
                FROM {$this->table} p
                JOIN users u ON p.user_id = u.id
                WHERE p.content LIKE :kw
                ORDER BY p.created_at DESC
                LIMIT 50";
        $stmt = $this->conn->prepare($sql);
        $like = "%{$keyword}%";
        $stmt->bindParam(':kw', $like);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}