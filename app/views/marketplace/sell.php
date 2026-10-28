<?php
// app/views/marketplace/sell.php
// "Sell your items" form. Variables from MarketplaceController::sell():
// $error, $old (title, price, description), $success (title, price) or null
require_once _DIR_ . '/../layouts/header.php';
$e = static fn($v) => htmlspecialchars((string) $v, ENT_QUOTES, 'UTF-8');
?>
<div class="sell-page">
    <div class="sell-form-card">
        <a href="index.php?page=feed" class="sell-back">‹ Back</a>
        <h2>Sell your items</h2>
        <p class="sell-sub">Turn your unused school supplies into cash!</p>

        <?php if (!empty($success)): ?>
            <div class="sell-alert sell-alert-ok">
                Your item "<?= $e($success['title']) ?>" was posted for
                ₱ <?= number_format((float) $success['price'], (float) $success['price'] == floor((float) $success['price']) ? 0 : 2) ?>!
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="sell-alert sell-alert-err"><?= $e($error) ?></div>
        <?php endif; ?>

        <form method="post" action="index.php?page=sell" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $e($_SESSION['csrf_token']) ?>">

            <label for="title">Item name</label>
            <input type="text" id="title" name="title" maxlength="120" required
                   placeholder="e.g. Scientific Calculator" value="<?= $e($old['title']) ?>">

            <label for="price">Price (₱)</label>
            <input type="number" id="price" name="price" min="0" step="0.01" required
                   placeholder="e.g. 150" value="<?= $e($old['price']) ?>">

            <label for="description">Description</label>
            <textarea id="description" name="description" rows="4" maxlength="3000" required
                      placeholder="Condition, size, how long you used it..."><?= $e($old['description']) ?></textarea>

            <label for="image">Photo</label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">

            <div class="sell-actions">
                <a href="index.php?page=feed" class="sell-cancel">Cancel</a>
                <button type="submit" class="sell-submit">Post item</button>
            </div>
        </form>
    </div>
</div>
<?php require_once _DIR_ . '/../layouts/footer.php'; ?>