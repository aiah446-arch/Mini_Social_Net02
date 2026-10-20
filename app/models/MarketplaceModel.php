<?php
class MarketplaceModel {
    private PDO $db;
    public function __construct(PDO $db) { $this->db = $db; }
    public function all(string $search = ''): array {
        $sql = "SELECT p.*, u.username, u.full_name, u.profile_image FROM marketplace_products p JOIN users u ON u.id=p.user_id WHERE p.status='available'";
        $params = [];
        if ($search !== '') { $sql .= " AND (p.title LIKE :q OR p.description LIKE :q OR p.category LIKE :q)"; $params[':q'] = '%' . $search . '%'; }
        $sql .= " ORDER BY p.created_at DESC";
        $st = $this->db->prepare($sql); $st->execute($params); return $st->fetchAll();
    }
    public function find(int $id): ?array {
        $st=$this->db->prepare("SELECT * FROM marketplace_products WHERE id=?"); $st->execute([$id]); $r=$st->fetch(); return $r ?: null;
    }
    public function create(int $userId, array $d): bool {
        $st=$this->db->prepare("INSERT INTO marketplace_products (user_id,title,description,price,category,condition_label,image) VALUES (:uid,:title,:description,:price,:category,:condition_label,:image)");
        return $st->execute([':uid'=>$userId, ':title'=>$d['title'], ':description'=>$d['description'], ':price'=>$d['price'], ':category'=>$d['category'], ':condition_label'=>$d['condition_label'], ':image'=>$d['image']]);
    }
    public function update(int $id, int $userId, array $d): bool {
        $st=$this->db->prepare("UPDATE marketplace_products SET title=:title,description=:description,price=:price,category=:category,condition_label=:condition_label,image=COALESCE(:image,image) WHERE id=:id AND user_id=:uid");
        return $st->execute([':title'=>$d['title'], ':description'=>$d['description'], ':price'=>$d['price'], ':category'=>$d['category'], ':condition_label'=>$d['condition_label'], ':image'=>$d['image'], ':id'=>$id, ':uid'=>$userId]);
    }
    public function delete(int $id, int $userId): bool {
        $st=$this->db->prepare("DELETE FROM marketplace_products WHERE id=? AND user_id=?"); return $st->execute([$id,$userId]);
    }
}
