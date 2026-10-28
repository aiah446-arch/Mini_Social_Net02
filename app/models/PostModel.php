<?php
class PostModel {
    private $conn;
    private $table = 'posts';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $sql = "(
                    SELECT p.id, p.user_id, p.content, p.image, p.created_at, 'post' as type, NULL as item_name, NULL as price, p.shared_post_id, p.shared_type
                    FROM {$this->table} p
                ) UNION ALL (
                    SELECT m.id, m.user_id, m.description as content, m.image, m.created_at, 'marketplace' as type, m.item_name, m.price, NULL as shared_post_id, NULL as shared_type
                    FROM marketplace m
                )
                ORDER BY created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        require_once __DIR__ . '/UserModel.php';
        $userModel = new UserModel($this->conn);

        $finalFeed = [];
        foreach ($results as $row) {
            $user = $userModel->findById($row['user_id']);
            if ($user) {
                $row['full_name'] = $user['full_name'];
                $row['username'] = $user['username'];
                $row['profile_image'] = $user['profile_image'];
                $finalFeed[] = $row;
            }
        }
        return $finalFeed;
    }

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

    // UPDATED: Fetch original post from either posts or marketplace
    public function getOriginalPost($id, $type = 'post') {
        if ($type === 'marketplace') {
            $sql = "SELECT m.*, u.full_name, u.username, u.profile_image, 'marketplace' as type, m.description as content, m.item_name, m.price
                    FROM marketplace m
                    JOIN users u ON m.user_id = u.id
                    WHERE m.id = :id LIMIT 1";
        } else {
            $sql = "SELECT p.*, u.full_name, u.username, u.profile_image, 'post' as type
                    FROM {$this->table} p
                    JOIN users u ON p.user_id = u.id
                    WHERE p.id = :id LIMIT 1";
        }
        
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

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

    // UPDATED: Now accepts shared_type
    public function create($user_id, $content, $image = null, $shared_post_id = null, $shared_type = null) {
        $sql = "INSERT INTO {$this->table} (user_id, content, image, shared_post_id, shared_type)
                VALUES (:user_id, :content, :image, :shared_post_id, :shared_type)";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':image', $image);
        $stmt->bindParam(':shared_post_id', $shared_post_id, PDO::PARAM_INT);
        $stmt->bindParam(':shared_type', $shared_type);
        return $stmt->execute();
    }

    public function update($id, $content, $image = null) {
        if ($image !== null) {
            $sql = "UPDATE {$this->table} SET content = :content, image = :image WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':image', $image);
        } else {
            $sql = "UPDATE {$this->table} SET content = :content WHERE id = :id";
            $stmt = $this->conn->prepare($sql);
        }
        $stmt->bindParam(':content', $content);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public function search($keyword) {
        $sql = "SELECT p.*, u.username, u.full_name, u.profile_image
                FROM {$this->table} p
                JOIN users u ON p.user_id = u.id
                WHERE p.content LIKE :kw
                ORDER BY p.created_at DESC LIMIT 50";
        $stmt = $this->conn->prepare($sql);
        $like = "%{$keyword}%";
        $stmt->bindParam(':kw', $like);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}