<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
require_once __DIR__ . '/../../models/CommentModel.php';
require_once __DIR__ . '/../../models/LikeModel.php';
require_once __DIR__ . '/../../models/UserModel.php';
require_once __DIR__ . '/../../models/NotificationModel.php';
require_once __DIR__ . '/../../models/PostModel.php';

$db = (new Database())->getConnection();
$commentModel = new CommentModel($db);
$likeModel = new LikeModel($db);
$userModel = new UserModel($db);
$notifModel = new NotificationModel($db);
$postModel = new PostModel($db);

$unreadCount = $notifModel->countUnread($_SESSION['user_id']);
?>

<!-- Custom CSS for Full-Screen 3-Column Layout -->
<style>
        .neo-layout-wrapper {
        display: flex;
        height: calc(100vh - 80px);
        overflow: visible;
        width: 100%;
        margin: 0;
    }
    
    .neo-sidebar-left {
        width: 80px;
        flex-shrink: 0;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 20px 0;
        border-right: 1px solid var(--glass-chrome-1);
        background: var(--glass-bg);
        overflow: visible;
        position: relative;
        z-index: 20;
    }
    .neo-sidebar-right {
        width: 300px;
        flex-shrink: 0;
        padding: 20px;
        overflow-y: auto;
        border-left: 1px solid var(--glass-chrome-1);
        background: var(--glass-bg);
    }
    .neo-feed-center {
        flex-grow: 1;
        overflow-y: auto;
        padding: 20px 20px;
        background: var(--bg-base);
    }
    .neo-nav-btn {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        color: var(--text-muted);
        font-size: 1.4rem;
        margin: 0 auto 15px auto;
        text-decoration: none;
        transition: 0.2s;
        position: relative;
    }
    .neo-nav-btn:hover, .neo-nav-btn.active {
        background: var(--glass-bg-2);
        color: var(--accent);
        transform: scale(1.05);
    }
    .neo-nav-btn.active {
        background: var(--brand-color);
        color: var(--bg-base);
        box-shadow: 0 4px 15px rgba(74, 222, 128, 0.3);
    }
    .neo-feed-center::-webkit-scrollbar { width: 6px; }
    .neo-feed-center::-webkit-scrollbar-thumb { background: var(--glass-chrome-2); border-radius: 10px; }

    /* ===== "More" menu (Instagram-style popup) ===== */
    .more-wrap { position: relative; }

    .more-menu {
        --more-bg: #262626; --more-hover: #3a3a3a; --more-text: #f5f5f5;
        --more-line: #3a3a3a; --more-gap: #1a1a1a;
        position: absolute;
        left: calc(100% + 6px);
        bottom: 0;
        width: 266px;
        padding: 8px;
        text-align: left;
        background: var(--more-bg);
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(0, 0, 0, .55);
        z-index: 2000;
    }
    [data-theme="light"] .more-menu {
        --more-bg: #ffffff; --more-hover: #e8f3ec; --more-text: #051F20;
        --more-line: #cfe3d6; --more-gap: #eef6f0;
    }
    .more-menu[hidden] { display: none; }

    .more-item {
        display: flex; align-items: center; gap: 12px;
        width: 100%; padding: 16px;
        border: 0; border-radius: 8px; background: transparent;
        color: var(--more-text) !important;
        font-size: 14px; font-family: inherit; text-align: left;
        text-decoration: none !important; cursor: pointer;
    }
    .more-item i { font-size: 20px; }
    .more-item:hover {
        background: var(--more-hover);
        color: var(--more-text) !important;
        text-decoration: none !important;
    }
    .more-item.plain { padding: 16px 12px; }

    .more-divider { height: 1px; background: var(--more-line); margin: 8px 0; }
    .more-divider.thick { height: 6px; background: var(--more-gap); margin: 8px -8px; }

    #moreBtn[aria-expanded="true"] { background: var(--glass-bg-2); color: var(--accent); }

    /* ===== Redesigned Share Box ===== */
    .share-modal {
        background: var(--glass-bg-3);
        border: 1px solid var(--glass-chrome-1);
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        overflow: hidden;
    }
    .share-modal-header {
        padding: 12px 16px;
        border-bottom: 1px solid var(--glass-chrome-2);
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .share-modal-body { padding: 16px; }
    .share-input-large {
        width: 100%; background: transparent; border: none;
        color: var(--text-primary); font-size: 16px; resize: none; outline: none;
    }
    .share-input-large::placeholder { color: var(--text-muted); }
    .share-preview {
        background: var(--glass-bg-2);
        border: 1px solid var(--glass-chrome-2);
        border-radius: 8px; padding: 12px; margin-top: 12px;
    }
    .share-modal-footer {
        padding: 12px 16px;
        border-top: 1px solid var(--glass-chrome-2);
        display: flex; justify-content: flex-end; gap: 10px;
        background: var(--glass-bg-2);
    }

    /* ===== Custom 3-Dots Dropdown ===== */
    .custom-dropdown-menu {
        background: var(--glass-bg-3);
        border: 1px solid var(--glass-chrome-1);
        border-radius: 8px; min-width: 150px; z-index: 1050;
        padding: 8px 0; box-shadow: 0 4px 15px rgba(0,0,0,0.5);
    }
    .custom-dropdown-menu li a {
        display: block; padding: 8px 16px;
        color: var(--text-primary); text-decoration: none;
        font-size: 14px; transition: 0.2s;
    }
    .custom-dropdown-menu li a:hover { background: var(--glass-bg-2); }
    .custom-dropdown-menu li a.text-danger:hover { background: rgba(220, 53, 69, 0.1); }

    /* ===== Mobile bottom navigation + slide-up menu (phones / tablets) ===== */
    .neo-bottom-nav, .neo-sheet, .neo-sheet-backdrop { display: none; }

    .neo-sheet {
        --more-bg: #262626; --more-hover: #3a3a3a; --more-text: #f5f5f5;
        --more-line: #3a3a3a; --more-gap: #1a1a1a;
    }
    [data-theme="light"] .neo-sheet {
        --more-bg: #ffffff; --more-hover: #e8f3ec; --more-text: #051F20;
        --more-line: #cfe3d6; --more-gap: #eef6f0;
    }

    @media (max-width: 991px) {
        /* the two side bars are replaced by the bottom bar + slide-up menu */
        .neo-sidebar-left, .neo-sidebar-right { display: none; }
        .neo-layout-wrapper { height: calc(100vh - 80px); height: calc(100dvh - 80px); }
        .neo-feed-center { padding: 10px 10px 90px; }   /* room for the bottom bar */

        .neo-bottom-nav {
            display: flex; justify-content: space-around; align-items: center;
            position: fixed; left: 0; right: 0; bottom: 0; z-index: 1500;
            height: 64px; padding-bottom: env(safe-area-inset-bottom);
            background: var(--glass-bg-3);
            backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px);
            border-top: 1px solid var(--glass-chrome-1);
        }
        .neo-bottom-nav .neo-nav-btn { margin: 0; width: 46px; height: 46px; background: transparent; border: 0; }
        .neo-bottom-nav .neo-nav-btn.active { background: var(--brand-color); }

        .neo-sheet-backdrop { display: block; position: fixed; inset: 0; background: rgba(0, 0, 0, .55); z-index: 1900; }
        .neo-sheet {
            display: block; position: fixed; left: 0; right: 0; bottom: 0; z-index: 2000;
            max-height: 80vh; overflow-y: auto;
            padding: 10px 8px calc(16px + env(safe-area-inset-bottom));
            background: var(--more-bg);
            border-radius: 20px 20px 0 0;
            box-shadow: 0 -8px 30px rgba(0, 0, 0, .55);
        }
        .neo-sheet-handle { width: 40px; height: 4px; border-radius: 4px; background: var(--more-line); margin: 4px auto 10px; }
        .neo-sheet .more-item { padding: 14px 16px; }
        .neo-sheet .more-item .badge { margin-left: auto; }
    }
    .neo-sheet[hidden], .neo-sheet-backdrop[hidden] { display: none !important; }
