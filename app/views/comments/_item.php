<?php
// Expects: $comment (array)
?>
<div class="d-flex gap-2 mb-2 align-items-start">
    <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center flex-shrink-0"
         style="width: 32px; height: 32px; font-size: 0.85rem;">
        <?= strtoupper(substr($comment['full_name'], 0, 1)) ?>
    </div>
    <div class="flex-grow-1 bg-light rounded p-2">
        <div class="d-flex justify-content-between align-items-start">
            <div>
                <strong><?= htmlspecialchars($comment['full_name']) ?></strong>
                <small class="text-muted">@<?= htmlspecialchars($comment['username']) ?></small>
                <small class="text-muted ms-1">· <?= date('M j, g:i A', strtotime($comment['created_at'])) ?></small>
            </div>
            <?php if ($comment['user_id'] == $_SESSION['user_id']): ?>
                <div class="btn-group btn-group-sm">
                    <a href="index.php?page=comment.edit&id=<?= $comment['id'] ?>"
                       class="btn btn-link btn-sm text-secondary p-0 ms-2">
                        <i class="bi bi-pencil"></i>
                    </a>
                    <a href="index.php?page=comment.delete&id=<?= $comment['id'] ?>"
                       class="btn btn-link btn-sm text-danger p-0 ms-2"
                       onclick="return confirm('Delete this comment?')">
                        <i class="bi bi-trash"></i>
                    </a>
                </div>  
            <?php endif; ?>
        </div>
        <div class="mt-1"><?= nl2br(htmlspecialchars($comment['content'])) ?></div>
    </div>
</div>