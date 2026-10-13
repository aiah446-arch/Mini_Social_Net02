<?php
// public/index.php

session_start();

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../app/models/UserModel.php';
require_once __DIR__ . '/../app/models/PostModel.php';
require_once __DIR__ . '/../app/models/CommentModel.php';
require_once __DIR__ . '/../app/models/LikeModel.php';
require_once __DIR__ . '/../app/controllers/AuthController.php';
require_once __DIR__ . '/../app/controllers/PostController.php';
require_once __DIR__ . '/../app/controllers/CommentController.php';
require_once __DIR__ . '/../app/controllers/LikeController.php';
require_once __DIR__ . '/../app/controllers/ProfileController.php';
require_once __DIR__ . '/../app/controllers/SearchController.php';

$page = $_GET['page'] ?? 'feed';

switch ($page) {
    // Auth
    case 'register': (new AuthController())->register(); break;
    case 'login':    (new AuthController())->login();    break;
    case 'logout':   (new AuthController())->logout();   break;

    // Posts
    case 'post.create': (new PostController())->create(); break;
    case 'post.edit':   (new PostController())->edit();   break;
    case 'post.delete': (new PostController())->delete(); break;

    // Comments
    case 'comment.create': (new CommentController())->create(); break;
    case 'comment.edit':   (new CommentController())->edit();   break;
    case 'comment.delete': (new CommentController())->delete(); break;

    // Likes
    case 'like.toggle': (new LikeController())->toggle(); break;

    // Profile
    case 'profile':      (new ProfileController())->index(); break;
    case 'profile.edit': (new ProfileController())->edit();  break;

    // Search
    case 'search': (new SearchController())->index(); break;

    // Feed (default)
    case 'feed':
    default:
        (new PostController())->feed();
        break;
}