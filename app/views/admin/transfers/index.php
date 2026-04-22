<?php $customCss = ['modules/admin-listings.css']; ?>
<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<div class="adm-page">
    <div class="container-fluid">

        <!-- Alerta de éxito -->
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="adm-alert adm-alert-success"><i class="fas fa-check-circle"></i><?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Header -->
        <div class="adm-ph">
            <div class="adm-ph-left">
                <h1><i class="fas fa-shuttle-van me-2" style="color:var(--adm-teal)"></i><?= __('admin.transfers.title') ?></h1>
                <p><?= __('admin.transfers.subtitle') ?></p>
                <a href="<?= APP_URL ?>/admin/dashboard" class="adm-back-btn"><i class="fas fa-arrow-left"></i><?= __('common.back') ?></a>
            </div>
            <a href="<?= APP_URL ?>/admin/transfers_create" class="adm-new-btn"><i class="fas fa-plus-circle"></i><?= __('admin.transfers.new') ?></a>
        </div>

        <!-- Stats rápidos -->
        <?php
        $total     = count($transfers);
        $active    = count(array_filter($transfers, fn($t) => !empty($t['active'])));
        $vehicles  = count(array_unique(array_column($transfers, 'vehicle_type')));
        $maxPrice  = !empty($transfers) ? max(array_column($transfers, 'price')) : 0;
        ?>
        <div class="adm-stats-row">
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(0,119,182,.10);color:var(--adm-blue)"><i class="fas fa-shuttle-van"></i></div>
                <div><span class="adm-stat-val"><?= $total ?></span><span class="adm-stat-label">Total transfers</span></div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(46,196,182,.10);color:var(--adm-green)"><i class="fas fa-check-circle"></i></div>
                <div><span class="adm-stat-val"><?= $active ?></span><span class="adm-stat-label">Activos</span></div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(249,199,79,.18);color:#b8860b"><i class="fas fa-car"></i></div>
                <div><span class="adm-stat-val"><?= $vehicles ?></span><span class="adm-stat-label">Tipos vehículo</span></div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(0,180,216,.10);color:var(--adm-teal)"><i class="fas fa-chart-line"></i></div>
                <div><span class="adm-stat-val">US$ <?= number_format($maxPrice, 0) ?></span><span class="adm-stat-label">Precio máximo</span></div>
            </div>
        </div>

        <!-- Toolbar búsqueda -->
        <div class="adm-toolbar">
            <div class="adm-search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" class="adm-search-input" id="admSearch" placeholder="Buscar transfer…">
            </div>
            <select class="adm-filter-select" id="admVehicleFilter">
                <option value="">Todos los vehículos</option>
                <?php foreach (array_unique(array_filter(array_column($transfers, 'vehicle_type'))) as $v): ?>
                    <option value="<?= htmlspecialchars(strtolower($v)) ?>"><?= htmlspecialchars($v) ?></option>
                <?php endforeach; ?>
            </select>
            <select class="adm-filter-select" id="admStatusFilter">
                <option value="">Todos los estados</option>
                <option value="active">Solo activos</option>
            </select>
        </div>

        <!-- Tabla -->
        <div class="adm-table-card">
            <?php if (empty($transfers)): ?>
                <div class="adm-empty">
                    <i class="fas fa-shuttle-van"></i>
                    <h5><?= __('admin.transfers.empty') ?></h5>
                    <p><?= __('admin.transfers.empty_desc') ?></p>
                    <a href="<?= APP_URL ?>/admin/transfers_create" class="adm-new-btn"><i class="fas fa-plus-circle"></i><?= __('admin.transfers.new') ?></a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="adm-table" id="admTable">
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th><?= __('admin.transfers.name') ?></th>
                                <th><?= __('admin.transfers.vehicle') ?></th>
                                <th>Capacidad</th>
                                <th><?= __('admin.transfers.price') ?></th>
                                <th>Estado</th>
                                <th class="text-end"><?= __('common.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody id="admTbody">
                            <?php foreach ($transfers as $t): ?>
                                <tr data-name="<?= htmlspecialchars(strtolower($t['name'] ?? '')) ?>"
                                    data-vehicle="<?= htmlspecialchars(strtolower($t['vehicle_type'] ?? '')) ?>"
                                    data-active="<?= !empty($t['active']) ? 'active' : '' ?>">

                                    <td data-label="Imagen">
                                        <?php if (!empty($t['image'])): ?>
                                            <img src="<?= APP_URL ?>/assets/uploads/transfers/<?= htmlspecialchars($t['image']) ?>" class="adm-td-img" alt="<?= htmlspecialchars($t['name']) ?>">
                                        <?php else: ?>
                                            <div class="adm-td-img-ph"><i class="fas fa-car"></i></div>
                                        <?php endif; ?>
                                    </td>

                                    <td data-label="Nombre">
                                        <p class="adm-exc-name"><?= htmlspecialchars($t['name'] ?? __('common.not_defined')) ?></p>
                                        <span class="adm-exc-loc"><i class="fas fa-location-dot" style="color:var(--adm-teal)"></i><?= htmlspecialchars($t['from_location'] ?? __('admin.transfers.from')) ?> → <?= htmlspecialchars($t['to_location'] ?? __('admin.transfers.to')) ?></span>
                                    </td>

                                    <td data-label="Vehículo">
                                        <span class="adm-vehicle-tag"><i class="fas fa-car"></i><?= htmlspecialchars($t['vehicle_type'] ?? '—') ?></span>
                                    </td>

                                    <td data-label="Capacidad">
                                        <?php if (($t['max_passengers'] ?? 0) > 0): ?>
                                            <span><i class="fas fa-users me-1" style="color:var(--adm-blue)"></i><?= (int)($t['max_passengers'] ?? 0) ?> pasajeros</span>
                                        <?php else: ?>
                                            <span class="text-muted"><i class="fas fa-infinity me-1"></i> Ilimitada</span>
                                        <?php endif; ?>
                                    </td>

                                    <td data-label="Precio"><span class="adm-price">US$ <?= number_format((float)($t['price'] ?? 0), 2) ?></span></td>

                                    <td data-label="Estado">
                                        <span class="adm-badge <?= !empty($t['active']) ? 'adm-badge-active' : 'adm-badge-inactive' ?>">
                                            <i class="fas <?= !empty($t['active']) ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                                            <?= !empty($t['active']) ? __('common.active') : __('common.inactive') ?>
                                        </span>
                                    </td>

                                    <td data-label="Acciones">
                                        <div class="adm-actions">
                                            <a href="<?= APP_URL ?>/admin/transfers_edit/<?= (int)($t['id'] ?? 0) ?>" class="adm-btn-edit" title="Editar"><i class="fas fa-edit"></i></a>
                                            <a href="<?= APP_URL ?>/admin/transfers_delete/<?= (int)($t['id'] ?? 0) ?>" class="adm-btn-del" title="Eliminar" onclick="return confirm('<?= __('admin.transfers.confirm_delete') ?? '¿Eliminar este transfer?' ?>')"><i class="fas fa-trash"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <div id="admNoResults" class="adm-empty" style="display:none">
                    <i class="fas fa-search"></i>
                    <h5>Sin resultados</h5>
                    <p>Ningún transfer coincide con tu búsqueda.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>

<script>
    (function() {
        const search = document.getElementById('admSearch');
        const vehicleFilter = document.getElementById('admVehicleFilter');
        const statusFilter = document.getElementById('admStatusFilter');
        const tbody = document.getElementById('admTbody');
        const noRes = document.getElementById('admNoResults');
        if (!search || !tbody) return;

        function filter() {
            const q = search.value.toLowerCase();
            const vehicle = vehicleFilter?.value || '';
            const status = statusFilter?.value || '';
            let visible = 0;
            tbody.querySelectorAll('tr').forEach(tr => {
                const name = tr.dataset.name || '';
                const trVehicle = tr.dataset.vehicle || '';
                const active = tr.dataset.active || '';
                const okQ = !q || name.includes(q);
                const okVehicle = !vehicle || trVehicle === vehicle;
                const okStatus = !status || (status === 'active' && active === 'active');
                const show = okQ && okVehicle && okStatus;
                tr.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            if (noRes) noRes.style.display = visible === 0 ? 'block' : 'none';
        }

        search.addEventListener('input', filter);
        vehicleFilter?.addEventListener('change', filter);
        statusFilter?.addEventListener('change', filter);
    })();
</script>