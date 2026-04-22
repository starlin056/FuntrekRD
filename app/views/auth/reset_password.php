<?php
// app/views/auth/reset_password.php
$title = 'Nueva Contraseña';
$customCss = ['modules/auth.css'];
require_once APP_ROOT . '/app/views/layouts/header.php';
include APP_ROOT . '/app/views/layouts/navigation.php';
?>

<div class="auth-wrapper">
    <div class="auth-container animate-fade-in">
        <div class="auth-card">
            <div class="test-center mb-4 text-center">
                <div class="auth-icon-circle mb-3 mx-auto d-flex align-items-center justify-content-center"
                    style="width: 70px; height: 70px; background: rgba(22, 163, 74, 0.1); border-radius: 50%; color: #16a34a;">
                    <i class="fas fa-lock-open fa-2x"></i>
                </div>
                <h1 class="auth-title">Restablecer Clave</h1>
                <p class="auth-subtitle">Ingresa una clave robusta para proteger tu cuenta.</p>
            </div>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger text-center">
                    <i class="fas fa-exclamation-circle me-2"></i>
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= APP_URL ?>/auth/resetPassword/<?= $token ?>" id="resetForm">
                <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">

                <!-- Nueva Contraseña -->
                <div class="auth-form-group">
                    <label class="auth-label">Nueva Contraseña</label>
                    <input type="password" id="password" class="auth-input" name="password" placeholder="••••••••"
                        required autofocus>

                    <!-- Strength Meter -->
                    <div class="auth-strength-meter">
                        <div id="strength-bar" class="auth-strength-bar"></div>
                    </div>

                    <!-- Requirements -->
                    <div class="auth-requirements">
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
                <div class="auth-form-group">
                    <label class="auth-label">Confirmar Contraseña</label>
                    <input type="password" id="confirm_password" class="auth-input" name="confirm_password"
                        placeholder="••••••••" required>
                    <div id="pwd-match-msg" class="auth-match-msg"></div>
                </div>

                <button type="submit" class="auth-btn" id="submitBtn">
                    <i class="fas fa-save"></i>
                    Actualizar Contraseña
                </button>
            </form>

            <div class="auth-footer">
                <a href="<?= APP_URL ?>/auth/login" class="auth-link small">
                    Cancelar y volver al inicio
                </a>
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
        updateRequirements();
    });
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>