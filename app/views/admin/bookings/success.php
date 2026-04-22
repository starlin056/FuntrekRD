<?php require APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<div class="container py-5">
    <div class="text-center">
        <div class="mb-4">
            <i class="fas fa-check-circle text-success" style="font-size: 5rem;"></i>
        </div>
        <h1 class="display-4">¡Reserva Confirmada!</h1>
        <p class="lead">Gracias por su compra. Hemos enviado los detalles a su correo electrónico.</p>
        
        <div class="card mx-auto mt-4" style="max-width: 500px;">
            <div class="card-body">
                <h5>Detalles de la Reserva</h5>
                <p><strong>Referencia:</strong> <?= htmlspecialchars($booking['booking_reference']) ?></p>
                <p><strong>Servicio:</strong> <?= htmlspecialchars($booking['item_type']) ?> #<?= (int)$booking['item_id'] ?></p>
                <p><strong>Fecha de viaje:</strong> <?= htmlspecialchars($booking['travel_date']) ?></p>
                <p><strong>Total pagado:</strong> $<?= number_format((float)$booking['total_price'], 2) ?></p>
            </div>
        </div>

        <div class="mt-4">
            <a href="<?= APP_URL ?>/dashboard" class="btn btn-primary me-2">Mi Dashboard</a>
            <a href="<?= APP_URL ?>/" class="btn btn-outline-secondary">Volver al inicio</a>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>