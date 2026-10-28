<?php 
$pageTitle = 'Notifications - NEO'; 
require_once __DIR__ . '/../layouts/header.php'; 

require_once __DIR__ . '/../../models/UserModel.php';
$uModel = new UserModel((new Database())->getConnection());
?>

<div class="row justify-content-center pt-4">
    <div class="col-lg-8">
        <div class="card shadow-lg border-0" style="background: var(--glass-bg); border: 1px solid var(--glass-chrome-1) !important; border-radius: 16px;">
            <div class="card-body p-4">
                
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-start mb-4">
                    <div>
                        <h3 class="fw-bold mb-1" style="color: var(--text-primary);">Notifications</h3>
                        <p class="text-muted mb-0" style="font-size: 14px;">Stay updated on your posts and marketplace activity.</p>
                    </div>
                    <!-- Mark all as read -->
                    <button id="markReadBtn" class="btn btn-link text-decoration-none text-muted p-0" style="font-size: 13px;">
                        Mark all as read
                    </button>
                </div>

                <hr style="border-color: var(--glass-chrome-2);">

                <!-- Notification List -->
                <div class="list-group list-group-flush" style="background: transparent;">
                    <?php if (empty($notifications)): ?>
                        <div class="text-center py-5 text-muted">
                            <i class="bi bi-bell-slash" style="font-size: 3rem; opacity: 0.5;"></i>
                            <p class="mt-3">No notifications yet.</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($notifications as $notif): ?>
                            <?php 
                                $isFollowNotif = ($notif['type'] === 'follow');
                                $isMessageNotif = ($notif['type'] === 'message');
                                $alreadyFollowing = $isFollowNotif ? $uModel->isFollowing($_SESSION['user_id'], $notif['sender_id']) : false;
                            ?>
                            <div class="list-group-item d-flex gap-3 py-3" style="background: transparent; border-bottom: 1px solid var(--glass-chrome-2);">
                                
                                <!-- Icon -->
                                <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" 
                                     style="width: 45px; height: 45px; background: <?= $notif['type'] === 'like' ? 'rgba(16, 185, 129, 0.15)' : ($isMessageNotif ? 'rgba(234, 179, 8, 0.15)' : 'rgba(59, 130, 246, 0.15)') ?>;">
                                    <i class="bi <?= $notif['type'] === 'like' ? 'bi-heart' : ($isMessageNotif ? 'bi-chat-dots' : 'bi-person-plus') ?>" 
                                       style="color: <?= $notif['type'] === 'like' ? '#10B981' : ($isMessageNotif ? '#EAB308' : '#3B82F6') ?>; font-size: 1.2rem;"></i>
                                </div>

                                <!-- Text Area -->
                                <div class="flex-grow-1">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-1 text-white fw-bold" style="font-size: 15px;">
                                            <a href="index.php?page=profile&id=<?= $notif['sender_id'] ?>" class="text-white text-decoration-none">
                                                <?= htmlspecialchars($notif['full_name']) ?>
                                            </a>
                                            <?php if ($notif['type'] === 'like'): ?>
                                                liked your post
                                            <?php elseif ($isMessageNotif): ?>
                                                sent you a message
                                            <?php else: ?>
                                                started following you
                                            <?php endif; ?>
                                        </h6>
                                        
                                        <!-- Follow Back Button -->
                                        <?php if ($isFollowNotif && !$alreadyFollowing): ?>
                                            <button type="button" class="btn btn-sm follow-btn-notif btn-primary" data-userid="<?= $notif['sender_id'] ?>" style="border-radius: 20px; font-size: 11px; padding: 2px 10px;">
                                                Follow Back
                                            </button>
                                        <?php elseif ($isMessageNotif): ?>
                                            <a href="index.php?page=chats&with=<?= $notif['sender_id'] ?>" class="btn btn-sm btn-outline-secondary" style="border-radius: 20px; font-size: 11px; padding: 2px 10px;">
                                                Reply
                                            </a>
                                        <?php endif; ?>
                                    </div>
                                    
                                    <!-- Preview Content -->
                                    <p class="text-muted mb-1" style="font-size: 13px; font-style: italic;">
                                        <?php if ($notif['type'] === 'like'): ?>
                                            "Good things take time. 💚"
                                        <?php elseif ($isMessageNotif): ?>
                                            "Click 'Reply' to open the chat."
                                        <?php else: ?>
                                            "@<?= htmlspecialchars($notif['username']) ?> is now in your network."
                                        <?php endif; ?>
                                    </p>
                                    
                                    <!-- Timestamp -->
                                    <small class="text-muted" style="font-size: 11px;">
                                        <?= date('M j, g:i A', strtotime($notif['created_at'])) ?>
                                    </small>
                                </div>

                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>

                <!-- "You're all caught up!" Box -->
                <div class="card mt-4 border-0" style="background: rgba(255, 255, 255, 0.03); border-radius: 12px;">
                    <div class="card-body text-center py-4">
                        <i class="bi bi-bell text-muted" style="font-size: 2rem;"></i>
                        <h6 class="text-white mt-2 mb-1">You're all caught up!</h6>
                        <p class="text-muted mb-0" style="font-size: 13px;">This message appears when there are no new notifications.</p>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
    // --- AJAX Follow Back Logic ---
    document.querySelectorAll('.follow-btn-notif').forEach(button => {
        button.addEventListener('click', function() {
            const userId = this.getAttribute('data-userid');
            const btn = this;
            
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
            
            fetch('index.php?page=profile.follow&ajax=1&id=' + userId)
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'followed') {
                        btn.outerHTML = '<span class="badge bg-secondary" style="font-size: 10px;">Following</span>';
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    btn.innerHTML = 'Error';
                });
        });
    });

    // --- AJAX Mark as Read Logic ---
    const markReadBtn = document.getElementById('markReadBtn');
    if (markReadBtn) {
        markReadBtn.addEventListener('click', function() {
            const btn = this;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';
            
            fetch('index.php?page=notifications&action=mark_read')
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        btn.innerHTML = '<i class="bi bi-check2-all"></i> All read';
                        btn.classList.add('text-success');
                        btn.classList.remove('text-muted');
                        
                        const redBadge = document.querySelector('.badge.bg-danger');
                        if (redBadge) redBadge.remove();
                    }
                })
                .catch(err => console.error('Error:', err));
        });
    }
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>