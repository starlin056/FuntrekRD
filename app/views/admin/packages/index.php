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
                <h1><i class="fas fa-mountain-sun me-2" style="color:var(--adm-teal)"></i><?= __('admin.packages.title') ?></h1>
                <p>Gestiona el catálogo de paquetes turísticos</p>
                <a href="<?= APP_URL ?>/admin/dashboard" class="adm-back-btn"><i class="fas fa-arrow-left"></i><?= __('common.back') ?></a>
            </div>
            <a href="<?= APP_URL ?>/admin/packages_create" class="adm-new-btn"><i class="fas fa-plus-circle"></i><?= __('admin.packages.new') ?></a>
        </div>

        <!-- Stats rápidos -->
        <?php
        $total    = count($packages);
        $active   = count(array_filter($packages, fn($p) => (int)$p['active'] === 1));
        $inactive = count(array_filter($packages, fn($p) => (int)$p['active'] === 0));
        $feat     = count(array_filter($packages, fn($p) => (int)$p['featured'] === 1));
        $cats     = count(array_unique(array_column($packages, 'category')));
        ?>


        <div class="adm-stats-row">
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(0,119,182,.10);color:var(--adm-blue)">
                    <i class="fas fa-mountain-sun"></i>
                </div>
                <div><span class="adm-stat-val"><?= $total ?></span><span class="adm-stat-label">Total</span></div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(46,196,182,.10);color:var(--adm-green)">
                    <i class="fas fa-check-circle"></i>
                </div>
                <div><span class="adm-stat-val"><?= $active ?></span><span class="adm-stat-label">Activas</span></div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(249,199,79,.18);color:#b8860b">
                    <i class="fas fa-fire"></i>
                </div>
                <div><span class="adm-stat-val"><?= $feat ?></span><span class="adm-stat-label">Destacadas</span></div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(0,180,216,.10);color:var(--adm-teal)">
                    <i class="fas fa-tags"></i>
                </div>
                <div><span class="adm-stat-val"><?= $cats ?></span><span class="adm-stat-label">Categorías</span></div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(255,0,0,.10);color:#dc3545">
                    <i class="fas fa-ban"></i>
                </div>
                <div><span class="adm-stat-val"><?= $inactive ?></span><span class="adm-stat-label">Inactivas</span></div>
            </div>
        </div>


        <!-- Toolbar búsqueda -->
        <div class="adm-toolbar">
            <div class="adm-search-wrap">
                <i class="fas fa-search"></i>
                <input type="text" class="adm-search-input" id="admSearch" placeholder="Buscar excursión…">
            </div>
            <select class="adm-filter-select" id="admCatFilter">
                <option value="">Todas las categorías</option>
                <?php foreach (array_unique(array_filter(array_column($packages, 'category'))) as $cat): ?>
                    <option value="<?= htmlspecialchars(strtolower($cat)) ?>"><?= htmlspecialchars($cat) ?></option>
                <?php endforeach; ?>
            </select>
            <select class="adm-filter-select" id="admStatusFilter">
                <option value="">Todos los estados</option>
                <option value="active">Solo activas</option>
                <option value="inactive">Solo inactivas</option>
                <option value="featured">Solo destacadas</option>
            </select>
        </div>
        <!-- Tabla -->
        <div class="adm-table-card">
            <?php if (empty($packages)): ?>
                <div class="adm-empty">
                    <i class="fas fa-mountain"></i>
                    <h5><?= __('admin.packages.empty') ?></h5>
                    <p>Comienza creando el primer paquete del catálogo.</p>
                    <a href="<?= APP_URL ?>/admin/packages_create" class="adm-new-btn">
                        <i class="fas fa-plus-circle"></i>Crear paquete
                    </a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="adm-table" id="admTable">
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th><?= __('admin.packages.name') ?></th>
                                <th><?= __('admin.packages.category') ?></th>
                                <th><?= __('admin.packages.price') ?></th>
                                <th>Duración</th>
                                <th>Estado</th>
                                <th class="text-end"><?= __('common.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody id="admTbody">
                            <?php foreach ($packages as $p): ?>
                                <tr data-name="<?= htmlspecialchars(strtolower($p['name'] ?? '')) ?>"
                                    data-cat="<?= htmlspecialchars(strtolower($p['category'] ?? '')) ?>"
                                    data-active="<?= !empty($p['active']) ? 'active' : 'inactive' ?>"
                                    data-feat="<?= !empty($p['featured']) ? 'featured' : '' ?>">

                                    <td data-label="Imagen">
                                        <?php if (!empty($p['image'])): ?>
                                            <img src="<?= APP_URL ?>/assets/uploads/packages/<?= htmlspecialchars($p['image']) ?>"
                                                class="adm-td-img"
                                                alt="<?= htmlspecialchars($p['name'] ?? '') ?>">
                                        <?php else: ?>
                                            <div class="adm-td-img-ph"><i class="fas fa-suitcase"></i></div>
                                        <?php endif; ?>
                                    </td>

                                    <td data-label="Nombre">
                                        <p class="adm-exc-name"><?= htmlspecialchars($p['name'] ?? '') ?></p>
                                        <span class="adm-exc-loc">
                                            <i class="fas fa-map-marker-alt" style="color:var(--adm-teal)"></i>
                                            <?= htmlspecialchars($p['location'] ?? '—') ?>
                                        </span>
                                        <?php if (!empty($p['featured'])): ?>
                                            <span class="adm-badge adm-badge-feat ms-1">
                                                <i class="fas fa-crown"></i>Destacado
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td data-label="Categoría">
                                        <?php if (!empty($p['category'])): ?>
                                            <span class="adm-cat-tag">
                                                <i class="fas fa-tag"></i><?= htmlspecialchars($p['category']) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="text-muted">—</span>
                                        <?php endif; ?>
                                    </td>

                                    <td data-label="Precio">
                                        <?php if (!empty($p['discount_price']) && $p['discount_price'] < $p['price']): ?>
                                            <span class="adm-price-old"
                                                style="text-decoration:line-through;color:var(--adm-muted)">
                                                $<?= number_format((float)$p['price'], 2) ?>
                                            </span><br>
                                            <span class="adm-price"
                                                style="color:var(--adm-green);font-weight:700">
                                                $<?= number_format((float)$p['discount_price'], 2) ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="adm-price">$<?= number_format((float)$p['price'], 2) ?></span>
                                        <?php endif; ?>
                                    </td>

                                    <td data-label="Duración">
                                        <?php if (!empty($p['days']) || !empty($p['nights'])): ?>
                                            <span style="font-size:.84rem;color:var(--adm-muted);display:flex;align-items:center;gap:5px;">
                                                <i class="fas fa-calendar-alt" style="color:var(--adm-blue)"></i>
                                                <?= (int)($p['days'] ?? 0) ?>d / <?= (int)($p['nights'] ?? 0) ?>n
                                            </span>
                                        <?php else: ?>
                                            —
                                        <?php endif; ?>
                                    </td>

                                    <td data-label="Estado">
                                        <span class="adm-badge <?= !empty($p['active']) ? 'adm-badge-active' : 'adm-badge-inactive' ?>">
                                            <i class="fas <?= !empty($p['active']) ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                                            <?= !empty($p['active']) ? __('common.active') : __('common.inactive') ?>
                                        </span>
                                    </td>

                                    <td data-label="Acciones">
                                        <div class="adm-actions">
                                            <a href="<?= APP_URL ?>/admin/packages_edit/<?= $p['id'] ?>"
                                                class="adm-btn-edit" title="Editar">
                                                <i class="fas fa-edit"></i>
                                            </a>

                                            <?php if (!empty($p['active'])): ?>
                                                <!-- Botón para desactivar -->
                                                <!-- En lugar de packages_deactivate, usa packages_delete -->
                                                <a href="<?= APP_URL ?>/admin/packages_delete/<?= $p['id'] ?>"
                                                    class="adm-btn-del"
                                                    title="Desactivar"
                                                    onclick="return confirm('¿Seguro que quieres desactivar este paquete?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            <?php else: ?>
                                                <!-- Botón para reactivar -->
                                                <a href="<?= APP_URL ?>/admin/packages_restore/<?= $p['id'] ?>"
                                                    class="adm-btn-restore"
                                                    title="Reactivar"
                                                    onclick="return confirm('¿Seguro que quieres reactivar este paquete?')">
                                                    <i class="fas fa-undo"></i>
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
                    <p>Ninguna excursión coincide con tu búsqueda.</p>
                </div>
            <?php endif; ?>
        </div>

    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>

<script>
    (function() {
        const search = document.getElementById('admSearch');
        const catF = document.getElementById('admCatFilter');
        const stF = document.getElementById('admStatusFilter');
        const tbody = document.getElementById('admTbody');
        const noRes = document.getElementById('admNoResults');
        if (!search || !tbody) return;

        function filter() {
            const q = search.value.toLowerCase();
            const cat = catF?.value || '';
            const st = stF?.value || '';
            let visible = 0;

            tbody.querySelectorAll('tr').forEach(tr => {
                const name = tr.dataset.name || '';
                const trCat = tr.dataset.cat || '';
                const active = tr.dataset.active || '';
                const feat = tr.dataset.feat || '';

                // Condiciones de filtrado
                const okQ = !q || name.includes(q);
                const okCat = !cat || trCat === cat;
                const okSt = !st ||
                    (st === 'active' && active === 'active') ||
                    (st === 'inactive' && active === 'inactive') ||
                    (st === 'featured' && feat === 'featured');

                const show = okQ && okCat && okSt;
                tr.style.display = show ? '' : 'none';
                if (show) visible++;
            });

            if (noRes) noRes.style.display = visible === 0 ? 'block' : 'none';
        }

        // Eventos
        search.addEventListener('input', filter);
        catF?.addEventListener('change', filter);
        stF?.addEventListener('change', filter);
    })();
</script>
