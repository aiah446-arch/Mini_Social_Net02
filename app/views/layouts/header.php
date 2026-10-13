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
    <title><?= $pageTitle ?? 'Mini Social' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
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

<nav class="navbar navbar-expand-lg mb-4">
    <div class="container">
        <a class="navbar-brand" href="index.php?page=feed">
            <i class="bi bi-people-fill"></i> Mini Social
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navMenu">
            <?php if (isset($_SESSION['user_id'])): ?>
                <form class="d-flex ms-lg-3 my-2 my-lg-0 flex-grow-1" method="GET" action="index.php">
                    <input type="hidden" name="page" value="search">
                    <div class="input-group">
                        <input type="text" name="q" class="form-control form-control-sm"
                               placeholder="Search users or posts..."
                               value="<?= htmlspecialchars($_GET['q'] ?? '') ?>">
                        <button class="btn btn-primary btn-sm" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            <?php endif; ?>
            <ul class="navbar-nav ms-auto align-items-lg-center">
                <li class="nav-item d-flex align-items-center">
                    <label class="theme-switch" title="Toggle dark / light mode">
                        <input type="checkbox" id="themeToggle">
                        <span class="theme-slider"></span>
                    </label>
                </li>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=feed"><i class="bi bi-house-door"></i> Feed</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=profile&id=<?= $_SESSION['user_id'] ?>"><i class="bi bi-person-circle"></i> Profile</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=logout"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=login">Login</a></li>
                    <li class="nav-item"><a class="nav-link" href="index.php?page=register">Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>

<main class="container">