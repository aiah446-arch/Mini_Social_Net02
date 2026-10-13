<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <!-- Profile Header -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body text-center p-4">
                <img src="assets/uploads/<?= htmlspecialchars($user['profile_image'] ?: 'default.png') ?>"
                     alt="Profile"
                     class="rounded-circle border border-3 border-white shadow-sm mb-3"
                     style="width: 120px; height: 120px; object-fit: cover; background: #1877f2;"
                     onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                <div class="rounded-circle bg-primary text-white mx-auto mb-3 align-items-center justify-content-center"
                     style="width: 120px; height: 120px; font-size: 3rem; display: none;">
                    <?= strtoupper(substr($user['full_name'], 0, 1)) ?>
                </div>

                <h3 class="mb-1"><?= htmlspecialchars($user['full_name']) ?></h3>
                <p class="text-muted mb-2">@<?= htmlspecialchars($user['username']) ?></p>

                <?php if (!empty($user['bio'])): ?>
                    <p class="mb-3"><?= nl2br(htmlspecialchars($user['bio'])) ?></p>
                <?php else: ?>
                    <p class="text-muted fst-italic mb-3">No bio yet.</p>
                <?php endif; ?>

                <p class="text-muted small mb-3">
                    <i class="bi bi-calendar"></i>
                    Joined <?= date('F Y', strtotime($user['created_at'])) ?>
                </p>

                <?php if ($isOwnProfile): ?>
                    <a href="index.php?page=profile.edit" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Edit Profile
                    </a>
                <?php endif; ?>
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
                <div class="card-body text-center text-muted py-4">
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
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>