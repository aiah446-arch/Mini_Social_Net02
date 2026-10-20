<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<style>
    /* Same layout styles as feed, plus specific marketplace grid */
    .neo-wrapper { display: flex; min-height: calc(100vh - 70px); background: var(--bg-base); }
    .neo-center-content { flex-grow: 1; padding: 20px 40px; max-width: 1000px; margin: 0 auto; }
    
    /* Marketplace Grid */
    .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(220px, 1fr)); gap: 20px; }
    .product-card { background: var(--glass-bg); border: 1px solid var(--glass-chrome-1); border-radius: 12px; overflow: hidden; transition: 0.3s; cursor: pointer; }
    .product-card:hover { transform: translateY(-5px); box-shadow: 0 10px 20px rgba(0,0,0,0.2); border-color: var(--accent); }
    .product-img { width: 100%; height: 200px; object-fit: cover; }
    .product-info { padding: 15px; }
    .product-title { font-size: 15px; font-weight: 600; color: var(--text-primary); margin-bottom: 5px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .product-price { font-size: 16px; font-weight: 700; color: var(--accent); margin-bottom: 5px; }
    .product-meta { font-size: 11px; color: var(--text-muted); display: flex; justify-content: space-between; }
    
    /* Category Pills */
    .category-pills { display: flex; gap: 10px; overflow-x: auto; padding-bottom: 15px; margin-bottom: 20px; }
    .category-pills::-webkit-scrollbar { display: none; }
    .category-pill { padding: 8px 16px; border-radius: 50px; background: var(--glass-bg-2); color: var(--text-secondary); border: 1px solid var(--glass-chrome-2); font-size: 13px; text-decoration: none; white-space: nowrap; transition: 0.2s; }
    .category-pill.active, .category-pill:hover { background: var(--accent); color: var(--bg-base); border-color: var(--accent); }
</style>

<div class="neo-wrapper">
    
    <!-- INCLUDE LEFT SIDEBAR -->
    <?php require_once __DIR__ . '/../layouts/sidebar.php'; ?>

    <!-- MAIN CONTENT -->
    <div class="neo-center-content">
        
        <h3 class="fw-bold mb-4" style="color: var(--text-primary);">Marketplace</h3>

        <!-- Search Bar -->
        <div class="input-group mb-4 shadow-sm" style="border-radius: 50px; overflow: hidden; border: 1px solid var(--glass-chrome-2);">
            <span class="input-group-text border-0" style="background: var(--glass-bg-2); color: var(--text-muted);"><i class="bi bi-search"></i></span>
            <input type="text" class="form-control border-0" placeholder="Search products, shops, or sellers..." style="background: var(--glass-bg-2); box-shadow: none; color: var(--text-primary);">
        </div>

        <!-- Category Filters -->
        <div class="category-pills">
            <a href="#" class="category-pill active">All</a>
            <a href="#" class="category-pill">Handmade</a>
            <a href="#" class="category-pill">Electronics</a>
            <a href="#" class="category-pill">Clothes</a>
            <a href="#" class="category-pill">Books</a>
            <a href="#" class="category-pill">School Supplies</a>
        </div>

        <!-- Product Grid -->
        <div class="product-grid">
            <?php if (empty($products)): ?>
                <div class="col-12 text-center py-5">
                    <h5 class="text-muted">No products listed yet.</h5>
                    <a href="index.php?page=post.create" class="btn btn-primary mt-3">Be the first to sell!</a>
                </div>
            <?php else: ?>
                <?php foreach ($products as $product): ?>
                    <!-- Product Card -->
                    <div class="product-card">
                        <!-- If no image, use a default placeholder -->
                        <img src="<?= !empty($product['image']) ? 'assets/uploads/' . htmlspecialchars($product['image']) : 'https://placehold.co/400x300/1e293b/ffffff?text=No+Image' ?>" class="product-img" alt="<?= htmlspecialchars($product['product_title']) ?>">
                        
                        <div class="product-info">
                            <div class="product-title"><?= htmlspecialchars($product['product_title']) ?></div>
                            <div class="product-price">₱<?= number_format($product['price'], 2) ?></div>
                            <div class="product-meta">
                                <span><i class="bi bi-tag"></i> <?= htmlspecialchars($product['product_condition']) ?></span>
                                <span><i class="bi bi-person"></i> <?= htmlspecialchars($product['full_name']) ?></span>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>