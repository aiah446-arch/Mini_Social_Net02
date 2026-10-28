<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h4 class="mb-3"><i class="bi bi-pencil-square"></i> Edit Profile</h4>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $e): ?>
                                <li><?= htmlspecialchars($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=profile.edit" enctype="multipart/form-data">

                    <div class="mb-3 text-center">
                        <img src="assets/uploads/<?= htmlspecialchars($user['profile_image'] ?: '') ?>"
                             alt="Profile"
                             class="rounded-circle mb-2"
                             style="width: 100px; height: 100px; object-fit: cover; background: #1877f2;"
                             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">

                    </div>

                    <div class="mb-3">
                        <label class="form-label">Profile Picture</label>
                        <input type="file" name="profile_image" class="form-control" accept="image/*">
                        <small class="text-muted">Max 2MB. JPG, PNG, GIF, WEBP only.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($user['username']) ?>" disabled>
                        <small class="text-muted">Username cannot be changed.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control"
                               value="<?= htmlspecialchars($_POST['full_name'] ?? $user['full_name']) ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" rows="3" maxlength="500"><?= htmlspecialchars($_POST['bio'] ?? $user['bio']) ?></textarea>
                        <small class="text-muted">Max 500 characters.</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Save Changes
                        </button>
                        <a href="index.php?page=profile&id=<?= $_SESSION['user_id'] ?>"
                           class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>