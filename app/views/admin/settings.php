<?php $customCss = ['modules/admin-dashboard.css']; ?>
<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<style>
    .settings-page {
        max-width: 1000px;
        margin: 0 auto;
        padding-bottom: 50px;
    }
    .settings-header {
        background: linear-gradient(135deg, var(--adm-blue) 0%, var(--adm-teal) 100%);
        border-radius: 20px;
        color: white;
        padding: 40px;
        margin-bottom: 40px;
        box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        position: relative;
        overflow: hidden;
    }
    .settings-header::after {
        content: '\f1de';
        font-family: 'Font Awesome 6 Free';
        font-weight: 900;
        position: absolute;
        right: -20px;
        bottom: -20px;
        font-size: 15rem;
        opacity: 0.1;
        transform: rotate(-15deg);
    }
    .setting-card {
        background: white;
        border-radius: 20px;
        border: none;
        box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        margin-bottom: 30px;
        overflow: hidden;
    }
    .setting-card-header {
        background: #f8f9fa;
        padding: 20px 30px;
        border-bottom: 1px solid #edf2f7;
        display: flex;
        align-items: center;
        gap: 15px;
    }
    .setting-card-header i {
        background: white;
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        color: var(--adm-blue);
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .setting-card-body {
        padding: 30px;
    }
    .logo-uploader {
        text-align: center;
        margin-bottom: 30px;
    }
    .logo-preview-wrapper {
        width: 250px;
        height: 150px;
        background: #f1f5f9;
        border: 2px dashed #cbd5e1;
        border-radius: 15px;
        margin: 0 auto 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        cursor: pointer;
        transition: all 0.3s;
        overflow: hidden;
    }
    .logo-preview-wrapper:hover {
        border-color: var(--adm-blue);
        background: #eff6ff;
    }
    .logo-preview-wrapper img {
        max-width: 90%;
        max-height: 90%;
        object-fit: contain;
    }
    .form-floating > .form-control {
        border-radius: 12px;
        border: 2px solid #e2e8f0;
    }
    .form-floating > .form-control:focus {
        border-color: var(--adm-blue);
        box-shadow: none;
    }
    .btn-save-settings {
        background: var(--adm-blue);
        color: white;
        border: none;
        padding: 15px 40px;
        border-radius: 50px;
        font-weight: 700;
        letter-spacing: 1px;
        box-shadow: 0 5px 15px rgba(0, 119, 182, 0.3);
        transition: all 0.3s;
    }
    .btn-save-settings:hover {
        background: #023e8a;
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0, 119, 182, 0.4);
    }
</style>