</style>

<div class="neo-layout-wrapper">
    
    <!-- FAR LEFT: NAVIGATION SIDEBAR -->
    <div class="neo-sidebar-left">
        <div class="w-100 text-center">
            <a href="index.php?page=feed" class="neo-nav-btn active" title="News Feed"><i class="bi bi-house-door-fill"></i></a>
            <a href="index.php?page=chats" class="neo-nav-btn" title="Chats">
                <i class="bi bi-chat-dots-fill"></i>
                <?php if (isset($unreadMsgs) && $unreadMsgs > 0): ?>
                    <span class="badge bg-danger rounded-pill" style="position: absolute; top: 5px; right: 5px; font-size: 0.55rem; padding: 3px 5px; border: 1px solid var(--bg-base);"><?= $unreadMsgs ?></span>
                <?php endif; ?>
            </a>
            <a href="index.php?page=search" class="neo-nav-btn" title="Search"><i class="bi bi-search"></i></a>
            <a href="index.php?page=profile&id=<?= $_SESSION['user_id'] ?>" class="neo-nav-btn" title="Profile">
                <?php if (!empty($currentUser['profile_image']) && $currentUser['profile_image'] !== 'default.png'): ?>
                    <img src="assets/uploads/<?= htmlspecialchars($currentUser['profile_image']) ?>" alt="Profile" style="width: 32px; height: 32px; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent);">
                <?php else: ?>
                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.9rem; border: 2px solid var(--accent);">
                        <?= strtoupper(substr($currentUser['full_name'], 0, 1)) ?>
                    </div>
                <?php endif; ?>
            </a>
        </div>

        <div class="w-100 text-center more-wrap">
            <button type="button" id="moreBtn" class="neo-nav-btn border-0 bg-transparent"
                    title="More" aria-haspopup="true" aria-expanded="false" aria-controls="moreMenu">
                <i class="bi bi-list"></i>
            </button>

            <div id="moreMenu" class="more-menu" hidden>
                <a class="more-item" href="index.php?page=profile.edit"><i class="bi bi-gear"></i><span>Settings</span></a>
                <a class="more-item" href="index.php?page=notifications"><i class="bi bi-activity"></i><span>Your activity</span></a>
                <a class="more-item" href="index.php?page=marketplace.saved"><i class="bi bi-bookmark"></i><span>Saved</span></a>
                <button type="button" class="more-item" id="themeToggleBtn" role="switch"><i class="bi bi-moon"></i><span>Switch appearance</span><span class="tg" aria-hidden="true"></span></button>
                <div class="more-divider"></div>
                <a class="more-item plain" href="index.php?page=logout">Log out</a>
            </div>
        </div>
    </div>

    <!-- CENTER: MAIN FEED -->
    <div class="neo-feed-center">
        
        <!-- Create Post Box -->
        <div class="card mb-4 shadow-sm mx-auto" style="border: 1px solid var(--glass-chrome-1); max-width: 1000px;">
            <div class="card-body p-4">
                <form method="POST" action="index.php?page=post.create" enctype="multipart/form-data">
                    <div class="d-flex gap-3 mb-3 align-items-start">
                        <?php if (!empty($currentUser['profile_image']) && $currentUser['profile_image'] !== 'default.png'): ?>
                            <img src="assets/uploads/<?= htmlspecialchars($currentUser['profile_image']) ?>" alt="User" class="rounded-circle" style="width: 45px; height: 45px; object-fit: cover; border: 2px solid var(--accent);">
                        <?php else: ?>
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; font-size: 1.2rem; border: 2px solid var(--accent);">
                                <?= strtoupper(substr($currentUser['full_name'], 0, 1)) ?>
                            </div>
                        <?php endif; ?>
                        <textarea name="content" class="form-control" rows="2" placeholder="What's on your mind, <?= htmlspecialchars($_SESSION['full_name']) ?>?" style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); resize: none;"></textarea>
                    </div>
                    <div id="feedPreviewBox" class="flex-wrap gap-2 mb-2 ms-5" style="display: none;"></div>
                    <div class="d-flex justify-content-between align-items-center border-top pt-3" style="border-color: var(--glass-chrome-2) !important;">
                        <div class="d-flex gap-2">
                            <input type="file" name="images[]" id="feedImageInput" accept="image/*" multiple style="display: none;">
                            <label for="feedImageInput" class="btn btn-sm btn-outline-secondary mb-0" id="feedImageLabel" style="cursor: pointer;"><i class="bi bi-image"></i> Photo</label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="bi bi-send"></i> Post</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Feed Posts -->
        <div id="feed-container" class="mx-auto" style="max-width: 1000px;">
            <?php if (empty($posts)): ?>
                <div class="card text-center py-5" style="border: 1px solid var(--glass-chrome-1);">
                    <div class="card-body">
                        <div class="empty-state-icon mx-auto mb-3"><i class="bi bi-chat-square-text"></i></div>
                        <h5 class="mb-1">No posts yet</h5>
                        <p class="text-muted mb-0">Be the first to share something!</p>
                    </div>
                </div>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <?php
                    $isMarket = (isset($post['type']) && $post['type'] === 'marketplace');
                    $itemType = $isMarket ? 'marketplace' : 'post';
                    $comments = $commentModel->getByPost($post['id'], $itemType);
                    $likeCount = $likeModel->countByPost($post['id'], $itemType);
                    $liked = $likeModel->hasLiked($post['id'], $_SESSION['user_id'], $itemType);
                    $isFollowing = ($post['user_id'] != $_SESSION['user_id']) ? $userModel->isFollowing($_SESSION['user_id'], $post['user_id']) : false;
                    $originalPost = null;
                    if (!empty($post['shared_post_id'])) {
                        $originalType = $post['shared_type'] ?? 'post';
                        $originalPost = $postModel->getOriginalPost($post['shared_post_id'], $originalType);
                    }
                    ?>
                    <div class="card mb-4 shadow-sm" id="post-<?= $post['id'] ?>" style="border: 1px solid var(--glass-chrome-1); position: relative;">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <div class="d-flex gap-3 align-items-center">
                                    <a href="index.php?page=profile&id=<?= $post['user_id'] ?>" class="flex-shrink-0">
                                        <?php if (!empty($post['profile_image']) && $post['profile_image'] !== 'default.png'): ?>
                                            <img src="assets/uploads/<?= htmlspecialchars($post['profile_image']) ?>" alt="User" class="rounded-circle" style="width: 46px; height: 46px; object-fit: cover; border: 2px solid var(--accent);">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; font-size: 1.2rem; border: 2px solid var(--accent);"><?= strtoupper(substr($post['full_name'], 0, 1)) ?></div>
                                        <?php endif; ?>
                                    </a>
                                    <div>
                                        <div class="fw-bold" style="font-size: 15px;">
                                            <a href="index.php?page=profile&id=<?= $post['user_id'] ?>" class="text-decoration-none" style="color: var(--text-primary);"><?= htmlspecialchars($post['full_name']) ?></a>
                                            <span class="text-muted fw-normal" style="font-size: 13px;">@<?= htmlspecialchars($post['username']) ?></span>
                                        </div>
                                        <div class="text-muted" style="font-size: 12px;"><?= date('M j, Y g:i A', strtotime($post['created_at'])) ?> · <i class="bi bi-globe"></i></div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
                                        <div class="dropdown-wrapper" style="position: relative;">
                                            <button class="btn btn-link text-muted p-0 shadow-none custom-dots-btn" data-target="dots-menu-<?= $post['id'] ?>">
                                                <i class="bi bi-three-dots fs-5"></i>
                                            </button>
                                            <ul class="custom-dropdown-menu" id="dots-menu-<?= $post['id'] ?>" style="display: none; position: absolute; right: 0; top: 100%;">
                                                <?php if ($isMarket): ?>
                                                    <li><a href="index.php?page=marketplace">Manage Item</a></li>
                                                <?php else: ?>
                                                    <li><a href="index.php?page=post.edit&id=<?= $post['id'] ?>"><i class="bi bi-pencil me-2"></i> Edit</a></li>
                                                    <li><a href="index.php?page=post.delete&id=<?= $post['id'] ?>" class="text-danger" onclick="return confirm('Delete this post?');"><i class="bi bi-trash me-2"></i> Delete</a></li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-sm follow-btn-feed user-<?= $post['user_id'] ?> <?= $isFollowing ? 'btn-primary' : 'btn-outline-secondary' ?>" data-userid="<?= $post['user_id'] ?>" style="border-radius: 20px; font-weight: 600; font-size: 13px; padding: 4px 16px;">
                                            <?= $isFollowing ? 'Following' : 'Follow' ?>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </div>

                            <div class="mb-3" style="font-size: 15px; line-height: 1.6; color: var(--text-primary);">
                                <?php if ($isMarket): ?>
                                    <div class="d-inline-block mb-2 px-2 py-1 rounded" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #10B981; font-size: 12px; font-weight: 600;">
                                        <i class="bi bi-shop"></i> Listed an item in the Marketplace
                                    </div>
                                    <h5 class="fw-bold mb-1" style="color: var(--text-primary) !important;"><?= htmlspecialchars($post['item_name']) ?></h5>
                                    <h4 class="mb-2" style="color: var(--accent);">₱<?= number_format($post['price'], 2) ?></h4>
                                <?php endif; ?>
                                <?= nl2br(htmlspecialchars($post['content'])) ?>

                                <?php if ($originalPost): ?>
                                    <div class="card mt-3 p-3" style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2);">
                                        <div class="d-flex align-items-center gap-2 mb-2">
                                            <?php if (!empty($originalPost['profile_image']) && $originalPost['profile_image'] !== 'default.png'): ?>
                                                <img src="assets/uploads/<?= htmlspecialchars($originalPost['profile_image']) ?>" class="rounded-circle" style="width: 24px; height: 24px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.7rem;">
                                                    <?= strtoupper(substr($originalPost['full_name'], 0, 1)) ?>
                                                </div>
                                            <?php endif; ?>
                                            <span class="fw-bold text-white" style="font-size: 13px;"><?= htmlspecialchars($originalPost['full_name']) ?></span>
                                            <span class="text-muted" style="font-size: 11px;">@<?= htmlspecialchars($originalPost['username']) ?></span>
                                        </div>
                                        <?php if (($originalPost['type'] ?? 'post') === 'marketplace'): ?>
                                            <div class="d-inline-block mb-2 px-2 py-1 rounded" style="background: rgba(16, 185, 129, 0.15); border: 1px solid rgba(16, 185, 129, 0.3); color: #10B981; font-size: 10px; font-weight: 600;">
                                                <i class="bi bi-shop"></i> Marketplace Item
                                            </div>
                                            <h6 class="fw-bold text-white mb-1"><?= htmlspecialchars($originalPost['item_name']) ?></h6>
                                            <h5 class="mb-2" style="color: var(--accent);">₱<?= number_format($originalPost['price'], 2) ?></h5>
                                            <p class="mb-0" style="font-size: 14px;"><?= nl2br(htmlspecialchars($originalPost['content'])) ?></p>
                                        <?php else: ?>
                                            <p class="mb-0" style="font-size: 14px;"><?= nl2br(htmlspecialchars($originalPost['content'])) ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($originalPost['image'])): ?>
                                            <img src="assets/uploads/<?= htmlspecialchars($originalPost['image']) ?>" class="img-fluid rounded mt-2 neo-zoom" style="max-height: 300px; object-fit: cover;">
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php
                            $galleryImages = $post['images'] ?? (!empty($post['image']) ? [$post['image']] : []);
                            require __DIR__ . '/_images.php';
                            ?>

                            <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2" style="border-color: var(--glass-chrome-2) !important;">
                                <div class="d-flex align-items-center gap-2">
                                    <button type="button" class="btn btn-sm btn-link text-decoration-none like-btn <?= $liked ? 'text-danger' : 'text-muted' ?>" data-postid="<?= $post['id'] ?>" data-type="<?= $itemType ?>">
                                        <i class="bi <?= $liked ? 'bi-heart-fill' : 'bi-heart' ?>"></i> <span class="like-count"><?= $likeCount ?></span>
                                    </button>
                                    <button class="btn btn-sm btn-link text-decoration-none text-muted"><i class="bi bi-chat"></i> <?= count($comments) ?></button>
                                    <button type="button" class="btn btn-sm btn-link text-decoration-none text-muted share-btn-trigger" data-postid="<?= $post['id'] ?>" data-type="<?= $itemType ?>">
                                        <i class="bi bi-send"></i> Share
                                    </button>
                                </div>
                            </div>

                            <div class="share-inline-box mt-3 d-none" id="share-box-<?= $post['id'] ?>">
                                <div class="share-modal">
                                    <div class="share-modal-header">
                                        <span class="fw-bold" style="color: var(--text-primary);"><i class="bi bi-arrow-repeat me-2"></i> Share Post</span>
                                        <button type="button" class="btn-close btn-close-white close-share-btn" data-postid="<?= $post['id'] ?>"></button>
                                    </div>
                                    <div class="share-modal-body">
                                        <textarea class="share-input-large share-inline-input" rows="3" placeholder="Add a thought (optional)..."></textarea>
                                        <div class="share-preview">
                                            <div class="d-flex align-items-center gap-2 mb-2">
                                                <?php if (!empty($post['profile_image']) && $post['profile_image'] !== 'default.png'): ?>
                                                    <img src="assets/uploads/<?= htmlspecialchars($post['profile_image']) ?>" class="rounded-circle" style="width: 20px; height: 20px; object-fit: cover;">
                                                <?php else: ?>
                                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 20px; height: 20px; font-size: 0.6rem;">
                                                        <?= strtoupper(substr($post['full_name'], 0, 1)) ?>
                                                    </div>
                                                <?php endif; ?>
                                                <span class="fw-bold" style="font-size: 12px; color: var(--text-primary);"><?= htmlspecialchars($post['full_name']) ?></span>
                                                <span class="text-muted" style="font-size: 10px;">@<?= htmlspecialchars($post['username']) ?></span>
                                            </div>
                                            <p class="mb-0" style="font-size: 13px; color: var(--text-muted);"><?= htmlspecialchars(substr($post['content'], 0, 100)) ?><?= strlen($post['content']) > 100 ? '...' : '' ?></p>
                                        </div>
                                    </div>
                                    <div class="share-modal-footer">
                                        <button type="button" class="btn btn-outline-secondary btn-sm copy-link-btn" data-postid="<?= $post['id'] ?>" title="Copy Link">
                                            <i class="bi bi-link-45deg"></i> Copy Link
                                        </button>
                                        <button type="button" class="btn btn-primary btn-sm submit-inline-share" data-postid="<?= $post['id'] ?>" data-type="<?= $itemType ?>">
                                            <i class="bi bi-send"></i> Share Now
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="border-top pt-3 mt-3" style="border-color: var(--glass-chrome-2) !important;">
                                <div class="mb-3" id="comments-list-<?= $post['id'] ?>">
                                    <?php if (!empty($comments)): ?>
                                        <?php foreach ($comments as $comment): ?>
                                            <?php require __DIR__ . '/../comments/_item.php'; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </div>
                                <form method="POST" action="index.php?page=comment.create" class="d-flex gap-2 comment-form" data-postid="<?= $post['id'] ?>">
                                    <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                    <input type="hidden" name="type" value="<?= $itemType ?>">
                                    <input type="text" name="content" class="form-control form-control-sm comment-input" placeholder="Write a comment..." required maxlength="500" style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); border-radius: 50px;">
                                    <button type="submit" class="btn btn-sm btn-primary" style="border-radius: 50%; width: 32px; height: 32px; padding: 0; display: flex; align-items: center; justify-content: center;"><i class="bi bi-send"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- RIGHT: QUICK ACCESS SIDEBAR -->
    <div class="neo-sidebar-right">
        <div class="card shadow-sm mb-4" style="border: 1px solid var(--glass-chrome-1); background: transparent;">
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <img src="assets/uploads/neo_logo.png" alt="NEO Logo" style="width: 40px; height: 40px; object-fit: contain;">
                    <div>
                        <h5 class="m-0 fw-bold" style="color: var(--text-primary);">NEO</h5>
                        <p class="m-0 text-muted" style="font-size: 11px;">Connect. Share. Shop</p>
                    </div>
                </div>
                <h6 class="fw-bold mb-3 text-uppercase" style="color: var(--accent); font-size: 12px; letter-spacing: 1px;">Quick Access</h6>
                <ul class="list-unstyled m-0">
                    <li class="mb-1"><a href="index.php?page=feed" class="d-flex align-items-center gap-3 text-decoration-none p-2 rounded" style="background: var(--glass-bg-2); color: var(--accent); border: 1px solid var(--glass-chrome-2);"><i class="bi bi-house-door"></i> News Feed</a></li>
                    <li class="mb-1"><a href="index.php?page=marketplace" class="d-flex align-items-center gap-3 text-decoration-none p-2 rounded text-muted" onmouseover="this.style.background='var(--glass-bg-2)'" onmouseout="this.style.background='transparent'"><i class="bi bi-shop"></i> Marketplace</a></li>
                    <li class="mb-1"><a href="index.php?page=marketplace.saved" class="d-flex align-items-center gap-3 text-decoration-none p-2 rounded text-muted" onmouseover="this.style.background='var(--glass-bg-2)'" onmouseout="this.style.background='transparent'"><i class="bi bi-bookmark"></i> Saved Items</a></li>
                    <li class="mb-1">
                        <a href="index.php?page=notifications" class="d-flex align-items-center justify-content-between text-decoration-none p-2 rounded text-muted" onmouseover="this.style.background='var(--glass-bg-2)'" onmouseout="this.style.background='transparent'">
                            <div class="d-flex align-items-center gap-3">
                                <i class="bi bi-bell"></i> Notifications
                            </div>
                            <?php if ($unreadCount > 0): ?>
                                <span class="badge bg-danger rounded-pill" style="font-size: 0.65rem;"><?= $unreadCount ?></span>
                            <?php endif; ?>
                        </a>
                    </li>
                    <li class="mb-1"><a href="index.php?page=profile&id=<?= $_SESSION['user_id'] ?>" class="d-flex align-items-center gap-3 text-decoration-none p-2 rounded text-muted" onmouseover="this.style.background='var(--glass-bg-2)'" onmouseout="this.style.background='transparent'"><i class="bi bi-person"></i> Profile</a></li>
                </ul>
            </div>
        </div>

        <?php
        // small helper for the avatars in the sidebar lists
        function neo_side_avatar($p) {
            if (!empty($p['profile_image']) && $p['profile_image'] !== 'default.png') {
                return '<img src="assets/uploads/' . htmlspecialchars($p['profile_image']) . '" alt="" class="rounded-circle" style="width: 38px; height: 38px; object-fit: cover; border: 2px solid var(--accent);">';
            }
            return '<div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 38px; height: 38px; font-size: 1rem; border: 2px solid var(--accent);">' . htmlspecialchars(strtoupper(substr($p['full_name'], 0, 1))) . '</div>';
        }
        ?>

        <!-- Suggested follows -->
        <?php if (!empty($suggestions)): ?>
        <div class="card shadow-sm mb-4" style="border: 1px solid var(--glass-chrome-1); background: transparent;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 text-uppercase" style="color: var(--accent); font-size: 12px; letter-spacing: 1px;">Suggested for you</h6>
                <?php foreach ($suggestions as $sg): ?>
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <a href="index.php?page=profile&id=<?= (int)$sg['id'] ?>" class="flex-shrink-0"><?= neo_side_avatar($sg) ?></a>
                        <div class="flex-grow-1" style="min-width: 0;">
                            <a href="index.php?page=profile&id=<?= (int)$sg['id'] ?>" class="d-block text-decoration-none fw-semibold text-truncate" style="color: var(--text-primary); font-size: 14px;"><?= htmlspecialchars($sg['full_name']) ?></a>
                            <small class="d-block text-muted text-truncate">@<?= htmlspecialchars($sg['username']) ?></small>
                        </div>
                        <button type="button" class="btn btn-sm btn-outline-secondary follow-btn-feed user-<?= (int)$sg['id'] ?>" data-userid="<?= (int)$sg['id'] ?>" style="border-radius: 20px; font-weight: 600; font-size: 12px; padding: 3px 12px; white-space: nowrap; flex-shrink: 0;">Follow</button>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- Contacts (people you chatted with) -->
        <?php if (!empty($contacts)): ?>
        <div class="card shadow-sm mb-4" style="border: 1px solid var(--glass-chrome-1); background: transparent;">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3 text-uppercase" style="color: var(--accent); font-size: 12px; letter-spacing: 1px;">Contacts</h6>
                <?php foreach ($contacts as $ct): ?>
                    <a href="index.php?page=chats&with=<?= (int)$ct['id'] ?>" class="d-flex align-items-center gap-2 mb-3 text-decoration-none">
                        <span class="flex-shrink-0"><?= neo_side_avatar($ct) ?></span>
                        <span class="flex-grow-1" style="min-width: 0;">
                            <span class="d-block fw-semibold text-truncate" style="color: var(--text-primary); font-size: 14px;"><?= htmlspecialchars($ct['full_name']) ?></span>
                            <small class="d-block text-muted text-truncate">@<?= htmlspecialchars($ct['username']) ?></small>
                        </span>
                        <i class="bi bi-chat-dots text-muted"></i>
                    </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require __DIR__ . '/_lightbox.php'; ?>

