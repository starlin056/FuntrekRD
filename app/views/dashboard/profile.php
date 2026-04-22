<?php 
$customCss = ['modules/admin-dashboard.css', 'modules/admin-forms.css'];
require_once APP_ROOT . '/app/views/layouts/header.php'; 
require_once APP_ROOT . '/app/views/layouts/navigation.php'; 

// Local helper for initials
$getInitials = function($name) {
    if (empty($name)) return '??';
    $words = explode(' ', trim($name));
    $initials = '';
    foreach ($words as $w) {
        $initials .= mb_substr($w, 0, 1);
        if (mb_strlen($initials) >= 2) break;
    }
    return mb_strtoupper($initials);
};
?>

<div class="adm-page">
    <div class="container-fluid animate-fade-in">
        <div class="row g-4">
            
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="adm-sidebar-sticky">
                    <div class="adm-sidebar">
                        <a href="<?= APP_URL ?>/dashboard/profile" class="adm-sidebar-header">
                            <div class="adm-avatar-small">
                                <?= mb_strtoupper(mb_substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <div class="adm-sidebar-info">
                                <span><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></span>
                                <small>Cliente FunTrek</small>
                            </div>
                        </a>
                        <nav class="adm-nav">
                            <a href="<?= APP_URL ?>/dashboard" class="adm-nav-link">
                                <i class="fas fa-th-large"></i> Resumen
                            </a>
                            <a href="<?= APP_URL ?>/dashboard/bookings" class="adm-nav-link">
                                <i class="fas fa-calendar-check"></i> Mis Reservas
                            </a>
                            <a href="<?= APP_URL ?>/dashboard/profile" class="adm-nav-link active">
                                <i class="fas fa-user-edit"></i> Editar Perfil
                            </a>
                            <hr>
                            <a href="<?= APP_URL ?>/auth/logout" class="adm-nav-link text-danger">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </nav>
                    </div>

                    <div class="card adm-card bg-primary text-white border-0 overflow-hidden shadow-lg"
                        style="animation-delay: 0.2s">
                        <div class="card-body p-4 position-relative">
                            <i class="fas fa-umbrella-beach position-absolute opacity-10"
                                style="font-size: 8rem; right: -20px; bottom: -20px;"></i>
                            <h6 class="fw-bold mb-2">¿Planeas otro viaje?</h6>
                            <p class="small mb-3 opacity-75">Descubre nuestras ofertas exclusivas en paquetes todo
                                incluido.
                            </p>
                            <a href="<?= APP_URL ?>/paquetes"
                                class="btn btn-light btn-sm rounded-pill fw-bold px-3">Explorar más</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Alertas -->
                <?php foreach (['success' => 'success', 'error' => 'danger'] as $key => $type): ?>
                    <?php if (!empty($_SESSION[$key])): ?>
                        <div class="adm-alert adm-alert-<?= $type ?> mb-4 shadow-sm border-0">
                            <i class="fas <?= $key === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle' ?> me-2"></i>
                            <?= htmlspecialchars($_SESSION[$key]) ?>
                            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION[$key]); ?>
                    <?php endif; ?>
                <?php endforeach; ?>

                <div class="d-flex align-items-center mb-4">
                    <a href="<?= APP_URL ?>/dashboard" class="adm-btn-outline me-3">
                        <i class="fas fa-arrow-left"></i>
                    </a>
                    <div>
                        <h1 class="adm-title mb-0"><?= __('profile.title') ?></h1>
                        <p class="text-muted small mb-0">Gestiona tu información de acceso y contacto</p>
                    </div>
                </div>

                <div class="adm-card border-0 shadow-sm overflow-hidden">
                    <div class="card-header bg-white p-4 border-0">
                        <div class="d-flex align-items-center gap-3">
                            <div class="adm-avatar-large shadow-sm">
                                <?= $getInitials($user['full_name'] ?? 'U') ?>
                            </div>
                            <div>
                                <h3 class="fw-bold mb-1" style="color: var(--adm-dark);"><?= htmlspecialchars($user['full_name'] ?? 'Usuario') ?></h3>
                                <p class="text-muted small mb-0"><i class="fas fa-envelope me-1"></i> <?= htmlspecialchars($user['email'] ?? '') ?></p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body p-4 pt-0">
                        <form method="POST" action="<?= APP_URL ?>/dashboard/profile" class="row g-4">
                            
                            <div class="col-md-6">
                                <div class="adm-form-group">
                                    <label class="adm-form-label" for="full_name">
                                        <i class="fas fa-user me-1 text-primary"></i> <?= __('profile.full_name') ?>
                                    </label>
                                    <input type="text" class="adm-form-control" id="full_name" name="full_name"
                                        value="<?= htmlspecialchars($user['full_name'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="adm-form-group">
                                    <label class="adm-form-label" for="email">
                                        <i class="fas fa-at me-1 text-primary"></i> <?= __('profile.email') ?>
                                    </label>
                                    <input type="email" class="adm-form-control" id="email" name="email"
                                        value="<?= htmlspecialchars($user['email'] ?? '') ?>" required>
                                </div>
                            </div>

                            <div class="col-12">
                                <hr class="opacity-10 my-2">
                                <div class="adm-form-group">
                                    <label class="adm-form-label" for="password">
                                        <i class="fas fa-lock me-1 text-primary"></i> <?= __('profile.new_password') ?>
                                    </label>
                                    <input type="password" class="adm-form-control" id="password" name="password"
                                        placeholder="<?= __('profile.password_placeholder') ?>">
                                    <div class="adm-form-text text-muted small mt-2">
                                        <i class="fas fa-info-circle me-1"></i>
                                        Para mantener tu contraseña actual, deja este campo vacío.
                                    </div>
                                    
                                    <div id="password-requirements" class="mt-3 p-3 bg-light rounded-3 d-none">
                                        <div class="requirement mb-1 small" id="req-length"><i class="fas fa-circle me-2"></i> 8+ caracteres</div>
                                        <div class="requirement mb-1 small" id="req-upper"><i class="fas fa-circle me-2"></i> Una mayúscula</div>
                                        <div class="requirement mb-0 small" id="req-number"><i class="fas fa-circle me-2"></i> Un número</div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 pt-3">
                                <button type="submit" class="adm-new-btn w-100 justify-content-center py-3">
                                    <i class="fas fa-save me-2"></i> <?= __('profile.update') ?>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pwd = document.getElementById('password');
    const reqsBox = document.getElementById('password-requirements');
    
    pwd.addEventListener('input', function() {
        const val = this.value;
        if(val.length > 0) reqsBox.classList.remove('d-none');
        else reqsBox.classList.add('d-none');

        const checks = {
            length: val.length >= 8,
            upper: /[A-Z]/.test(val),
            number: /[0-9]/.test(val)
        };

        Object.keys(checks).forEach(k => {
            const el = document.getElementById('req-'+k);
            if(checks[k]) {
                el.classList.add('text-success', 'fw-bold');
                el.querySelector('i').className = 'fas fa-check-circle me-2';
            } else {
                el.classList.remove('text-success', 'fw-bold');
                el.querySelector('i').className = 'fas fa-circle me-2';
            }
        });
    });
});
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>