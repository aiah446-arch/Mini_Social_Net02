<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center pt-4">
    <div class="col-lg-10">
        
        <!-- Header & Search -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold mb-0" style="color: var(--text-primary);">Marketplace</h2>
            <a href="index.php?page=marketplace.create" class="btn btn-primary">
                <i class="bi bi-plus-lg"></i> Sell Item
            </a>
        </div>

        <form method="GET" action="index.php" class="d-flex gap-2 mb-4">
            <input type="hidden" name="page" value="marketplace">
            <input type="text" name="search" class="form-control" placeholder="Search items..." value="<?= htmlspecialchars($search ?? '') ?>" style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); color: var(--text-primary);">
            <button type="submit" class="btn btn-primary px-4" title="Search"><i class="bi bi-search"></i></button>
            <a href="index.php?page=marketplace" class="btn btn-outline-secondary px-4">All</a>
        </form>

        <!-- Marketplace Grid -->
        <div class="row g-4">
            <?php if (empty($items)): ?>
                <div class="col-12 text-center py-5">
                    <div class="empty-state-icon mx-auto mb-3"><i class="bi bi-shop"></i></div>
                    <?php if (!empty($search)): ?>
                        <h5 style="color: var(--text-primary);">No items found</h5>
                        <p class="text-muted">No results for "<?= htmlspecialchars($search) ?>". Try another word or click All.</p>
                    <?php else: ?>
                        <h5 style="color: var(--text-primary);">No items yet</h5>
                        <p class="text-muted">Be the first to sell something!</p>
                    <?php endif; ?>
                </div>
            <?php else: ?>
                <?php foreach ($items as $item): ?>
                    <div class="col-md-6 col-lg-4">
                        
                        <!-- The Card (Link) -->
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
                                    
                                    <!-- Bookmark Button (Styled as a clickable div so it doesn't trigger the link) -->
                                                                       <!-- Bookmark Button -->
                                    <?php $isSaved = in_array($item['id'], $savedIds); ?>
                                    <div class="btn btn-sm btn-dark position-absolute save-btn" 
                                         data-id="<?= $item['id'] ?>" 
                                         style="top: 10px; right: 10px; border-radius: 50%; width: 32px; height: 32px; padding: 0; opacity: 0.8; z-index: 10; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                                        
                                        <!-- Check if it's saved, show green filled, else white empty -->
                                        <i class="bi <?= $isSaved ? 'bi-bookmark-fill' : 'bi-bookmark' ?>" 
                                           style="color: <?= $isSaved ? '#10B981' : '#fff' ?>;"></i>
                                    </div>
                                </div>

                                <!-- Item Details -->
                                <div class="card-body d-flex flex-column">
                                    <h5 class="fw-bold mb-1" style="color: var(--text-primary);"><?= htmlspecialchars($item['item_name']) ?></h5>
                                    <h4 class="mb-2" style="color: var(--accent);">₱<?= number_format($item['price'], 2) ?></h4>
                                    <p class="text-muted small mb-3 text-truncate"><?= htmlspecialchars($item['description']) ?></p>
                                    
                                    <div class="d-flex align-items-center gap-2 mt-auto">
                                        <?php if (!empty($item['profile_image']) && $item['profile_image'] !== 'default.png'): ?>
                                            <img src="assets/uploads/<?= htmlspecialchars($item['profile_image']) ?>" class="rounded-circle" style="width: 24px; height: 24px; object-fit: cover;">
                                        <?php else: ?>
                                            <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 24px; height: 24px; font-size: 0.7rem;">
                                                <?= strtoupper(substr($item['full_name'], 0, 1)) ?>
                                            </div>
                                        <?php endif; ?>
                                        <small class="text-muted"><?= htmlspecialchars($item['full_name']) ?> · <?= date('M j', strtotime($item['created_at'])) ?></small>
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

<!-- AJAX Script for Saving Items -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.save-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            //Stops the link from opening
            e.preventDefault(); 
            e.stopPropagation(); 
            
            const itemId = this.getAttribute('data-id');
            const icon = this.querySelector('i');
            
            // Send request to save/unsave
            fetch('index.php?page=marketplace.save&id=' + itemId)
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'saved') {
                        icon.classList.remove('bi-bookmark');
                        icon.classList.add('bi-bookmark-fill');
                        icon.style.color = '#10B981'; // Glowing Green
                    } else if (data.status === 'unsaved') {
                        icon.classList.remove('bi-bookmark-fill');
                        icon.classList.add('bi-bookmark');
                        icon.style.color = '#fff'; // White
                    }
                })
                .catch(err => console.error('Error saving item:', err));
        });
    });
});
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>