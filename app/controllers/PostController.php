<?php
// app/controllers/PostController.php
// Handles posts: feed, create, edit, delete

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/PostModel.php';

class PostController {
    private $postModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->postModel = new PostModel($db);
    }

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
    }

    // Handle file upload — returns filename or null
    private function handleUpload($fileKey) {
        if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
            return ['filename' => null, 'error' => null];
        }

        $file = $_FILES[$fileKey];
        $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
        $maxSize = 3 * 1024 * 1024; // 3MB

        if (!in_array($file['type'], $allowed)) {
            return ['filename' => null, 'error' => 'Only JPG, PNG, GIF, or WEBP images allowed.'];
        }
        if ($file['size'] > $maxSize) {
            return ['filename' => null, 'error' => 'Image must be under 3MB.'];
        }

        $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = 'post_' . $_SESSION['user_id'] . '_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
        $uploadPath = __DIR__ . '/../../public/assets/uploads/' . $filename;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return ['filename' => $filename, 'error' => null];
        }

        return ['filename' => null, 'error' => 'Failed to upload image.'];
    }

    // -------------------------
    // NEWSFEED
    // -------------------------
    public function feed() {
        $this->requireLogin();
        $posts = $this->postModel->getAll();
        $pageTitle = 'Newsfeed - NEO';
        require __DIR__ . '/../views/posts/feed.php';
    }

        // -------------------------
    // MARKETPLACE
    // -------------------------
    public function marketplace() {
    $this->requireLogin();
    $products = $this->postModel->getMarketplaceItems();
    $pageTitle = 'Marketplace - NEO';
    require __DIR__ . '/../views/posts/marketplace.php'; // <--- FIXED
}
    // -------------------------
    // CREATE POST (Handles Social & Products)
    // -------------------------
    public function create() {
        $this->requireLogin();

        $error = '';
        $content = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = trim($_POST['content'] ?? '');
            $post_type = $_POST['post_type'] ?? 'social';
            
            // Product specific fields
            $product_title = !empty($_POST['product_title']) ? trim($_POST['product_title']) : null;
            $price = !empty($_POST['price']) ? floatval($_POST['price']) : null;
            $product_condition = !empty($_POST['product_condition']) ? $_POST['product_condition'] : null;
            $category = !empty($_POST['category']) ? $_POST['category'] : null;
            
            $image = null;

            // If it's a product, content might be optional, but let's require a title
            if ($post_type === 'product' && empty($product_title)) {
                $error = 'Product title is required for listings.';
            } elseif ($post_type === 'social' && $content === '') {
                $error = 'Post content cannot be empty.';
            } elseif (strlen($content) > 1000) {
                $error = 'Post is too long (max 1000 characters).';
            } else {
                $upload = $this->handleUpload('image');
                if ($upload['error']) {
                    $error = $upload['error'];
                } else {
                    $image = $upload['filename'];
                }

                if (empty($error)) {
                    // Pass all the new variables to the Model
                    if ($this->postModel->create(
                        $_SESSION['user_id'], 
                        $content, 
                        $image, 
                        $post_type, 
                        $product_title, 
                        $price, 
                        $product_condition, 
                        $category
                    )) {
                        // If it's a product, maybe redirect to marketplace? For now, feed is fine.
                        header('Location: index.php?page=feed');
                        exit;
                    } else {
                        $error = 'Failed to create post. Please try again.';
                    }
                }
            }
        }

        $pageTitle = 'Create Post - NEO';
        require __DIR__ . '/../views/posts/create.php';
    }

    // -------------------------
    // EDIT POST
    // -------------------------
    public function edit() {
        $this->requireLogin();

        $id = (int)($_GET['id'] ?? 0);
        $post = $this->postModel->findById($id);

        if (!$post) {
            header('Location: index.php?page=feed');
            exit;
        }
        if ($post['user_id'] != $_SESSION['user_id']) {
            header('Location: index.php?page=feed');
            exit;
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $content = trim($_POST['content'] ?? '');
            $image = null;

            if ($content === '') {
                $error = 'Post content cannot be empty.';
            } elseif (strlen($content) > 1000) {
                $error = 'Post is too long (max 1000 characters).';
            } else {
                $upload = $this->handleUpload('image');
                if ($upload['error']) {
                    $error = $upload['error'];
                } else {
                    $image = $upload['filename'];

                    if ($image !== null && !empty($post['image'])) {
                        $oldPath = __DIR__ . '/../../public/assets/uploads/' . $post['image'];
                        if (file_exists($oldPath)) {
                            unlink($oldPath);
                        }
                    }
                }

                if (empty($error)) {
                    if ($this->postModel->update($id, $content, $image)) {
                        header('Location: index.php?page=feed');
                        exit;
                    } else {
                        $error = 'Failed to update post.';
                    }
                }
            }
        }

        $pageTitle = 'Edit Post - NEO';
        require __DIR__ . '/../views/posts/edit.php';
    }

    // -------------------------
    // DELETE POST
    // -------------------------
    public function delete() {
        $this->requireLogin();

        $id = (int)($_GET['id'] ?? 0);
        $post = $this->postModel->findById($id);

        if ($post && $post['user_id'] == $_SESSION['user_id']) {
            if (!empty($post['image'])) {
                $imgPath = __DIR__ . '/../../public/assets/uploads/' . $post['image'];
                if (file_exists($imgPath)) {
                    unlink($imgPath);
                }
            }
            $this->postModel->delete($id);
        }

        header('Location: index.php?page=feed');
        exit;
    }
}