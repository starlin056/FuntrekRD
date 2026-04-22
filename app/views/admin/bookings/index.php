<?php
$customCss = ['modules/admin-listings.css'];
require APP_ROOT . '/app/views/layouts/header.php';
require APP_ROOT . '/app/views/layouts/navigation.php';

// -----------------------------------------------------------------------------
// Helpers locales
// -----------------------------------------------------------------------------

/**
 * Obtiene la clase CSS para el badge de estado de reserva
 */
function getBookingStatusBadgeClass(string $status): string
{
    $map = [
        'pending' => 'adm-badge-pending',
        'confirmed' => 'adm-badge-confirmed',
        'cancelled' => 'adm-badge-cancelled',
        'completed' => 'adm-badge-completed',
    ];
    return $map[$status] ?? 'adm-badge-pending';
}

/**
 * Obtiene la clase CSS para el badge de estado de pago
 */
function getPaymentStatusBadgeClass(string $paymentStatus): string
{
    $map = [
        'pending' => 'adm-payment-pending',
        'paid' => 'adm-payment-paid',
        'refunded' => 'adm-payment-refunded',
    ];
    return $map[$paymentStatus] ?? 'adm-payment-pending';
}

/**
 * Obtiene el nombre del servicio según el tipo
 */
function getServiceDisplayName(array $booking): string
{
    $type = $booking['item_type'] ?? '';
    switch ($type) {
        case 'package':
            return $booking['package_name'] ?? __('admin.bookings.package');
        case 'excursion':
            return $booking['excursion_name'] ?? __('admin.bookings.excursion');
        default:
            return $booking['transfer_name'] ?? __('admin.bookings.transfer');
    }
}

// -----------------------------------------------------------------------------
// Vista principal
// -----------------------------------------------------------------------------
?>

