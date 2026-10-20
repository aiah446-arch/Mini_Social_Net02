<?php
require_once __DIR__ . '/../layouts/header.php';
?>

<div class="row justify-content-center align-items-center" style="min-height: 75vh;">
    <div class="col-md-6 col-lg-5">
        
        <div class="card shadow-lg" style="border: 1px solid var(--glass-chrome-1); background: var(--glass-bg-3);">
            <div class="card-body p-4 p-md-5">

                <!-- Glass Logo & Welcome Text -->
                <div class="text-center mb-4">
                    <div class="logo-circle mx-auto mb-3">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <h3 class="mb-1 fw-bold" style="color: var(--text-primary);">Welcome Back</h3>
                    <p class="text-muted small mb-0">Sign in to continue to NEO</p>
                </div>

                <!-- Error Alert -->
                <?php if (!empty($error)): ?>
                    <div class="alert alert-danger" style="border-radius: 10px;">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <!-- Login Form -->
                <form method="POST" action="index.php?page=login">
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold" style="font-size: 13px; color: var(--text-secondary);">Username</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); border-right: none; color: var(--text-muted); border-radius: 50px 0 0 50px;">
                                <i class="bi bi-person"></i>
                            </span>
                            <input type="text" name="username" class="form-control" 
                                   value="<?= htmlspecialchars($old_username ?? '') ?>" required autofocus
                                   placeholder="Enter your username"
                                   style="border-radius: 0 50px 50px 0; border-left: none;">
                        </div>
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-bold" style="font-size: 13px; color: var(--text-secondary);">Password</label>
                        <div class="input-group">
                            <span class="input-group-text" style="background: var(--glass-bg-2); border: 1px solid var(--glass-chrome-2); border-right: none; color: var(--text-muted); border-radius: 50px 0 0 50px;">
                                <i class="bi bi-lock"></i>
                            </span>
                            <input type="password" name="password" class="form-control" required
                                   placeholder="Enter your password"
                                   style="border-radius: 0 50px 50px 0; border-left: none;">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 py-2 fs-6">
                        <i class="bi bi-box-arrow-in-right me-1"></i> Login
                    </button>
                </form>

                <!-- Register Link -->
                <p class="text-center mt-4 mb-0" style="font-size: 14px; color: var(--text-muted);">
                    Don't have an account?
                    <a href="index.php?page=register" class="fw-bold text-decoration-none" style="color: var(--accent);">Register here</a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>