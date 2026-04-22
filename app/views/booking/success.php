<?php
// app/views/booking/success.php
require_once APP_ROOT . '/app/views/layouts/header.php';
require_once APP_ROOT . '/app/views/layouts/navigation.php';

/*
 * FUENTE DE VERDAD: $booking['payment_method'] (guardado en BD al crear la reserva)
 * $payment_method viene del controller como respaldo
 * NO leer el método del query string — se puede manipular
 */
$methodFromDb = $booking['payment_method'] ?? 'paypal';
$isCash = ($methodFromDb === 'cash');

$ref = htmlspecialchars($booking['booking_reference'] ?? '—');
$custName = htmlspecialchars($booking['customer_name'] ?? '');
$email = htmlspecialchars($booking['customer_email'] ?? '');
$price = number_format((float) ($booking['total_price'] ?? 0), 2);
$adults = (int) ($booking['adults'] ?? 1);
$children = (int) ($booking['children'] ?? 0);

$travelDate = !empty($booking['travel_date'])
    ? date('d/m/Y', strtotime($booking['travel_date']))
    : '—';

// Nombre del servicio reservado
$serviceName = '';
if (!empty($booking['package_name']))
    $serviceName = $booking['package_name'];
elseif (!empty($booking['excursion_name']))
    $serviceName = $booking['excursion_name'];
elseif (!empty($booking['transfer_name']))
    $serviceName = $booking['transfer_name'];

// Estado de la reserva siempre pending al crear (el admin confirma)
$bookingStatus = $booking['status'] ?? 'pending';
$paymentStatus = $booking['payment_status'] ?? 'pending';

