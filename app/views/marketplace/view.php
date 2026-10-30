<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center pt-4">
    <div class="col-lg-10">
        
        <div class="mb-3">
            <a href="index.php?page=marketplace" class="text-muted text-decoration-none"><i class="bi bi-arrow-left"></i> Back to Marketplace</a>
        </div>

        <div class="card shadow-lg border-0" style="background: var(--glass-bg); border: 1px solid var(--glass-chrome-1) !important; border-radius: 20px; overflow: hidden;">
            <div class="row g-0">
                
                <!-- Left Side: Image -->
                <div class="col-md-6" style="min-height: 400px; background: #000;">
                    <?php if (!empty($item['image'])): ?>
                        <img src="assets/uploads/<?= htmlspecialchars($item['image']) ?>" style="width: 100%; height: 100%; object-fit: contain;">
                    <?php else: ?>
                        <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                            <i class="bi bi-image" style="font-size: 4rem;"></i>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Right Side: Details Only -->
                <div class="col-md-6 d-flex flex-column" style="max-height: 80vh;">
                    
                    <!-- Header & Details -->
                    <div class="p-4 flex-grow-1" style="overflow-y: auto;">
                        <div class="d-flex align-items-center gap-2 mb-3">
                            <?php if (!empty($item['profile_image']) && $item['profile_image'] !== 'default.png'): ?>
                                <img src="assets/uploads/<?= htmlspecialchars($item['profile_image']) ?>" class="rounded-circle" style="width: 32px; height: 32px; object-fit: cover;">
                            <?php else: ?>
                                <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; font-size: 0.8rem;">
                                    <?= strtoupper(substr($item['full_name'], 0, 1)) ?>
                                </div>
                            <?php endif; ?>
                            <div>
                                <span class="fw-bold" style="color: var(--text-primary);"><?= htmlspecialchars($item['full_name']) ?></span><br>
                                <small class="text-muted"><?= date('M j, Y', strtotime($item['created_at'])) ?></small>
                            </div>
                        </div>
                        <h2 class="fw-bold mb-1" style="color: var(--text-primary);"><?= htmlspecialchars($item['item_name']) ?></h2>
                        <h3 style="color: var(--accent);">₱<?= number_format($item['price'], 2) ?></h3>
                        
                        <hr style="border-color: var(--glass-chrome-2); margin: 20px 0;">
                        
                        <h6 class="text-uppercase text-muted mb-2" style="font-size: 0.75rem; letter-spacing: 1px;">Description</h6>
                        <p style="line-height: 1.6; color: var(--text-primary);"><?= nl2br(htmlspecialchars($item['description'])) ?></p>
                        
                        <!-- MESSAGE SELLER / EDIT / DELETE BUTTONS -->
                        <div class="mt-4 pt-3 border-top" style="border-color: var(--glass-chrome-2) !important;">
                            <?php if (isset($_SESSION['user_id']) && $_SESSION['user_id'] == $item['user_id']): ?>
                                <!-- Owner View: Show Edit and Delete -->
                                <div class="d-flex gap-2">
                                    <a href="index.php?page=marketplace.edit&id=<?= $item['id'] ?>" class="btn btn-outline-primary w-50 py-2" style="border-radius: 12px; font-weight: 600;">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="index.php?page=marketplace.delete&id=<?= $item['id'] ?>" class="btn btn-danger w-50 py-2" style="border-radius: 12px; font-weight: 600;" onclick="return confirm('Are you sure you want to delete this listing? This cannot be undone.');">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </div>
                            <?php elseif (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $item['user_id']): ?>
                                <!-- Buyer View: Show Message Seller -->
                                <a href="index.php?page=chats&with=<?= $item['user_id'] ?>" class="btn btn-primary w-100 py-2" style="border-radius: 12px; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 8px;">
                                    <i class="bi bi-chat-dots-fill"></i> Message Seller
                                </a>
                            <?php else: ?>
                                <!-- Not logged in -->
                                <a href="index.php?page=login" class="btn btn-outline-primary w-100 py-2" style="border-radius: 12px; font-weight: 600;">
                                    Log in to Message Seller
                                </a>
                            <?php endif; ?>
                        </div>
                        <!-- END BUTTONS -->

                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>