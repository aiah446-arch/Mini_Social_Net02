<?php
require_once __DIR__ . '/../models/MarketplaceModel.php';
class MarketplaceController {
    private MarketplaceModel $model;
    public function __construct() { $this->model = new MarketplaceModel((new Database())->getConnection()); }
    private function requireLogin(): int {
        if (empty($_SESSION['user_id'])) { header('Location: index.php?page=login'); exit; }
        return (int)$_SESSION['user_id'];
    }
    private function token(): void {
        if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $sent = $_POST['csrf_token'] ?? '';
            if (!hash_equals($_SESSION['csrf_token'], $sent)) { http_response_code(403); exit('Invalid form token. Please go back and try again.'); }
        }
    }
    private function uploadImage(?string $old = null): ?string {
        if (empty($_FILES['image']['name'])) return $old;
        if ($_FILES['image']['error'] !== UPLOAD_ERR_OK) throw new RuntimeException('The image could not be uploaded.');
        if ($_FILES['image']['size'] > 5 * 1024 * 1024) throw new RuntimeException('Please choose an image smaller than 5 MB.');
        $finfo = new finfo(FILEINFO_MIME_TYPE); $mime = $finfo->file($_FILES['image']['tmp_name']);
        $allowed = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
        if (!isset($allowed[$mime])) throw new RuntimeException('Please use a JPG, PNG, or WEBP image.');
        $dir = __DIR__ . '/../../public/assets/uploads/marketplace/';
        if (!is_dir($dir) && !mkdir($dir, 0755, true)) throw new RuntimeException('Upload folder could not be created.');
        $name = 'product_' . bin2hex(random_bytes(12)) . '.' . $allowed[$mime];
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $dir . $name)) throw new RuntimeException('Image upload failed.');
        return $name;
    }
    private function cleanForm(): array {
        $title=trim($_POST['title'] ?? ''); $description=trim($_POST['description'] ?? '');
        $price=$_POST['price'] ?? ''; $category=trim($_POST['category'] ?? 'Other'); $condition=trim($_POST['condition_label'] ?? 'Good');
        if ($title==='' || mb_strlen($title)>120) throw new RuntimeException('Enter a product name (up to 120 characters).');
        if ($description==='' || mb_strlen($description)>3000) throw new RuntimeException('Enter a description (up to 3,000 characters).');
        if (!is_numeric($price) || (float)$price < 0 || (float)$price > 99999999) throw new RuntimeException('Enter a valid price.');
        $categories=['Books & School','Electronics','Clothing','Food & Crafts','Accessories','Other'];
        $conditions=['New','Like New','Good','Fair'];
        if (!in_array($category,$categories,true)) $category='Other';
        if (!in_array($condition,$conditions,true)) $condition='Good';
        return ['title'=>$title,'description'=>$description,'price'=>round((float)$price,2),'category'=>$category,'condition_label'=>$condition];
    }
    public function index(): void {
        $this->requireLogin(); $this->token();
        $search=trim($_GET['q'] ?? ''); $products=$this->model->all($search); $pageTitle='Campus Marketplace | NEO';
        require __DIR__ . '/../views/marketplace/index.php';
    }
    public function create(): void {
        $uid=$this->requireLogin(); $this->token(); $error=''; $data=['title'=>'','description'=>'','price'=>'','category'=>'Other','condition_label'=>'Good','image'=>null];
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            try { $data=array_merge($this->cleanForm(), ['image'=>$this->uploadImage()]); $this->model->create($uid,$data); header('Location: index.php?page=marketplace&posted=1'); exit; }
            catch (Throwable $e) { $error=$e->getMessage(); $data=array_merge($data, array_intersect_key($_POST,array_flip(['title','description','price','category','condition_label']))); }
        }
        $pageTitle='Sell an Item | NEO'; require __DIR__ . '/../views/marketplace/form.php';
    }
    public function edit(): void {
        $uid=$this->requireLogin(); $this->token(); $id=(int)($_GET['id'] ?? 0); $product=$this->model->find($id);
        if (!$product || (int)$product['user_id']!==$uid) { http_response_code(404); exit('Listing not found or you do not have permission to edit it.'); }
        $error=''; $data=$product;
        if ($_SERVER['REQUEST_METHOD']==='POST') {
            try { $data=array_merge($product,$this->cleanForm(),['image'=>$this->uploadImage($product['image'])]); $this->model->update($id,$uid,$data); header('Location: index.php?page=marketplace&updated=1'); exit; }
            catch (Throwable $e) { $error=$e->getMessage(); $data=array_merge($data,array_intersect_key($_POST,array_flip(['title','description','price','category','condition_label']))); }
        }
        $pageTitle='Edit Listing | NEO'; require __DIR__ . '/../views/marketplace/form.php';
    }
    public function delete(): void {
        $uid=$this->requireLogin(); $this->token();
        if ($_SERVER['REQUEST_METHOD']!=='POST') { header('Location: index.php?page=marketplace'); exit; }
        $this->model->delete((int)($_POST['id'] ?? 0),$uid); header('Location: index.php?page=marketplace&deleted=1'); exit;
    }
}