$paxStr = $adults . ' adulto' . ($adults > 1 ? 's' : '');
if ($children > 0)
    $paxStr .= ' + ' . $children . ' niño' . ($children > 1 ? 's' : '');
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=DM+Sans:wght@300;400;500&display=swap');

    :root {
        --c-ocean: #0077B6;
        --c-light: #00B4D8;
        --c-foam: #EAF6FF;
        --c-green: #16a34a;
        --c-paypal: #003087;
        --c-dark: #0D1B2A;
        --c-muted: #6E8FA5;
        --ease: cubic-bezier(.22, 1, .36, 1);
        --fh: 'Sora', sans-serif;
        --fb: 'DM Sans', sans-serif;
    }

    body {
        font-family: var(--fb);
        background: #F0F7FC;
    }

    .sc-wrap {
        padding: 80px 0 120px;
        min-height: 65vh;
        display: flex;
        align-items: center;
    }

    .sc-card {
        background: #fff;
        border-radius: 26px;
        box-shadow: 0 16px 60px rgba(0, 45, 79, .12);
        border: 1px solid rgba(0, 119, 182, .08);
        padding: 52px 48px;
        max-width: 580px;
        margin: 0 auto;
        text-align: center;
    }

    @media(max-width:560px) {
        .sc-card {
            padding: 32px 20px;
        }
    }

    /* Icono animado */
    .sc-icon {
        width: 86px;
        height: 86px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 22px;
        font-size: 2.2rem;
        animation: scPop .55s var(--ease) both;
    }

    @keyframes scPop {
        from {
            opacity: 0;
            transform: scale(.3)
        }

        to {
            opacity: 1;
            transform: scale(1)
        }
    }

    .sc-icon-cash {
        background: linear-gradient(135deg, #16a34a, #22c55e);
        color: #fff;
        box-shadow: 0 10px 28px rgba(22, 163, 74, .28);
    }

    .sc-icon-paypal {
        background: linear-gradient(135deg, #003087, #009cde);
        color: #fff;
        box-shadow: 0 10px 28px rgba(0, 48, 135, .28);
    }

    .sc-title {
        font-family: var(--fh);
        font-size: 1.6rem;
        font-weight: 800;
        color: var(--c-dark);
        margin-bottom: 10px;
    }

    .sc-sub {
        font-size: .92rem;
        color: var(--c-muted);
        line-height: 1.65;
        max-width: 400px;
        margin: 0 auto 28px;
    }

    /* Referencia */
    .sc-ref {
        display: inline-flex;
        align-items: center;
        gap: 10px;
        background: var(--c-foam);
        border: 1.5px dashed rgba(0, 119, 182, .30);
        border-radius: 13px;
        padding: 11px 22px;
        margin-bottom: 28px;
    }

    .sc-ref-lbl {
        font-family: var(--fh);
        font-size: .68rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: .10em;
        color: var(--c-muted);
    }

    .sc-ref-val {
        font-family: var(--fh);
        font-size: 1rem;
        font-weight: 800;
        color: var(--c-ocean);
    }

    /* Tabla detalles */
    .sc-details {
        background: var(--c-foam);
        border-radius: 14px;
        padding: 18px 22px;
        margin-bottom: 24px;
        text-align: left;
    }

    .sc-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        font-size: .86rem;
        padding: 7px 0;
        border-bottom: 1px solid rgba(0, 119, 182, .08);
    }

    .sc-row:last-child {
        border-bottom: none;
    }

    .sc-dl {
        color: var(--c-muted);
        font-weight: 500;
    }

    .sc-dv {
        font-family: var(--fh);
        font-weight: 700;
        color: var(--c-dark);
    }

    /* Badges */
    .sc-badge-pending {
        background: rgba(234, 179, 8, .15);
        border: 1px solid rgba(234, 179, 8, .40);
        color: #854d0e;
    }

    .sc-badge-paid {
        background: rgba(22, 163, 74, .12);
        border: 1px solid rgba(22, 163, 74, .35);
        color: #14532d;
    }

    .sc-badge-cash {
        background: rgba(22, 163, 74, .12);
        border: 1px solid rgba(22, 163, 74, .35);
        color: #14532d;
    }

    .sc-badge-paypal {
        background: rgba(0, 48, 135, .10);
        border: 1px solid rgba(0, 48, 135, .30);
        color: var(--c-paypal);
    }

    .sc-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        border-radius: 999px;
        font-family: var(--fh);
        font-size: .72rem;
        font-weight: 700;
        padding: 4px 12px;
    }

    /* Aviso contextual */
    .sc-notice {
        border-radius: 13px;
        padding: 14px 18px;
        margin-bottom: 24px;
        font-size: .83rem;
        line-height: 1.6;
        text-align: left;
        display: flex;
        align-items: flex-start;
        gap: 10px;
    }

    .sc-notice i {
        flex-shrink: 0;
        margin-top: 2px;
        font-size: 1rem;
    }

    .sc-notice strong {
        display: block;
        margin-bottom: 3px;
        font-family: var(--fh);
    }

    .sc-notice-cash {
        background: rgba(22, 163, 74, .08);
        border: 1px solid rgba(22, 163, 74, .25);
        color: #14532d;
    }

    .sc-notice-paypal {
        background: rgba(0, 48, 135, .07);
        border: 1px solid rgba(0, 48, 135, .18);
        color: var(--c-paypal);
    }

    /* Botones */
    .sc-actions {
        display: flex;
        gap: 12px;
        justify-content: center;
        flex-wrap: wrap;
    }

    .sc-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-family: var(--fh);
        font-weight: 700;
        font-size: .86rem;
        padding: 11px 24px;
        border-radius: 999px;
        text-decoration: none;
        border: none;
        cursor: pointer;
        transition: all .25s var(--ease);
    }

    .sc-btn-primary {
        background: linear-gradient(135deg, var(--c-ocean), var(--c-light));
        color: #fff;
        box-shadow: 0 6px 18px rgba(0, 119, 182, .26);
    }

    .sc-btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 12px 26px rgba(0, 119, 182, .34);
        color: #fff;
    }

    .sc-btn-outline {
        background: transparent;
        color: var(--c-ocean);
        border: 1.5px solid var(--c-ocean);
    }

    .sc-btn-outline:hover {
        background: var(--c-ocean);
        color: #fff;
        transform: translateY(-2px);
    }

    /* Confetti */
    .sc-confetti {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        pointer-events: none;
        z-index: 0;
        overflow: hidden;
    }

    .sc-dot {
        position: absolute;
        border-radius: 2px;
        animation: dotFall linear forwards;
    }

    @keyframes dotFall {
        from {
            opacity: 1;
            transform: translateY(-30px) rotate(0deg)
        }

        to {
            opacity: 0;
            transform: translateY(110vh) rotate(720deg)
        }
    }
</style>

<div class="sc-confetti" id="scConfetti" aria-hidden="true"></div>

