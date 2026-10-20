<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center mt-4">
    <div class="col-lg-6 col-md-8">
        
        <div class="card shadow-sm" style="border: 1px solid var(--glass-chrome-1);">
            <div class="card-body p-4">
                
                <div class="d-flex align-items-center justify-content-between mb-4">
                    <h4 class="mb-0 fw-bold" style="color: var(--text-primary);">
                        <i class="bi bi-pencil me-2" style="color: var(--accent);"></i> Edit Post
                    </h4>
                    <a href="index.php?page=feed" class="btn-close" style="filter: invert(1); opacity: 0.5;" title="Cancel"></a>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" style="border-radius: 10px;"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=post.edit&id=<?= $post['id'] ?>" enctype="multipart/form-data">
                    
                    <!-- Text Area -->
                    <div class="mb-3">
                        <textarea name="content" class="form-control" rows="5" required
                                  style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); resize: none; font-size: 15px; line-height: 1.6;"><?= htmlspecialchars($_POST['content'] ?? $post['content']) ?></textarea>
                    </div>

                    <!-- File Upload -->
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="color: var(--text-secondary); font-size: 13px;">
                            <i class="bi bi-image me-1"></i> Change Photo (optional)
                        </label>
                        
                        <!-- Custom File Input Button -->
                        <div class="d-flex align-items-center gap-2">
                            <input type="file" name="image" class="form-control" accept="image/*" id="imageInput" style="display: none;">
                            <label for="imageInput" class="btn btn-outline-secondary d-flex align-items-center gap-2 w-100 py-2" style="border-style: dashed; border-width: 2px; cursor: pointer;">
                                <i class="bi bi-cloud-arrow-up fs-5"></i> Choose a new image
                            </label>
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">Leave empty to keep the current image. Max 3MB.</small>
                    </div>

                    <!-- Current Image Display -->
                    <?php if (!empty($post['image'])): ?>
                        <div class="mb-3 p-3 rounded" style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2);">
                            <p class="text-muted small mb-2 fw-bold">Current image:</p>
                            <img src="assets/uploads/<?= htmlspecialchars($post['image']) ?>"
                                 alt="Current image" class="rounded-3 w-100"
                                 style="max-height: 300px; object-fit: contain;">
                        </div>
                    <?php endif; ?>

                    <!-- New Image Preview -->
                    <div class="mb-3" id="previewBox" style="display: none;">
                        <p class="text-muted small mb-2 fw-bold">New image preview:</p>
                        <img id="imagePreview" src="" alt="Preview" class="rounded-3 w-100"
                             style="max-height: 300px; object-fit: contain; border: 1px solid var(--glass-chrome-2);">
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top" style="border-color: var(--glass-chrome-2) !important;">
                        <a href="index.php?page=feed" class="btn btn-outline-secondary px-4">Cancel</a>
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-check-circle me-1"></i> Save Changes
                        </button>
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