<!-- MOBILE: bottom navigation + slide-up menu (shown under 992px only) -->
<nav class="neo-bottom-nav" aria-label="Main navigation">
    <a href="index.php?page=feed" class="neo-nav-btn active" title="News Feed"><i class="bi bi-house-door-fill"></i></a>
    <a href="index.php?page=chats" class="neo-nav-btn" title="Chats"><i class="bi bi-chat-dots-fill"></i></a>
    <a href="index.php?page=search" class="neo-nav-btn" title="Search"><i class="bi bi-search"></i></a>
    <a href="index.php?page=profile&id=<?= (int)$_SESSION['user_id'] ?>" class="neo-nav-btn" title="Profile">
        <?php if (!empty($currentUser['profile_image']) && $currentUser['profile_image'] !== 'default.png'): ?>
            <img src="assets/uploads/<?= htmlspecialchars($currentUser['profile_image']) ?>" alt="Profile" style="width: 30px; height: 30px; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent);">
        <?php else: ?>
            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-size: 0.85rem; border: 2px solid var(--accent);">
                <?= htmlspecialchars(strtoupper(substr($currentUser['full_name'], 0, 1))) ?>
            </div>
        <?php endif; ?>
    </a>
    <button type="button" id="mobileMoreBtn" class="neo-nav-btn" title="Menu" aria-haspopup="true" aria-expanded="false" aria-controls="mobileSheet">
        <i class="bi bi-list"></i>
        <?php if ($unreadCount > 0): ?>
            <span class="badge bg-danger rounded-pill" style="position: absolute; top: 4px; right: 2px; font-size: 0.55rem; padding: 3px 5px;"><?= (int)$unreadCount ?></span>
        <?php endif; ?>
    </button>
