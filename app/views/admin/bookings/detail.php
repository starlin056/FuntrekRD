<?php require APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require APP_ROOT . '/app/views/layouts/navigation.php'; ?>

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
        gap: 10px;
        background: linear-gradient(135deg, rgba(0, 119, 182, .05), rgba(0, 180, 216, .02));
    }

    .adm-card-header-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: .9rem;
        flex-shrink: 0;
    }

    .adm-card-header h5 {
        font-family: 'Sora', sans-serif;
        font-size: .95rem;
        font-weight: 700;
        color: var(--adm-dark);
        margin: 0;
    }

    .adm-card-body {
        padding: 20px 22px;
    }

    .adm-info-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 16px;
    }

    .adm-info-item {
        margin-bottom: 8px;
    }

    .adm-info-label {
        font-size: .7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .07em;
        color: var(--adm-muted);
        display: block;
        margin-bottom: 2px;
    }

    .adm-info-value {
        font-size: .9rem;
        color: var(--adm-dark);
        font-weight: 500;
    }

    .adm-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        font-family: 'Sora', sans-serif;
        font-size: .68rem;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 999px;
        white-space: nowrap;
    }

    .adm-badge-pending {
        background: rgba(249, 199, 79, .18);
        color: #b8860b;
    }

    .adm-badge-confirmed {
        background: rgba(46, 196, 182, .12);
        color: #1a9c8e;
    }

    .adm-badge-cancelled {
        background: rgba(231, 76, 60, .10);
        color: var(--adm-red);
    }

    .adm-badge-completed {
        background: rgba(0, 119, 182, .12);
        color: var(--adm-blue);
    }

    .adm-payment-pending {
        background: rgba(249, 199, 79, .18);
        color: #b8860b;
    }

    .adm-payment-paid {
        background: rgba(46, 196, 182, .12);
        color: #1a9c8e;
    }

    .adm-payment-refunded {
        background: rgba(231, 76, 60, .10);
        color: var(--adm-red);
    }

    .adm-select {
        width: 100%;
        font-size: .9rem;
        padding: 8px 12px;
        border: 1.5px solid rgba(0, 119, 182, .18);
        border-radius: 10px;
        background: var(--adm-foam);
        color: var(--adm-dark);
        outline: none;
        transition: border-color .2s, box-shadow .2s;
        font-family: inherit;
    }

    .adm-select:focus {
        border-color: var(--adm-blue);
        box-shadow: 0 0 0 3px rgba(0, 119, 182, .12);
        background: #fff;
    }

    .adm-note {
        background: rgba(0, 119, 182, .05);
        padding: 14px 18px;
        border-radius: 12px;
        border-left: 3px solid var(--adm-blue);
    }

    @media(max-width:768px) {
        .adm-info-grid {
            grid-template-columns: 1fr;
        }

        .adm-ph {
            flex-direction: column;
        }
    }
</style>

