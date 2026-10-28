document.addEventListener('DOMContentLoaded', function () {
    // --- Theme Toggle Logic ---
    var html = document.documentElement;
    var btn  = document.getElementById('themeToggle');
    var icon = document.getElementById('themeIcon');

    if (btn && icon) {
        function setTheme(theme) {
            html.setAttribute('data-bs-theme', theme);
            try { localStorage.setItem('theme', theme); } catch (e) {}

            // Light = sun, Dark = half moon
            icon.className = (theme === 'dark') ? 'bi bi-moon-fill' : 'bi bi-sun-fill';
            btn.setAttribute('aria-checked', theme === 'dark' ? 'true' : 'false');
        }

        // Theme was already applied by the script in header.php
        setTheme(html.getAttribute('data-bs-theme') || 'light');

        btn.addEventListener('click', function () {
            var next = html.getAttribute('data-bs-theme') === 'dark' ? 'light' : 'dark';
            setTheme(next);
        });
    }

    // --- Share Post Logic ---
    document.addEventListener('click', async (e) => {
        const shareBtn = e.target.closest('.share-btn');
        if (!shareBtn) return;

        e.preventDefault();

        const postUrl = shareBtn.getAttribute('data-url') || window.location.href;
        const postTitle = shareBtn.getAttribute('data-title') || 'Check out this post!';

        // Option A: Native Web Share API (Mobile & modern desktop browsers)
        if (navigator.share) {
            try {
                await navigator.share({
                    title: postTitle,
                    url: postUrl
                });
            } catch (err) {
                if (err.name !== 'AbortError') {
                    console.error('Error sharing:', err);
                }
            }
        } 
        // Option B: Fallback - Copy Link to Clipboard
        else {
            try {
                await navigator.clipboard.writeText(postUrl);
                
                // Show user feedback using Bootstrap Icons style
                const originalText = shareBtn.innerHTML;
                shareBtn.innerHTML = '<i class="bi bi-check"></i> Copied!';
                setTimeout(() => {
                    shareBtn.innerHTML = originalText;
                }, 2000);
            } catch (err) {
                console.error('Failed to copy link:', err);
            }
        }
    });
});