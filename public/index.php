<?php
// public/index.php

session_start();

// Load config
require_once dirname(__DIR__) . '/config/database.php';

// Load models
require_once dirname(__DIR__) . '/app/models/UserModel.php';
require_once dirname(__DIR__) . '/app/models/PostModel.php';
require_once dirname(__DIR__) . '/app/models/CommentModel.php';
require_once dirname(__DIR__) . '/app/models/LikeModel.php';
require_once dirname(__DIR__) . '/app/models/MarketplaceModel.php';
require_once dirname(__DIR__) . '/app/models/NotificationModel.php';
require_once dirname(__DIR__) . '/app/models/MessageModel.php';

// Load controllers
require_once dirname(__DIR__) . '/app/controllers/AuthController.php';
require_once dirname(__DIR__) . '/app/controllers/PostController.php';
require_once dirname(__DIR__) . '/app/controllers/CommentController.php';
require_once dirname(__DIR__) . '/app/controllers/LikeController.php';
require_once dirname(__DIR__) . '/app/controllers/ProfileController.php';
require_once dirname(__DIR__) . '/app/controllers/SearchController.php';
require_once dirname(__DIR__) . '/app/controllers/MarketplaceController.php';
require_once dirname(__DIR__) . '/app/controllers/NotificationController.php';
require_once dirname(__DIR__) . '/app/controllers/MessageController.php';

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
    case 'post.share':  (new PostController())->share();  break;

    // Comments
    case 'comment.create': (new CommentController())->create(); break;
    case 'comment.edit':   (new CommentController())->edit();   break;
    case 'comment.delete': (new CommentController())->delete(); break;

    // Likes
    case 'like.toggle': (new LikeController())->toggle(); break;

    // Profile & Follow
    case 'profile':        (new ProfileController())->index(); break;
    case 'profile.edit':   (new ProfileController())->edit();  break;
    case 'profile.follow': (new ProfileController())->followAction(); break;

    // Search
    case 'search': (new SearchController())->index(); break;

    // Notifications
    case 'notifications': (new NotificationController())->index(); break;

    // Campus Marketplace
    case 'marketplace':        (new MarketplaceController())->index(); break;
    case 'marketplace.create': (new MarketplaceController())->create(); break;
    case 'marketplace.view':   (new MarketplaceController())->view(); break;
    case 'marketplace.save':   (new MarketplaceController())->toggleSave(); break;
    case 'marketplace.saved':  (new MarketplaceController())->saved(); break;
    case 'marketplace.delete': (new MarketplaceController())->delete(); break; // <--- ADDED THIS
    
    // Chats (Direct Messaging)
    case 'chats': (new MessageController())->index(); break;
    case 'chats.send': (new MessageController())->send(); break;

    // Feed (default)
    case 'feed':
    default:
        (new PostController())->feed();
        break;
}