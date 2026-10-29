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
    public function update($id, $full_name, $bio, $profile_image = null, $cover_image = null) {
        $sql = "UPDATE {$this->table} SET full_name = :full_name, bio = :bio";
        if ($profile_image) { $sql .= ", profile_image = :profile_image"; }
        if ($cover_image)   { $sql .= ", cover_image = :cover_image"; }
        $sql .= " WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        if ($profile_image) { $stmt->bindParam(':profile_image', $profile_image); }
        if ($cover_image)   { $stmt->bindParam(':cover_image', $cover_image); }
        $stmt->bindParam(':full_name', $full_name);
        $stmt->bindParam(':bio', $bio);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // Save a new (already hashed) password
    public function updatePassword($id, $hashed) {
        $sql = "UPDATE {$this->table} SET password = :password WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':password', $hashed);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    // "Suggested for you": people you don't follow yet.
    // People followed by the people you follow come first, then the most followed.
    public function getSuggestions($user_id, $limit = 5) {
        $sql = "SELECT u.id, u.username, u.full_name, u.profile_image
                FROM {$this->table} u
                WHERE u.id <> :me1
                  AND u.id NOT IN (SELECT following_id FROM follows WHERE follower_id = :me2)
                ORDER BY
                  (SELECT COUNT(*) FROM follows f1
                    WHERE f1.following_id = u.id
                      AND f1.follower_id IN (SELECT following_id FROM follows WHERE follower_id = :me3)) DESC,
                  (SELECT COUNT(*) FROM follows f2 WHERE f2.following_id = u.id) DESC,
                  u.id DESC
                LIMIT " . (int)$limit;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':me1' => $user_id, ':me2' => $user_id, ':me3' => $user_id]);
        return $stmt->fetchAll();
    }

    // "Contacts": people you have chatted with, newest conversation first
    public function getContacts($user_id, $limit = 5) {
        $sql = "SELECT u.id, u.username, u.full_name, u.profile_image, MAX(m.created_at) AS last_time
                FROM messages m
                JOIN {$this->table} u
                  ON u.id = CASE WHEN m.sender_id = :me1 THEN m.receiver_id ELSE m.sender_id END
                WHERE (m.sender_id = :me2 OR m.receiver_id = :me3) AND u.id <> :me4
                GROUP BY u.id, u.username, u.full_name, u.profile_image
                ORDER BY last_time DESC
                LIMIT " . (int)$limit;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':me1' => $user_id, ':me2' => $user_id, ':me3' => $user_id, ':me4' => $user_id]);
        return $stmt->fetchAll();
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

    // How many people this user follows
    public function getFollowingCount($user_id) {
        $sql = "SELECT COUNT(*) as total FROM follows WHERE follower_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetch()['total'];
    }

    // People who follow this user (for the Followers list)
    public function getFollowers($user_id, $limit = 200) {
        $sql = "SELECT u.id, u.username, u.full_name, u.profile_image
                FROM follows f
                JOIN {$this->table} u ON u.id = f.follower_id
                WHERE f.following_id = :user_id
                ORDER BY f.id DESC
                LIMIT " . (int)$limit;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll();
    }

    // People this user follows (for the Following list)
    public function getFollowing($user_id, $limit = 200) {
        $sql = "SELECT u.id, u.username, u.full_name, u.profile_image
                FROM follows f
                JOIN {$this->table} u ON u.id = f.following_id
                WHERE f.follower_id = :user_id
                ORDER BY f.id DESC
                LIMIT " . (int)$limit;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll();
    }
}