<?php
// app/controllers/SearchController.php
// Handles search: users and posts

require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/PostModel.php';

class SearchController {
    private $userModel;
    private $postModel;

    public function __construct() {
        $db = (new Database())->getConnection();
        $this->userModel = new UserModel($db);
        $this->postModel = new PostModel($db);
    }

    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }

        $keyword = trim($_GET['q'] ?? '');
        $type = $_GET['type'] ?? 'all'; // all | users | posts

        $users = [];
        $posts = [];

        if ($keyword !== '') {
            if ($type === 'all' || $type === 'users') {
                $users = $this->userModel->search($keyword);
            }
            if ($type === 'all' || $type === 'posts') {
                $posts = $this->postModel->search($keyword);
            }
        }

        $pageTitle = 'Search - Mini Social';
        require __DIR__ . '/../views/search/index.php';
    }
}