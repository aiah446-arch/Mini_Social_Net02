<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <!-- Profile Header -->
        <div class="card shadow-sm border-0 mb-4" style="overflow: hidden;">
            
            <!-- Cover Photo -->
            <div style="height: 200px; background: linear-gradient(135deg, var(--c-4), var(--c-1)); position: relative;">
                <?php if (!empty($user['cover_image'])): ?>
                    <img src="assets/uploads/<?= htmlspecialchars($user['cover_image']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                <?php endif; ?>
            </div>

            <div class="card-body text-center position-relative" style="padding-top: 0;">
                
                <!-- Avatar (pulled up over the cover photo) -->
                <div style="margin-top: -60px; margin-bottom: 15px;">
                    <?php if (!empty($user['profile_image']) && $user['profile_image'] !== 'default.png'): ?>
                        <img src="assets/uploads/<?= htmlspecialchars($user['profile_image']) ?>" class="rounded-circle border border-4" style="width: 120px; height: 120px; object-fit: cover; border-color: var(--bg-card) !important; background: var(--bg-card);">
                    <?php else: ?>
                        <div class="rounded-circle border border-4 d-flex align-items-center justify-content-center mx-auto" style="width: 120px; height: 120px; font-size: 3rem; background: var(--c-4); color: var(--c-6); border-color: var(--bg-card) !important;">
                            <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                        </div>
                    <?php endif; ?>
                </div>

                <h3 class="mb-1"><?= htmlspecialchars($user['full_name']) ?></h3>
                <p class="text-muted mb-2">@<?= htmlspecialchars($user['username']) ?></p>

                <?php if (!empty($user['bio'])): ?>
                    <p class="mb-3"><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
                <?php else: ?>
                    <p class="text-muted fst-italic mb-3">No bio yet.</p>
                <?php endif; ?>

                <!-- Follow / Edit Buttons -->
                <div class="d-flex justify-content-center gap-3 mt-3">
                    <?php if ($isOwnProfile): ?>
                        <a href="index.php?page=profile.edit" class="btn btn-primary">
                            <i class="bi bi-pencil"></i> Edit Profile
                        </a>
                    <?php else: ?>
                        <?php if ($isFollowing): ?>
                            <a href="index.php?page=profile.follow&id=<?= $user['id'] ?>" class="btn btn-outline-secondary">
                                <i class="bi bi-person-check-fill"></i> Following
                            </a>
                        <?php else: ?>
                            <a href="index.php?page=profile.follow&id=<?= $user['id'] ?>" class="btn btn-primary">
                                <i class="bi bi-person-plus-fill"></i> Follow
                            </a>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>

                <!-- Follower Count & Joined Date -->
                <p class="text-muted small mt-3 mb-0">
                    <strong><?= $followerCount ?? 0 ?></strong> Followers · Joined <?= date('F Y', strtotime($user['created_at'])) ?>
                </p>
            </div>
        </div>

        <!-- User's Posts -->
        <h5 class="mb-3">
            <i class="bi bi-file-text"></i>
            <?= $isOwnProfile ? 'My Posts' : htmlspecialchars($user['full_name']) . "'s Posts" ?>
            <span class="text-muted">(<?= count($posts) ?>)</span>
        </h5>

        <?php if (empty($posts)): ?>
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-4">
                    <i class="bi bi-inbox" style="font-size: 2rem;"></i>
                    <p class="mt-2 mb-0">No posts yet.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">
                        <small class="text-muted">
                            <?= date('M j, Y g:i A', strtotime($post['created_at'])) ?>
                        </small>
                        <p class="mt-2 mb-0"><?= nl2br(htmlspecialchars($post['content'])) ?></p>
                        
                        <?php if (!empty($post['image'])): ?>
                            <div class="mt-3">
                                <img src="assets/uploads/<?= htmlspecialchars($post['image']) ?>" class="img-fluid rounded w-100" style="max-height: 400px; object-fit: cover; border: 1px solid var(--glass-chrome-1);">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>