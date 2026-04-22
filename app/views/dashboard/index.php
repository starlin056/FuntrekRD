<?php
$customCss = ['modules/admin-dashboard.css', 'modules/admin-listings.css'];
require_once APP_ROOT . '/app/views/layouts/header.php';
require_once APP_ROOT . '/app/views/layouts/navigation.php';
?>

<div class="adm-page">
    <div class="container-fluid">

        <!-- Alertas -->
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="adm-alert adm-alert-success animate-fade-in">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Header -->
        <div class="adm-ph text-center justify-content-center">
            <div class="adm-ph-left w-100 text-center">
                <div class="adm-welcome-badge animate-fade-in mx-auto">
                    <i class="fas fa-hand-peace"></i> <?= __('dashboard.welcome') ?>,
                    <?= htmlspecialchars($user_name) ?>
                </div>
                <h1 class="adm-title text-center mt-3"><?= __('dashboard.title') ?? 'Mi Dashboard' ?></h1>
                <p class="adm-subtitle text-center"><?= __('dashboard.subtitle') ?></p>
            </div>
            <!-- <div class="adm-actions-bar w-100 justify-content-center mt-3">
                <a href="<?= APP_URL ?>/paquetes" class="adm-btn-outline"><i class="fas fa-suitcase me-1"></i> <?= __('nav.packages') ?></a>
                <a href="<?= APP_URL ?>/excursiones" class="adm-btn-outline"><i class="fas fa-map-marked-alt me-1"></i> <?= __('nav.excursions') ?></a>
                <a href="<?= APP_URL ?>/transfers" class="adm-btn-outline"><i class="fas fa-car me-1"></i> <?= __('nav.transfers') ?></a>
                <a href="<?= APP_URL ?>/auth/logout" class="adm-btn-outline text-danger"><i class="fas fa-sign-out-alt me-1"></i> <?= __('nav.logout') ?></a>
            </div> -->
        </div>

        <!-- Métricas Rápidas -->
        <?php
        $totalBookings = count($bookings);
        $confirmed = count(array_filter($bookings, fn($b) => $b['status'] === 'confirmed'));
        $pending = count(array_filter($bookings, fn($b) => $b['status'] === 'pending'));
        $completed = count(array_filter($bookings, fn($b) => $b['status'] === 'completed'));
        ?>
        <div class="adm-stats-row mb-4">
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(0,119,182,.10);color:var(--adm-blue)">
                    <i class="fas fa-calendar-alt"></i>
                </div>
                <div>
                    <span class="adm-stat-val"><?= $totalBookings ?></span>
                    <span class="adm-stat-label">Total Reservas</span>
                </div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(46,196,182,.10);color:var(--adm-green)">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div>
                    <span class="adm-stat-val"><?= $confirmed ?></span>
                    <span class="adm-stat-label">Confirmadas</span>
                </div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(249,199,79,.18);color:#b8860b">
                    <i class="fas fa-clock"></i>
                </div>
                <div>
                    <span class="adm-stat-val"><?= $pending ?></span>
                    <span class="adm-stat-label">Pendientes</span>
                </div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(0,180,216,.10);color:var(--adm-teal)">
                    <i class="fas fa-flag-checkered"></i>
                </div>
                <div>
                    <span class="adm-stat-val"><?= $completed ?></span>
                    <span class="adm-stat-label">Completadas</span>
                </div>
            </div>
        </div>

        <!-- Accesos Rápidos -->
        <div class="adm-quick-cards mb-5">
            <a href="<?= APP_URL ?>/dashboard/bookings" class="adm-quick-card">
                <div class="adm-quick-icon"><i class="fas fa-calendar-check"></i></div>
                <h5><?= __('dashboard.my_bookings') ?></h5>
                <p><?= __('dashboard.my_bookings_desc') ?></p>
            </a>
            <a href="<?= APP_URL ?>/dashboard/profile" class="adm-quick-card">
                <div class="adm-quick-icon"><i class="fas fa-user-edit"></i></div>
                <h5><?= __('dashboard.profile') ?></h5>
                <p><?= __('dashboard.profile_desc') ?></p>
            </a>
            <a href="<?= APP_URL ?>/contacto" class="adm-quick-card">
                <div class="adm-quick-icon"><i class="fas fa-headset"></i></div>
                <h5><?= __('dashboard.support') ?></h5>
                <p><?= __('dashboard.support_desc') ?></p>
            </a>
        </div>

        <!-- Reservas Recientes Section -->
        <div class="d-flex align-items-center justify-content-between mb-3 mt-4">
            <h4 class="adm-section-title mb-0">
                <i class="fas fa-history me-2 text-primary"></i><?= __('bookings.title') ?> Recientes
            </h4>
            <a href="<?= APP_URL ?>/dashboard/bookings" class="adm-link-more">
                Ver todas <i class="fas fa-arrow-right ms-1"></i>
            </a>
        </div>

        <!-- Toolbar -->
        <div class="adm-toolbar">
            <div class="adm-search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" class="adm-search-input" id="bookingSearch" placeholder="Buscar por referencia...">
            </div>
            <select class="adm-filter-select" id="statusFilter">
                <option value="">Todos los estados</option>
                <option value="pending">Pendientes</option>
                <option value="confirmed">Confirmadas</option>
                <option value="completed">Completadas</option>
            </select>
        </div>

        <!-- Tabla -->
        <div class="adm-table-card">
            <?php if (empty($bookings)): ?>
                <div class="adm-empty">
                    <i class="fas fa-calendar-times"></i>
                    <h5><?= __('bookings.empty') ?></h5>
                    <p>Aún no has realizado ninguna reserva. ¡Descubre nuestras ofertas!</p>
                    <a href="<?= APP_URL ?>/paquetes" class="btn btn-primary rounded-pill px-4">Explorar Paquetes</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="adm-table" id="bookingsTable">
                        <thead>
                            <tr>
                                <th><?= __('bookings.ref') ?></th>
                                <th><?= __('bookings.service') ?></th>
                                <th><?= __('bookings.date') ?></th>
                                <th><?= __('bookings.total') ?></th>
                                <th><?= __('bookings.status') ?></th>
                                <th class="text-end"></th>
                            </tr>
                        </thead>
                        <tbody id="bookingsTbody">
                            <?php foreach (array_slice($bookings, 0, 5) as $booking): ?>
                                <?php
                                $itemType = $booking['item_type'] ?? 'package';
                                $serviceName = ($itemType === 'package') ? ($booking['package_name'] ?? 'Paquete') :
                                    (($itemType === 'excursion') ? ($booking['excursion_name'] ?? 'Excursión') :
                                        ($booking['transfer_name'] ?? 'Transfer'));

                                $status = $booking['status'];
                                $statusBadge = [
                                    'pending' => 'adm-badge-inactive',
                                    'confirmed' => 'adm-badge-active',
                                    'completed' => 'adm-badge-active',
                                    'cancelled' => 'adm-badge-inactive'
                                ][$status] ?? 'adm-badge-inactive';
                                ?>
                                <tr data-status="<?= $status ?>"
                                    data-search="<?= strtolower($booking['booking_reference'] . ' ' . $serviceName) ?>">
                                    <td><span
                                            class="fw-bold text-primary"><?= htmlspecialchars($booking['booking_reference'] ?? '#' . $booking['id']) ?></span>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <i
                                                class="fas <?= ($itemType === 'package') ? 'fa-suitcase' : (($itemType === 'excursion') ? 'fa-hiking' : 'fa-car') ?> text-muted"></i>
                                            <?= htmlspecialchars($serviceName) ?>
                                        </div>
                                    </td>
                                    <td><?= date('d/m/Y', strtotime($booking['booking_date'] ?? $booking['travel_date'])) ?>
                                    </td>
                                    <td><span class="adm-price">$<?= number_format($booking['total_price'], 2) ?></span></td>
                                    <td>
                                        <span class="adm-badge <?= $statusBadge ?>">
                                            <?= ucfirst($status) ?>
                                        </span>
                                    </td>
                                    <td class="text-end">
                                        <a href="<?= APP_URL ?>/dashboard/booking/<?= $booking['id'] ?>" class="adm-btn-edit">
                                            <i class="fas fa-arrow-right"></i>
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

<script>
    (function () {
        const searchInput = document.getElementById('bookingSearch');
        const statusFilter = document.getElementById('statusFilter');
        const tbody = document.getElementById('bookingsTbody');
        if (!tbody) return;

        function filter() {
            const query = searchInput.value.toLowerCase();
            const status = statusFilter.value;
            tbody.querySelectorAll('tr').forEach(row => {
                const rowStatus = row.dataset.status;
                const matchesSearch = row.dataset.search.includes(query);
                const matchesStatus = !status || rowStatus === status;
                row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
            });
        }
        searchInput.addEventListener('input', filter);
        statusFilter.addEventListener('change', filter);
    })();
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>
script>