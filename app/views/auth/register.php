<?php
// app/views/auth/register.php
$title = __('auth.register.title') ?? 'Crear Cuenta';
$customCss = ['modules/auth.css'];
require_once APP_ROOT . '/app/views/layouts/header.php';
include APP_ROOT . '/app/views/layouts/navigation.php';
?>

<div class="auth-wrapper">
    <div class="auth-container register animate-fade-in">
        <div class="auth-card">
            <div class="auth-header">
                <h1 class="auth-title"><?= $title ?></h1>
                <p class="auth-subtitle">Únete a nuestra comunidad de viajeros</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= APP_URL ?>/auth/register" id="regForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="auth-label"><?= __('auth.full_name') ?></label>
                        <input type="text" class="auth-input" name="full_name" placeholder="Tu nombre..." required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="auth-label"><?= __('auth.username') ?></label>
                        <input type="text" class="auth-input" name="username" placeholder="Usuario..." required>
                    </div>
                </div>

                <div class="auth-form-group">
                    <label class="auth-label"><?= __('auth.email') ?></label>
                    <input type="email" class="auth-input" name="email" placeholder="correo@ejemplo.com" required>
                </div>

                <div class="row">
                    <!-- Contraseña -->
                    <div class="col-md-6 mb-4">
                        <label class="auth-label"><?= __('auth.password') ?></label>
                        <input type="password" id="password" class="auth-input" name="password" placeholder="••••••••"
                            required>

                        <!-- Strength Meter -->
                        <div class="auth-strength-meter">
                            <div id="strength-bar" class="auth-strength-bar"></div>
                        </div>

                        <!-- Requirements List -->
                        <div class="auth-requirements">
                            <span class="auth-req-title">
                                <i class="fas fa-shield-alt"></i> Tu contraseña necesita:
                            </span>
                            <ul class="auth-req-list">
                                <li class="auth-req-item" id="req-length">
                                    <i class="fas fa-circle"></i> 8+ caracteres
                                </li>
                                <li class="auth-req-item" id="req-upper">
                                    <i class="fas fa-circle"></i> Una mayúscula
                                </li>
                                <li class="auth-req-item" id="req-number">
                                    <i class="fas fa-circle"></i> Un número
                                </li>
                                <li class="auth-req-item" id="req-special">
                                    <i class="fas fa-circle"></i> Un símbolo (!@#)
                                </li>
                            </ul>
                        </div>
                    </div>

                    <!-- Confirmar -->
                    <div class="col-md-6 mb-4">
                        <label class="auth-label"><?= __('auth.confirm_password') ?></label>
                        <input type="password" id="confirm_password" class="auth-input" name="confirm_password"
                            placeholder="••••••••" required>
                        <div id="pwd-match-msg" class="auth-match-msg"></div>

                        <div class="mt-4 p-3 bg-light rounded-3 small text-muted border border-secondary-subtle">
                            <i class="fas fa-info-circle me-1"></i>
                            Asegúrate de que ambas contraseñas coincidan antes de continuar.
                        </div>
                    </div>
                </div>

                <button type="submit" class="auth-btn" id="submitBtn">
                    <i class="fas fa-user-plus"></i>
                    <?= __('auth.register.button') ?>
                </button>
            </form>

            <div class="auth-footer">
                <p>
                    <?= __('auth.register.have_account') ?>
                    <a href="<?= APP_URL ?>/auth/login" class="auth-link">
                        <?= __('auth.register.login_here') ?>
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const pwd = document.getElementById('password');
        const confirm = document.getElementById('confirm_password');
        const matchMsg = document.getElementById('pwd-match-msg');
        const bar = document.getElementById('strength-bar');
        const btn = document.getElementById('submitBtn');

        const reqElements = {
            length: document.getElementById('req-length'),
            upper: document.getElementById('req-upper'),
            number: document.getElementById('req-number'),
            special: document.getElementById('req-special')
        };

        const rules = {
            length: (v) => v.length >= 8,
            upper: (v) => /[A-Z]/.test(v),
            number: (v) => /[0-9]/.test(v),
            special: (v) => /[!@#$%^&*(),.?":{}|<>_]/.test(v)
        };

        function updateRequirements() {
            const val = pwd.value;
            let score = 0;

            Object.keys(rules).forEach(key => {
                const isValid = rules[key](val);
                const el = reqElements[key];
                const icon = el.querySelector('i');

                if (isValid) {
                    el.classList.add('valid');
                    el.classList.remove('invalid');
                    icon.className = 'fas fa-check-circle';
                    score++;
                } else {
                    el.classList.remove('valid');
                    icon.className = 'fas fa-circle';
                    if (val.length > 0) el.classList.add('invalid');
                    else el.classList.remove('invalid');
                }
            });

            // Update Strength Bar
            bar.className = 'auth-strength-bar strength-' + score;

            checkMatch();
        }

        function checkMatch() {
            if (confirm.value.length === 0) {
                matchMsg.innerHTML = '';
                return;
            }

            if (confirm.value === pwd.value) {
                matchMsg.innerHTML = '<i class="fas fa-check-circle"></i> Las contraseñas coinciden';
                matchMsg.className = 'auth-match-msg text-success';
            } else {
                matchMsg.innerHTML = '<i class="fas fa-times-circle"></i> Las contraseñas no coinciden';
                matchMsg.className = 'auth-match-msg text-danger';
            }
        }

        pwd.addEventListener('input', updateRequirements);
        confirm.addEventListener('input', checkMatch);

        // Initial check
        updateRequirements();
    });
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>