<div class="adm-page">
    <div class="container-fluid">
        <div class="adm-ph">
            <div>
                <h1><i class="fas fa-receipt me-2" style="color:var(--adm-teal)"></i>Detalle de Reserva</h1>
                <p>Información completa de la reserva #<?= htmlspecialchars($booking['booking_reference'] ?? '') ?></p>
            </div>
            <a href="<?= APP_URL ?>/admin/bookings" class="adm-back-btn"><i class="fas fa-arrow-left"></i>Volver a Reservas</a>
        </div>

        <?php if (!empty($booking)): ?>
            <div class="row g-4">
                <div class="col-lg-8">
                    <!-- Info reserva -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon" style="background:rgba(0,119,182,.10);color:var(--adm-blue)">
                                <i class="fas fa-info-circle"></i>
                            </div>
                            <h5>Información de la reserva</h5>
                        </div>
                        <div class="adm-card-body">
                            <div class="adm-info-grid">
                                <div>
                                    <div class="adm-info-item">
                                        <span class="adm-info-label">Referencia</span>
                                        <span class="adm-info-value"><?= htmlspecialchars($booking['booking_reference']) ?></span>
                                    </div>
                                    <div class="adm-info-item">
                                        <span class="adm-info-label">Cliente</span>
                                        <span class="adm-info-value"><?= htmlspecialchars($booking['customer_name']) ?></span>
                                    </div>
                                    <div class="adm-info-item">
                                        <span class="adm-info-label">Email</span>
                                        <span class="adm-info-value"><?= htmlspecialchars($booking['customer_email']) ?></span>
                                    </div>
                                    <div class="adm-info-item">
                                        <span class="adm-info-label">Teléfono</span>
                                        <span class="adm-info-value"><?= htmlspecialchars($booking['customer_phone'] ?? 'No proporcionado') ?></span>
                                    </div>
                                </div>
                                <div>
                                    <div class="adm-info-item">
                                        <span class="adm-info-label">Fecha de viaje</span>
                                        <span class="adm-info-value"><?= date('d/m/Y', strtotime($booking['travel_date'])) ?></span>
                                    </div>
                                    <div class="adm-info-item">
                                        <span class="adm-info-label">Adultos / Niños</span>
                                        <span class="adm-info-value"><?= (int)$booking['adults'] ?> / <?= (int)($booking['children'] ?? 0) ?></span>
                                    </div>
                                    <div class="adm-info-item">
                                        <span class="adm-info-label">Total</span>
                                        <span class="adm-info-value fw-bold">$<?= number_format((float)$booking['total_price'], 2) ?></span>
                                    </div>
                                    <div class="adm-info-item">
                                        <span class="adm-info-label">Método de pago</span>
                                        <span class="adm-info-value"><?= ucfirst($booking['payment_method'] ?? 'PayPal') ?></span>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($booking['special_requests'])): ?>
                                <div class="adm-note mt-3">
                                    <i class="fas fa-comment-dots me-2" style="color:var(--adm-blue)"></i>
                                    <strong>Solicitudes especiales:</strong>
                                    <p class="mb-0 mt-1"><?= htmlspecialchars($booking['special_requests']) ?></p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- Detalle del servicio -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon" style="background:rgba(46,196,182,.10);color:var(--adm-green)">
                                <i class="fas fa-concierge-bell"></i>
                            </div>
                            <h5>Servicio reservado</h5>
                        </div>
                        <div class="adm-card-body">
                            <div class="adm-info-grid">
                                <div>
                                    <div class="adm-info-item">
                                        <span class="adm-info-label">Tipo</span>
                                        <span class="adm-info-value"><?= ucfirst($booking['item_type']) ?></span>
                                    </div>
                                    <div class="adm-info-item">
                                        <span class="adm-info-label">ID del servicio</span>
                                        <span class="adm-info-value"><?= (int)$booking['item_id'] ?></span>
                                    </div>
                                </div>
                                <div>
                                    <?php
                                    $serviceName = '';
                                    if ($booking['item_type'] === 'package') {
                                        $serviceName = $booking['package_name'] ?? 'Paquete no encontrado';
                                    } elseif ($booking['item_type'] === 'excursion') {
                                        $serviceName = $booking['excursion_name'] ?? 'Excursión no encontrada';
                                    } else {
                                        $serviceName = $booking['transfer_name'] ?? 'Transfer no encontrado';
                                    }
                                    ?>
                                    <div class="adm-info-item">
                                        <span class="adm-info-label">Nombre</span>
                                        <span class="adm-info-value"><?= htmlspecialchars($serviceName) ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Panel lateral -->
                <div class="col-lg-4">
                    <!-- Gestión de estados -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon" style="background:rgba(249,199,79,.18);color:#b8860b">
                                <i class="fas fa-tasks"></i>
                            </div>
                            <h5>Gestión de estados</h5>
                        </div>
                        <div class="adm-card-body">
                            <form method="POST" action="<?= APP_URL ?>/admin/updateBookingStatus/<?= $booking['id'] ?>" id="statusForm">
                                <div class="mb-3">
                                    <label class="adm-info-label">Estado de la reserva</label>
                                    <select name="status" class="adm-select" onchange="this.form.submit()">
                                        <option value="pending" <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Pendiente</option>
                                        <option value="confirmed" <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>Confirmada</option>
                                        <option value="cancelled" <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>Cancelada</option>
                                        <option value="completed" <?= $booking['status'] === 'completed' ? 'selected' : '' ?>>Completada</option>
                                    </select>
                                </div>
                            </form>

                            <form method="POST" action="<?= APP_URL ?>/admin/updatePaymentStatus/<?= $booking['id'] ?>" id="paymentForm">
                                <div class="mb-3">
                                    <label class="adm-info-label">Estado de pago</label>
                                    <select name="payment_status" class="adm-select" onchange="this.form.submit()">
                                        <option value="pending" <?= $booking['payment_status'] === 'pending' ? 'selected' : '' ?>>Pendiente</option>
                                        <option value="paid" <?= $booking['payment_status'] === 'paid' ? 'selected' : '' ?>>Pagado</option>
                                        <option value="refunded" <?= $booking['payment_status'] === 'refunded' ? 'selected' : '' ?>>Reembolsado</option>
                                    </select>
                                </div>
                            </form>

                            <div class="mt-3">
                                <span class="adm-info-label">Estado actual</span>
                                <div class="mt-1">
                                    <?php
                                    $statusClass = [
                                        'pending' => 'adm-badge-pending',
                                        'confirmed' => 'adm-badge-confirmed',
                                        'cancelled' => 'adm-badge-cancelled',
                                        'completed' => 'adm-badge-completed',
                                    ][$booking['status']] ?? 'adm-badge-pending';
                                    $paymentClass = [
                                        'pending' => 'adm-payment-pending',
                                        'paid' => 'adm-payment-paid',
                                        'refunded' => 'adm-payment-refunded',
                                    ][$booking['payment_status']] ?? 'adm-payment-pending';
                                    ?>
                                    <span class="adm-badge <?= $statusClass ?> me-2"><?= __('status.' . $booking['status']) ?></span>
                                    <span class="adm-badge <?= $paymentClass ?>"><?= __('payment.' . $booking['payment_status']) ?></span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Historial -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon" style="background:rgba(0,180,216,.10);color:var(--adm-teal)">
                                <i class="fas fa-history"></i>
                            </div>
                            <h5>Historial</h5>
                        </div>
                        <div class="adm-card-body">
                            <div class="adm-info-item">
                                <span class="adm-info-label">Creada</span>
                                <span class="adm-info-value"><?= date('d/m/Y H:i', strtotime($booking['created_at'])) ?></span>
                            </div>
                            <div class="adm-info-item mt-2">
                                <span class="adm-info-label">Última actualización</span>
                                <span class="adm-info-value"><?= date('d/m/Y H:i', strtotime($booking['updated_at'])) ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="adm-card">
                <div class="adm-card-body text-center py-5">
                    <i class="fas fa-exclamation-triangle fa-2x mb-3" style="color:var(--adm-red)"></i>
                    <h5>Reserva no encontrada</h5>
                    <p class="text-muted">No se pudo encontrar la reserva solicitada.</p>
                    <a href="<?= APP_URL ?>/admin/bookings" class="adm-back-btn mt-2">Volver a Reservas</a>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>