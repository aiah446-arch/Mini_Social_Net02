<?php
$currentPage = $_GET['page'] ?? 'feed';
?>
<div class="neo-sidebar-left">
    <ul class="list-unstyled m-0">
        <li>
            <a href="index.php?page=feed" class="neo-side-link <?= $currentPage == 'feed' ? 'active' : '' ?>">
                <i class="bi bi-house-door-fill"></i> <span>Home Feed</span>
            </a>
        </li>
        <li>
            <a href="index.php?page=marketplace" class="neo-side-link <?= $currentPage == 'marketplace' ? 'active' : '' ?>">
                <i class="bi bi-shop"></i> <span>Marketplace</span>
            </a>
        </li>
        <li>
            <a href="index.php?page=search" class="neo-side-link <?= $currentPage == 'search' ? 'active' : '' ?>">
                <i class="bi bi-compass"></i> <span>Explore</span>
            </a>
        </li>
    </ul>

    <a href="index.php?page=post.create" class="btn btn-primary w-100 mt-4 mb-4" style="border-radius: 10px; padding: 10px; font-weight: 600;">
        <i class="bi bi-plus-lg me-1"></i> <span>Create Post</span>
    </a>

    <div class="mt-auto pt-3">
        <a href="index.php?page=profile&id=<?= $_SESSION['user_id'] ?>" class="d-flex align-items-center gap-2 text-decoration-none p-2 rounded" style="background: var(--glass-bg-2);">
            <img src="<?= $_SESSION['profile_image'] ?? 'https://i.pravatar.cc/150?img=47' ?>" class="rounded-circle" style="width: 35px; height: 35px; object-fit: cover;">
            <div style="line-height: 1.2;" class="d-none d-xl-block">
                <div class="fw-bold" style="font-size: 13px; color: var(--text-primary);"><?= htmlspecialchars($_SESSION['full_name']) ?></div>
                <div class="text-muted" style="font-size: 11px;">Online</div>
            </div>
        </a>
    </div>
</div>