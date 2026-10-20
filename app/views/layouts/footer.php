</main> <!-- Closes the <main> tag from header.php -->

<footer class="text-center py-4 mt-5">
    <small>&copy; <?= date('Y') ?> NEO — Mini Social & Marketplace</small>
</footer>

<!-- Bootstrap JS Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Theme Toggle Logic -->
<script>
    (function() {
        var toggle = document.getElementById('themeToggle');
        var root = document.documentElement;

        if (!toggle) return;

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