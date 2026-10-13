<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<?php
require_once __DIR__ . '/../../models/CommentModel.php';
require_once __DIR__ . '/../../models/LikeModel.php';
$db = (new Database())->getConnection();
$commentModel = new CommentModel($db);
$likeModel = new LikeModel($db);
?>

<div class="row justify-content-center">
    <div class="col-lg-8">

        <!-- Create Post Box -->
        <div class="card shadow-sm border-0 mb-4">
            <div class="card-body">
                <form method="POST" action="index.php?page=post.create" enctype="multipart/form-data">
                    <textarea name="content" class="form-control mb-2" rows="2"
                              placeholder="What's on your mind, <?= htmlspecialchars($_SESSION['full_name']) ?>?"></textarea>

                    <!-- Photo preview (hidden by default) -->
                    <div id="feedPreviewBox" class="mb-2 position-relative d-none">
                        <img id="feedPreviewImg" src="" alt=""
                             class="rounded-3 border"
                             style="max-height: 120px; max-width: 200px; object-fit: cover;">
                        <button type="button" id="removePreview"
                                class="btn btn-sm btn-danger position-absolute"
                                style="top: -10px; right: -10px; border-radius: 50%; width: 28px; height: 28px; padding: 0; line-height: 1;"
                                title="Remove photo">
                            <i class="bi bi-x-lg" style="font-size: 0.7rem;"></i>
                        </button>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <input type="file" name="image" id="feedImageInput" accept="image/*"
                                   style="display: none;">
                            <label for="feedImageInput" class="btn btn-sm btn-outline-secondary mb-0" id="feedImageLabel" style="cursor: pointer;">
                                <i class="bi bi-image"></i> Photo
                            </label>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm">
                            <i class="bi bi-send"></i> Post
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Feed -->
        <?php if (empty($posts)): ?>
            <div class="card shadow-sm border-0">
                <div class="card-body text-center py-5">
                    <div class="empty-state-icon mx-auto mb-3">
                        <i class="bi bi-chat-square-text"></i>
                    </div>
                    <h5 class="mb-1">No posts yet</h5>
                    <p class="text-muted mb-0">Be the first to share something!</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($posts as $post): ?>
                <?php
                $comments = $commentModel->getByPost($post['id']);
                $likeCount = $likeModel->countByPost($post['id']);
                $liked = $likeModel->hasLiked($post['id'], $_SESSION['user_id']);
                ?>
                <div class="card shadow-sm border-0 mb-3">
                    <div class="card-body">

                        <div class="d-flex align-items-center mb-2">
                            <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2"
                                 style="width: 42px; height: 42px;">
                                <?= strtoupper(substr($post['full_name'], 0, 1)) ?>
                            </div>
                            <div>
                                <strong><?= htmlspecialchars($post['full_name']) ?></strong>
                                <span class="text-muted">@<?= htmlspecialchars($post['username']) ?></span><br>
                                <small class="text-muted">
                                    <?= date('M j, Y g:i A', strtotime($post['created_at'])) ?>
                                </small>
                            </div>
                        </div>

                        <p class="mb-3"><?= nl2br(htmlspecialchars($post['content'])) ?></p>

                        <?php if (!empty($post['image'])): ?>
                            <div class="mb-3">
                                <img src="assets/uploads/<?= htmlspecialchars($post['image']) ?>"
                                     alt="Post image" class="rounded-3"
                                     style="max-width: 100%; max-height: 400px; object-fit: cover;">
                            </div>
                        <?php endif; ?>

                        <div class="border-top pt-2 d-flex align-items-center gap-2">
                            <a href="index.php?page=like.toggle&id=<?= $post['id'] ?>"
                               class="btn btn-sm <?= $liked ? 'btn-danger' : 'btn-outline-danger' ?>">
                                <i class="bi <?= $liked ? 'bi-heart-fill' : 'bi-heart' ?>"></i>
                                <?= $likeCount ?>
                            </a>

                            <?php if ($post['user_id'] == $_SESSION['user_id']): ?>
                                <a href="index.php?page=post.edit&id=<?= $post['id'] ?>"
                                   class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="index.php?page=post.delete&id=<?= $post['id'] ?>"
                                   class="btn btn-sm btn-outline-danger"
                                   onclick="return confirm('Delete this post?')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                            <?php endif; ?>
                        </div>

                        <div class="border-top pt-3 mt-3">
                            <?php if (!empty($comments)): ?>
                                <div class="mb-3">
                                    <?php foreach ($comments as $comment): ?>
                                        <?php require __DIR__ . '/../comments/_item.php'; ?>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="index.php?page=comment.create" class="d-flex gap-2">
                                <input type="hidden" name="post_id" value="<?= $post['id'] ?>">
                                <input type="text" name="content" class="form-control form-control-sm"
                                       placeholder="Write a comment..." required maxlength="500">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="bi bi-send"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

    </div>
</div>

<script>
    // Live preview for photo upload
    (function() {
        var input = document.getElementById('feedImageInput');
        var previewBox = document.getElementById('feedPreviewBox');
        var previewImg = document.getElementById('feedPreviewImg');
        var label = document.getElementById('feedImageLabel');
        var removeBtn = document.getElementById('removePreview');

        if (!input) return;

        input.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(ev) {
                    previewImg.src = ev.target.result;
                    previewBox.classList.remove('d-none');
                    previewBox.classList.add('d-inline-block');
                    label.innerHTML = '<i class="bi bi-check-circle"></i> Photo ready';
                };
                reader.readAsDataURL(file);
            } else {
                previewImg.src = '';
                previewBox.classList.add('d-none');
                previewBox.classList.remove('d-inline-block');
                label.innerHTML = '<i class="bi bi-image"></i> Photo';
            }
        });

        removeBtn.addEventListener('click', function() {
            input.value = '';
            previewImg.src = '';
            previewBox.classList.add('d-none');
            previewBox.classList.remove('d-inline-block');
            label.innerHTML = '<i class="bi bi-image"></i> Photo';
        });
    })();
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>