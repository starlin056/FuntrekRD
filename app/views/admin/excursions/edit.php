<?php $customCss = ['modules/admin-forms.css']; ?>
<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<div class="adm-page">
    <div class="container-fluid">

        <div class="adm-ph">
            <div>
                <h1><i class="fas fa-pencil-alt me-2"
                        style="color:var(--adm-sand)"></i><?= __('admin.excursions.edit_title') ?></h1>
                <p>Modifica los datos de la excursión y guarda los cambios</p>
            </div>
            <a href="<?= APP_URL ?>/admin/excursions" class="adm-back-btn"><i
                    class="fas fa-arrow-left"></i><?= __('common.back') ?></a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="adm-alert-err"><i class="fas fa-exclamation-triangle"></i><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (empty($excursion) || !isset($excursion['id'])): ?>
            <div class="adm-alert-err"><i class="fas fa-exclamation-triangle"></i><?= __('admin.excursions.not_found') ?>
            </div>
            <?php return; ?>
        <?php endif; ?>

        <?php
        // Preparar galería existente
        $galleryExisting = [];
        if (!empty($excursion['gallery'])) {
            $galleryExisting = is_array($excursion['gallery'])
                ? $excursion['gallery']
                : (json_decode($excursion['gallery'], true) ?: []);
        }
        // Preparar includes y requirements
        $incVal = $excursion['includes'] ?? [];
        if (is_string($incVal))
            $incVal = json_decode($incVal, true) ?: [];
        $reqVal = $excursion['requirements'] ?? [];
        if (is_string($reqVal))
            $reqVal = json_decode($reqVal, true) ?: [];
        ?>

        <form method="POST" action="<?= APP_URL ?>/admin/excursions_edit/<?= (int) $excursion['id'] ?>"
            enctype="multipart/form-data" id="excForm">
            <div class="adm-form-grid">

                <!-- Columna principal -->
                <div>

                    <!-- Info general -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon"
                                style="background:rgba(0,119,182,.10);color:var(--adm-blue)"><i
                                    class="fas fa-info-circle"></i></div>
                            <div>
                                <h5>Información general</h5>
                                <p>Datos principales de la excursión</p>
                            </div>
                        </div>
                        <div class="adm-card-body">
                            <div class="adm-field">
                                <label class="adm-label">Nombre <span class="adm-req">*</span></label>
                                <input type="text" name="name" class="adm-input"
                                    value="<?= htmlspecialchars($excursion['name'] ?? '') ?>" required>
                            </div>
                            <div class="adm-field-row">
                                <div class="adm-field">
                                    <label class="adm-label">Ubicación <span class="adm-req">*</span></label>
                                    <input type="text" name="location" class="adm-input"
                                        value="<?= htmlspecialchars($excursion['location'] ?? '') ?>" required>
                                </div>
                                <div class="adm-field">
                                    <label class="adm-label">Categoría</label>
                                    <input type="text" name="category" class="adm-input"
                                        value="<?= htmlspecialchars($excursion['category'] ?? '') ?>"
                                        placeholder="Aventura, Cultura, Playa…">
                                </div>
                            </div>
                            <div class="adm-field">
                                <label class="adm-label">Descripción</label>
                                <textarea name="description"
                                    class="adm-textarea"><?= htmlspecialchars($excursion['description'] ?? '') ?></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles del servicio -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon"
                                style="background:rgba(0,180,216,.10);color:var(--adm-teal)"><i
                                    class="fas fa-sliders"></i></div>
                            <div>
                                <h5>Detalles del servicio</h5>
                                <p>Precio, duración y capacidad</p>
                            </div>
                        </div>
                        <div class="adm-card-body">
                            <div class="adm-field-row">
                                <div class="adm-field">
                                    <label class="adm-label">Precio ($) <span class="adm-req">*</span></label>
                                    <input type="number" step="0.01" name="price" class="adm-input"
                                        value="<?= number_format((float) ($excursion['price'] ?? 0), 2, '.', '') ?>"
                                        min="0" required>
                                </div>
                                <div class="adm-field">
                                    <label class="adm-label">Tipo de precio <span class="adm-req">*</span></label>
                                    <select class="adm-select" name="price_type" required>
                                        <option value="persona" <?= ($excursion['price_type'] ?? '') === 'persona' ? 'selected' : '' ?>>Precio por Persona</option>
                                        <option value="paquete" <?= ($excursion['price_type'] ?? '') === 'paquete' ? 'selected' : '' ?>>Precio por Paquete (Fijo)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="adm-field">
                                <label class="adm-label">Duración</label>
                                <input type="text" name="duration" class="adm-input"
                                    value="<?= htmlspecialchars($excursion['duration'] ?? '') ?>"
                                    placeholder="Ej: 6 horas">
                            </div>
                            <div class="adm-field">
                                <label class="adm-label">Máximo de personas por grupo</label>
                                <input type="number" name="max_people" class="adm-input"
                                    value="<?= (int) ($excursion['max_people'] ?? 15) ?>" min="1" max="100">
                            </div>
                            <div class="adm-field-row">
                                <div class="adm-field">
                                    <label class="adm-label">Incluye (separado por comas)</label>
                                    <input type="text" name="includes" class="adm-input"
                                        value="<?= htmlspecialchars(implode(', ', array_filter($incVal))) ?>">
                                </div>
                                <div class="adm-field">
                                    <label class="adm-label">Requisitos</label>
                                    <input type="text" name="requirements" class="adm-input"
                                        value="<?= htmlspecialchars(implode(', ', array_filter($reqVal))) ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Columna lateral -->
                <div>

                    <!-- Imagen principal actual -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon"
                                style="background:rgba(46,196,182,.10);color:var(--adm-green)"><i
                                    class="fas fa-image"></i></div>
                            <div>
                                <h5>Imagen principal</h5>
                                <p>Foto de portada</p>
                            </div>
                        </div>
                        <div class="adm-card-body">
                            <?php if (!empty($excursion['image'])): ?>
                                <div class="adm-img-preview mb-3">
                                    <div class="adm-img-prev-item">
                                        <img src="<?= APP_URL ?>/assets/uploads/excursions/<?= htmlspecialchars($excursion['image']) ?>"
                                            alt="Imagen actual"
                                            class="main-img">
                                        <div class="adm-img-prev-label"><i class="fas fa-check-circle me-1"></i>Imagen actual
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="adm-upload-zone" id="mainImgZone"
                                onclick="document.getElementById('mainImgInput').click()">
                                <i class="fas fa-cloud-arrow-up"></i>
                                <p><?= !empty($excursion['image']) ? 'Reemplazar imagen' : 'Subir imagen principal' ?>
                                </p>
                                <small>JPG, PNG, WEBP · Máx. 5 MB</small>
                            </div>
                            <input type="file" id="mainImgInput" name="image" accept="image/*">
                            <div id="mainNewPreview" class="adm-img-preview" style="display:none;margin-top:10px;">
                                <div class="adm-img-prev-item">
                                    <img id="mainNewImg" src="" alt="Nueva imagen" class="main-img">
                                    <div class="adm-img-prev-label">Nueva</div>
                                </div>
                            </div>
                            <div class="adm-change-note" id="mainChangeNote" style="display:none;"><i class="fas fa-info-circle"></i>Nueva imagen seleccionada — se reemplazará al guardar</div>
                        </div>
                    </div>

                    <!-- Galería -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon" style="background:rgba(249,199,79,.18);color:#b8860b"><i
                                    class="fas fa-images"></i></div>
                            <div>
                                <h5>Galería de fotos</h5>
                                <p><?= !empty($galleryExisting) ? count($galleryExisting) . ' foto(s) actualmente' : 'Sin fotos en galería' ?>
                                </p>
                            </div>
                        </div>
                        <div class="adm-card-body">
                            <!-- Galería existente -->
                            <?php if (!empty($galleryExisting)): ?>
                                <div class="adm-label" style="margin-bottom:6px;">Fotos actuales</div>
                                <div class="adm-gallery-existing" id="existingGalleryGrid">
                                    <?php foreach ($galleryExisting as $gi => $gImg): ?>
                                        <div class="adm-gal-item" data-filename="<?= htmlspecialchars($gImg) ?>">
                                            <img src="<?= APP_URL ?>/assets/uploads/excursions/<?= htmlspecialchars($gImg) ?>"
                                                alt="Galería <?= $gi + 1 ?>">
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
                                <div class="adm-upload-zone" id="galleryZone"
                                    onclick="document.getElementById('galleryInput').click()">
                                    <i class="fas fa-folder-plus"></i>
                                    <p>Clic para agregar más fotos</p>
                                    <small>Las nuevas se agregan a las existentes</small>
                                </div>
                                <input type="file" id="galleryInput" name="gallery_files[]" accept="image/*" multiple>
                                <div id="galleryNewPreview" class="adm-img-preview"></div>
                            </div>
                        </div>
                    </div>

                    <!-- Destacada -->
                    <div class="adm-card">
                        <div class="adm-card-body">
                            <div class="adm-switch-wrap">
                                <div class="adm-switch-info">
                                    <strong><i class="fas fa-fire" style="color:#e8a817;margin-right:6px"></i>Excursión
                                        destacada</strong>
                                    <span>Aparece primero con badge "Popular"</span>
                                </div>
                                <label class="adm-switch">
                                    <input type="checkbox" name="featured" value="1" id="featSwitch"
                                        <?= !empty($excursion['featured']) ? 'checked' : '' ?>>
                                    <span class="adm-switch-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Guardar -->
                    <button type="submit" class="adm-submit-btn">
                        <i class="fas fa-save"></i>
                        <?= __('admin.excursions.update_button') ?>
                    </button>

                </div>
            </div>
        </form>

    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>

