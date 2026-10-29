</div> <!-- Closes the .row from feed.php -->
</main> <!-- Closes the <main class="container"> from header.php -->

<footer class="text-center py-4 mt-5">
    <small>&copy; <?= date('Y') ?> NEO </small>
</footer>

<!-- ===== MOBILE BOTTOM NAVIGATION ===== -->
<div class="mobile-nav">
    <a href="index.php?page=feed" class="<?= ($_GET['page'] ?? 'feed') === 'feed' ? 'active' : '' ?>" title="Home">
        <i class="bi bi-house-door-fill"></i>
    </a>
    <a href="index.php?page=chats" class="<?= ($_GET['page'] ?? '') === 'chats' ? 'active' : '' ?>" title="Chats">
        <i class="bi bi-chat-dots-fill"></i>
    </a>
    <a href="index.php?page=search" class="<?= ($_GET['page'] ?? '') === 'search' ? 'active' : '' ?>" title="Search">
        <i class="bi bi-search"></i>
    </a>
    <a href="index.php?page=marketplace" class="<?= ($_GET['page'] ?? '') === 'marketplace' ? 'active' : '' ?>" title="Marketplace">
        <i class="bi bi-shop"></i>
    </a>
    <a href="index.php?page=profile&id=<?= $_SESSION['user_id'] ?? 0 ?>" class="<?= ($_GET['page'] ?? '') === 'profile' ? 'active' : '' ?>" title="Profile">
        <i class="bi bi-person"></i>
    </a>

    <!-- MORE BUTTON (Mobile) - Opens the same dropdown as the left sidebar -->
    <button type="button" class="btn p-0" id="mobileMoreBtn" title="More">
        <i class="bi bi-list" style="font-size: 1.5rem; color: var(--text-muted);"></i>
    </button>
</div>

<!-- Mobile "More" dropdown menu (fixed position) -->
<div id="mobileMoreMenu" class="mobile-more-menu" style="display: none;">
    <a class="more-item" href="index.php?page=profile.edit"><i class="bi bi-gear"></i><span>Settings</span></a>
    <a class="more-item" href="index.php?page=notifications"><i class="bi bi-activity"></i><span>Your activity</span></a>
    <a class="more-item" href="index.php?page=marketplace.saved"><i class="bi bi-bookmark"></i><span>Saved</span></a>
    <button type="button" class="more-item" id="mobileThemeToggleBtn" role="switch"><i class="bi bi-moon"></i><span>Switch appearance</span><span class="tg" aria-hidden="true"></span></button>
    <a class="more-item" href="index.php?page=logout" class="text-danger"><i class="bi bi-box-arrow-right"></i><span>Log out</span></a>
</div>

<!-- Mobile "More" Menu Styles -->
<style>
    .mobile-more-menu {
        position: fixed;
        bottom: 75px;
        right: 10px;
        width: 260px;
        background: #262626;
        border: 1px solid var(--glass-chrome-1);
        border-radius: 16px;
        box-shadow: 0 6px 28px rgba(0, 0, 0, 0.55);
        padding: 8px;
        z-index: 2100;
    }
    [data-theme="light"] .mobile-more-menu {
        background: #ffffff;
    }
    .mobile-more-menu .more-item {
        display: flex;
        align-items: center;
        gap: 12px;
        width: 100%;
        padding: 14px 16px;
        border: 0;
        border-radius: 8px;
        background: transparent;
        color: var(--text-primary) !important;
        font-size: 14px;
        font-family: inherit;
        text-align: left;
        text-decoration: none !important;
        cursor: pointer;
    }
    .mobile-more-menu .more-item i { font-size: 20px; }
    .mobile-more-menu .more-item:hover {
        background: var(--glass-bg-2);
        color: var(--text-primary) !important;
    }
    .mobile-more-menu .more-item.text-danger {
        color: var(--danger) !important;
    }
</style>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Theme Toggle Logic -->
<script>
    (function() {
        var toggle = document.getElementById('themeToggle');
        var root = document.documentElement;
        if (toggle) {
            var current = root.getAttribute('data-theme') || 'dark';
            toggle.checked = (current === 'light');
            toggle.addEventListener('change', function() {
                var next = this.checked ? 'light' : 'dark';
                root.setAttribute('data-theme', next);
                try { localStorage.setItem('theme', next); } catch (e) {}
            });
        }
    })();

    // --- Mobile More Menu Toggle ---
    (function() {
        var mobileMoreBtn = document.getElementById('mobileMoreBtn');
        var mobileMoreMenu = document.getElementById('mobileMoreMenu');
        var mobileThemeBtn = document.getElementById('mobileThemeToggleBtn');
        var root = document.documentElement;

        if (mobileMoreBtn && mobileMoreMenu) {
            mobileMoreBtn.addEventListener('click', function(e) {
                e.stopPropagation();
                mobileMoreMenu.style.display = mobileMoreMenu.style.display === 'block' ? 'none' : 'block';
            });

            document.addEventListener('click', function(e) {
                if (!mobileMoreMenu.contains(e.target) && !mobileMoreBtn.contains(e.target)) {
                    mobileMoreMenu.style.display = 'none';
                }
            });
        }

        // Mobile Theme Toggle
        if (mobileThemeBtn) {
            mobileThemeBtn.addEventListener('click', function() {
                var current = root.getAttribute('data-theme') || 'dark';
                var next = current === 'dark' ? 'light' : 'dark';
                root.setAttribute('data-theme', next);
                try { localStorage.setItem('theme', next); } catch (e) {}
                mobileMoreMenu.style.display = 'none';
            });
        }
    })();
</script>

</body>
</html>