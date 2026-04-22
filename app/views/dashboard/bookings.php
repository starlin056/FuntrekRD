<?php
$customCss = ['modules/admin-dashboard.css', 'modules/admin-listings.css'];
require_once APP_ROOT . '/app/views/layouts/header.php';
require_once APP_ROOT . '/app/views/layouts/navigation.php';

// Helper for status badges
$getStatusBadge = function ($status) {
    return [
        'pending' => 'adm-badge-inactive',
        'confirmed' => 'adm-badge-active',
        'cancelled' => 'adm-badge-inactive',
        'completed' => 'adm-badge-active'
    ][$status] ?? 'adm-badge-inactive';
};
?>

<div class="adm-page">
    <div class="container-fluid">
        <div class="row g-4">
            <!-- Sidebar -->
            <div class="col-lg-3">
                <div class="adm-sidebar-sticky">
                    <div class="adm-sidebar animate-fade-in">
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
                            <a href="<?= APP_URL ?>/dashboard/bookings" class="adm-nav-link active">
                                <i class="fas fa-calendar-check"></i> Mis Reservas
                            </a>
                            <a href="<?= APP_URL ?>/dashboard/profile" class="adm-nav-link">
                                <i class="fas fa-user-edit"></i> Editar Perfil
                            </a>
                            <hr>
                            <a href="<?= APP_URL ?>/auth/logout" class="adm-nav-link text-danger">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </nav>
                    </div>

                    <div class="card adm-card bg-primary text-white border-0 overflow-hidden shadow-lg animate-fade-in"
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
                <div class="adm-main animate-fade-in">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h1 class="adm-title mb-1">Mis Reservas</h1>
                            <p class="text-muted small mb-0">Gestiona tus viajes y experiencias programadas</p>
                        </div>
                        <a href="<?= APP_URL ?>/paquetes" class="adm-new-btn">
                            <i class="fas fa-plus"></i> Nueva Reserva
                        </a>
                    </div>

                    <!-- Toolbar -->
                    <div class="adm-toolbar bg-white p-3 rounded-4 shadow-sm mb-4 border border-light">
                        <div class="adm-search-wrap">
                            <i class="fas fa-search"></i>
                            <input type="text" class="adm-search-input" id="searchBookings"
                                placeholder="Buscar por referencia o servicio...">
                        </div>
                        <select class="adm-filter-select" id="filterStatus">
                            <option value="">Todos los estados</option>
                            <option value="pending">Pendientes</option>
                            <option value="confirmed">Confirmadas</option>
                            <option value="completed">Completadas</option>
                        </select>
                    </div>

                    <!-- Table Card -->
                    <div class="adm-table-card">
                        <?php if (empty($bookings)): ?>
                            <div class="adm-empty p-5">
                                <i class="fas fa-calendar-times mb-3"></i>
                                <h5>No hay reservas registradas</h5>
                                <p>Parece que aún no tienes aventuras planeadas con nosotros.</p>
                                <a href="<?= APP_URL ?>/paquetes" class="btn btn-primary rounded-pill mt-3 px-4">Ver
                                    Catálogo</a>
                            </div>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="adm-table" id="bookingsTable">
                                    <thead>
                                        <tr>
                                            <th>Ref</th>
                                            <th>Servicio</th>
                                            <th>Fecha Viaje</th>
                                            <th>Total</th>
                                            <th>Estado</th>
                                            <th class="text-end">Acciones</th>
                                        </tr>
                                    </thead>
                                    <tbody id="bookingsTbody">
                                        <?php foreach ($bookings as $booking): ?>
                                            <?php
                                            $itemType = $booking['item_type'] ?? 'package';
                                            $sName = ($itemType === 'package') ? ($booking['package_name'] ?? 'Paquete') :
                                                (($itemType === 'excursion') ? ($booking['excursion_name'] ?? 'Excursión') :
                                                    ($booking['transfer_name'] ?? 'Transfer'));

                                            $sDate = $booking['booking_date'] ?? $booking['travel_date'];
                                            $status = $booking['status'];
                                            ?>
                                            <tr data-status="<?= $status ?>"
                                                data-search="<?= strtolower($booking['booking_reference'] . ' ' . $sName) ?>">
                                                <td><span class="fw-bold text-primary">
                                                        <?= htmlspecialchars($booking['booking_reference']) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="adm-icon-mini">
                                                            <i
                                                                class="fas <?= ($itemType === 'package') ? 'fa-suitcase' : (($itemType === 'excursion') ? 'fa-hiking' : 'fa-car') ?>"></i>
                                                        </div>
                                                        <span class="fw-medium">
                                                            <?= htmlspecialchars($sName) ?>
                                                        </span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="text-nowrap">
                                                        <i class="far fa-calendar-alt text-muted me-1"></i>
                                                        <?= date('d M, Y', strtotime($sDate)) ?>
                                                    </div>
                                                </td>
                                                <td><span class="adm-price">$
                                                        <?= number_format($booking['total_price'], 2) ?>
                                                    </span>
                                                </td>
                                                <td>
                                                    <span class="adm-badge <?= $getStatusBadge($status) ?>">
                                                        <?= ucfirst($status) ?>
                                                    </span>
                                                </td>
                                                <td class="text-end">
                                                    <a href="<?= APP_URL ?>/dashboard/booking/<?= $booking['id'] ?>"
                                                        class="adm-btn-edit" title="Ver Detalles">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const search = document.getElementById('searchBookings');
        const filter = document.getElementById('filterStatus');
        const tbody = document.getElementById('bookingsTbody');
        if (!tbody) return;

        function doFilter() {
            const q = search.value.toLowerCase();
            const s = filter.value;
            tbody.querySelectorAll('tr').forEach(row => {
                const rowS = row.dataset.status;
                const rowTxt = row.dataset.search;
                const matchesQ = rowTxt.includes(q);
                const matchesS = !s || rowS === s;
                row.style.display = (matchesQ && matchesS) ? '' : 'none';
            });
        }

        search.addEventListener('input', doFilter);
        filter.addEventListener('change', doFilter);
    });
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>