<script>
    (function () {
        // --- Imagen Principal (sin cambios) ---
        const mainInput = document.getElementById('mainImgInput');
        const mainZone = document.getElementById('mainImgZone');
        const mainPrev = document.getElementById('mainNewPreview');
        const mainImg = document.getElementById('mainNewImg');

        mainInput?.addEventListener('change', function () {
            if (this.files[0]) {
                const r = new FileReader();
                r.onload = e => {
                    mainImg.src = e.target.result;
                    mainPrev.style.display = 'grid';
                    document.getElementById('mainChangeNote').style.display = 'flex';
                    mainZone.innerHTML = '<i class="fas fa-check-circle" style="color:var(--adm-green)"></i><p style="color:var(--adm-green);font-weight:700">Nueva imagen lista</p><small>Clic para cambiar</small>';
                };
                r.readAsDataURL(this.files[0]);
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

        galleryInput?.addEventListener('change', function () {
            Array.from(this.files).forEach(file => {
                const exists = pendingFiles.some(f => f.name === file.name && f.size === file.size);
                if (!exists) pendingFiles.push(file);
            });
            updateGalleryPreview();
        });

        // Eliminar fotos nuevas (de la preview)
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
            existingGrid.addEventListener('click', function (e) {
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

        // --- Drag & Drop (sin cambios) ---
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