<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <h4 class="mb-3"><i class="bi bi-search"></i> Search</h4>

                <form method="GET" action="index.php">
                    <input type="hidden" name="page" value="search">
                    <div class="input-group mb-3">
                        <input type="text" name="q" class="form-control"
                               placeholder="Search users or posts..."
                               value="<?= htmlspecialchars($keyword) ?>" required>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>

                    <div class="btn-group btn-group-sm" role="group">
                        <a href="index.php?page=search&q=<?= urlencode($keyword) ?>&type=all"
                           class="btn btn-outline-primary <?= $type === 'all' ? 'active' : '' ?>">All</a>
                        <a href="index.php?page=search&q=<?= urlencode($keyword) ?>&type=users"
                           class="btn btn-outline-primary <?= $type === 'users' ? 'active' : '' ?>">Users</a>
                        <a href="index.php?page=search&q=<?= urlencode($keyword) ?>&type=posts"
                           class="btn btn-outline-primary <?= $type === 'posts' ? 'active' : '' ?>">Posts</a>
                    </div>
                </form>
            </div>
        </div>

        <?php if ($keyword === ''): ?>
            <div class="card shadow-sm border-0">
                <div class="card-body text-center text-muted py-5">
                    <i class="bi bi-search" style="font-size: 3rem;"></i>
                    <p class="mt-3 mb-0">Type something to search.</p>
                </div>
            </div>
        <?php else: ?>

            <!-- Users results -->
            <?php if ($type === 'all' || $type === 'users'): ?>
                <h5 class="mb-3">
                    <i class="bi bi-people"></i>
                    Users <span class="text-muted">(<?= count($users) ?>)</span>
                </h5>
                <?php if (empty($users)): ?>
                    <p class="text-muted">No users found.</p>
                <?php else: ?>
                    <?php foreach ($users as $u): ?>
                        <a href="index.php?page=profile&id=<?= $u['id'] ?>"
                           class="card shadow-sm border-0 mb-2 text-decoration-none text-dark">
                            <div class="card-body d-flex align-items-center">
                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-3"
                                     style="width: 45px; height: 45px;">
                                    <?= strtoupper(substr($u['full_name'], 0, 1)) ?>
                                </div>
                                <div>
                                    <strong><?= htmlspecialchars($u['full_name']) ?></strong>
                                    <div class="text-muted small">@<?= htmlspecialchars($u['username']) ?></div>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
                <hr class="my-4">
            <?php endif; ?>

            <!-- Posts results -->
            <?php if ($type === 'all' || $type === 'posts'): ?>
                <h5 class="mb-3">
                    <i class="bi bi-file-text"></i>
                    Posts <span class="text-muted">(<?= count($posts) ?>)</span>
                </h5>
                <?php if (empty($posts)): ?>
                    <p class="text-muted">No posts found.</p>
                <?php else: ?>
                    <?php foreach ($posts as $p): ?>
                        <div class="card shadow-sm border-0 mb-3">
                            <div class="card-body">
                                <div class="d-flex align-items-center mb-2">
                                    <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                         style="width: 38px; height: 38px;">
                                        <?= strtoupper(substr($p['full_name'], 0, 1)) ?>
                                    </div>
                                    <div>
                                        <strong><?= htmlspecialchars($p['full_name']) ?></strong>
                                        <small class="text-muted">@<?= htmlspecialchars($p['username']) ?></small>
                                    </div>
                                </div>
                                <p class="mb-0"><?= nl2br(htmlspecialchars($p['content'])) ?></p>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            <?php endif; ?>

        <?php endif; ?>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>