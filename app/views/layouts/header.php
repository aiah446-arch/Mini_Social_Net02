<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'NEO - Mini Social' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">
    <script>
        (function() {
            try {
                var saved = localStorage.getItem('theme') || 'dark';
                document.documentElement.setAttribute('data-theme', saved);
            } catch (e) {
                document.documentElement.setAttribute('data-theme', 'dark');
            }
        })();
    </script>
</head>
<body>

<!-- Cleaned up NEO Header -->
<header class="neo-header" style="height: 75px; background: var(--glass-bg-3); border-bottom: 1px solid var(--glass-chrome-1); padding: 0 20px; display: flex; align-items: center; justify-content: space-between; position: sticky; top: 0; z-index: 1000;">
    
    <!-- Logo -->
    <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" href="index.php?page=feed">
        <div class="neo-logo-icon" style="background: #dcfce7; color: var(--accent); width: 45px; height: 45px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 14px;">neo</div>
        <div class="d-none d-sm-block">
            <h1 class="neo-brand-title" style="font-size: 20px; font-weight: 700; color: var(--text-primary); margin: 0; line-height: 1;">NEO</h1>
            <p class="neo-brand-subtitle" style="font-size: 11px; color: var(--text-muted); margin: 0;">Connect. Share. Shop.</p>
        </div>
    </a>

    <!-- Right Side Nav (Just the Profile Image) -->
    <div class="d-flex align-items-center gap-3">
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="index.php?page=profile&id=<?= $_SESSION['user_id'] ?>">
                <img src="<?= $_SESSION['profile_image'] ?? 'https://i.pravatar.cc/150?img=47' ?>" alt="User" class="neo-profile-pic" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--accent);">
            </a>
        <?php else: ?>
            <a href="index.php?page=login" class="btn btn-outline-primary btn-sm rounded-pill px-4">Login</a>
        <?php endif; ?>
    </div>
</header>

<!-- Main Container starts here (DO NOT CLOSE IT) -->
<main class="w-100 m-0 p-0" style="overflow-x: hidden;"></main>