<div class="sc-wrap" style="position:relative;z-index:1;">
    <div class="container">
        <div class="sc-card">

            <!-- Icono -->
            <div class="sc-icon <?= $isCash ? 'sc-icon-cash' : 'sc-icon-paypal' ?>">
                <i class="fas <?= $isCash ? 'fa-money-bill-wave' : 'fa-circle-check' ?>"></i>
            </div>

            <h1 class="sc-title">
                <?= $isCash ? '¡Reserva registrada!' : '¡Pago recibido!' ?>
            </h1>
            <p class="sc-sub">
                <?php if ($isCash): ?>
                    Tu reserva está <strong>pendiente de confirmación</strong>.
                    Un agente la revisará y te contactará a <strong><?= $email ?></strong> para coordinar el servicio.
                <?php else: ?>
                    Tu pago fue procesado. La reserva está <strong>pendiente de confirmación</strong>
                    por nuestro equipo. Recibirás un correo a <strong><?= $email ?></strong>.
                <?php endif; ?>
            </p>

            <!-- Referencia -->
            <div class="sc-ref">
                <div>
                    <div class="sc-ref-lbl">Número de reserva</div>
                    <div class="sc-ref-val"><?= $ref ?></div>
                </div>
            </div>

            <!-- Detalles -->
            <div class="sc-details">
                <div class="sc-row">
                    <span class="sc-dl">Cliente</span>
                    <span class="sc-dv"><?= $custName ?></span>
                </div>
                <?php if (!empty($serviceName)): ?>
                    <div class="sc-row">
                        <span class="sc-dl">Servicio</span>
                        <span class="sc-dv"><?= htmlspecialchars($serviceName) ?></span>
                    </div>
                <?php endif; ?>
                <div class="sc-row">
                    <span class="sc-dl">Fecha del viaje</span>
                    <span class="sc-dv"><?= $travelDate ?></span>
                </div>
                <div class="sc-row">
                    <span class="sc-dl">Pasajeros</span>
                    <span class="sc-dv"><?= $paxStr ?></span>
                </div>
                <div class="sc-row">
                    <span class="sc-dl">Total</span>
                    <span class="sc-dv" style="color:var(--c-ocean)">$<?= $price ?> USD</span>
                </div>
                <div class="sc-row">
                    <span class="sc-dl">Método de pago</span>
                    <span class="sc-dv">
                        <?php if ($isCash): ?>
                            <span class="sc-badge sc-badge-cash">
                                <i class="fas fa-money-bill-wave"></i> Efectivo
                            </span>
                        <?php else: ?>
                            <span class="sc-badge sc-badge-paypal">
                                <i class="fab fa-paypal"></i> PayPal
                            </span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="sc-row">
                    <span class="sc-dl">Estado de pago</span>
                    <span class="sc-dv">
                        <?php if ($paymentStatus === 'paid'): ?>
                            <span class="sc-badge sc-badge-paid"><i class="fas fa-check-circle"></i> Pagado</span>
                        <?php else: ?>
                            <span class="sc-badge sc-badge-pending"><i class="fas fa-clock"></i>
                                <?= $isCash ? 'Pendiente (efectivo)' : 'Pendiente de verificación' ?>
                            </span>
                        <?php endif; ?>
                    </span>
                </div>
                <div class="sc-row">
                    <span class="sc-dl">Estado de reserva</span>
                    <span class="sc-dv">
                        <span class="sc-badge sc-badge-pending">
                            <i class="fas fa-hourglass-half"></i> Pendiente de confirmación
                        </span>
                    </span>
                </div>
            </div>

            <!-- Aviso específico por método -->
            <?php if ($isCash): ?>
                <div class="sc-notice sc-notice-cash">
                    <i class="fas fa-info-circle"></i>
                    <span>
                        <strong>¿Qué sigue?</strong>
                        Un agente revisará tu solicitud y te enviará un correo de confirmación.
                        El pago en efectivo se realiza al conductor o representante el día del servicio.
                        Guarda tu referencia <strong><?= $ref ?></strong> para identificarte.
                    </span>
                </div>
            <?php else: ?>
                <div class="sc-notice sc-notice-paypal">
                    <i class="fab fa-paypal"></i>
                    <span>
                        <strong>Pago recibido vía PayPal</strong>
                        Hemos registrado tu pago. Un agente confirmará tu reserva en breve.
                        Guarda tu referencia <strong><?= $ref ?></strong> para cualquier consulta.
                    </span>
                </div>
            <?php endif; ?>

            <!-- Acciones -->
            <div class="sc-actions">
                <a href="<?= APP_URL ?>/dashboard/bookings" class="sc-btn sc-btn-primary">
                    <i class="fas fa-list-check"></i> Ver mis reservas
                </a>
                <a href="<?= APP_URL ?>/" class="sc-btn sc-btn-outline">
                    <i class="fas fa-home"></i> Inicio
                </a>
            </div>

        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>

<script>
    /* Confetti — 100% JS, sin PHP dentro de bloques JS */
    (function () {
        var container = document.getElementById('scConfetti');
        if (!container) return;
        var colors = ['#F9C74F', '#00B4D8', '#2EC4B6', '#0077B6', '#fff', '#22c55e'];
        var count = 55;
        for (var i = 0; i < count; i++) {
            var dot = document.createElement('div');
            dot.className = 'sc-dot';
            var size = Math.random() * 9 + 5;
            var dur = (Math.random() * 2.5 + 1.5).toFixed(2);
            var del = (Math.random() * 1.2).toFixed(2);
            var left = (Math.random() * 100).toFixed(1);
            var col = colors[Math.floor(Math.random() * colors.length)];
            dot.style.cssText =
                'width:' + size + 'px;' +
                'height:' + size + 'px;' +
                'left:' + left + '%;' +
                'background:' + col + ';' +
                'animation-duration:' + dur + 's;' +
                'animation-delay:' + del + 's;';
            container.appendChild(dot);
        }
        setTimeout(function () {
            if (container.parentNode) container.parentNode.removeChild(container);
        }, 5000);
    }());
</script>