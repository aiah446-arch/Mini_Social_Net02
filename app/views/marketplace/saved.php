<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center pt-4">
    <div class="col-lg-10">
        
        <!-- Header -->
        <div class="mb-4">
            <h2 class="fw-bold mb-1" style="color: var(--text-primary);">Saved Items</h2>
            <p class="text-muted">Your saved posts and marketplace items in one place.</p>
        </div>

        <!-- Search Bar -->
        <form method="GET" action="index.php" class="mb-4">
            <input type="hidden" name="page" value="marketplace.saved">
            <div class="input-group">
                <input type="text" name="search" class="form-control" 
                       placeholder="Search saved items..." 
                       value="<?= htmlspecialchars($search ?? '') ?>"
                       style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); color: var(--text-primary); border-right: none;">
                <button type="submit" class="btn btn-outline-secondary" style="border: 1px solid var(--glass-chrome-2); border-left: none; background: var(--glass-bg-2);">
                    <i class="bi bi-search text-muted"></i>
                </button>
            </div>
        </form>

        <!-- Saved Grid -->
        <div class="row g-4">
            <?php if (empty($items)): ?>
                <div class="col-12 text-center py-5">
                    <div class="empty-state-icon mx-auto mb-3"><i class="bi bi-bookmark"></i></div>
                    <h5 style="color: var(--text-primary);">No saved items found</h5>
                    <p class="text-muted">Try searching for something else or save items in the Marketplace!</p>
                    <a href="index.php?page=marketplace" class="btn btn-primary mt-2">Browse Marketplace</a>
                </div>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <div class="col-md-6 col-lg-4">
                        
                        <a href="index.php?page=marketplace.view&id=<?= $item['id'] ?>" class="text-decoration-none">
                            <div class="card h-100 shadow-sm" style="background: var(--glass-bg); border: 1px solid var(--glass-chrome-1); border-radius: 16px; overflow: hidden; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.02)'" onmouseout="this.style.transform='scale(1)'">
                                
                                <!-- Item Image -->
                                <div style="height: 180px; background: var(--bg-base); position: relative;">
                                    <?php if (!empty($item['image'])): ?>
                                        <img src="assets/uploads/<?= htmlspecialchars($item['image']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                    <?php else: ?>
                                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                            <i class="bi bi-image" style="font-size: 3rem;"></i>
                                        </div>
                                    <?php endif; ?>
                                    
                                    <!-- Saved Icon -->
                                    <div class="btn btn-sm btn-dark position-absolute" 
                                         style="top: 10px; right: 10px; border-radius: 50%; width: 32px; height: 32px; padding: 0; opacity: 0.8; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-bookmark-fill" style="color: #10B981;"></i>
                                    </div>
                                </div>

                                <!-- Item Details -->
                                <div class="card-body d-flex flex-column">
                                    <h5 class="fw-bold mb-1" style="color: var(--text-primary);"><?= htmlspecialchars($item['item_name']) ?></h5>
                                    <h4 class="mb-2" style="color: var(--accent);">₱<?= number_format($item['price'], 2) ?></h4>
                                    <p class="text-muted small mb-3 text-truncate"><?= htmlspecialchars($item['description']) ?></p>
                                    
                                    <div class="d-flex align-items-center gap-2 mt-auto pt-3 border-top" style="border-color: var(--glass-chrome-2) !important;">
                                        <?php if (!empty($item['profile_image']) && $item['profile_image'] !== 'default.png'): ?>
                                            <img src="assets/uploads/<?= htmlspecialchars($item['profile_image']) ?>" class="rounded-circle" style="width: 24px; height: 24px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.7rem;">
                                                <?= strtoupper(substr($item['full_name'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <div class="d-flex flex-column">
                                            <small class="lh-1 mb-1" style="color: var(--text-primary);"><?= htmlspecialchars($item['full_name']) ?></small>
                                            <small class="text-muted lh-1" style="font-size: 0.7rem;">Saved Item</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </a>
                        
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>