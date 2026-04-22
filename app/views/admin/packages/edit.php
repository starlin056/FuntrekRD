<?php $customCss = ['modules/admin-forms.css']; ?>
<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<div class="adm-page">
    <div class="container-fluid">
        <div class="adm-ph">
            <div>
                <h1><i class="fas fa-edit me-2" style="color:var(--adm-teal)"></i><?= __('admin.packages.edit_title') ?></h1>
                <p>Edita los detalles del paquete turístico</p>
            </div>
            <a href="<?= APP_URL ?>/admin/packages" class="adm-back-btn"><i class="fas fa-arrow-left"></i><?= __('common.back') ?></a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="adm-alert-err"><i class="fas fa-exclamation-triangle"></i><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (empty($package) || !isset($package['id'])): ?>
            <div class="adm-alert-err"><i class="fas fa-exclamation-triangle"></i><?= __('admin.packages.not_found') ?></div>
            <?php return; ?>
        <?php endif; ?>

        <?php
        // Preparar includes para el input
        $incVal = $package['included'] ?? [];
        if (is_string($incVal)) $incVal = json_decode($incVal, true) ?: [];
        // Preparar galería existente
        $galleryExisting = $package['gallery'] ?? [];
        if (is_string($galleryExisting)) $galleryExisting = json_decode($galleryExisting, true) ?: [];
        ?>

        <form method="POST" action="<?= APP_URL ?>/admin/packages_edit/<?= (int)$package['id'] ?>" enctype="multipart/form-data" id="packageForm">
            <div class="adm-form-grid">

                <!-- Columna principal -->
                <div>
                    <!-- Info básica -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon" style="background:rgba(0,119,182,.10);color:var(--adm-blue)"><i class="fas fa-info-circle"></i></div>
                            <div>
                                <h5>Información general</h5>
                                <p>Datos principales del paquete</p>
                            </div>
                        </div>
                        <div class="adm-card-body">
                            <div class="adm-field">
                                <label class="adm-label"><?= __('admin.packages.name') ?> <span class="adm-req">*</span></label>
                                <input type="text" name="name" class="adm-input" value="<?= htmlspecialchars($package['name'] ?? '') ?>" required>
                            </div>
                            <div class="adm-field">
                                <label class="adm-label"><?= __('admin.packages.category') ?> <span class="adm-req">*</span></label>
                                <select class="adm-select" name="category" required>
                                    <?php $cats = ['playa' => 'admin.packages.category_beach', 'aventura' => 'admin.packages.category_adventure', 'romantico' => 'admin.packages.category_romantic', 'familiar' => 'admin.packages.category_family', 'luxury' => 'admin.packages.category_luxury', 'cultural' => 'admin.packages.category_cultural', 'gastronomico' => 'admin.packages.category_gastronomic', 'naturaleza' => 'admin.packages.category_nature', 'deporte' => 'admin.packages.category_sport', 'relax' => 'admin.packages.category_relax']; ?>
                                    <?php foreach ($cats as $val => $key): ?>
                                        <option value="<?= $val ?>" <?= ($package['category'] ?? '') === $val ? 'selected' : '' ?>><?= __($key) ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="adm-field">
                                <label class="adm-label"><?= __('admin.packages.description') ?> <span class="adm-req">*</span></label>
                                <textarea name="description" class="adm-textarea" required><?= htmlspecialchars($package['description'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles del paquete -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon" style="background:rgba(0,180,216,.10);color:var(--adm-teal)"><i class="fas fa-sliders-h"></i></div>
                            <div>
                                <h5>Detalles del servicio</h5>
                                <p>Precios, duración y capacidad</p>
                            </div>
                        </div>
                        <div class="adm-card-body">
                            <div class="adm-field-row">
                                <div class="adm-field">
                                    <label class="adm-label"><?= __('admin.packages.price') ?> ($) <span class="adm-req">*</span></label>
                                    <input type="number" step="0.01" name="price" class="adm-input" value="<?= number_format((float)($package['price'] ?? 0), 2, '.', '') ?>" required>
                                </div>
                                <div class="adm-field">
                                    <label class="adm-label">Tipo de precio <span class="adm-req">*</span></label>
                                    <select class="adm-select" name="price_type" required>
                                        <option value="persona" <?= ($package['price_type'] ?? '') === 'persona' ? 'selected' : '' ?>>Precio por Persona</option>
                                        <option value="paquete" <?= ($package['price_type'] ?? '') === 'paquete' ? 'selected' : '' ?>>Precio por Paquete (Fijo)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="adm-field">
                                <label class="adm-label"><?= __('admin.packages.discount_price') ?> ($)</label>
                                <input type="number" step="0.01" name="discount_price" class="adm-input" value="<?= !empty($package['discount_price']) ? number_format((float)$package['discount_price'], 2, '.', '') : '' ?>">
                            </div>
                            <div class="adm-field-row">
                                <div class="adm-field">
                                    <label class="adm-label"><?= __('admin.packages.days') ?></label>
                                    <input type="number" name="days" class="adm-input" value="<?= (int)($package['days'] ?? 1) ?>" required>
                                </div>
                                <div class="adm-field">
                                    <label class="adm-label"><?= __('admin.packages.nights') ?></label>
                                    <input type="number" name="nights" class="adm-input" value="<?= (int)($package['nights'] ?? 0) ?>" required>
                                </div>
                            </div>
                            <div class="adm-field">
                                <label class="adm-label"><?= __('admin.packages.max_people') ?></label>
                                <input type="number" name="max_people" class="adm-input" value="<?= (int)($package['max_people'] ?? 2) ?>">
                            </div>
                            <div class="adm-field">
                                <label class="adm-label"><?= __('admin.packages.includes') ?></label>
                                <input type="text" name="included" class="adm-input" value="<?= htmlspecialchars(implode(', ', array_filter($incVal))) ?>" placeholder="<?= __('admin.packages.includes_placeholder') ?>">
                                <div class="adm-input-hint">Ej: Desayuno, traslados, tours guiados</div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Columna lateral -->
                <div>
                    <!-- Imagen principal -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon" style="background:rgba(46,196,182,.10);color:var(--adm-green)"><i class="fas fa-image"></i></div>
                            <div>
                                <h5><?= __('admin.packages.image') ?></h5>
                                <p>Foto de portada del paquete</p>
                            </div>
                        </div>
                        <div class="adm-card-body">
                            <?php if (!empty($package['image'])): ?>
                                <div class="adm-img-preview" style="margin-bottom: 16px;">
                                    <div class="adm-img-prev-item">
                                        <img src="<?= APP_URL ?>/assets/uploads/packages/<?= htmlspecialchars($package['image']) ?>" alt="Imagen actual" class="main-img">
                                        <span class="adm-img-prev-label">Actual</span>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="adm-upload-zone" id="mainImgZone" onclick="document.getElementById('mainImgInput').click()">
                                <i class="fas fa-cloud-arrow-up"></i>
                                <p><?= !empty($package['image']) ? 'Reemplazar imagen' : 'Subir imagen principal' ?></p>
                                <small>JPG, PNG, WEBP · Máx. 5 MB</small>
                            </div>
                            <input type="file" id="mainImgInput" name="image" accept="image/*" style="display:none">
                            <div id="mainPreview" class="adm-img-preview-wrap" style="display:none; margin-top:12px;">
                                <div class="adm-img-preview">
                                    <div class="adm-img-prev-item"><img id="mainPreviewImg" src="" alt="Nueva preview" class="main-img"><span class="adm-img-prev-label">Nueva</span></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Galería de fotos -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon" style="background:rgba(249,199,79,.18);color:#b8860b"><i class="fas fa-images"></i></div>
                            <div>
                                <h5>Galería de fotos</h5>
                                <p><?= !empty($galleryExisting) ? count($galleryExisting) . ' foto(s) actualmente' : 'Sin fotos en galería' ?></p>
                            </div>
                        </div>
                        <div class="adm-card-body">
                            <!-- Galería existente -->
                            <?php if (!empty($galleryExisting)): ?>
                                <div class="adm-label" style="margin-bottom:6px;">Fotos actuales</div>
                                <div class="adm-gallery-existing" id="existingGalleryGrid">
                                    <?php foreach ($galleryExisting as $gi => $gImg): ?>
                                        <div class="adm-gal-item" data-filename="<?= htmlspecialchars($gImg) ?>">
                                            <img src="<?= APP_URL ?>/assets/uploads/packages/<?= htmlspecialchars($gImg) ?>" alt="Galería <?= $gi + 1 ?>">
                                            <button type="button" class="adm-gal-del" title="Eliminar foto">
                                                <i class="fas fa-times"></i>
                                            </button>
                                            <div class="adm-gal-num"><?= $gi + 1 ?></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <div id="deleteInputs"></div>
                            <?php endif; ?>

                            <!-- Agregar nuevas -->
                            <div style="margin-top:<?= !empty($galleryExisting) ? '16px' : '0' ?>">
                                <?php if (!empty($galleryExisting)): ?>
                                    <div class="adm-label" style="margin-bottom:6px;">Agregar más fotos</div>
                                <?php endif; ?>
                                <div class="adm-upload-zone" id="galleryZone" onclick="document.getElementById('galleryInput').click()">
                                    <i class="fas fa-folder-plus"></i>
                                    <p>Clic para agregar más fotos</p>
                                    <small>Las nuevas se agregan a las existentes</small>
                                </div>
                                <input type="file" id="galleryInput" name="gallery_files[]" accept="image/*" multiple style="display:none">
                                <div id="galleryNewPreview" class="adm-img-preview" style="display:none; margin-top:8px;"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Destacado toggle -->
                    <div class="adm-card">
                        <div class="adm-card-body">
                            <div class="adm-switch-wrap">
                                <div class="adm-switch-info">
                                    <strong><i class="fas fa-star" style="color:#e8a817;margin-right:6px"></i><?= __('admin.packages.featured_label') ?></strong>
                                    <span>Aparece destacado en la página principal</span>
                                </div>
                                <label class="adm-switch">
                                    <input type="checkbox" name="featured" value="1" <?= !empty($package['featured']) ? 'checked' : '' ?>>
                                    <span class="adm-switch-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Botón actualizar -->
                    <button type="submit" class="adm-submit-btn">
                        <i class="fas fa-save"></i>
                        <?= __('admin.packages.update_button') ?>
                    </button>
                </div>

            </div>
        </form>
    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>

<script>
    (function() {
        // --- Imagen Principal ---
        const mainInput = document.getElementById('mainImgInput');
        const mainZone = document.getElementById('mainImgZone');
        const mainPrev = document.getElementById('mainPreview');
        const mainImg = document.getElementById('mainPreviewImg');

        mainInput?.addEventListener('change', function() {
            if (this.files[0]) {
                const reader = new FileReader();
                reader.onload = e => {
                    mainImg.src = e.target.result;
                    mainPrev.style.display = 'block';
                    mainZone.querySelector('p').textContent = 'Nueva imagen lista';
                    mainZone.querySelector('i').className = 'fas fa-check-circle';
                    mainZone.querySelector('i').style.color = 'var(--adm-green)';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // --- Galería Multi-Selección Acumulativa (Nuevas fotos) ---
        const galleryInput = document.getElementById('galleryInput');
        const galleryZone = document.getElementById('galleryZone');
        const galleryPreview = document.getElementById('galleryNewPreview');
        let pendingFiles = [];

        function updateGalleryPreview() {
            galleryPreview.innerHTML = '';
            if (pendingFiles.length === 0) {
                galleryPreview.style.display = 'none';
                if (galleryZone) galleryZone.querySelector('p').textContent = 'Clic para agregar más fotos';
                galleryInput.files = (new DataTransfer()).files; 
                return;
            }

            galleryPreview.style.display = 'grid';
            const dt = new DataTransfer();

            pendingFiles.forEach((file, idx) => {
                dt.items.add(file);
                const reader = new FileReader();
                reader.onload = e => {
                    const wrap = document.createElement('div');
                    wrap.className = 'adm-gal-item'; 
                    wrap.innerHTML = `
                        <img src="${e.target.result}" alt="Nueva">
                        <button type="button" class="adm-gal-del" style="opacity:1; transform:none;" data-index="${idx}" title="Quitar">
                            <i class="fas fa-times"></i>
                        </button>
                        <div class="adm-gal-num">Nuev.</div>
                    `;
                    galleryPreview.appendChild(wrap);
                };
                reader.readAsDataURL(file);
            });

            galleryInput.files = dt.files;
            if (galleryZone) galleryZone.querySelector('p').textContent = pendingFiles.length + ' foto(s) por subir';
        }

        galleryInput?.addEventListener('change', function() {
            Array.from(this.files).forEach(file => {
                const exists = pendingFiles.some(f => f.name === file.name && f.size === file.size);
                if (!exists) pendingFiles.push(file);
            });
            updateGalleryPreview();
        });

        galleryPreview?.addEventListener('click', e => {
            const delBtn = e.target.closest('.adm-gal-del');
            if (delBtn && delBtn.hasAttribute('data-index')) {
                const idx = parseInt(delBtn.dataset.index);
                pendingFiles.splice(idx, 1);
                updateGalleryPreview();
            }
        });

        // --- 🛡️ GESTIÓN DE ELIMINACIÓN DE FOTOS EXISTENTES ---
        const existingGrid = document.getElementById('existingGalleryGrid');
        const deleteInputs = document.getElementById('deleteInputs');

        if (existingGrid) {
            existingGrid.addEventListener('click', function(e) {
                const delBtn = e.target.closest('.adm-gal-del');
                if (!delBtn) return;

                e.preventDefault();
                e.stopPropagation();

                const galItem = delBtn.closest('.adm-gal-item');
                if (!galItem) return;

                const filename = galItem.getAttribute('data-filename');
                if (!filename) return;

                if (!confirm('¿Estás seguro de que deseas eliminar esta foto de la galería?')) return;

                galItem.style.opacity = '0.3';
                galItem.style.pointerEvents = 'none';
                delBtn.style.display = 'none';

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'delete_gallery[]';
                input.value = filename;
                if (deleteInputs) deleteInputs.appendChild(input);
            });
        }

        // --- Drag & Drop ---
        [mainZone, galleryZone].forEach(zone => {
            if (!zone) return;
            zone.addEventListener('dragover', e => {
                e.preventDefault();
                zone.classList.add('drag-over');
            });
            zone.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
            zone.addEventListener('drop', e => {
                e.preventDefault();
                zone.classList.remove('drag-over');
                if (e.dataTransfer.files.length > 0) {
                    if (zone === mainZone) {
                        mainInput.files = e.dataTransfer.files;
                        mainInput.dispatchEvent(new Event('change'));
                    } else {
                        Array.from(e.dataTransfer.files).forEach(f => {
                            if (f.type.startsWith('image/')) pendingFiles.push(f);
                        });
                        updateGalleryPreview();
                    }
                }
            });
        });
    })();
</script>