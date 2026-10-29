<?php
// app/views/posts/_images.php
// Shows a post's photos as a grid. Tap a photo to open the viewer.
// Expects: $galleryImages = array of file names (in assets/uploads/)
$galleryImages = array_values($galleryImages ?? []);
$galleryCount  = count($galleryImages);
if ($galleryCount > 0):
    $galleryUrls = array_map(function ($f) { return 'assets/uploads/' . $f; }, $galleryImages);
    $galleryShown = min($galleryCount, 4);
    $galleryClass = 'g-' . $galleryShown;
?>
<div class="neo-gallery <?= $galleryClass ?> mb-3"
     data-images="<?= htmlspecialchars(json_encode($galleryUrls), ENT_QUOTES, 'UTF-8') ?>">
    <?php for ($i = 0; $i < $galleryShown; $i++): ?>
        <button type="button" class="neo-g-item" data-i="<?= $i ?>" aria-label="View photo <?= $i + 1 ?> of <?= $galleryCount ?>">
            <img src="<?= htmlspecialchars($galleryUrls[$i]) ?>" alt="Post photo <?= $i + 1 ?>" loading="lazy">
            <?php if ($i === 3 && $galleryCount > 4): ?>
                <span class="neo-g-more">+<?= $galleryCount - 4 ?></span>
            <?php endif; ?>
        </button>
    <?php endfor; ?>
</div>
<?php endif; ?>