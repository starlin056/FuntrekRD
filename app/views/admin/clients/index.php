<?php $customCss = ['modules/admin-listings.css']; ?>
<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<div class="adm-page">
    <div class="container-fluid">

        <!-- Alerta de éxito -->
        <?php if (!empty($_SESSION['success'])): ?>
            <div class="adm-alert adm-alert-success">
                <i class="fas fa-check-circle"></i>
                <?= htmlspecialchars($_SESSION['success']) ?>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <!-- Header -->
        <div class="adm-ph">
            <div class="adm-ph-left">
                <h1><i class="fas fa-users me-2" style="color:var(--adm-teal)"></i>Gestión de Clientes</h1>
                <p>Usuarios registrados en la plataforma</p>
                <a href="<?= APP_URL ?>/admin/dashboard" class="adm-back-btn"><i class="fas fa-arrow-left"></i>Volver al Dashboard</a>
            </div>
        </div>

        <!-- Stats rápidos -->
        <?php
        $total = count($clients);
        $active = count(array_filter($clients, fn($c) => !empty($c['active'])));
        $admins = count(array_filter($clients, fn($c) => ($c['role'] ?? '') === 'admin'));
        ?>
        <div class="adm-stats-row">
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(0,119,182,.10);color:var(--adm-blue)"><i class="fas fa-users"></i></div>
                <div class="adm-stat-info">
                    <h4>Total clientes</h4>
                    <div class="adm-stat-number"><?= $total ?></div>
                </div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(46,196,182,.10);color:var(--adm-green)"><i class="fas fa-check-circle"></i></div>
                <div class="adm-stat-info">
                    <h4>Activos</h4>
                    <div class="adm-stat-number"><?= $active ?></div>
                </div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(249,199,79,.18);color:#b8860b"><i class="fas fa-user-shield"></i></div>
                <div class="adm-stat-info">
                    <h4>Administradores</h4>
                    <div class="adm-stat-number"><?= $admins ?></div>
                </div>
            </div>
        </div>

        <!-- Toolbar de búsqueda -->
        <div class="adm-toolbar">
            <div class="adm-search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" class="adm-search-input" id="admSearch" placeholder="Buscar por nombre, usuario o email...">
            </div>
            <select class="adm-filter-select" id="admRoleFilter">
                <option value="">Todos los roles</option>
                <option value="client">Clientes</option>
                <option value="admin">Administradores</option>
            </select>
            <select class="adm-filter-select" id="admStatusFilter">
                <option value="">Todos los estados</option>
                <option value="active">Activos</option>
                <option value="inactive">Inactivos</option>
            </select>
        </div>

        <!-- Tabla de clientes -->
        <div class="adm-table-card">
            <?php if (empty($clients)): ?>
                <div class="adm-empty">
                    <i class="fas fa-user-slash"></i>
                    <h5>No hay clientes registrados</h5>
                    <p>Los clientes aparecerán aquí cuando se registren en la plataforma.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="adm-table" id="admTable">
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Usuario</th>
                                <th>Email</th>
                                <th>Rol</th>
                                <th>Estado</th>
                                <th class="text-end">Acciones</th>
                            </tr>
                        </thead>
                        <tbody id="admTbody">
                            <?php foreach ($clients as $c): ?>
                                <tr data-name="<?= htmlspecialchars(strtolower($c['full_name'] ?? $c['username'])) ?>"
                                    data-username="<?= htmlspecialchars(strtolower($c['username'])) ?>"
                                    data-email="<?= htmlspecialchars(strtolower($c['email'])) ?>"
                                    data-role="<?= htmlspecialchars($c['role'] ?? 'client') ?>"
                                    data-active="<?= !empty($c['active']) ? 'active' : 'inactive' ?>">
                                    <td data-label="Cliente">
                                        <div class="d-flex align-items-center gap-2">
                                            <span class="adm-user-avatar">
                                                <i class="fas fa-user"></i>
                                            </span>
                                            <span class="adm-user-name">
                                                <?= htmlspecialchars($c['full_name'] ?? $c['username']) ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td data-label="Usuario"><?= htmlspecialchars($c['username']) ?></td>
                                    <td data-label="Email"><?= htmlspecialchars($c['email']) ?></td>
                                    <td data-label="Rol">
                                        <span class="adm-badge adm-badge-role">
                                            <i class="fas <?= ($c['role'] ?? 'client') === 'admin' ? 'fa-user-shield' : 'fa-user' ?>"></i>
                                            <?= ($c['role'] ?? 'client') === 'admin' ? 'Administrador' : 'Cliente' ?>
                                        </span>
                                    </td>
                                    <td data-label="Estado">
                                        <span class="adm-badge <?= !empty($c['active']) ? 'adm-badge-active' : 'adm-badge-inactive' ?>">
                                            <i class="fas <?= !empty($c['active']) ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                                            <?= !empty($c['active']) ? 'Activo' : 'Inactivo' ?>
                                        </span>
                                    </td>
                                    <td data-label="Acciones">
                                        <div class="adm-actions">
                                            <a href="<?= APP_URL ?>/admin/client_view/<?= $c['id'] ?>" class="adm-btn-icon adm-btn-view" title="Ver cliente">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <?php if (!empty($c['active'])): ?>
                                                <a href="<?= APP_URL ?>/admin/client_deactivate/<?= $c['id'] ?>" class="adm-btn-icon adm-btn-deactivate" onclick="return confirm('¿Desactivar este cliente?')" title="Desactivar">
                                                    <i class="fas fa-ban"></i>
                                                </a>
                                            <?php else: ?>
                                                <a href="<?= APP_URL ?>/admin/client_activate/<?= $c['id'] ?>" class="adm-btn-icon adm-btn-activate" onclick="return confirm('¿Reactivar este cliente?')" title="Reactivar">
                                                    <i class="fas fa-check-circle"></i>
                                                </a>
                                            <?php endif; ?>
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
                    <p>Ningún cliente coincide con tu búsqueda.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<script>
    (function() {
        const search = document.getElementById('admSearch');
        const roleF = document.getElementById('admRoleFilter');
        const statusF = document.getElementById('admStatusFilter');
        const tbody = document.getElementById('admTbody');
        const noRes = document.getElementById('admNoResults');
        if (!tbody) return;

        function filter() {
            const q = search?.value.toLowerCase() || '';
            const role = roleF?.value || '';
            const status = statusF?.value || '';
            let visible = 0;
            tbody.querySelectorAll('tr').forEach(tr => {
                const name = tr.dataset.name || '';
                const username = tr.dataset.username || '';
                const email = tr.dataset.email || '';
                const trRole = tr.dataset.role || '';
                const trStatus = tr.dataset.active || '';
                const okQ = !q || name.includes(q) || username.includes(q) || email.includes(q);
                const okRole = !role || trRole === role;
                const okStatus = !status || trStatus === status;
                const show = okQ && okRole && okStatus;
                tr.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            if (noRes) noRes.style.display = visible === 0 ? 'block' : 'none';
        }

        if (search) search.addEventListener('input', filter);
        if (roleF) roleF.addEventListener('change', filter);
        if (statusF) statusF.addEventListener('change', filter);
    })();
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>