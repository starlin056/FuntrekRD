<?php
// app/views/auth/login.php
$title = __('auth.login.title') ?? 'Iniciar Sesión';
$customCss = ['modules/auth.css'];
require_once APP_ROOT . '/app/views/layouts/header.php';
include APP_ROOT . '/app/views/layouts/navigation.php';
?>

<div class="auth-wrapper">
    <div class="auth-container animate-fade-in">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title"><?= $title ?></h1>
                <p class="auth-subtitle">Ingresa tus credenciales para acceder</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= htmlspecialchars($error, ENT_QUOTES, 'UTF-8') ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= APP_URL ?>/auth/login" autocomplete="off">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="auth-form-group">
                    <label class="auth-label" for="email"><?= __('auth.email') ?></label>
                    <input type="email" id="email" class="auth-input" name="email" placeholder="ejemplo@correo.com"
                        required autofocus>
                </div>

                <div class="auth-form-group">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <label class="auth-label mb-0" for="password"><?= __('auth.password') ?></label>
                        <a href="<?= APP_URL ?>/auth/forgotPassword" class="auth-link small"
                            style="font-size: 0.75rem;">
                            ¿Olvidaste tu contraseña?
                        </a>
                    </div>
                    <input type="password" id="password" class="auth-input" name="password" placeholder="••••••••"
                        required>
                </div>

                <button type="submit" class="auth-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    <?= __('auth.login.button') ?>
                </button>
            </form>

            <div class="auth-footer">
                <p>
                    <?= __('auth.login.no_account') ?>
                    <a href="<?= APP_URL ?>/auth/register" class="auth-link">
                        <?= __('auth.login.register_here') ?>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>