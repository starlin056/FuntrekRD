<?php $customCss = ['modules/admin-forms.css']; ?>
<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>
<div class="adm-page">
    <div class="container-fluid">

        <div class="adm-ph">
            <div>
                <h1><i class="fas fa-box-open me-2" style="color:var(--adm-teal)"></i><?= __('admin.packages.create_title') ?></h1>
                <p>Completa los campos para crear un nuevo paquete turístico</p>
            </div>
            <a href="<?= APP_URL ?>/admin/packages" class="adm-back-btn"><i class="fas fa-arrow-left"></i><?= __('common.back') ?></a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="adm-alert-err"><i class="fas fa-exclamation-triangle"></i><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/admin/packages_create" enctype="multipart/form-data" id="packageForm">
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
                                <input type="text" name="name" class="adm-input" placeholder="Ej: Paraíso Tropical" required>
                            </div>
                            <div class="adm-field">
                                <label class="adm-label"><?= __('admin.packages.category') ?> <span class="adm-req">*</span></label>
                                <select class="adm-select" name="category" required>
                                    <option value="playa"><?= __('admin.packages.category_beach') ?></option>
                                    <option value="aventura"><?= __('admin.packages.category_adventure') ?></option>
                                    <option value="romantico"><?= __('admin.packages.category_romantic') ?></option>
                                    <option value="familiar"><?= __('admin.packages.category_family') ?></option>
                                    <option value="luxury"><?= __('admin.packages.category_luxury') ?></option>
                                    <option value="cultural"><?= __('admin.packages.category_cultural') ?></option>
                                    <option value="gastronomico"><?= __('admin.packages.category_gastronomic') ?></option>
                                    <option value="naturaleza"><?= __('admin.packages.category_nature') ?></option>
                                    <option value="deporte"><?= __('admin.packages.category_sport') ?></option>
                                    <option value="relax"><?= __('admin.packages.category_relax') ?></option>
                                    <option value="night club"><?= __('admin.packages.category_night_club') ?></option>
                                </select>
                            </div>
                            <div class="adm-field">
                                <label class="adm-label"><?= __('admin.packages.description') ?> <span class="adm-req">*</span></label>
                                <textarea name="description" class="adm-textarea" placeholder="Describe la experiencia, los servicios incluidos y los atractivos del paquete..." required></textarea>
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
                                    <input type="number" step="0.01" name="price" class="adm-input" placeholder="0.00" min="0" required>
                                </div>
                                <div class="adm-field">
                                    <label class="adm-label">Tipo de precio <span class="adm-req">*</span></label>
                                    <select class="adm-select" name="price_type" required>
                                        <option value="persona">Precio por Persona</option>
                                        <option value="paquete">Precio por Paquete (Fijo)</option>
                                    </select>
                                </div>
                            </div>
                            <div class="adm-field">
                                <label class="adm-label"><?= __('admin.packages.discount_price') ?> ($)</label>
                                <input type="number" step="0.01" name="discount_price" class="adm-input" placeholder="0.00" min="0">
                            </div>
                            <div class="adm-field-row">
                                <div class="adm-field">
                                    <label class="adm-label"><?= __('admin.packages.days') ?></label>
                                    <input type="number" name="days" class="adm-input" value="5" min="1">
                                </div>
                                <div class="adm-field">
                                    <label class="adm-label"><?= __('admin.packages.nights') ?></label>
                                    <input type="number" name="nights" class="adm-input" value="4" min="0">
                                </div>
                            </div>
                            <div class="adm-field">
                                <label class="adm-label"><?= __('admin.packages.max_people') ?></label>
                                <input type="number" name="max_people" class="adm-input" value="2" min="1">
                            </div>
                            <div class="adm-field">
                                <label class="adm-label"><?= __('admin.packages.includes') ?></label>
                                <input type="text" name="included" class="adm-input" placeholder="<?= __('admin.packages.includes_placeholder') ?>">
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
                            <div class="adm-upload-zone" id="mainImgZone" onclick="document.getElementById('mainImgInput').click()">
                                <i class="fas fa-cloud-arrow-up"></i>
                                <p>Clic para subir imagen principal</p>
                                <small>JPG, PNG, WEBP · Máx. 5 MB</small>
                            </div>
                            <input type="file" id="mainImgInput" name="image" accept="image/*" style="display:none">
                            <div id="mainPreview" class="adm-img-preview-wrap" style="display:none">
                                <div class="adm-img-preview">
                                    <div class="adm-img-prev-item"><img id="mainPreviewImg" src="" alt="Preview" class="main-img"><span class="adm-img-prev-label">Principal</span></div>
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
                                <p>Agrega fotos adicionales del paquete</p>
                            </div>
                        </div>
                        <div class="adm-card-body">
                            <div class="adm-upload-zone" id="galleryZone" onclick="document.getElementById('galleryInput').click()">
                                <i class="fas fa-folder-plus"></i>
                                <p>Clic para agregar fotos a la galería</p>
                                <small>JPG, PNG, WEBP · Máx. 5 MB cada una · Múltiples archivos</small>
                            </div>
                            <input type="file" id="galleryInput" name="gallery_files[]" accept="image/*" multiple style="display:none">
                            <div id="galleryPreview" class="adm-img-preview-wrap" style="display:none;margin-top:12px">
                                <div class="adm-label" style="margin-bottom:6px">Fotos seleccionadas</div>
                                <div class="adm-img-preview" id="galleryPreviewGrid"></div>
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
                                    <input type="checkbox" name="featured" value="1" id="featSwitch">
                                    <span class="adm-switch-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Botón publicar -->
                    <button type="submit" class="adm-submit-btn">
                        <i class="fas fa-save"></i>
                        <?= __('admin.packages.create_button') ?>
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
                    mainZone.style.display = 'none';
                };
                reader.readAsDataURL(this.files[0]);
            }
        });

        // --- Galería Multi-Selección Acumulativa ---
        const galleryInput = document.getElementById('galleryInput');
        const galleryZone = document.getElementById('galleryZone');
        const galleryPreview = document.getElementById('galleryPreview');
        const galleryPreviewGrid = document.getElementById('galleryPreviewGrid');
        let pendingFiles = [];

        function updateGalleryPreview() {
            galleryPreviewGrid.innerHTML = '';
            if (pendingFiles.length === 0) {
                galleryPreview.style.display = 'none';
                if (galleryZone) galleryZone.querySelector('p').textContent = 'Clic para agregar fotos a la galería';
                galleryInput.files = (new DataTransfer()).files; 
                return;
            }

            galleryPreview.style.display = 'block';
            const dt = new DataTransfer();

            pendingFiles.forEach((file, idx) => {
                dt.items.add(file);
                const reader = new FileReader();
                reader.onload = e => {
                    const wrap = document.createElement('div');
                    wrap.className = 'adm-img-prev-item';
                    wrap.style.position = 'relative';
                    wrap.innerHTML = `
                        <img src="${e.target.result}" alt="Preview">
                        <button type="button" class="btn-remove-gal" data-index="${idx}" 
                                style="position:absolute; top:-5px; right:-5px; width:20px; height:20px; border-radius:50%; background:#dc3545; color:#fff; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:12px; z-index:10;">
                            &times;
                        </button>
                        <span class="adm-img-prev-label">#${idx + 1}</span>
                    `;
                    galleryPreviewGrid.appendChild(wrap);
                };
                reader.readAsDataURL(file);
            });

            galleryInput.files = dt.files;
            if (galleryZone) galleryZone.querySelector('p').textContent = pendingFiles.length + ' foto(s) seleccionada(s)';
        }

        galleryInput?.addEventListener('change', function() {
            Array.from(this.files).forEach(file => {
                const exists = pendingFiles.some(f => f.name === file.name && f.size === file.size);
                if (!exists) pendingFiles.push(file);
            });
            updateGalleryPreview();
        });

        galleryPreviewGrid?.addEventListener('click', e => {
            const delBtn = e.target.closest('.btn-remove-gal');
            if (delBtn) {
                const idx = parseInt(delBtn.dataset.index);
                pendingFiles.splice(idx, 1);
                updateGalleryPreview();
            }
        });

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
