<?php
// Each product has: name, image, price
$products = [
    ['name' => 'Notebook',       'image' => 'images/notebook.svg', 'price' => 45],
    ['name' => 'Gel Pens (Set)', 'image' => 'images/pens.svg',     'price' => 35],
    ['name' => 'Water Bottle',   'image' => 'images/bottle.svg',   'price' => 120],
];
?>
<section class="shop-card">
    <div class="shop-head">
        <h3>School Shop</h3>
        <a href="index.php?page=shop" class="see-all">See All</a>
    </div>

    <!-- Products: name, image, price -->
    <div class="shop-grid">
        <?php foreach ($products as $p): ?>
            <div class="shop-item">
                <img src="<?= htmlspecialchars($p['image']) ?>" alt="<?= htmlspecialchars($p['name']) ?>">
                <span class="item-name"><?= htmlspecialchars($p['name']) ?></span>
                <span class="item-price">₱ <?= number_format($p['price']) ?></span>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Sell your items -->
    <a href="index.php?page=sell" class="sell-box">
        <span class="sell-icon">🏪</span>
        <span class="sell-text">
            <strong>Sell your items</strong>
            <small>Turn your unused school supplies into cash!</small>
        </span>
        <span class="arrow">›</span>
    </a>
</section>