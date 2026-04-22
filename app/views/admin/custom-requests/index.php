<?php $customCss = ['modules/custom-requests.css']; ?>
<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>
<?php
// app/views/admin/custom-requests/index.php
$counts = $counts ?? ['pending' => 0, 'reviewing' => 0, 'approved' => 0, 'rejected' => 0];
$total = array_sum($counts);

$statusMap = [
    'pending' => ['label' => 'Pendiente', 'class' => 'badge-pend', 'icon' => 'fa-clock', 'color' => '#9a6c00'],
    'reviewing' => ['label' => 'En revisión', 'class' => 'badge-rev', 'icon' => 'fa-eye', 'color' => '#0077B6'],
    'approved' => ['label' => 'Aprobada', 'class' => 'badge-ok', 'icon' => 'fa-check-circle', 'color' => '#117a72'],
    'rejected' => ['label' => 'Rechazada', 'class' => 'badge-rej', 'icon' => 'fa-times-circle', 'color' => '#b91c1c'],
];

// Función para obtener iniciales
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
?>

<div class="cr-page">
    <div class="container-fluid px-0">

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="cr-alert cr-alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="cr-alert cr-alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- PAGE HEADER -->
        <div class="cr-page-header">
            <div>
                <h1 class="cr-page-title">
                    <i class="fas fa-magic"></i>
                    Excursiones Personalizadas
                </h1>
                <p class="cr-page-subtitle">Gestión de solicitudes a medida · <?= $total ?>
                    solicitud<?= $total !== 1 ? 'es' : '' ?> en total</p>
            </div>
            <a href="<?= APP_URL ?>/admin/dashboard" class="cr-back-btn">
                <i class="fas fa-arrow-left"></i> Dashboard
            </a>
        </div>

        <!-- STATS -->
        <div class="cr-stats">
            <a href="<?= APP_URL ?>/admin/custom_excursion_requests"
                class="cr-stat-card <?= empty($_GET['status']) ? 'active' : '' ?>">
                <div class="cr-stat-icon" style="background:rgba(0,119,182,.10);color:var(--ocean)"><i
                        class="fas fa-inbox"></i></div>
                <div>
                    <div class="cr-stat-val"><?= $total ?></div>
                    <div class="cr-stat-lbl">Total</div>
                </div>
            </a>
            <a href="<?= APP_URL ?>/admin/custom_excursion_requests?status=pending"
                class="cr-stat-card <?= ($_GET['status'] ?? '') === 'pending' ? 'active' : '' ?>">
                <div class="cr-stat-icon" style="background:rgba(249,199,79,.18);color:#9a6c00"><i
                        class="fas fa-clock"></i></div>
                <div>
                    <div class="cr-stat-val" style="color:#9a6c00"><?= $counts['pending'] ?></div>
                    <div class="cr-stat-lbl">Pendientes</div>
                </div>
            </a>
            <a href="<?= APP_URL ?>/admin/custom_excursion_requests?status=reviewing"
                class="cr-stat-card <?= ($_GET['status'] ?? '') === 'reviewing' ? 'active' : '' ?>">
                <div class="cr-stat-icon" style="background:rgba(0,180,216,.10);color:var(--light)"><i
                        class="fas fa-eye"></i></div>
                <div>
                    <div class="cr-stat-val" style="color:var(--ocean)"><?= $counts['reviewing'] ?></div>
                    <div class="cr-stat-lbl">En revisión</div>
                </div>
            </a>
            <a href="<?= APP_URL ?>/admin/custom_excursion_requests?status=approved"
                class="cr-stat-card <?= ($_GET['status'] ?? '') === 'approved' ? 'active' : '' ?>">
                <div class="cr-stat-icon" style="background:rgba(46,196,182,.12);color:var(--teal)"><i
                        class="fas fa-check-circle"></i></div>
                <div>
                    <div class="cr-stat-val" style="color:var(--teal)"><?= $counts['approved'] ?></div>
                    <div class="cr-stat-lbl">Aprobadas</div>
                </div>
            </a>
        </div>

        <!-- TOOLBAR -->
        <div class="cr-toolbar">
            <div class="cr-search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" class="cr-search-input" id="crSearch"
                    placeholder="Buscar por nombre, email, destino, fecha o teléfono…"
                    value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
            </div>
            <select class="cr-filter-select" id="crStatusFilter">
                <option value="">Todos los estados</option>
                <?php foreach (['pending' => 'Pendientes', 'reviewing' => 'En revisión', 'approved' => 'Aprobadas', 'rejected' => 'Rechazadas'] as $val => $lbl): ?>
                    <option value="<?= $val ?>" <?= ($_GET['status'] ?? '') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- TABLE -->
        <div class="cr-table-card">
            <?php if (empty($requests)): ?>
                <div class="cr-empty">
                    <div class="cr-empty-icon"><i class="fas fa-inbox"></i></div>
                    <h5>Sin solicitudes</h5>
                    <p>Cuando un visitante envíe una solicitud personalizada, aparecerá aquí.</p>
                </div>
            <?php else: ?>
                <div class="cr-table-wrapper" id="crTableWrapper">
                    <table class="cr-table" id="crTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Cliente</th>
                                <th>Destinos / Actividades</th>
                                <th>Fecha viaje</th>
                                <th>Pers.</th>
                                <th>Presupuesto</th>
                                <th>Cotización</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="crTbody">
                            <?php foreach ($requests as $req):
                                $st = $req['status'] ?? 'pending';
                                $stCfg = $statusMap[$st] ?? $statusMap['pending'];

                                // Unificar todos los campos relevantes en una sola cadena de búsqueda
                                $searchParts = [
                                    $req['customer_name'] ?? '',
                                    $req['customer_email'] ?? '',
                                    $req['customer_phone'] ?? '',
                                    $req['destinations'] ?? '',
                                    $req['activities'] ?? '',
                                    $req['travel_date'] ?? '',
                                    ($req['people_count'] ?? 0) . ' personas',
                                    $req['budget'] ?? '',
                                    '#' . ($req['id'] ?? '')
                                ];
                                $searchStr = strtolower(implode(' ', array_filter($searchParts)));
                                ?>
                                <tr data-search="<?= htmlspecialchars($searchStr) ?>"
                                    data-status="<?= htmlspecialchars($st) ?>">
                                    <td data-label="#">
                                        <span
                                            style="font-family:var(--fm);font-weight:600;font-size:.82rem;color:var(--muted)">#<?= (int) $req['id'] ?></span>
                                    </td>
                                    <td data-label="Cliente">
                                        <div class="cr-client-cell">
                                            <?php
                                            $initials = getInitials($req['customer_name'] ?? '');
                                            $avatarColor = ((int) ($req['id'] ?? 0) % 5) + 1;
                                            ?>
                                            <div class="cr-client-avatar cr-avatar-<?= $avatarColor ?>">
                                                <?= htmlspecialchars($initials) ?>
                                            </div>
                                            <div>
                                                <div class="cr-client-name">
                                                    <?= htmlspecialchars($req['customer_name'] ?? 'Invitado') ?></div>
                                                <div class="cr-client-email">
                                                    <?= htmlspecialchars($req['customer_email'] ?? '—') ?></div>
                                                <?php if (!empty($req['customer_phone'])): ?>
                                                    <div class="cr-client-phone">
                                                        <i class="fas fa-phone-alt"></i>
                                                        <?= htmlspecialchars($req['customer_phone']) ?>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>
                                    <td data-label="Destinos">
                                        <div class="cr-dest-primary">
                                            <i class="fas fa-location-arrow"></i>
                                            <?= htmlspecialchars($req['destinations'] ?? 'Sin destino') ?>
                                        </div>
                                        <?php if (!empty($req['activities'])): ?>
                                            <div class="cr-activities">
                                                <i class="fas fa-star" style="color:var(--sand)"></i>
                                                <?= htmlspecialchars(mb_strimwidth($req['activities'], 0, 45, '…')) ?>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Fecha">
                                        <?= !empty($req['travel_date']) ? date('d/m/Y', strtotime($req['travel_date'])) : '—' ?>
                                    </td>
                                    <td data-label="Personas"><strong><?= (int) ($req['people_count'] ?? 0) ?></strong><span
                                            style="color:var(--muted);font-size:.75rem"> pers.</span></td>
                                    <td data-label="Presupuesto">
                                        <?= !empty($req['budget']) ? '<strong style="color:var(--teal)">' . htmlspecialchars($req['budget']) . '</strong>' : '<span class="cr-no-price">Sin especificar</span>' ?>
                                    </td>
                                    <td data-label="Cotización">
                                        <?= !empty($req['quoted_price']) ? '<span class="cr-price-tag">$' . number_format((float) $req['quoted_price'], 2) . '</span>' : '<span class="cr-no-price">Pendiente</span>' ?>
                                    </td>
                                    <td data-label="Estado">
                                        <span class="cr-badge <?= $stCfg['class'] ?>"><i
                                                class="fas <?= $stCfg['icon'] ?>"></i><?= $stCfg['label'] ?></span>
                                        <?php if (!empty($req['last_contacted_at'])): ?>
                                            <div style="font-size:.68rem;color:var(--teal);margin-top:4px"><i
                                                    class="fas fa-phone-alt"></i> Contactado</div>
                                        <?php endif; ?>
                                    </td>
                                    <td data-label="Acciones">
                                        <div class="cr-row-actions">
                                            <a href="<?= APP_URL ?>/admin/custom_request_view/<?= (int) $req['id'] ?>"
                                                class="cr-act-btn cr-act-view" onclick="event.stopPropagation()">
                                                <i class="fas fa-eye"></i> Detalle
                                            </a>

                                            <span class="cr-action-separator"></span>

                                            <?php if ($st === 'pending' || $st === 'reviewing'): ?>
                                                <form method="POST"
                                                    action="<?= APP_URL ?>/admin/updateCustomRequestStatus/<?= (int) $req['id'] ?>"
                                                    style="display:inline" onclick="event.stopPropagation()">
                                                    <input type="hidden" name="status" value="approved">
                                                    <button type="submit" class="cr-act-btn cr-act-approve"
                                                        onclick="return confirm('¿Aprobar esta solicitud y notificar al cliente?')">
                                                        <i class="fas fa-check"></i> Aprobar
                                                    </button>
                                                </form>
                                            <?php endif; ?>

                                            <?php if ($st !== 'rejected' && $st !== 'approved'): ?>
                                                <form method="POST"
                                                    action="<?= APP_URL ?>/admin/updateCustomRequestStatus/<?= (int) $req['id'] ?>"
                                                    style="display:inline" onclick="event.stopPropagation()">
                                                    <input type="hidden" name="status" value="rejected">
                                                    <button type="submit" class="cr-act-btn cr-act-reject"
                                                        onclick="return confirm('¿Rechazar esta solicitud? Esta acción no se puede deshacer.')">
                                                        <i class="fas fa-times"></i> Rechazar
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div id="crNoResults" class="cr-empty" style="display: none;">
                    <div class="cr-empty-icon"><i class="fas fa-search"></i></div>
                    <h5>Sin resultados</h5>
                    <p>Ninguna solicitud coincide con los filtros aplicados.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
    (function () {
        const searchInput = document.getElementById('crSearch');
        const statusSelect = document.getElementById('crStatusFilter');
        const tbody = document.getElementById('crTbody');
        const noResults = document.getElementById('crNoResults');
        const wrapper = document.getElementById('crTableWrapper');

        if (!searchInput || !statusSelect || !tbody) return;

        function doFilter() {
            const query = searchInput.value.trim().toLowerCase();
            const selectedStatus = statusSelect.value; // '' significa todos

            let visibleCount = 0;
            const rows = tbody.querySelectorAll('tr');

            rows.forEach(tr => {
                const rowStatus = tr.dataset.status;
                const rowSearch = tr.dataset.search;

                const matchesText = !query || rowSearch.includes(query);
                const matchesStatus = !selectedStatus || rowStatus === selectedStatus;

                const visible = matchesText && matchesStatus;
                tr.style.display = visible ? '' : 'none';
                if (visible) visibleCount++;
            });

            // Mostrar/ocultar mensaje "sin resultados"
            if (noResults) {
                noResults.style.display = (visibleCount === 0) ? 'block' : 'none';
            }
            if (wrapper) {
                wrapper.style.display = (visibleCount === 0) ? 'none' : 'block';
            }
        }

        // Eventos
        searchInput.addEventListener('input', doFilter);
        statusSelect.addEventListener('change', doFilter);

        // Ejecutar al cargar la página para aplicar el filtro inicial (si hay estado en URL)
        window.addEventListener('DOMContentLoaded', function () {
            // Si hay un parámetro 'status' en la URL, aseguramos que el select lo refleje
            const urlParams = new URLSearchParams(window.location.search);
            const urlStatus = urlParams.get('status');
            if (urlStatus) {
                statusSelect.value = urlStatus;
            }
            doFilter();
        });
    })();
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>