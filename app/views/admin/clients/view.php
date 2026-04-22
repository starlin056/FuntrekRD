<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<style>
    :root {
        --adm-blue: #0077B6;
        --adm-teal: #00B4D8;
        --adm-green: #2EC4B6;
        --adm-sand: #F9C74F;
        --adm-red: #e74c3c;
        --adm-dark: #0D1B2A;
        --adm-muted: #6E8FA5;
        --adm-foam: #EAF6FF;
        --ease: cubic-bezier(.22, 1, .36, 1);
    }

    .adm-page {
        padding: 28px 0 60px;
        min-height: 100vh;
        background: #F0F7FC;
    }

    .adm-ph {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 28px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .adm-ph h1 {
        font-family: 'Sora', sans-serif;
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--adm-dark);
        margin: 0 0 6px;
    }

    .adm-ph p {
        font-size: .87rem;
        color: var(--adm-muted);
        margin: 0;
    }

    .adm-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-family: 'Sora', sans-serif;
        font-size: .77rem;
        font-weight: 700;
        color: var(--adm-blue);
        border: 1.5px solid var(--adm-blue);
        background: transparent;
        padding: 7px 14px;
        border-radius: 999px;
        text-decoration: none;
        transition: all .22s var(--ease);
    }

    .adm-back-btn:hover {
        background: var(--adm-blue);
        color: #fff;
    }

    .adm-card {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 6px 28px rgba(0, 45, 79, .09);
        border: 1px solid rgba(0, 119, 182, .08);
        overflow: hidden;
        margin-bottom: 20px;
    }

    .adm-card-header {
        padding: 18px 22px;
        border-bottom: 1px solid rgba(0, 119, 182, .08);
        display: flex;
        align-items: center;
        gap: 12px;
        background: linear-gradient(90deg, rgba(0, 119, 182, .04), rgba(0, 180, 216, .02));
    }

    .adm-card-header-icon {
        width: 44px;
        height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        flex-shrink: 0;
        background: rgba(0, 119, 182, .10);
        color: var(--adm-blue);
    }

    .adm-card-header h5 {
        font-family: 'Sora', sans-serif;
        font-size: 1rem;
        font-weight: 700;
        color: var(--adm-dark);
        margin: 0;
    }

    .adm-card-body {
        padding: 24px 28px;
    }

    .adm-info-row {
        display: flex;
        padding: 12px 0;
        border-bottom: 1px solid rgba(0, 119, 182, .06);
    }

    .adm-info-label {
        width: 140px;
        font-family: 'Sora', sans-serif;
        font-size: .78rem;
        font-weight: 700;
        color: var(--adm-muted);
        text-transform: uppercase;
        letter-spacing: .06em;
        flex-shrink: 0;
    }

    .adm-info-value {
        font-size: .95rem;
        color: var(--adm-dark);
        font-weight: 500;
    }

    .adm-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-family: 'Sora', sans-serif;
        font-size: .72rem;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 999px;
    }

    .adm-badge-active {
        background: rgba(46, 196, 182, .12);
        color: #1a9c8e;
    }

    .adm-badge-inactive {
        background: rgba(110, 143, 165, .10);
        color: var(--adm-muted);
    }

    .adm-badge-admin {
        background: rgba(249, 199, 79, .18);
        color: #b8860b;
    }

    .adm-badge-client {
        background: rgba(0, 119, 182, .10);
        color: var(--adm-blue);
    }

    .adm-avatar-large {
        width: 80px;
        height: 80px;
        border-radius: 24px;
        background: linear-gradient(135deg, var(--adm-blue), var(--adm-teal));
        color: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        margin-bottom: 16px;
    }

    @media(max-width: 768px) {
        .adm-info-row {
            flex-direction: column;
            gap: 6px;
        }

        .adm-info-label {
            width: auto;
        }

        .adm-card-body {
            padding: 20px;
        }
    }
</style>

