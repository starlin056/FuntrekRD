<?php
// app/views/auth/forgot_password.php
$title = 'Recuperar Acceso';
$customCss = ['modules/auth.css'];
require_once APP_ROOT . '/app/views/layouts/header.php';
include APP_ROOT . '/app/views/layouts/navigation.php';
?>

<div class="auth-wrapper">
    <div class="auth-container animate-fade-in">
        <div class="auth-card">
            <div class="test-center mb-4 text-center">
                <div class="auth-icon-circle mb-3 mx-auto d-flex align-items-center justify-content-center"
                    style="width: 70px; height: 70px; background: var(--auth-border); border-radius: 50%; color: var(--auth-primary);">
                    <i class="fas fa-key fa-2x"></i>
                </div>
                <h1 class="auth-title">¿Olvidaste tu clave?</h1>
                <p class="auth-subtitle">No te preocupes, dinos tu correo y te enviaremos un enlace seguro.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <?php if (!empty($success)): ?>
                <div class="alert alert-success text-center">
                    <i class="fas fa-check-circle me-2"></i>
                    <?= htmlspecialchars($success) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= APP_URL ?>/auth/forgotPassword">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="auth-form-group">
                    <label class="auth-label"><?= __('auth.email') ?></label>
                    <input type="email" class="auth-input" name="email" placeholder="ejemplo@correo.com" required
                        autofocus>
                </div>

                <button type="submit" class="auth-btn">
                    <i class="fas fa-paper-plane"></i>
                    Enviar Instrucciones
                </button>
            </form>

            <div class="auth-footer">
                <a href="<?= APP_URL ?>/auth/login" class="auth-link small">
                    <i class="fas fa-arrow-left me-1"></i> Volver al inicio de sesión
                </a>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>