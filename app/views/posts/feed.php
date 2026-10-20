<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
require_once __DIR__ . '/../../models/CommentModel.php';
require_once __DIR__ . '/../../models/LikeModel.php';
$db = (new Database())->getConnection();
$commentModel = new CommentModel($db);
$likeModel = new LikeModel($db);
?>

<div class="neo-app-layout">
    
    <!-- 1. LEFT SIDEBAR -->
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <!-- 2. CENTER FEED -->
    <div class="neo-center-feed">
        
        <!-- STORIES BAR -->
        <div class="d-flex gap-3 overflow-auto mb-4 w-100" style="max-width: 600px;">
            <div class="text-center" style="cursor: pointer;">
                <div class="rounded-circle d-flex align-items-center justify-content-center mb-1" style="width: 60px; height: 60px; background: var(--glass-bg-2); border: 1px dashed var(--text-muted);">
                    <i class="bi bi-plus-lg text-muted"></i>
                </div>
                <small class="text-muted">Your Story</small>
            </div>
            <div class="text-center" style="cursor: pointer;">
                <img src="https://i.pravatar.cc/150?img=32" class="rounded-circle mb-1" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid var(--accent);">
                <small class="text-muted">Alyssa</small>
            </div>
            <div class="text-center" style="cursor: pointer;">
                <img src="https://i.pravatar.cc/150?img=12" class="rounded-circle mb-1" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid var(--accent);">
                <small class="text-muted">Ralph</small>
            </div>
            <div class="text-center" style="cursor: pointer;">
                <img src="https://i.pravatar.cc/150?img=5" class="rounded-circle mb-1" style="width: 60px; height: 60px; object-fit: cover; border: 2px solid var(--accent);">
                <small class="text-muted">Mika</small>
            </div>
        </div>

        <!-- CREATE POST BOX -->
        <div class="card w-100 mb-4" style="max-width: 600px; border: 1px solid var(--glass-chrome-1);">
            <div class="card-body p-3">
                <form method="POST" action="index.php?page=post.create" enctype="multipart/form-data">
                    <div class="d-flex gap-3 mb-3 align-items-center">
                        <img src="<?= $_SESSION['profile_image'] ?? 'https://i.pravatar.cc/150?img=47' ?>" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                        <input type="text" name="content" class="form-control" placeholder="What's on your mind, <?= htmlspecialchars($_SESSION['full_name']) ?>?" style="background: var(--glass-bg-2); border-radius: 20px; border: 1px solid var(--glass-chrome-2);">
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top" style="border-color: var(--glass-chrome-1) !important;">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-sm text-muted"><i class="bi bi-image text-success"></i> Photo</button>
                            <button type="button" class="btn btn-sm text-muted"><i class="bi bi-tag text-success"></i> Sell an Item</button>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm px-4">Post</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- FEED POSTS -->
        <div class="w-100" style="max-width: 600px;">
            <?php if (empty($posts)): ?>
                <p class="text-muted text-center">No posts yet.</p>
            <?php else: ?>
                <?php foreach ($posts as $post): ?>
                    <?php
                    $comments = $commentModel->getByPost($post['id']);
                    $likeCount = $likeModel->countByPost($post['id']);
                    $liked = $likeModel->hasLiked($post['id'], $_SESSION['user_id']);
                    ?>
                    <div class="card mb-4" style="border: 1px solid var(--glass-chrome-1);">
                        <div class="card-body p-3">
                            
                            <!-- Post Header -->
                            <div class="d-flex gap-3 align-items-center mb-3">
                                <img src="<?= !empty($post['profile_image']) ? htmlspecialchars($post['profile_image']) : 'https://i.pravatar.cc/150?img=12' ?>" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                <div>
                                    <div class="fw-bold" style="font-size: 14px;"><?= htmlspecialchars($post['full_name']) ?></div>
                                    <div class="text-muted" style="font-size: 11px;"><?= date('M j, g:i A', strtotime($post['created_at'])) ?></div>
                                </div>
                            </div>

                            <p style="font-size: 14px;"><?= nl2br(htmlspecialchars($post['content'])) ?></p>

                            <?php if (!empty($post['image'])): ?>
                                <img src="assets/uploads/<?= htmlspecialchars($post['image']) ?>" class="img-fluid rounded w-100 mb-3" style="max-height: 400px; object-fit: cover;">
                            <?php endif; ?>

                            <!-- Post Actions -->
                            <div class="d-flex gap-3 pt-2 border-top" style="border-color: var(--glass-chrome-1) !important; font-size: 13px;">
                                <a href="index.php?page=like.toggle&id=<?= $post['id'] ?>" class="text-decoration-none <?= $liked ? 'text-danger' : 'text-muted' ?>"><i class="bi <?= $liked ? 'bi-heart-fill' : 'bi-heart' ?>"></i> <?= $likeCount ?></a>
                                <span class="text-muted"><i class="bi bi-chat"></i> <?= count($comments) ?></span>
                                <span class="text-muted"><i class="bi bi-send"></i> Share</span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <!-- 3. RIGHT SIDEBAR -->
    <div class="neo-sidebar-right d-none d-xl-block">
        <h6 class="fw-bold mb-3" style="color: var(--text-primary);">Trending</h6>
        <div class="mb-3">
            <a href="#" class="text-decoration-none fw-bold" style="color: var(--accent); font-size: 14px;">#CampusLife</a>
            <div class="text-muted" style="font-size: 11px;">1.2K posts</div>
        </div>
        <div class="mb-3">
            <a href="#" class="text-decoration-none fw-bold" style="color: var(--accent); font-size: 14px;">#Handmade</a>
            <div class="text-muted" style="font-size: 11px;">890 posts</div>
        </div>

        <h6 class="fw-bold mt-4 mb-3" style="color: var(--text-primary);">Suggested for you</h6>
        <div class="d-flex align-items-center justify-content-between mb-3">
            <div class="d-flex align-items-center gap-2">
                <img src="https://i.pravatar.cc/150?img=5" class="rounded-circle" style="width: 35px; height: 35px;">
                <div>
                    <div class="fw-bold" style="font-size: 13px; color: var(--text-primary);">Alyssa Marie</div>
                    <div class="text-muted" style="font-size: 11px;">@alyssamari</div>
                </div>
            </div>
            <button class="btn btn-sm btn-outline-success rounded-pill" style="font-size: 11px;">Follow</button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>