<div class="adm-page">
    <div class="container-fluid">

        <div class="adm-ph">
            <div>
                <h1><i class="fas fa-user-circle me-2" style="color:var(--adm-teal)"></i>Perfil de Cliente</h1>
                <p>Información detallada del usuario registrado</p>
            </div>
            <a href="<?= APP_URL ?>/admin/clients" class="adm-back-btn"><i class="fas fa-arrow-left"></i>Volver a clientes</a>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="adm-card">
                    <div class="adm-card-header">
                        <div class="adm-card-header-icon"><i class="fas fa-user"></i></div>
                        <h5>Información del Cliente</h5>
                    </div>
                    <div class="adm-card-body">
                        <div class="adm-info-row">
                            <div class="adm-info-label">Nombre completo</div>
                            <div class="adm-info-value"><?= htmlspecialchars($client['full_name'] ?? 'N/A') ?></div>
                        </div>
                        <div class="adm-info-row">
                            <div class="adm-info-label">Usuario</div>
                            <div class="adm-info-value"><?= htmlspecialchars($client['username']) ?></div>
                        </div>
                        <div class="adm-info-row">
                            <div class="adm-info-label">Email</div>
                            <div class="adm-info-value"><?= htmlspecialchars($client['email']) ?></div>
                        </div>
                        <div class="adm-info-row">
                            <div class="adm-info-label">Rol</div>
                            <div class="adm-info-value">
                                <span class="adm-badge <?= ($client['role'] ?? 'client') === 'admin' ? 'adm-badge-admin' : 'adm-badge-client' ?>">
                                    <i class="fas <?= ($client['role'] ?? 'client') === 'admin' ? 'fa-user-shield' : 'fa-user' ?>"></i>
                                    <?= ($client['role'] ?? 'client') === 'admin' ? 'Administrador' : 'Cliente' ?>
                                </span>
                            </div>
                        </div>
                        <div class="adm-info-row">
                            <div class="adm-info-label">Estado</div>
                            <div class="adm-info-value">
                                <span class="adm-badge <?= !empty($client['active']) ? 'adm-badge-active' : 'adm-badge-inactive' ?>">
                                    <i class="fas <?= !empty($client['active']) ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                                    <?= !empty($client['active']) ? 'Activo' : 'Inactivo' ?>
                                </span>
                            </div>
                        </div>
                        <div class="adm-info-row">
                            <div class="adm-info-label">Registrado</div>
                            <div class="adm-info-value"><?= date('d/m/Y H:i', strtotime($client['created_at'])) ?></div>
                        </div>
                        <div class="adm-info-row">
                            <div class="adm-info-label">Último login</div>
                            <div class="adm-info-value">
                                <?= $client['last_login'] ? date('d/m/Y H:i', strtotime($client['last_login'])) : 'Nunca' ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="adm-card text-center">
                    <div class="adm-card-body">
                        <div class="adm-avatar-large mx-auto">
                            <i class="fas fa-user"></i>
                        </div>
                        <h5 class="mt-2 mb-1"><?= htmlspecialchars($client['full_name'] ?? $client['username']) ?></h5>
                        <p class="text-muted small"><?= htmlspecialchars($client['email']) ?></p>
                        <div class="d-flex justify-content-center gap-2 mt-3">
                            <a href="<?= APP_URL ?>/admin/clients" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-arrow-left me-1"></i>Volver
                            </a>
                            <?php if (!empty($client['active'])): ?>
                                <a href="<?= APP_URL ?>/admin/client_deactivate/<?= $client['id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('¿Desactivar este cliente?')">
                                    <i class="fas fa-ban me-1"></i>Desactivar
                                </a>
                            <?php else: ?>
                                <a href="<?= APP_URL ?>/admin/client_activate/<?= $client['id'] ?>" class="btn btn-outline-success btn-sm" onclick="return confirm('¿Reactivar este cliente?')">
                                    <i class="fas fa-check-circle me-1"></i>Reactivar
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>