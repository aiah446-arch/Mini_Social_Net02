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
    <link href="assets/css/style.css" rel="stylesheet">
    <!-- FAVICON (Browser Tab Icon) -->
    <link rel="icon" type="image/png" href="assets/uploads/neo_logo.png">
    
    <title><?= htmlspecialchars($pageTitle ?? 'NEO') ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="assets/css/style.css" rel="stylesheet">

    <script>
        (function () {
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

<!-- NEO Header -->
<header class="neo-header">
    <div class="container-fluid px-3 py-2 d-flex align-items-center justify-content-between" style="min-height: 80px;">

        <!-- Logo -->
        <a class="navbar-brand d-flex align-items-center gap-2 text-decoration-none" href="index.php?page=feed" style="height: 100%;">

            <!-- Custom Logo Image -->
            <img src="assets/uploads/neo_logo.png" alt="NEO Logo" style="width: 45px; height: 45px; object-fit: contain;">

            <!-- Scaled-up title and subtitle -->
            <div class="d-none d-sm-block">
                <h1 class="neo-brand-title" style="font-size: 18px; font-weight: 700; color: var(--text-primary); margin: 0 0 2px 0; line-height: 1;">
                    NEO
                </h1>
                <p class="neo-brand-subtitle" style="font-size: 11px; color: var(--text-muted); margin: 0; line-height: 1.1;">
                    Networking, Entrepreneurship, and Opportunities
                </p>
            </div>

        </a>

        <!-- Right Side Navigation (Emptied) -->
        <div class="d-flex align-items-center gap-3">
            <!-- If you need a login button later, you can put it here -->
        </div>

    </div>
</header>