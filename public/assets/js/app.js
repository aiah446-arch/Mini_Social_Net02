document.addEventListener('DOMContentLoaded', function () {
    var html = document.documentElement;
    var btn  = document.getElementById('themeToggle');
    var icon = document.getElementById('themeIcon');

    if (!btn || !icon) return;

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
});
