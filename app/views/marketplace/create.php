<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center align-items-center" style="min-height: 80vh;">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-lg border-0" style="background: var(--glass-bg); border: 1px solid var(--glass-chrome-1) !important;">
            <div class="card-body p-5">
                
                <div class="mb-4">
                    <a href="index.php?page=marketplace" class="text-muted text-decoration-none small mb-2 d-block"><i class="bi bi-arrow-left"></i> Back to Marketplace</a>
                    <h2 class="fw-bold" style="color: var(--accent);">Sell your items</h2>
                    <p class="text-muted small">Turn your unused school supplies into cash!</p>
                </div>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                <?php endif; ?>

                <?php if ($success): ?>
                    <div class="text-center py-4">
                        <div class="rounded-circle bg-success d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 80px; height: 80px; box-shadow: 0 0 20px rgba(25, 135, 84, 0.5);">
                            <i class="bi bi-check-lg text-white" style="font-size: 3rem;"></i>
                        </div>
                        <h4 class="mb-2" style="color: var(--text-primary);">Your item has been posted!</h4>
                        <p class="text-muted mb-4">Your "<?= htmlspecialchars($_POST['item_name'] ?? 'Item') ?>" was posted successfully for ₱<?= htmlspecialchars($_POST['price'] ?? '0') ?>!</p>
                        <a href="index.php?page=marketplace" class="btn btn-primary w-100">View Marketplace</a>
                    </div>
                <?php else: ?>
                    <form method="POST" action="index.php?page=marketplace.create" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label small fw-bold" style="color: var(--text-primary);">Item name</label>
                            <input type="text" name="item_name" class="form-control" placeholder="e.g. Scientific Calculator" required style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); color: var(--text-primary);">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label small fw-bold" style="color: var(--text-primary);">Price (₱)</label>
                            <input type="number" name="price" class="form-control" placeholder="e.g. 150" step="0.01" required style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); color: var(--text-primary);">
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold" style="color: var(--text-primary);">Description</label>
                            <textarea name="description" class="form-control" rows="3" placeholder="Condition, size, how long you used it..." style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); color: var(--text-primary);"></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold" style="color: var(--text-primary);">Photo</label>
                            <input type="file" name="image" class="form-control" accept="image/*" style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); color: var(--text-primary);">
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php?page=marketplace" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-primary">Post item</button>
                        </div>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>