<div class="adm-page">
    <div class="container-fluid">

        <!-- ========== CABECERA ========== -->
        <div class="adm-ph">
            <div class="adm-ph-left">
                <h1>
                    <i class="fas fa-calendar-alt me-2" style="color:var(--adm-teal)"></i>
                    <?= __('admin.bookings.title') ?>
                </h1>
                <p>Gestiona todas las reservas de paquetes, excursiones y transfers</p>
                <a href="<?= APP_URL ?>/admin/dashboard" class="adm-back-btn">
                    <i class="fas fa-arrow-left"></i> <?= __('common.back') ?>
                </a>
            </div>
        </div>

        <!-- ========== ESTADÍSTICAS RÁPIDAS ========== -->
        <?php
        $total = count($bookings);
        $pending = count(array_filter($bookings, fn($b) => $b['status'] === 'pending'));
        $confirmed = count(array_filter($bookings, fn($b) => $b['status'] === 'confirmed'));
        $totalRevenue = array_sum(array_column($bookings, 'total_price'));

        $statCards = [
            [
                'icon' => 'fa-calendar-check',
                'value' => $total,
                'label' => 'Total reservas',
                'color' => 'var(--adm-blue)',
                'bg' => 'rgba(0,119,182,.10)'
            ],
            [
                'icon' => 'fa-clock',
                'value' => $pending,
                'label' => 'Pendientes',
                'color' => '#b8860b',
                'bg' => 'rgba(249,199,79,.18)'
            ],
            [
                'icon' => 'fa-check-circle',
                'value' => $confirmed,
                'label' => 'Confirmadas',
                'color' => '#1a9c8e',
                'bg' => 'rgba(46,196,182,.12)'
            ],
            [
                'icon' => 'fa-dollar-sign',
                'value' => '$' . number_format($totalRevenue, 2),
                'label' => 'Ingresos totales',
                'color' => 'var(--adm-teal)',
                'bg' => 'rgba(0,180,216,.10)'
            ],
        ];
        ?>
        <div class="adm-stats-row">
            <?php foreach ($statCards as $card): ?>
                <div class="adm-stat-card">
                    <div class="adm-stat-icon" style="background:<?= $card['bg'] ?>; color:<?= $card['color'] ?>">
                        <i class="fas <?= $card['icon'] ?>"></i>
                    </div>
                    <div>
                        <span class="adm-stat-val"><?= $card['value'] ?></span>
                        <span class="adm-stat-label"><?= $card['label'] ?></span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- ========== BARRA DE FILTROS ========== -->
        <div class="adm-toolbar">
            <div class="adm-search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" class="adm-search-input" id="searchInput"
                    placeholder="Buscar por referencia, cliente o email...">
            </div>
            <select class="adm-filter-select" id="statusFilter">
                <option value="">Todos los estados</option>
                <option value="pending">Pendientes</option>
                <option value="confirmed">Confirmadas</option>
                <option value="cancelled">Canceladas</option>
                <option value="completed">Completadas</option>
            </select>
            <select class="adm-filter-select" id="paymentFilter">
                <option value="">Todos los pagos</option>
                <option value="pending">Pendientes</option>
                <option value="paid">Pagados</option>
                <option value="refunded">Reembolsados</option>
            </select>
        </div>

        <!-- ========== TABLA DE RESERVAS ========== -->
        <div class="adm-table-card">
            <?php if (empty($bookings)): ?>
                <div class="adm-empty">
                    <i class="fas fa-calendar-week"></i>
                    <h5><?= __('admin.bookings.empty') ?></h5>
                    <p>No hay reservas registradas aún.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="adm-table" id="bookingsTable">
                        <thead>
                            <tr>
                                <th><?= __('admin.bookings.ref') ?></th>
                                <th><?= __('admin.bookings.customer') ?></th>
                                <th><?= __('admin.bookings.service') ?></th>
                                <th><?= __('admin.bookings.total') ?></th>
                                <th><?= __('admin.bookings.travel_date') ?></th>
                                <th><?= __('admin.bookings.status') ?></th>
                                <th><?= __('admin.bookings.payment') ?></th>
                                <th class="text-end"><?= __('common.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody id="tableBody">
                            <?php foreach ($bookings as $b): ?>
                                <?php
                                $serviceName = getServiceDisplayName($b);
                                $statusClass = getBookingStatusBadgeClass($b['status']);
                                $paymentClass = getPaymentStatusBadgeClass($b['payment_status']);
                                ?>
                                <tr data-ref="<?= strtolower($b['booking_reference']) ?>"
                                    data-customer="<?= strtolower($b['customer_name']) ?>"
                                    data-email="<?= strtolower($b['customer_email']) ?>" data-status="<?= $b['status'] ?>"
                                    data-payment="<?= $b['payment_status'] ?>">
                                    <!-- Referencia -->
                                    <td data-label="Referencia">
                                        <a href="<?= APP_URL ?>/admin/bookingDetail/<?= $b['id'] ?>"
                                            class="text-decoration-none fw-semibold" style="color:var(--adm-blue)">
                                            <?= htmlspecialchars($b['booking_reference']) ?>
                                        </a>
                                    </td>
                                    <!-- Cliente -->
                                    <td data-label="Cliente">
                                        <?= htmlspecialchars($b['customer_name']) ?><br>
                                        <small class="text-muted"><?= htmlspecialchars($b['customer_email']) ?></small>
                                    </td>
                                    <!-- Servicio -->
                                    <td data-label="Servicio">
                                        <?= htmlspecialchars($serviceName) ?><br>
                                        <small class="text-muted"><?= ucfirst($b['item_type']) ?></small>
                                    </td>
                                    <!-- Total -->
                                    <td data-label="Total" class="fw-bold">
                                        $<?= number_format((float) $b['total_price'], 2) ?>
                                    </td>
                                    <!-- Fecha de viaje -->
                                    <td data-label="Fecha">
                                        <?= date('d/m/Y', strtotime($b['travel_date'])) ?>
                                    </td>
                                    <!-- Estado -->
                                    <td data-label="Estado">
                                        <span class="adm-badge <?= $statusClass ?>">
                                            <?= __('status.' . $b['status']) ?>
                                        </span>
                                    </td>
                                    <!-- Pago -->
                                    <td data-label="Pago">
                                        <span class="adm-badge <?= $paymentClass ?>">
                                            <?= __('payment.' . $b['payment_status']) ?>
                                        </span>
                                    </td>
                                    <!-- Acciones -->
                                    <td data-label="Acciones" class="text-end">
                                        <a href="<?= APP_URL ?>/admin/bookingDetail/<?= $b['id'] ?>" class="adm-btn-edit"
                                            title="Ver detalle">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div id="noResults" class="adm-empty" style="display:none">
                    <i class="fas fa-search"></i>
                    <h5>Sin resultados</h5>
                    <p>No hay reservas que coincidan con tu búsqueda.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- ========== SCRIPT DE FILTROS ========== -->
<script>
    (function () {
        const searchInput = document.getElementById('searchInput');
        const statusFilter = document.getElementById('statusFilter');
        const paymentFilter = document.getElementById('paymentFilter');
        const tbody = document.getElementById('tableBody');
        const noResults = document.getElementById('noResults');

        if (!tbody) return;

        function applyFilters() {
            const query = (searchInput?.value || '').toLowerCase();
            const status = statusFilter?.value || '';
            const payment = paymentFilter?.value || '';

            let visibleRows = 0;

            tbody.querySelectorAll('tr').forEach(row => {
                const ref = row.dataset.ref || '';
                const customer = row.dataset.customer || '';
                const email = row.dataset.email || '';
                const rowStatus = row.dataset.status || '';
                const rowPayment = row.dataset.payment || '';

                const matchesSearch = !query || ref.includes(query) || customer.includes(query) || email.includes(query);
                const matchesStatus = !status || rowStatus === status;
                const matchesPayment = !payment || rowPayment === payment;

                const show = matchesSearch && matchesStatus && matchesPayment;
                row.style.display = show ? '' : 'none';
                if (show) visibleRows++;
            });

            if (noResults) {
                noResults.style.display = visibleRows === 0 ? 'block' : 'none';
            }
        }

        // Asignar eventos
        searchInput?.addEventListener('input', applyFilters);
        statusFilter?.addEventListener('change', applyFilters);
        paymentFilter?.addEventListener('change', applyFilters);
    })();
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>