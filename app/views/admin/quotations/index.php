<?php $customCss = ['modules/admin-dashboard.css']; ?>
<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<style>
    /* ===== VARIABLES Y ESTILOS BASE (consistentes con diseño anterior) ===== */
    :root {
        --adm-primary: #0a58ca;
        --adm-primary-light: #e6f0ff;
        --adm-secondary: #6c757d;
        --adm-success: #198754;
        --adm-danger: #dc3545;
        --adm-warning: #ffc107;
        --adm-info: #0dcaf0;
        --adm-light: #f8f9fa;
        --adm-dark: #212529;
        --adm-border-radius: 1rem;
        --adm-box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
        --adm-transition: all 0.25s ease-in-out;
    }

    .adm-page {
        padding-bottom: 4rem;
    }

    /* ===== BOTONES ===== */
    .btn-primary-custom {
        border-radius: 2.5rem;
        padding: 0.7rem 1.8rem;
        font-weight: 600;
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.15);
        transition: var(--adm-transition);
    }

    .btn-primary-custom:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 18px rgba(13, 110, 253, 0.25);
    }

    .btn-outline-action {
        border-radius: 2rem;
        padding: 0.4rem 1rem;
        font-size: 0.85rem;
        font-weight: 500;
        transition: var(--adm-transition);
    }

    .btn-outline-action i {
        margin-right: 0.3rem;
    }

    /* ===== TARJETA PRINCIPAL ===== */
    .list-card {
        border: none;
        border-radius: var(--adm-border-radius);
        background: #ffffff;
        box-shadow: var(--adm-box-shadow);
        overflow: hidden;
    }

    /* ===== TABLA MODERNA ===== */
    .modern-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 0.5rem;
        margin-bottom: 0;
    }

    .modern-table thead th {
        background-color: transparent;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #5b6c7e;
        padding: 0.75rem 1.25rem;
        border-bottom: 2px solid #eef2f6;
    }

    .modern-table tbody tr {
        background-color: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: var(--adm-transition);
    }

    .modern-table tbody tr:hover {
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.06);
        background-color: #fdfdfe;
        transform: scale(1.002);
    }

    .modern-table td {
        padding: 1.2rem 1.25rem;
        vertical-align: middle;
        border: none;
    }

    /* Estados de badge */
    .status-badge {
        font-weight: 600;
        font-size: 0.7rem;
        padding: 0.35rem 1rem;
        border-radius: 2rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        display: inline-block;
    }

    .status-draft {
        background-color: #e9ecef;
        color: #495057;
    }

    .status-sent {
        background-color: #cff4fc;
        color: #055160;
    }

    .status-confirmed {
        background-color: #d1e7dd;
        color: #0a3622;
    }

    .status-expired {
        background-color: #f8d7da;
        color: #58151c;
    }

    /* Acciones con iconos */
    .action-group {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .action-btn {
        width: 36px;
        height: 36px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: white;
        border: 1px solid #dee2e6;
        color: var(--adm-dark);
        transition: var(--adm-transition);
        text-decoration: none;
    }

    .action-btn:hover {
        background: var(--adm-primary);
        border-color: var(--adm-primary);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(13, 110, 253, 0.2);
    }

    .action-btn-danger:hover {
        background: var(--adm-danger);
        border-color: var(--adm-danger);
    }

    .action-btn-warning:hover {
        background: var(--adm-warning);
        border-color: var(--adm-warning);
        color: #000;
    }

    /* Estado vacío */
    .empty-state {
        padding: 4rem 2rem;
        text-align: center;
    }

    .empty-state-icon {
        font-size: 4rem;
        color: #cbd5e1;
        margin-bottom: 1.5rem;
    }
</style>

<div class="adm-page">
    <div class="container-xl py-4">
        <!-- Alertas (mejoradas) -->
        <?php foreach (['success' => 'success', 'error' => 'danger'] as $key => $type): ?>
            <?php if (!empty($_SESSION[$key])): ?>
                <div class="alert alert-<?= $type ?> d-flex align-items-center border-0 shadow-sm rounded-3 mb-4" role="alert">
                    <i class="fas <?= $key === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle' ?> me-3 fs-5"></i>
                    <div><?= htmlspecialchars($_SESSION[$key]) ?></div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION[$key]); ?>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Header profesional -->
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
            <div>
                <div class="mb-3">
                    <a href="<?= APP_URL ?>/admin/dashboard" class="adm-back-btn"
                        style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.2rem; background: white; border: 1.5px solid #e9ecef; border-radius: 2.5rem; color: var(--adm-dark); font-weight: 600; text-decoration: none; transition: var(--adm-transition);">
                        <i class="fas fa-arrow-left"></i> Volver al Panel
                    </a>
                </div>
                <h1 class="display-6 fw-bold mb-1">
                    <i class="fas fa-file-invoice-dollar me-2" style="color: var(--adm-primary)"></i>Gestión de
                    Cotizaciones
                </h1>
                <p class="text-secondary-emphasis">Crea y administra presupuestos para tus clientes</p>
            </div>
            <div class="mt-3 mt-sm-0">
                <a href="<?= APP_URL ?>/admin/quotations_create" class="btn btn-primary btn-primary-custom">
                    <i class="fas fa-plus-circle me-2"></i>Nueva Cotización
                </a>
            </div>
        </div>

        <!-- Listado de cotizaciones -->
        <div class="list-card">
            <div class="p-3">
                <?php if (!empty($quotations)): ?>
                    <div class="table-responsive">
                        <table class="modern-table">
                            <thead>
                                <tr>
                                    <th>Cotización</th>
                                    <th>Cliente</th>
                                    <th>Fecha Viaje</th>
                                    <th>Total</th>
                                    <th>Estado</th>
                                    <th class="text-end">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($quotations as $quote): ?>
                                    <?php
                                    $statusClass = [
                                        'draft' => 'status-draft',
                                        'sent' => 'status-sent',
                                        'confirmed' => 'status-confirmed',
                                        'expired' => 'status-expired'
                                    ][$quote['status']] ?? 'status-draft';
                                    ?>
                                    <tr>
                                        <td>
                                            <span
                                                class="fw-bold text-primary">#<?= htmlspecialchars($quote['quote_number']) ?></span>
                                            <div class="text-muted small mt-1">
                                                <i class="far fa-calendar-alt me-1"></i>
                                                <?= date('d/m/Y', strtotime($quote['created_at'])) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="fw-semibold"><?= htmlspecialchars($quote['customer_name']) ?></div>
                                            <div class="text-muted small"><?= htmlspecialchars($quote['customer_email']) ?>
                                            </div>
                                        </td>
                                        <td>
                                            <?php if (!empty($quote['travel_date'])): ?>
                                                <span class="d-flex align-items-center">
                                                    <i class="fas fa-plane-departure me-2 text-secondary"></i>
                                                    <?= date('d/m/Y', strtotime($quote['travel_date'])) ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">—</span>
                                            <?php endif; ?>
                                        </td>
                                        <td class="fw-bold text-dark">
                                            $<?= number_format($quote['total_price'], 2) ?>
                                        </td>
                                        <td>
                                            <span class="status-badge <?= $statusClass ?>">
                                                <?= ucfirst($quote['status']) ?>
                                            </span>
                                        </td>
                                        <td>
                                            <div class="action-group">
                                                <a href="<?= APP_URL ?>/admin/quotations_print/<?= $quote['id'] ?>"
                                                    target="_blank" class="action-btn" title="Imprimir Cotización">
                                                    <i class="fas fa-print"></i>
                                                </a>
                                                <a href="<?= APP_URL ?>/admin/airport_sign/<?= $quote['id'] ?>" target="_blank"
                                                    class="action-btn action-btn-warning" title="Letrero Aeropuerto">
                                                    <i class="fas fa-fighter-jet"></i>
                                                </a>
                                                <a href="<?= APP_URL ?>/admin/quotations_delete/<?= $quote['id'] ?>"
                                                    class="action-btn action-btn-danger"
                                                    onclick="return confirm('¿Estás seguro de eliminar esta cotización?')"
                                                    title="Eliminar">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <i class="fas fa-file-invoice"></i>
                        </div>
                        <h4 class="fw-bold text-dark">No hay cotizaciones aún</h4>
                        <p class="text-secondary mb-4">Comienza creando tu primer presupuesto profesional.</p>
                        <a href="<?= APP_URL ?>/admin/quotations_create" class="btn btn-primary btn-primary-custom">
                            <i class="fas fa-plus-circle me-2"></i>Crear Primera Cotización
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>