<?php
// app/models/UserModel.php
// Handles all database operations related to users

class UserModel {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    // Find user by username (for login)
    public function findByUsername($username) {
        $sql = "SELECT * FROM {$this->table} WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Find user by ID (for profile)
    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    // Create new user (register)
    public function create($username, $password, $full_name) {
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO {$this->table} (username, password, full_name) 
                VALUES (:username, :password, :full_name)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashed);
        $stmt->bindParam(':full_name', $full_name);
        return $stmt->execute();
    }

    // Update profile info
    public function update($id, $full_name, $bio, $profile_image = null) {
        if ($profile_image) {
            $sql = "UPDATE {$this->table} 
                    SET full_name = :full_name, bio = :bio, profile_image = :profile_image 
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':profile_image', $profile_image);
        } else {
            $sql = "UPDATE {$this->table} 
                    SET full_name = :full_name, bio = :bio 
                    WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
        }
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Check if a username already exists
    public function usernameExists($username) {
        $sql = "SELECT id FROM {$this->table} WHERE username = :username LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return $stmt->fetch() !== false;
    }

    // Search users by username or full name
    public function search($keyword) {
        $sql = "SELECT id, username, full_name, profile_image 
                FROM {$this->table} 
                WHERE username LIKE :kw OR full_name LIKE :kw 
                LIMIT 20";
        $stmt = $this->conn->prepare($sql);
        $like = "%{$keyword}%";
        $stmt->bindParam(':kw', $like);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    // ==========================================
    // NEW: FOLLOW / UNFOLLOW FUNCTIONS
    // ==========================================

    public function isFollowing($follower_id, $following_id) {
        $sql = "SELECT id FROM follows WHERE follower_id = :follower_id AND following_id = :following_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':follower_id' => $follower_id, ':following_id' => $following_id]);
        return $stmt->fetch() !== false;
    }

    public function follow($follower_id, $following_id) {
        $sql = "INSERT IGNORE INTO follows (follower_id, following_id) VALUES (:follower_id, :following_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':follower_id' => $follower_id, ':following_id' => $following_id]);
    }

    public function unfollow($follower_id, $following_id) {
        $sql = "DELETE FROM follows WHERE follower_id = :follower_id AND following_id = :following_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':follower_id' => $follower_id, ':following_id' => $following_id]);
    }

    public function getFollowerCount($user_id) {
        $sql = "SELECT COUNT(*) as total FROM follows WHERE following_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetch()['total'];
    }
}