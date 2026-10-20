<?php
// Expects: $comment (array)
?>
<div class="d-flex gap-2 mb-3 align-items-start">
    
    <!-- User Avatar -->
    <div class="rounded-circle d-flex align-items-center justify-content-center flex-shrink-0 text-white fw-bold"
         style="width: 32px; height: 32px; font-size: 0.85rem; background: var(--accent); color: var(--bg-base) !important; border: 1px solid var(--glass-chrome-1);">
        <?= strtoupper(substr($comment['full_name'], 0, 1)) ?>
    </div>
    
    <!-- Comment Bubble -->
    <div class="flex-grow-1 rounded-3 p-2 px-3" style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2);">
        <div class="d-flex justify-content-between align-items-start mb-1">
            <div>
                <strong style="color: var(--text-primary); font-size: 13px;"><?= htmlspecialchars($comment['full_name']) ?></strong>
                <small class="text-muted ms-1" style="font-size: 11px;">@<?= htmlspecialchars($comment['username']) ?> · <?= date('M j, g:i A', strtotime($comment['created_at'])) ?></small>
            </div>
            
            <!-- Edit/Delete Buttons (Only for owner) -->
            <?php if ($comment['user_id'] == $_SESSION['user_id']): ?>
                <div class="d-flex gap-2 ms-2">
                    <a href="index.php?page=comment.edit&id=<?= $comment['id'] ?>"
                       class="text-secondary text-decoration-none" style="font-size: 12px;">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="index.php?page=comment.delete&id=<?= $comment['id'] ?>"
                       class="text-danger text-decoration-none" style="font-size: 12px;"
                       onclick="return confirm('Delete this comment?')">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>  
            <?php endif; ?>
        </div>
        
        <!-- Comment Content -->
        <div style="font-size: 13px; color: var(--text-primary); line-height: 1.4;">
            <?= nl2br(htmlspecialchars($comment['content'])) ?>
        </div>
    </div>
</div>