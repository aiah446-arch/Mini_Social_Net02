<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4 p-md-5">

                <!-- Glass logo circle -->
                <div class="text-center mb-4">
                    <div class="logo-circle mx-auto mb-3">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <h3 class="mb-0">Create Account</h3>
                    <p class="text-muted small mb-0">Join Mini Social today</p>
                </div>

                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            <?php foreach ($errors as $e): ?>
                                <li><?= htmlspecialchars($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=register">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username" class="form-control"
                               value="<?= htmlspecialchars($old['username'] ?? '') ?>" required>
                        <small class="text-muted">Letters, numbers, underscore only. Min 3 characters.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Full Name</label>
                        <input type="text" name="full_name" class="form-control"
                               value="<?= htmlspecialchars($old['full_name'] ?? '') ?>" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-control" required>
                        <small class="text-muted">Minimum 6 characters.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Confirm Password</label>
                        <input type="password" name="confirm_password" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle"></i> Register
                    </button>
                </form>

                <p class="text-center mt-4 mb-0">
                    Already have an account?
                    <a href="index.php?page=login">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>