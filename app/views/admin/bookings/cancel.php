<?php require APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<div class="container py-5">
    <div class="text-center">
        <div class="mb-4">
            <i class="fas fa-times-circle text-danger" style="font-size: 5rem;"></i>
        </div>
        <h1 class="display-4">Reserva Cancelada</h1>
        <p class="lead">Su reserva ha sido cancelada. Puede intentarlo nuevamente cuando lo desee.</p>
        
        <div class="mt-4">
            <a href="<?= APP_URL ?>/reserva/create/<?= htmlspecialchars($_GET['type'] ?? 'package') ?>/<?= (int)($_GET['id'] ?? 1) ?>" class="btn btn-primary me-2">Reintentar</a>
            <a href="<?= APP_URL ?>/" class="btn btn-outline-secondary">Volver al inicio</a>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>