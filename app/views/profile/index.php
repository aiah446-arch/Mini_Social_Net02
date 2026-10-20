<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center mt-2">
    <div class="col-lg-8">

        <!-- Profile Header -->
        <div class="card mb-4 shadow-sm" style="border: 1px solid var(--glass-chrome-1);">
            <div class="card-body text-center p-5">
                
                <!-- Profile Image / Fallback -->
                <img src="<?= htmlspecialchars($user['profile_image'] ?: 'https://i.pravatar.cc/150?img=12') ?>"
                     alt="Profile"
                     class="rounded-circle mb-3 shadow"
                     style="width: 120px; height: 120px; object-fit: cover; border: 3px solid var(--accent); background: var(--glass-bg-2);"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                <div class="rounded-circle mx-auto mb-3 align-items-center justify-content-center shadow"
                     style="width: 120px; height: 120px; font-size: 3rem; display: none; background: var(--accent); color: var(--bg-base); border: 3px solid var(--glass-chrome-1);">
                    <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                </div>

                <h3 class="mb-1 fw-bold" style="color: var(--text-primary);"><?= htmlspecialchars($user['full_name']) ?></h3>
                <p class="text-muted mb-3" style="font-size: 14px;">@<?= htmlspecialchars($user['username']) ?></p>

                <?php if (!empty($user['bio'])): ?>
                    <p class="mb-3" style="color: var(--text-primary); line-height: 1.6;"><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
                <?php else: ?>
                    <p class="text-muted fst-italic mb-3">No bio yet.</p>
                <?php endif; ?>

                <p class="text-muted small mb-4">
                    <i class="bi bi-calendar"></i>
                    Joined <?= date('F Y', strtotime($user['created_at'])) ?>
                </p>

                <?php if ($isOwnProfile): ?>
                    <a href="index.php?page=profile.edit" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-pencil me-1"></i> Edit Profile
                    </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- User's Posts -->
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0 fw-bold" style="color: var(--text-primary);">
                <i class="bi bi-file-text me-1"></i>
                <?= $isOwnProfile ? 'My Posts' : htmlspecialchars($user['full_name']) . "'s Posts" ?>
                <span class="text-muted ms-1" style="font-size: 14px;">(<?= count($posts) ?>)</span>
            </h5>
        </div>

        <?php if (empty($posts)): ?>
            <div class="card shadow-sm" style="border: 1px solid var(--glass-chrome-1);">
                <div class="card-body text-center py-5">
                    <div class="empty-state-icon mx-auto mb-3">
                        <i class="bi bi-inbox"></i>
                    </div>
                    <h5 class="text-muted mb-0">No posts yet.</h5>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <div class="card mb-3 shadow-sm" style="border: 1px solid var(--glass-chrome-1);">
                    <div class="card-body p-4">
                        <small class="text-muted d-block mb-2" style="font-size: 12px;">
                            <i class="bi bi-clock me-1"></i> <?= date('M j, Y g:i A', strtotime($post['created_at'])) ?>
                        </small>
                        <p class="mt-1 mb-0" style="font-size: 15px; line-height: 1.6; color: var(--text-primary);">
                            <?= nl2br(htmlspecialchars($post['content'])) ?>
                        </p>

                        <!-- Post Image (Added in case the user's post has an image!) -->
                        <?php if (!empty($post['image'])): ?>
                            <div class="mt-3">
                                <img src="assets/uploads/<?= htmlspecialchars($post['image']) ?>" alt="Post image" class="img-fluid rounded w-100" style="max-height: 400px; object-fit: cover; border: 1px solid var(--glass-chrome-2);">
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>