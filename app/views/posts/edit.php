<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="mb-3"><i class="bi bi-pencil"></i> Edit Post</h4>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=post.edit&id=<?= $post['id'] ?>" enctype="multipart/form-data">
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="5" required><?= htmlspecialchars($_POST['content'] ?? $post['content']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">
                            <i class="bi bi-image"></i> Change Photo (optional)
                        </label>
                        <input type="file" name="image" class="form-control" accept="image/*" id="imageInput">
                        <small class="text-muted">Leave empty to keep current image. Max 3MB.</small>
                    </div>

                    <?php if (!empty($post['image'])): ?>
                        <div class="mb-3">
                            <p class="text-muted small mb-1">Current image:</p>
                            <img src="assets/uploads/<?= htmlspecialchars($post['image']) ?>"
                                 alt="Current image" class="rounded-3"
                                 style="max-width: 100%; max-height: 300px; object-fit: cover;">
                        </div>
                    <?php endif; ?>

                    <div class="mb-3" id="previewBox" style="display: none;">
                        <p class="text-muted small mb-1">New image preview:</p>
                        <img id="imagePreview" src="" alt="Preview" class="rounded-3"
                             style="max-width: 100%; max-height: 300px; object-fit: cover;">
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Save Changes
                        </button>
                        <a href="index.php?page=feed" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.getElementById('imageInput').addEventListener('change', function(e) {
        var file = e.target.files[0];
        var previewBox = document.getElementById('previewBox');
        var preview = document.getElementById('imagePreview');

        if (file) {
            var reader = new FileReader();
            reader.onload = function(ev) {
                preview.src = ev.target.result;
                previewBox.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            previewBox.style.display = 'none';
        }
    });
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>