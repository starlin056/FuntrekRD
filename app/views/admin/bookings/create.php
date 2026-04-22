<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <h1 class="mb-4">Reservar: <?= htmlspecialchars($item['name']) ?></h1>

            <?php if (!empty($_SESSION['error'])): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <form method="POST" action="<?= APP_URL ?>/reserva/process">
                <!-- Datos del servicio -->
                <input type="hidden" name="item_type" value="<?= htmlspecialchars($type) ?>">
                <input type="hidden" name="item_id" value="<?= (int) $item['id'] ?>">
                <input type="hidden" name="total_price"
                    value="<?= number_format((float) ($item['price'] ?? $item['discount_price'] ?? 0), 2, '.', '') ?>">

                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Detalles del Servicio</h5>
                        <p><strong><?= htmlspecialchars($item['name']) ?></strong></p>
                        <p>Precio: $<?= number_format((float) ($item['price'] ?? 0), 2) ?></p>
                        <?php if (!empty($item['duration'])): ?>
                            <p>Duración: <?= htmlspecialchars($item['duration']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Datos personales -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Datos Personales</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="name" class="form-label">Nombre Completo *</label>
                                <input type="text" class="form-control" id="name" name="name"
                                    value="<?= htmlspecialchars($userData['name'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email *</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?= htmlspecialchars($userData['email'] ?? '') ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="phone" class="form-label">Teléfono</label>
                                <input type="text" class="form-control" id="phone" name="phone"
                                    value="<?= htmlspecialchars($userData['phone'] ?? '') ?>">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="travel_date" class="form-label">Fecha de Viaje *</label>
                                <input type="date" class="form-control" id="travel_date" name="travel_date" required>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Detalles de la reserva -->
                <div class="card mb-4">
                    <div class="card-body">
                        <h5 class="card-title">Detalles de la Reserva</h5>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="adults" class="form-label">Adultos</label>
                                <select class="form-select" id="adults" name="adults">
                                    <?php for ($i = 1; $i <= 10; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="children" class="form-label">Niños</label>
                                <select class="form-select" id="children" name="children">
                                    <option value="0">0</option>
                                    <?php for ($i = 1; $i <= 10; $i++): ?>
                                        <option value="<?= $i ?>"><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="requests" class="form-label">Solicitudes Especiales</label>
                            <textarea class="form-control" id="requests" name="requests" rows="3"></textarea>
                        </div>
                    </div>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <a href="<?= APP_URL ?>/" class="btn btn-secondary me-md-2">Cancelar</a>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-credit-card me-1"></i> Proceder al Pago
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>