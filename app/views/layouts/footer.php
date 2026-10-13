</main>

<footer class="text-center py-4 mt-5">
    <small>&copy; <?= date('Y') ?> Mini Social — Final Project</small>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    (function() {
        var toggle = document.getElementById('themeToggle');
        var root = document.documentElement;

        if (!toggle) {
            console.error('Theme toggle not found');
            return;
        }

        var current = root.getAttribute('data-theme') || 'dark';
        toggle.checked = (current === 'light');

        toggle.addEventListener('change', function() {
            var next = this.checked ? 'light' : 'dark';
            root.setAttribute('data-theme', next);
            try { localStorage.setItem('theme', next); } catch (e) {}
        });
    })();
</script>

</body>
</html>