<?php
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/MarketplaceModel.php';

class MarketplaceController {
    private $marketModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->marketModel = new MarketplaceModel($db);
    }

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    public function index() {
        $this->requireLogin();
        $items = $this->marketModel->getAllItems();
        $savedIds = $this->marketModel->getSavedItemIds($_SESSION['user_id']);
        
        $pageTitle = 'Marketplace - NEO';
        require __DIR__ . '/../views/marketplace/index.php';
    }

    public function create() {
        $this->requireLogin();
        $error = '';
        $success = false;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $item_name = trim($_POST['item_name'] ?? '');
            $price = (float)($_POST['price'] ?? 0);
            $description = trim($_POST['description'] ?? '');
            $image = null;

            if (empty($item_name) || empty($price)) {
                $error = 'Item name and a valid price are required.';
            } else {
                if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
                    $file = $_FILES['image'];
                    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
                    $filename = 'market_' . $_SESSION['user_id'] . '_' . time() . '.' . $ext;
                    $uploadPath = __DIR__ . '/../../public/assets/uploads/' . $filename;
                    
                    if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
                        $image = $filename;
                    }
                }

                if ($this->marketModel->create($_SESSION['user_id'], $item_name, $price, $description, $image)) {
                    $success = true;
                } else {
                    $error = 'Failed to save item to the database.';
                }
            }
        }

        $pageTitle = 'Sell an Item - NEO';
        require __DIR__ . '/../views/marketplace/create.php';
    }

    public function view() {
        $this->requireLogin();
        $id = (int)($_GET['id'] ?? 0);
        $item = $this->marketModel->getItemById($id);
        
        if (!$item) {
            header('Location: index.php?page=marketplace');
            exit;
        }

        $pageTitle = $item['item_name'] . ' - NEO';
        require __DIR__ . '/../views/marketplace/view.php';
    }

    public function toggleSave() {
        $this->requireLogin();
        $item_id = (int)($_GET['id'] ?? 0);
        $user_id = $_SESSION['user_id'];

        $status = 'error';
        if ($item_id > 0) {
            if ($this->marketModel->isSaved($user_id, $item_id)) {
                $this->marketModel->unsaveItem($user_id, $item_id);
                $status = 'unsaved';
            } else {
                $this->marketModel->saveItem($user_id, $item_id);
                $status = 'saved';
            }
        }

        header('Content-Type: application/json');
        echo json_encode(['status' => $status]);
        exit;
    }

    public function saved() {
        $this->requireLogin();
        
        $search = trim($_GET['search'] ?? '');
        $filter = $_GET['filter'] ?? 'all';
        
        $items = $this->marketModel->getSavedItems($_SESSION['user_id'], $search, $filter);
        
        $pageTitle = 'Saved Items - NEO';
        require __DIR__ . '/../views/marketplace/saved.php';
    }

    // NEW: Delete function
    public function delete() {
        $this->requireLogin();
        
        $id = (int)($_GET['id'] ?? 0);
        $item = $this->marketModel->getItemById($id);

        // Check if item exists AND belongs to the logged-in user
        if ($item && $item['user_id'] == $_SESSION['user_id']) {
            
            // Delete the image file from the server
            if (!empty($item['image'])) {
                $imgPath = __DIR__ . '/../../public/assets/uploads/' . $item['image'];
                if (file_exists($imgPath)) {
                    unlink($imgPath);
                }
            }

            // Delete from database
            $this->marketModel->delete($id);
        }

        header('Location: index.php?page=marketplace');
        exit;
    }

    public function edit() { 
        $this->requireLogin(); 
        // You can implement edit later if you want
        header('Location: index.php?page=marketplace'); 
        exit; 
    }
}