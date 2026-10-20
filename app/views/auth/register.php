<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="row justify-content-center align-items-center" style="min-height: 80vh; padding-top: 20px; padding-bottom: 20px;">
    <div class="col-md-6 col-lg-5">
        
        <div class="card shadow-lg" style="border: 1px solid var(--glass-chrome-1); background: var(--glass-bg-3);">
            <div class="card-body p-4 p-md-5">

                <!-- Glass Logo & Welcome Text -->
                <div class="text-center mb-4">
                    <div class="logo-circle mx-auto mb-3">
                        <i class="bi bi-person-plus-fill"></i>
                    </div>
                    <h3 class="mb-1 fw-bold" style="color: var(--text-primary);">Create Account</h3>
                    <p class="text-muted small mb-0">Join NEO today</p>
                </div>

                <!-- Error Alerts -->
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger" style="border-radius: 10px;">
                        <ul class="mb-0 ps-3">
                            <?php foreach ($errors as $e): ?>
                                <li><?= htmlspecialchars($e) ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <!-- Register Form -->
                <form method="POST" action="index.php?page=register">
                    
                    <!-- Username -->
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 13px; color: var(--text-secondary);">Username</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); border-right: none; color: var(--text-muted); border-radius: 50px 0 0 50px;">
                                <i class="bi bi-at"></i>
                            </span>
                            <input type="text" name="username" class="form-control"
                                   value="<?= htmlspecialchars($old['username'] ?? '') ?>" required
                                   placeholder="Choose a username"
                                   style="border-radius: 0 50px 50px 0; border-left: none;">
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">Letters, numbers, underscore only. Min 3 characters.</small>
                    </div>

                    <!-- Full Name -->
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 13px; color: var(--text-secondary);">Full Name</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); border-right: none; color: var(--text-muted); border-radius: 50px 0 0 50px;">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" name="full_name" class="form-control"
                                   value="<?= htmlspecialchars($old['full_name'] ?? '') ?>" required
                                   placeholder="Enter your full name"
                                   style="border-radius: 0 50px 50px 0; border-left: none;">
                        </div>
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 13px; color: var(--text-secondary);">Password</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); border-right: none; color: var(--text-muted); border-radius: 50px 0 0 50px;">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" name="password" class="form-control" required
                                   placeholder="Create a password"
                                   style="border-radius: 0 50px 50px 0; border-left: none;">
                        </div>
                        <small class="text-muted d-block mt-1" style="font-size: 11px;">Minimum 6 characters.</small>
                    </div>

                    <!-- Confirm Password -->
                    <div class="mb-4">
                        <label class="form-label fw-bold" style="font-size: 13px; color: var(--text-secondary);">Confirm Password</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); border-right: none; color: var(--text-muted); border-radius: 50px 0 0 50px;">
                                <i class="bi bi-shield-check"></i>
                            </span>
                            <input type="password" name="confirm_password" class="form-control" required
                                   placeholder="Confirm your password"
                                   style="border-radius: 0 50px 50px 0; border-left: none;">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="btn btn-primary w-100 py-2 fs-6">
                        <i class="bi bi-check-circle me-1"></i> Register
                    </button>
                </form>

                <!-- Login Link -->
                <p class="text-center mt-4 mb-0" style="font-size: 14px; color: var(--text-muted);">
                    Already have an account?
                    <a href="index.php?page=login" class="fw-bold text-decoration-none" style="color: var(--accent);">Login here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>