<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center mt-2">
    <div class="col-lg-8">

        <!-- Search Bar Card -->
        <div class="card mb-4 shadow-sm" style="border: 1px solid var(--glass-chrome-1);">
            <div class="card-body p-4">
                <h4 class="mb-4 fw-bold" style="color: var(--text-primary);"><i class="bi bi-search me-2" style="color: var(--accent);"></i> Search</h4>

                <form method="GET" action="index.php">
                    <input type="hidden" name="page" value="search">
                    
                    <div class="input-group mb-4">
                        <input type="text" name="q" class="form-control" 
                               placeholder="Search users or posts..." 
                               value="<?= htmlspecialchars($keyword) ?>" required
                               style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); border-right: none;">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>

                    <!-- Filter Pills -->
                    <div class="d-flex gap-2">
                        <a href="index.php?page=search&q=<?= urlencode($keyword) ?>&type=all"
                           class="btn btn-sm rounded-pill px-4 <?= $type === 'all' ? 'btn-primary' : 'btn-outline-secondary' ?>">All</a>
                        <a href="index.php?page=search&q=<?= urlencode($keyword) ?>&type=users"
                           class="btn btn-sm rounded-pill px-4 <?= $type === 'users' ? 'btn-primary' : 'btn-outline-secondary' ?>">Users</a>
                        <a href="index.php?page=search&q=<?= urlencode($keyword) ?>&type=posts"
                           class="btn btn-sm rounded-pill px-4 <?= $type === 'posts' ? 'btn-primary' : 'btn-outline-secondary' ?>">Posts</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- Empty State -->
        <?php if ($keyword === ''): ?>
            <div class="card shadow-sm" style="border: 1px solid var(--glass-chrome-1);">
                <div class="card-body text-center py-5">
                    <div class="empty-state-icon mx-auto mb-3">
                        <i class="bi bi-search"></i>
                    </div>
                    <h5 class="text-muted mb-0">Type something to search.</h5>
                </div>
            </div>
        <?php else: ?>

            <!-- Users Results -->
            <?php if ($type === 'all' || $type === 'users'): ?>
                <h5 class="mb-3 fw-bold" style="color: var(--text-primary);">
                    <i class="bi bi-people me-1" style="color: var(--accent);"></i> Users 
                    <span class="text-muted fw-normal" style="font-size: 14px;">(<?= count($users) ?>)</span>
                </h5>
                
                <?php if (empty($users)): ?>
                    <p class="text-muted mb-4">No users found.</p>
                <?php else: ?>
                    <div class="row g-3 mb-4">
                        <?php foreach ($users as $u): ?>
                            <div class="col-md-6">
                                <a href="index.php?page=profile&id=<?= $u['id'] ?>" class="card shadow-sm text-decoration-none h-100" style="border: 1px solid var(--glass-chrome-2); background: var(--glass-bg-2);">
                                    <div class="card-body d-flex align-items-center p-3">
                                        <div class="rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" 
                                             style="width: 45px; height: 45px; background: var(--accent); color: var(--bg-base);">
                                            <?= strtoupper(substr($u['full_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <strong style="color: var(--text-primary); font-size: 14px;"><?= htmlspecialchars($u['full_name']) ?></strong>
                                            <div class="text-muted small">@<?= htmlspecialchars($u['username']) ?></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Posts Results -->
            <?php if ($type === 'all' || $type === 'posts'): ?>
                <h5 class="mb-3 fw-bold mt-4" style="color: var(--text-primary);">
                    <i class="bi bi-file-text me-1" style="color: var(--accent);"></i> Posts 
                    <span class="text-muted fw-normal" style="font-size: 14px;">(<?= count($posts) ?>)</span>
                </h5>
                
                <?php if (empty($posts)): ?>
                    <p class="text-muted">No posts found.</p>
                <?php else: ?>
                    <?php foreach ($posts as $p): ?>
                        <div class="card mb-3 shadow-sm" style="border: 1px solid var(--glass-chrome-1);">
                            <div class="card-body p-4">
                                <div class="d-flex align-items-center mb-3">
                                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3 fw-bold" 
                                         style="width: 40px; height: 40px; background: var(--accent); color: var(--bg-base);">
                                        <?= strtoupper(substr($p['full_name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <strong style="color: var(--text-primary);"><?= htmlspecialchars($p['full_name']) ?></strong>
                                        <div class="text-muted small">@<?= htmlspecialchars($p['username']) ?></div>
                                    </div>
                                </div>
                                <p class="mb-0" style="font-size: 15px; line-height: 1.6; color: var(--text-primary);">
                                    <?= nl2br(htmlspecialchars($p['content'])) ?>
                                </p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>

        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>