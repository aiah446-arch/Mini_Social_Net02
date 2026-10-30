<?php
class MarketplaceModel {
    private $conn;
    private $table = 'marketplace';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAllItems($search = '') {
        $sql = "SELECT m.*, u.full_name, u.username, u.profile_image 
                FROM {$this->table} m
                JOIN users u ON m.user_id = u.id";
        $params = [];
        if ($search !== '') {
            $sql .= " WHERE (m.item_name LIKE :search_name OR m.description LIKE :search_desc)";
            $params[':search_name'] = '%' . $search . '%';
            $params[':search_desc'] = '%' . $search . '%';
        }
        $sql .= " ORDER BY m.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    public function create($user_id, $item_name, $price, $description, $image = null) {
        $sql = "INSERT INTO {$this->table} (user_id, item_name, price, description, image) 
                VALUES (:user_id, :item_name, :price, :description, :image)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            ':user_id' => $user_id,
            ':item_name' => $item_name,
            ':price' => $price,
            ':description' => $description,
            ':image' => $image
        ]);
    }

    public function getItemById($id) {
        $sql = "SELECT m.*, u.full_name, u.username, u.profile_image 
                FROM {$this->table} m
                JOIN users u ON m.user_id = u.id
                WHERE m.id = :id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch();
    }

    public function isSaved($user_id, $item_id) {
        $sql = "SELECT id FROM saved_items WHERE user_id = :user_id AND item_id = :item_id LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $user_id, ':item_id' => $item_id]);
        return $stmt->fetch() !== false;
    }

    public function saveItem($user_id, $item_id) {
        $sql = "INSERT INTO saved_items (user_id, item_id) VALUES (:user_id, :item_id)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':user_id' => $user_id, ':item_id' => $item_id]);
    }

    public function unsaveItem($user_id, $item_id) {
        $sql = "DELETE FROM saved_items WHERE user_id = :user_id AND item_id = :item_id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':user_id' => $user_id, ':item_id' => $item_id]);
    }

    public function getSavedItems($user_id, $search = '', $filter = 'all') {
        $sql = "SELECT m.*, u.full_name, u.username, u.profile_image 
                FROM saved_items s
                JOIN {$this->table} m ON s.item_id = m.id
                JOIN users u ON m.user_id = u.id
                WHERE s.user_id = :user_id";
        
        if (!empty($search)) {
            $sql .= " AND m.item_name LIKE :search";
        }
        
        $sql .= " ORDER BY s.created_at DESC";
        
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        
        if (!empty($search)) {
            $like = "%{$search}%";
            $stmt->bindParam(':search', $like);
        }
        
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getSavedItemIds($user_id) {
        $sql = "SELECT item_id FROM saved_items WHERE user_id = :user_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([':user_id' => $user_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    // DELETE FUNCTION (Properly placed outside other functions)
    public function delete($id) {
        $sql = "DELETE FROM marketplace WHERE id = :id";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([':id' => $id]);
    }
}