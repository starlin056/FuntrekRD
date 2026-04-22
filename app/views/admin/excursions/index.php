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
                <h1><i class="fas fa-mountain-sun me-2" style="color:var(--adm-teal)"></i><?= __('admin.excursions.title') ?></h1>
                <p>Gestiona el catálogo de excursiones y experiencias turísticas</p>
                <a href="<?= APP_URL ?>/admin/dashboard" class="adm-back-btn"><i class="fas fa-arrow-left"></i><?= __('common.back') ?></a>
            </div>
            <a href="<?= APP_URL ?>/admin/excursions_create" class="adm-new-btn"><i class="fas fa-plus-circle"></i><?= __('admin.excursions.new') ?></a>
        </div>

        <!-- Stats rápidos (incluye inactivas) -->
        <?php
        $total    = count($excursions);
        $active   = count(array_filter($excursions, fn($e) => !empty($e['active'])));
        $inactive = $total - $active;
        $feat     = count(array_filter($excursions, fn($e) => !empty($e['featured'])));
        $cats     = count(array_unique(array_column($excursions, 'category')));
        ?>
        <div class="adm-stats-row">
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(0,119,182,.10);color:var(--adm-blue)"><i class="fas fa-mountain-sun"></i></div>
                <div><span class="adm-stat-val"><?= $total ?></span><span class="adm-stat-label">Total</span></div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(46,196,182,.10);color:var(--adm-green)"><i class="fas fa-check-circle"></i></div>
                <div><span class="adm-stat-val"><?= $active ?></span><span class="adm-stat-label">Activas</span></div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(110,143,165,.10);color:var(--adm-muted)"><i class="fas fa-ban"></i></div>
                <div><span class="adm-stat-val"><?= $inactive ?></span><span class="adm-stat-label">Inactivas</span></div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(249,199,79,.18);color:#b8860b"><i class="fas fa-fire"></i></div>
                <div><span class="adm-stat-val"><?= $feat ?></span><span class="adm-stat-label">Destacadas</span></div>
            </div>
            <div class="adm-stat-card">
                <div class="adm-stat-icon" style="background:rgba(0,180,216,.10);color:var(--adm-teal)"><i class="fas fa-tags"></i></div>
                <div><span class="adm-stat-val"><?= $cats ?></span><span class="adm-stat-label">Categorías</span></div>
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
                <?php foreach (array_unique(array_filter(array_column($excursions, 'category'))) as $cat): ?>
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
            <?php if (empty($excursions)): ?>
                <div class="adm-empty">
                    <i class="fas fa-mountain"></i>
                    <h5><?= __('admin.excursions.empty') ?></h5>
                    <p>Comienza creando la primera excursión del catálogo.</p>
                    <a href="<?= APP_URL ?>/admin/excursions_create" class="adm-new-btn"><i class="fas fa-plus-circle"></i>Crear excursión</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="adm-table" id="admTable">
                        <thead>
                            <tr>
                                <th>Imagen</th>
                                <th><?= __('admin.excursions.name') ?></th>
                                <th><?= __('admin.excursions.category') ?></th>
                                <th><?= __('admin.excursions.price') ?></th>
                                <th>Duración</th>
                                <th>Estado</th>
                                <th class="text-end"><?= __('common.actions') ?></th>
                            </tr>
                        </thead>
                        <tbody id="admTbody">
                            <?php foreach ($excursions as $e): ?>
                                <tr data-name="<?= htmlspecialchars(strtolower($e['name'])) ?>"
                                    data-cat="<?= htmlspecialchars(strtolower($e['category'] ?? '')) ?>"
                                    data-active="<?= !empty($e['active']) ? 'active' : 'inactive' ?>"
                                    data-feat="<?= !empty($e['featured']) ? 'featured' : '' ?>">

                                    <td data-label="Imagen">
                                        <?php if (!empty($e['image'])): ?>
                                            <img src="<?= APP_URL ?>/assets/uploads/excursions/<?= htmlspecialchars($e['image']) ?>" class="adm-td-img" alt="<?= htmlspecialchars($e['name']) ?>">
                                        <?php else: ?>
                                            <div class="adm-td-img-ph"><i class="fas fa-mountain"></i></div>
                                        <?php endif; ?>
                                    </td>

                                    <td data-label="Nombre">
                                        <p class="adm-exc-name"><?= htmlspecialchars($e['name']) ?></p>
                                        <span class="adm-exc-loc"><i class="fas fa-map-marker-alt" style="color:var(--adm-teal)"></i><?= htmlspecialchars($e['location']) ?></span>
                                        <?php if (!empty($e['featured'])): ?><span class="adm-badge adm-badge-feat ms-1"><i class="fas fa-fire"></i>Popular</span><?php endif; ?>
                                    </td>

                                    <td data-label="Categoría">
                                        <?php if (!empty($e['category'])): ?>
                                            <span class="adm-cat-tag"><i class="fas fa-tag"></i><?= htmlspecialchars($e['category']) ?></span>
                                        <?php else: ?><span class="text-muted">—</span><?php endif; ?>
                                    </td>

                                    <td data-label="Precio"><span class="adm-price">$<?= number_format((float)$e['price'], 2) ?></span></td>

                                    <td data-label="Duración">
                                        <?php if (!empty($e['duration'])): ?>
                                            <span style="font-size:.84rem;color:var(--adm-muted);display:flex;align-items:center;gap:5px;"><i class="fas fa-clock" style="color:var(--adm-blue)"></i><?= htmlspecialchars($e['duration']) ?></span>
                                            <?php else: ?>—<?php endif; ?>
                                    </td>

                                    <td data-label="Estado">
                                        <span class="adm-badge <?= !empty($e['active']) ? 'adm-badge-active' : 'adm-badge-inactive' ?>">
                                            <i class="fas <?= !empty($e['active']) ? 'fa-check-circle' : 'fa-times-circle' ?>"></i>
                                            <?= !empty($e['active']) ? __('common.active') : __('common.inactive') ?>
                                        </span>
                                    </td>

                                    <td data-label="Acciones">
                                        <div class="adm-actions">
                                            <a href="<?= APP_URL ?>/admin/excursions_edit/<?= $e['id'] ?>" class="adm-btn-edit" title="Editar"><i class="fas fa-edit"></i></a>
                                            <?php if (!empty($e['active'])): ?>
                                                <!-- Desactivar -->
                                                <a href="<?= APP_URL ?>/admin/excursions_delete/<?= $e['id'] ?>" class="adm-btn-del" title="Desactivar" onclick="return confirm('¿Seguro que quieres desactivar esta excursión?')"><i class="fas fa-trash"></i></a>
                                            <?php else: ?>
                                                <!-- Reactivar -->
                                                <a href="<?= APP_URL ?>/admin/excursions_restore/<?= $e['id'] ?>" class="adm-btn-restore" title="Reactivar" onclick="return confirm('¿Seguro que quieres reactivar esta excursión?')"><i class="fas fa-undo-alt"></i></a>
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

        search.addEventListener('input', filter);
        catF?.addEventListener('change', filter);
        stF?.addEventListener('change', filter);
    })();
</script>