<div class="adm-page">
    <div class="container settings-page">
        <!-- Alertas -->
        <?php foreach (['success' => 'success', 'error' => 'danger'] as $key => $type): ?>
            <?php if (!empty($_SESSION[$key])): ?>
                <div class="adm-alert adm-alert-<?= $type ?> mb-4 shadow-sm">
                    <i class="fas <?= $key === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle' ?>"></i>
                    <?= htmlspecialchars($_SESSION[$key]) ?>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION[$key]); ?>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- New Premium Header -->
        <div class="settings-header">
            <div class="d-flex align-items-center gap-4">
                <div class="bg-white text-primary rounded-circle d-flex align-items-center justify-content-center shadow-lg" style="width: 80px; height: 80px;">
                    <i class="fas fa-building fa-2x"></i>
                </div>
                <div>
                    <h1 class="mb-1 text-white fw-bold">Configuración Corporativa</h1>
                    <p class="mb-0 opacity-75">Define la identidad visual y datos fiscales de tu agencia</p>
                </div>
            </div>
            <a href="<?= APP_URL ?>/admin/dashboard" class="btn btn-light btn-sm rounded-pill mt-4 px-3 shadow-sm">
                <i class="fas fa-arrow-left me-2"></i>Volver al Dashboard
            </a>
        </div>

        <form action="<?= APP_URL ?>/admin/settings_update" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="row">
                <!-- Branding Card -->
                <div class="col-lg-12">
                    <div class="setting-card">
                        <div class="setting-card-header">
                            <i class="fas fa-palette"></i>
                            <h4 class="mb-0 fw-bold">Branding & Logo</h4>
                        </div>
                        <div class="setting-card-body">
                            <div class="logo-uploader">
                                <div class="logo-preview-wrapper shadow-sm" onclick="document.getElementById('company_logo').click()">
                                    <?php if (!empty($settings['company_logo'])): ?>
                                        <img src="<?= APP_URL ?>/assets/uploads/agency/<?= htmlspecialchars($settings['company_logo']) ?>" id="logo-preview">
                                    <?php else: ?>
                                        <div class="text-center text-muted" id="logo-placeholder">
                                            <i class="fas fa-cloud-upload-alt fa-3x mb-2"></i>
                                            <p class="small mb-0">Haz clic para subir logo</p>
                                        </div>
                                        <img id="logo-preview" style="display: none;">
                                    <?php endif; ?>
                                </div>
                                <input type="file" name="company_logo" id="company_logo" hidden accept="image/*" onchange="previewLogo(this)">
                                <label for="company_logo" class="btn btn-outline-primary rounded-pill px-4 btn-sm">
                                    <i class="fas fa-image me-2"></i>Seleccionar Archivo
                                </label>
                                <p class="text-muted mt-3 small">Formatos permitidos: PNG, JPG (Se recomienda fondo transparente para PNG)</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Identity Data -->
                <div class="col-lg-6">
                    <div class="setting-card">
                        <div class="setting-card-header">
                            <i class="fas fa-address-card"></i>
                            <h4 class="mb-0 fw-bold">Datos Legales</h4>
                        </div>
                        <div class="setting-card-body">
                            <div class="form-floating mb-4">
                                <input type="text" name="company_name" class="form-control" id="company_name" placeholder="Nombre de la empresa" value="<?= htmlspecialchars($settings['company_name'] ?? '') ?>" required>
                                <label for="company_name">Nombre de la Empresa</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="number" step="0.01" name="default_tax_rate" class="form-control" id="tax_rate" placeholder="Impuesto (%)" value="<?= htmlspecialchars($settings['default_tax_rate'] ?? '18.00') ?>" required>
                                <label for="tax_rate">Tasa de ITBIS / Impuesto (%)</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Info -->
                <div class="col-lg-6">
                    <div class="setting-card">
                        <div class="setting-card-header">
                            <i class="fas fa-phone-volume"></i>
                            <h4 class="mb-0 fw-bold">Información de Contacto</h4>
                        </div>
                        <div class="setting-card-body">
                            <div class="form-floating mb-4">
                                <input type="email" name="company_email" class="form-control" id="company_email" placeholder="Email corporativo" value="<?= htmlspecialchars($settings['company_email'] ?? '') ?>" required>
                                <label for="company_email">Correo Electrónico</label>
                            </div>
                            <div class="form-floating mb-4">
                                <input type="text" name="company_phone" class="form-control" id="company_phone" placeholder="Teléfono" value="<?= htmlspecialchars($settings['company_phone'] ?? '') ?>" required>
                                <label for="company_phone">Teléfono de Oficina</label>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Address -->
                <div class="col-12">
                    <div class="setting-card">
                        <div class="setting-card-header">
                            <i class="fas fa-map-marked-alt"></i>
                            <h4 class="mb-0 fw-bold">Ubicación Física</h4>
                        </div>
                        <div class="setting-card-body">
                            <div class="form-floating">
                                <textarea name="company_address" class="form-control" placeholder="Dirección" id="company_address" style="height: 120px" required><?= htmlspecialchars($settings['company_address'] ?? '') ?></textarea>
                                <label for="company_address">Dirección Completa</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-12 text-center mt-2 mb-5">
                    <button type="submit" class="btn btn-save-settings btn-lg">
                        <i class="fas fa-check-circle me-3"></i>GUARDAR TODA LA CONFIGURACIÓN
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
function previewLogo(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function(e) {
            const preview = document.getElementById('logo-preview');
            const placeholder = document.getElementById('logo-placeholder');
            preview.src = e.target.result;
            preview.style.display = 'block';
            if(placeholder) placeholder.style.display = 'none';
        }
        reader.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>
