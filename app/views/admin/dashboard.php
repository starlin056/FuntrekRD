<?php $customCss = ['modules/admin-dashboard.css']; ?>
<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>
<?php
// Local helper for initials
if (!function_exists('getInitials')) {
    function getInitials($name)
    {
        if (empty($name))
            return '??';
        $words = explode(' ', trim($name));
        $initials = '';
        foreach ($words as $w) {
            $initials .= mb_substr($w, 0, 1);
            if (mb_strlen($initials) >= 2)
                break;
        }
        return mb_strtoupper($initials);
    }
}
?>
<div class="adm-page">
    <div class="container-fluid">

        <!-- Alertas -->
        <?php foreach (['success' => 'success', 'error' => 'danger'] as $key => $type): ?>
            <?php if (!empty($_SESSION[$key])): ?>
                <div class="adm-alert adm-alert-<?= $type ?>">
                    <i class="fas <?= $key === 'success' ? 'fa-check-circle' : 'fa-exclamation-triangle' ?>"></i>
                    <?= htmlspecialchars($_SESSION[$key]) ?>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
                <?php unset($_SESSION[$key]); ?>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Header -->
        <div class="adm-ph">
            <div class="adm-ph-left">
                <h1><i class="fas fa-tachometer-alt me-2"
                        style="color:var(--adm-teal)"></i><?= __('admin.dashboard.title') ?></h1>
                <p><?= __('admin.dashboard.subtitle') ?></p>
                <div class="d-flex gap-2 mt-2">
                    <a href="<?= APP_URL ?>/admin/settings" class="btn btn-sm btn-outline-primary rounded-pill px-3 shadow-sm">
                        <i class="fas fa-cogs me-1"></i>Ajustes de Empresa
                    </a>
                    <a href="<?= APP_URL ?>/admin/quotations_create" class="btn btn-sm btn-primary rounded-pill px-3 shadow-sm">
                        <i class="fas fa-plus me-1"></i>Nueva Cotización
                    </a>
                    <a href="<?= APP_URL ?>/admin/airport_sign" target="_blank" class="btn btn-sm btn-outline-dark rounded-pill px-3 shadow-sm">
                        <i class="fas fa-id-card me-1"></i>Letrero Aeropuerto
                    </a>
                </div>
            </div>
            <div class="adm-role-badge">
                <i class="fas fa-user-shield"></i> <?= __('admin.role') ?>
            </div>
        </div>

        <!-- Métricas -->
        <div class="adm-metrics-grid">
            <?php
            $metrics = [
                [
                    'title' => __('admin.metrics.packages'),
                    'value' => $totalPackages ?? 0,
                    'color' => '#0077B6',
                    'icon' => 'suitcase',
                    'url' => '/admin/packages',
                    'bg' => 'rgba(0,119,182,.10)'
                ],
                [
                    'title' => __('admin.metrics.excursions'),
                    'value' => $totalExcursions ?? 0,
                    'color' => '#2EC4B6',
                    'icon' => 'map-marked-alt',
                    'url' => '/admin/excursions',
                    'bg' => 'rgba(46,196,182,.10)'
                ],
                [
                    'title' => __('admin.metrics.transfers'),
                    'value' => $totalTransfers ?? 0,
                    'color' => '#F9C74F',
                    'icon' => 'car',
                    'url' => '/admin/transfers',
                    'bg' => 'rgba(249,199,79,.18)'
                ],
                [
                    'title' => __('admin.metrics.clients'),
                    'value' => $totalClients ?? 0,
                    'color' => '#00B4D8',
                    'icon' => 'users',
                    'url' => '/admin/clients',
                    'bg' => 'rgba(0,180,216,.10)'
                ],
                [
                    'title' => 'Cotizaciones',
                    'value' => $this->quotationModel->count() ?? 0,
                    'color' => '#6A4C93',
                    'icon' => 'file-invoice-dollar',
                    'url' => '/admin/quotations',
                    'bg' => 'rgba(106,76,147,.10)'
                ],
            ];
            ?>
            <?php foreach ($metrics as $m): ?>
                <a href="<?= APP_URL . $m['url'] ?>" class="adm-metric-card">
                    <div class="adm-metric-info">
                        <h4><?= $m['title'] ?></h4>
                        <div class="adm-metric-number"><?= number_format($m['value']) ?></div>
                        <span class="adm-metric-link">
                            <?= __('admin.metrics.manage') ?> <i class="fas fa-arrow-right ms-1"></i>
                        </span>
                    </div>
                    <div class="adm-metric-icon" style="background:<?= $m['bg'] ?>; color:<?= $m['color'] ?>">
                        <i class="fas fa-<?= $m['icon'] ?>"></i>
                    </div>
                </a>
            <?php endforeach; ?>
        </div>

        <!-- Contenido principal -->
        <div class="row g-4">
            <!-- Reservas recientes -->
            <div class="col-lg-8">
                <div class="adm-card">
                    <div class="adm-card-header">
                        <h5><i class="fas fa-calendar-alt me-2"
                                style="color:var(--adm-teal)"></i><?= __('admin.bookings.manager') ?></h5>
                        <a href="<?= APP_URL ?>/admin/bookings" class="adm-btn-sm">Ver todas <i
                                class="fas fa-chevron-right ms-1"></i></a>
                    </div>
                    <div class="adm-table-wrapper">
                        <?php if (!empty($recentBookings)): ?>
                            <table class="adm-table">
                                <thead>
                                    <tr>
                                        <th><?= __('booking_detail.customer') ?></th>
                                        <th>Reserva</th>
                                        <th><?= __('bookings.total') ?></th>
                                        <th><?= __('bookings.status') ?></th>
                                        <th><?= __('bookings.date') ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach (array_slice($recentBookings, 0, 10) as $booking): ?>
                                        <?php
                                        $statusMap = [
                                            'pending' => 'adm-badge-pending',
                                            'confirmed' => 'adm-badge-confirmed',
                                            'cancelled' => 'adm-badge-cancelled',
                                            'completed' => 'adm-badge-completed',
                                        ];
                                        $badgeClass = $statusMap[$booking['status']] ?? 'adm-badge-pending';

                                        $initials = getInitials($booking['customer_name'] ?? '');
                                        $avatarColor = ((int) ($booking['id'] ?? 0) % 5) + 1;
                                        ?>
                                        <tr>
                                            <td>
                                                <div class="adm-client-cell">
                                                    <div class="adm-client-avatar ac-avatar-<?= $avatarColor ?>">
                                                        <?= htmlspecialchars($initials) ?>
                                                    </div>
                                                    <div class="adm-client-info">
                                                        <strong><?= htmlspecialchars($booking['customer_name'] ?? 'Invitado') ?></strong>
                                                        <small><i
                                                                class="fas fa-envelope me-1"></i><?= htmlspecialchars($booking['customer_email'] ?? '') ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="text-muted font-monospace"
                                                    style="font-size: .75rem;">#<?= (int) $booking['id'] ?></span>
                                            </td>
                                            <td class="fw-bold" style="color: var(--adm-blue)">
                                                $<?= number_format($booking['total_price'], 2) ?></td>
                                            <td><span
                                                    class="adm-badge <?= $badgeClass ?>"><?= __('status.' . $booking['status']) ?></span>
                                            </td>
                                            <td class="text-muted"><?= date('d/m/Y', strtotime($booking['created_at'])) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="text-center py-5 text-muted">
                                <i class="fas fa-calendar-week fa-2x mb-2 opacity-50"></i>
                                <p><?= __('admin.no_recent_bookings') ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>

            <!-- Columna derecha -->
            <div class="col-lg-4 d-flex flex-column gap-4">
                <div class="adm-card">
                    <div class="adm-card-header">
                        <h5><i class="fas fa-chart-line me-2"
                                style="color:var(--adm-teal)"></i><?= __('admin.stats.title') ?></h5>
                    </div>
                    <div class="adm-stats-grid">
                        <div class="adm-stat-mini-card">
                            <div class="adm-stat-mini-icon"
                                style="background:rgba(0,119,182,0.1); color:var(--adm-blue)">
                                <i class="fas fa-calendar-day"></i>
                            </div>
                            <div class="adm-stat-content">
                                <span class="adm-stat-label"><?= __('admin.stats.today') ?></span>
                                <span class="adm-stat-value"><?= (int) ($stats['todayBookings'] ?? 0) ?></span>
                            </div>
                        </div>
                        <div class="adm-stat-mini-card">
                            <div class="adm-stat-mini-icon"
                                style="background:rgba(0,180,216,0.1); color:var(--adm-teal)">
                                <i class="fas fa-calendar-alt"></i>
                            </div>
                            <div class="adm-stat-content">
                                <span class="adm-stat-label"><?= __('admin.stats.month') ?></span>
                                <span class="adm-stat-value"><?= (int) ($stats['monthBookings'] ?? 0) ?></span>
                            </div>
                        </div>
                        <div class="adm-stat-mini-card">
                            <div class="adm-stat-mini-icon" style="background:rgba(249,199,79,0.18); color:#b8860b">
                                <i class="fas fa-clock"></i>
                            </div>
                            <div class="adm-stat-content">
                                <span class="adm-stat-label"><?= __('admin.stats.pending') ?></span>
                                <span class="adm-stat-value"><?= (int) ($stats['pendingBookings'] ?? 0) ?></span>
                            </div>
                        </div>
                        <div class="adm-stat-mini-card">
                            <div class="adm-stat-mini-icon"
                                style="background:rgba(46,196,182,0.12); color:var(--adm-green)">
                                <i class="fas fa-dollar-sign"></i>
                            </div>
                            <div class="adm-stat-content">
                                <span class="adm-stat-label"><?= __('admin.stats.revenue') ?></span>
                                <span
                                    class="adm-stat-value revenue">$<?= number_format($stats['totalRevenue'] ?? 0, 2) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Widget solicitudes personalizadas -->
                <div class="adm-card">
                    <div class="adm-card-header">
                        <div class="d-flex align-items-center gap-2">
                            <h5><i class="fas fa-magic text-warning me-1"></i> Excursiones personalizadas</h5>
                            <?php if (!empty($pendingCustomRequests) && $pendingCustomRequests > 0): ?>
                                <span class="cr-badge"><?= (int) $pendingCustomRequests ?></span>
                            <?php endif; ?>
                        </div>
                        <a href="<?= APP_URL ?>/admin/custom_excursion_requests" class="adm-btn-sm">Ver todas</a>
                    </div>
                    <div class="adm-card-body-scroll">
                        <?php if (empty($recentCustomRequests)): ?>
                            <div class="text-center text-muted py-4">
                                <i class="fas fa-inbox fa-2x mb-2 d-block opacity-50"></i>
                                <small>Sin solicitudes todavía</small>
                            </div>
                        <?php else: ?>
                            <?php
                            $statusChips = [
                                'pending' => ['cr-chip-pend', 'Pendiente'],
                                'reviewing' => ['cr-chip-rev', 'En revisión'],
                                'approved' => ['cr-chip-ok', 'Aprobada'],
                                'rejected' => ['cr-chip-rej', 'Rechazada'],
                            ];
                            ?>
                            <?php foreach ($recentCustomRequests as $req): ?>
                                <?php
                                [$chipClass, $chipLabel] = $statusChips[$req['status'] ?? 'pending'] ?? ['cr-chip-pend', 'Pendiente'];
                                $timeAgo = $this->timeAgo($req['created_at']);
                                ?>
                                <div class="cr-row">
                                    <div class="cr-avatar"><i class="fas fa-user"></i></div>
                                    <div class="flex-grow-1 min-w-0">
                                        <p class="cr-name"><?= htmlspecialchars($req['customer_name']) ?></p>
                                        <p class="cr-dest">
                                            <i class="fas fa-map-marker-alt me-1" style="color:var(--adm-blue)"></i>
                                            <?= htmlspecialchars(mb_strimwidth($req['destinations'], 0, 40, '…')) ?>
                                        </p>
                                        <div class="cr-meta">
                                            <span class="cr-chip <?= $chipClass ?>"><?= $chipLabel ?></span>
                                            <?php if ($req['people_count'] > 0): ?>
                                                <span class="cr-chip"
                                                    style="background:rgba(110,143,165,.10);color:var(--adm-muted);">
                                                    <i class="fas fa-users me-1"></i><?= (int) $req['people_count'] ?> pers.
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <span class="cr-time"><?= $timeAgo ?></span>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Acciones rápidas modernas -->
                <!-- <div class="adm-card">
                    <div class="adm-card-header">
                        <h5><i class="fas fa-bolt me-2"
                                style="color:var(--adm-sand)"></i><?= __('admin.quick_actions') ?></h5>
                    </div>
                    <div class="adm-quick-grid">
                       <a href="<?= APP_URL ?>/admin/bookings" class="adm-quick-card">
                            <div class="adm-quick-icon"><i class="fas fa-calendar-check"></i></div>
                            <div class="adm-quick-info">
                                <strong><?= __('admin.action.bookings') ?></strong>
                                <span>Ver todas las reservas</span>
                            </div>
                        </a> 

                        <a href="<?= APP_URL ?>/admin/custom_excursion_requests" class="adm-quick-card">
                            <div class="adm-quick-icon"><i class="fas fa-magic"></i></div>
                            <div class="adm-quick-info">
                                <strong>Excursiones personalizadas</strong>
                                <span>Gestiona solicitudes</span>
                                <?php if (!empty($pendingCustomRequests) && $pendingCustomRequests > 0): ?>
                                    <span class="adm-pulse-badge"><?= (int) $pendingCustomRequests ?></span>
                                <?php endif; ?>
                            </div>
                        </a>

                        <a href="<?= APP_URL ?>/admin/packages_create" class="adm-quick-card">
                            <div class="adm-quick-icon"><i class="fas fa-plus-circle"></i></div>
                            <div class="adm-quick-info">
                                <strong><?= __('admin.action.new_package') ?></strong>
                                <span>Agregar paquete turístico</span>
                            </div>
                        </a>

                        <a href="<?= APP_URL ?>/admin/excursions_create" class="adm-quick-card">
                            <div class="adm-quick-icon"><i class="fas fa-plus-circle"></i></div>
                            <div class="adm-quick-info">
                                <strong><?= __('admin.action.new_excursion') ?></strong>
                                <span>Crear excursión</span>
                            </div>
                        </a>

                        <a href="<?= APP_URL ?>/admin/transfers_create" class="adm-quick-card">
                            <div class="adm-quick-icon"><i class="fas fa-plus-circle"></i></div>
                            <div class="adm-quick-info">
                                <strong><?= __('admin.action.new_transfer') ?></strong>
                                <span>Agregar transfer</span>
                            </div>
                        </a>
                    </div> -->
            </div>
        </div>
    </div>
</div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>