</nav>

<div class="neo-sheet-backdrop" id="sheetBackdrop" hidden></div>
<div class="neo-sheet" id="mobileSheet" role="dialog" aria-label="Menu" hidden>
    <div class="neo-sheet-handle"></div>

    <a class="more-item" href="index.php?page=marketplace"><i class="bi bi-shop"></i><span>Marketplace</span></a>
    <a class="more-item" href="index.php?page=marketplace.saved"><i class="bi bi-bookmark"></i><span>Saved Items</span></a>
    <a class="more-item" href="index.php?page=notifications">
        <i class="bi bi-bell"></i><span>Notifications</span>
        <?php if ($unreadCount > 0): ?><span class="badge bg-danger rounded-pill"><?= (int)$unreadCount ?></span><?php endif; ?>
    </a>

    <div class="more-divider"></div>

    <a class="more-item" href="index.php?page=profile.edit"><i class="bi bi-gear"></i><span>Settings</span></a>
    <button type="button" class="more-item" id="mobileThemeBtn" role="switch"><i class="bi bi-moon"></i><span>Switch appearance</span><span class="tg" aria-hidden="true"></span></button>

    <div class="more-divider"></div>
    <a class="more-item plain" href="index.php?page=logout">Log out</a>
</div>

<!-- ALL JAVASCRIPT LOGIC FOR THE FEED (SINGLE SCRIPT BLOCK) -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    // --- 1. Multiple photo picker with previews ---
    var input = document.getElementById('feedImageInput');
    var previewBox = document.getElementById('feedPreviewBox');
    var label = document.getElementById('feedImageLabel');
    var MAX_PHOTOS = 10;
    var picked = (window.DataTransfer ? new DataTransfer() : null);

    function renderPreviews() {
        previewBox.innerHTML = '';
        var files = Array.from(picked.files);
        files.forEach(function (file, i) {
            var wrap = document.createElement('div');
            wrap.className = 'position-relative';
            var im = document.createElement('img');
            im.src = URL.createObjectURL(file);
            im.alt = '';
            im.className = 'rounded-3 border';
            im.style.cssText = 'width: 100px; height: 100px; object-fit: cover; border-color: var(--glass-chrome-2) !important;';
            var rm = document.createElement('button');
            rm.type = 'button';
            rm.title = 'Remove photo';
            rm.className = 'btn btn-sm btn-danger position-absolute';
            rm.style.cssText = 'top: -8px; right: -8px; border-radius: 50%; width: 24px; height: 24px; padding: 0; line-height: 1;';
            rm.innerHTML = '<i class="bi bi-x-lg" style="font-size: 0.6rem;"></i>';
            rm.addEventListener('click', function () {
                var next = new DataTransfer();
                files.forEach(function (f, j) { if (j !== i) next.items.add(f); });
                picked = next;
                input.files = picked.files;
                renderPreviews();
            });
            wrap.appendChild(im);
            wrap.appendChild(rm);
            previewBox.appendChild(wrap);
        });
        var n = files.length;
        previewBox.style.display = n ? 'flex' : 'none';
        label.innerHTML = n ? '<i class="bi bi-images"></i> ' + n + ' photo' + (n > 1 ? 's' : '') + ' (add more)' : '<i class="bi bi-image"></i> Photo';
    }

    if (input && previewBox && picked) {
        input.addEventListener('change', function () {
            Array.from(input.files).forEach(function (f) {
                if (picked.files.length < MAX_PHOTOS && f.type.indexOf('image/') === 0) picked.items.add(f);
            });
            input.files = picked.files;
            renderPreviews();
        });
    }

    // --- 2. "More" menu + Switch appearance ---
    const moreBtn  = document.getElementById('moreBtn');
    const moreMenu = document.getElementById('moreMenu');
    const themeBtn = document.getElementById('themeToggleBtn');
    const htmlElement = document.documentElement;
    function setMenuOpen(open) {
        if (moreMenu && moreBtn) {
            moreMenu.hidden = !open;
            moreBtn.setAttribute('aria-expanded', String(open));
        }
    }
    if (moreBtn && moreMenu) {
        moreBtn.addEventListener('click', function (e) {
            e.stopPropagation();
            setMenuOpen(moreMenu.hidden);
        });
        document.addEventListener('click', function (e) {
            if (!moreMenu.contains(e.target)) setMenuOpen(false);
        });
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') setMenuOpen(false);
        });
    }
    function applyTheme(theme) {
        htmlElement.setAttribute('data-theme', theme);
        try { localStorage.setItem('theme', theme); } catch (e) {}
        const icon = themeBtn ? themeBtn.querySelector('i') : null;
        if (icon) icon.className = (theme === 'light') ? 'bi bi-sun' : 'bi bi-moon';
    }
    if (themeBtn) {
        applyTheme(htmlElement.getAttribute('data-theme') || 'dark');
        themeBtn.addEventListener('click', function () {
            applyTheme(htmlElement.getAttribute('data-theme') === 'dark' ? 'light' : 'dark');
        });
    }

    // --- 3. AJAX Follow Button Logic ---
    document.body.addEventListener('click', function(e) {
        const button = e.target.closest('.follow-btn-feed');
        if (button) {
            e.preventDefault();
            const userId = button.getAttribute('data-userid');
            const allUserBtns = document.querySelectorAll('.user-' + userId);
            allUserBtns.forEach(b => b.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>');
            fetch('index.php?page=profile.follow&ajax=1&id=' + userId)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'followed') {
                        allUserBtns.forEach(b => {
                            b.className = 'btn btn-sm btn-primary follow-btn-feed user-' + userId;
                            b.style.borderRadius = '20px'; b.style.fontWeight = '600'; b.style.fontSize = '13px'; b.style.padding = '4px 16px';
                            b.innerHTML = 'Following';
                        });
                    } else if (data.status === 'unfollowed') {
                        allUserBtns.forEach(b => {
                            b.className = 'btn btn-sm btn-outline-secondary follow-btn-feed user-' + userId;
                            b.style.borderRadius = '20px'; b.style.fontWeight = '600'; b.style.fontSize = '13px'; b.style.padding = '4px 16px';
                            b.innerHTML = 'Follow';
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    allUserBtns.forEach(b => b.innerHTML = 'Error');
                });
        }
    });

    // --- 4. AJAX Like Button ---
    document.body.addEventListener('click', function(e) {
        const likeBtn = e.target.closest('.like-btn');
        if (likeBtn) {
            e.preventDefault(); 
            const postId = likeBtn.getAttribute('data-postid');
            const type = likeBtn.getAttribute('data-type');
            const icon = likeBtn.querySelector('i');
            const countSpan = likeBtn.querySelector('.like-count');
            if (!icon || !countSpan) return;
            if (icon.classList.contains('bi-heart')) {
                icon.classList.remove('bi-heart');
                icon.classList.add('bi-heart-fill');
                likeBtn.classList.remove('text-muted');
                likeBtn.classList.add('text-danger');
            } else {
                icon.classList.remove('bi-heart-fill');
                icon.classList.add('bi-heart');
                likeBtn.classList.remove('text-danger');
                likeBtn.classList.add('text-muted');
            }
            fetch('index.php?page=like.toggle&ajax=1&id=' + postId + '&type=' + type)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'liked') {
                        countSpan.innerText = parseInt(countSpan.innerText) + 1;
                    } else if (data.status === 'unliked') {
                        countSpan.innerText = parseInt(countSpan.innerText) - 1;
                    }
                })
                .catch(err => {
                    console.error('Error liking:', err);
                    if (icon.classList.contains('bi-heart-fill')) {
                        icon.classList.remove('bi-heart-fill');
                        icon.classList.add('bi-heart');
                    }
                });
        }
    });

    // --- 5. Inline Share Logic ---
    document.body.addEventListener('click', function(e) {
        const shareBtn = e.target.closest('.share-btn-trigger');
        if (shareBtn) {
            e.preventDefault();
            const postId = shareBtn.getAttribute('data-postid');
            const box = document.getElementById('share-box-' + postId);
            if (box.classList.contains('d-none')) {
                document.querySelectorAll('.share-inline-box').forEach(b => b.classList.add('d-none'));
                box.classList.remove('d-none');
                box.querySelector('.share-inline-input').focus();
            } else {
                box.classList.add('d-none');
            }
        }
        const closeBtn = e.target.closest('.close-share-btn');
        if(closeBtn) {
            const postId = closeBtn.getAttribute('data-postid');
            document.getElementById('share-box-' + postId).classList.add('d-none');
        }
    });

    document.body.addEventListener('click', function(e) {
        const submitBtn = e.target.closest('.submit-inline-share');
        if (submitBtn) {
            const postId = submitBtn.getAttribute('data-postid');
            const type = submitBtn.getAttribute('data-type');
            const box = document.getElementById('share-box-' + postId);
            const input = box.querySelector('.share-inline-input');
            let caption = input.value.trim();
            if (caption === '') caption = "Shared a post!";
            const formData = new FormData();
            formData.append('post_id', postId);
            formData.append('type', type);
            formData.append('caption', caption);
            formData.append('ajax', '1');
            const originalBtnHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            fetch('index.php?page=post.share', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    window.location.reload();
                } else {
                    alert('Failed to share.');
                    submitBtn.innerHTML = originalBtnHTML;
                }
            })
            .catch(err => {
                console.error(err);
                alert('Error sharing.');
                submitBtn.innerHTML = originalBtnHTML;
            });
        }
    });

    // --- 6. Copy Post Link Logic ---
    document.body.addEventListener('click', function(e) {
        const copyBtn = e.target.closest('.copy-link-btn');
        if (copyBtn) {
            const postId = copyBtn.getAttribute('data-postid');
            const urlToCopy = window.location.origin + window.location.pathname + '?page=feed#post-' + postId;
            navigator.clipboard.writeText(urlToCopy).then(() => {
                const originalHTML = copyBtn.innerHTML;
                copyBtn.innerHTML = '<i class="bi bi-check2 text-success"></i> Copied!';
                copyBtn.classList.remove('btn-outline-secondary');
                copyBtn.classList.add('btn-outline-success');
                setTimeout(() => {
                    copyBtn.innerHTML = originalHTML;
                    copyBtn.classList.remove('btn-outline-success');
                    copyBtn.classList.add('btn-outline-secondary');
                }, 2000);
            }).catch(err => {
                console.error('Failed to copy: ', err);
                alert('Failed to copy link.');
            });
        }
    });

    // --- 7. CUSTOM 3-DOTS MENU LOGIC ---
    document.querySelectorAll('.custom-dots-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation(); 
            const targetId = this.getAttribute('data-target');
            const menu = document.getElementById(targetId);
            document.querySelectorAll('.custom-dropdown-menu').forEach(m => {
                if (m.id !== targetId) m.style.display = 'none';
            });
            if (menu.style.display === 'none' || menu.style.display === '') {
                menu.style.display = 'block';
            } else {
                menu.style.display = 'none';
            }
        });
    });
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown-wrapper')) {
            document.querySelectorAll('.custom-dropdown-menu').forEach(menu => {
                menu.style.display = 'none';
            });
        }
    });

    // --- 8. AJAX COMMENT FORM (No Refresh) ---
    document.querySelectorAll('.comment-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault(); 
            const postId = this.getAttribute('data-postid');
            const input = this.querySelector('.comment-input');
            const content = input.value.trim();
            const submitBtn = this.querySelector('button[type="submit"]');
            const listContainer = document.getElementById('comments-list-' + postId);
            if (content === '') return;
            const originalBtnHTML = submitBtn.innerHTML;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            submitBtn.disabled = true;
            const formData = new FormData();
            formData.append('post_id', postId);
            formData.append('content', content);
            const typeInput = this.querySelector('input[name="type"]');
            if (typeInput) formData.append('type', typeInput.value);
            formData.append('ajax', '1'); 
            fetch('index.php?page=comment.create', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    input.value = ''; 
                    if (data.html && listContainer) {
                        listContainer.insertAdjacentHTML('beforeend', data.html);
                    }
                } else {
                    alert('Failed to post comment.');
                }
            })
            .catch(err => {
                console.error('Error commenting:', err);
                alert('An error occurred.');
            })
            .finally(() => {
                submitBtn.innerHTML = originalBtnHTML;
                submitBtn.disabled = false;
            });
        });
    });

    // --- 9. Phone menu (bottom bar "Menu" button -> slide-up sheet) ---
    (function () {
        const mobileBtn   = document.getElementById('mobileMoreBtn');
        const sheet       = document.getElementById('mobileSheet');
        const backdrop    = document.getElementById('sheetBackdrop');
        const mobileTheme = document.getElementById('mobileThemeBtn');
        if (!mobileBtn || !sheet || !backdrop) return;

        function setSheetOpen(open) {
            sheet.hidden = !open;
            backdrop.hidden = !open;
            mobileBtn.setAttribute('aria-expanded', String(open));
            document.body.style.overflow = open ? 'hidden' : '';
        }

        mobileBtn.addEventListener('click', function (e) { e.stopPropagation(); setSheetOpen(sheet.hidden); });
        backdrop.addEventListener('click', function () { setSheetOpen(false); });
        sheet.addEventListener('click', function (e) { if (e.target.closest('a')) setSheetOpen(false); });
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape') setSheetOpen(false); });
        window.addEventListener('resize', function () { if (window.innerWidth >= 992) setSheetOpen(false); });

        if (mobileTheme) {
            const root = document.documentElement;
            const icon = mobileTheme.querySelector('i');
            function syncIcon() { if (icon) icon.className = (root.getAttribute('data-theme') === 'light') ? 'bi bi-sun' : 'bi bi-moon'; }
            syncIcon();
            mobileTheme.addEventListener('click', function () {
                const next = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                root.setAttribute('data-theme', next);
                try { localStorage.setItem('theme', next); } catch (e) {}
                syncIcon();
            });
        }
    })();
});
</script>