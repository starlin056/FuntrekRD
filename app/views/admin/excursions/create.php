<?php $customCss = ['modules/admin-forms.css']; ?>
<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<div class="adm-page">
    <div class="container-fluid">

        <div class="adm-ph">
            <div>
                <h1><i class="fas fa-plus-circle me-2" style="color:var(--adm-teal)"></i><?= __('admin.excursions.create_title') ?></h1>
                <p>Completa los campos para publicar una nueva excursión en el catálogo</p>
            </div>
            <a href="<?= APP_URL ?>/admin/excursions" class="adm-back-btn"><i class="fas fa-arrow-left"></i><?= __('common.back') ?></a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="adm-alert-err"><i class="fas fa-exclamation-triangle"></i><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= APP_URL ?>/admin/excursions_create" enctype="multipart/form-data" id="excForm">
            <div class="adm-form-grid">

                <!-- Columna principal -->
                <div>
                    <!-- Info básica -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon" style="background:rgba(0,119,182,.10);color:var(--adm-blue)"><i class="fas fa-info-circle"></i></div>
                            <div>
                                <h5>Información general</h5>
                                <p>Datos principales de la excursión</p>
                            </div>
                        </div>
                        <div class="adm-card-body">
                            <div class="adm-field">
                                <label class="adm-label">Nombre <span class="adm-req">*</span></label>
                                <input type="text" name="name" class="adm-input" placeholder="Ej: Tour a las Cascadas del Limón" required>
                            </div>
                            <div class="adm-field-row">
                                <div class="adm-field">
                                    <label class="adm-label">Ubicación <span class="adm-req">*</span></label>
                                    <input type="text" name="location" class="adm-input" placeholder="Ej: Samaná" required>
                                </div>
                                <div class="adm-field">
                                    <label class="adm-label">Categoría</label>
                                    <input type="text" name="category" class="adm-input" placeholder="Aventura, Cultura, Playa…">
                                </div>
                            </div>
                            <div class="adm-field">
                                <label class="adm-label">Descripción</label>
                                <textarea name="description" class="adm-textarea" placeholder="Describe la experiencia, los atractivos y qué esperar en la excursión…"></textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Detalles -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon" style="background:rgba(0,180,216,.10);color:var(--adm-teal)"><i class="fas fa-sliders"></i></div>
                            <div>
                                <h5>Detalles del servicio</h5>
                                <p>Precio, duración y capacidad</p>
                            </div>
                        </div>
                        <div class="adm-card-body">
                            <div class="adm-field-row">
                                <div class="adm-field">
                                    <label class="adm-label">Precio ($) <span class="adm-req">*</span></label>
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
                                <label class="adm-label">Duración</label>
                                <input type="text" name="duration" class="adm-input" placeholder="Ej: 6 horas">
                            </div>
                            <div class="adm-field">
                                <label class="adm-label">Máximo de personas por grupo</label>
                                <input type="number" name="max_people" class="adm-input" value="0" min="0" max="100">
                                <div class="adm-input-hint">(0 = Sin límite visible)</div>
                            </div>
                            <div class="adm-field-row">
                                <div class="adm-field">
                                    <label class="adm-label">Incluye (separado por comas)</label>
                                    <input type="text" name="includes" class="adm-input" placeholder="Guía, Transporte, Comida">
                                </div>
                                <div class="adm-field">
                                    <label class="adm-label">Requisitos</label>
                                    <input type="text" name="requirements" class="adm-input" placeholder="Edad mínima, Seguro médico">
                                </div>
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
                                <h5>Imagen principal</h5>
                                <p>Foto de portada de la excursión</p>
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

                    <!-- Galería -->
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon" style="background:rgba(249,199,79,.18);color:#b8860b"><i class="fas fa-images"></i></div>
                            <div>
                                <h5>Galería adicional</h5>
                                <p>Fotos extra para el carrusel</p>
                            </div>
                        </div>
                        <div class="adm-card-body">
                            <div class="adm-upload-zone" id="galleryZone" onclick="document.getElementById('galleryInput').click()">
                                <i class="fas fa-folder-plus"></i>
                                <p>Clic para agregar más fotos</p>
                                <small>Puedes seleccionar varias a la vez</small>
                            </div>
                            <input type="file" id="galleryInput" name="gallery_files[]" accept="image/*" multiple style="display:none">
                            <div id="galleryPreview" class="adm-img-preview" style="margin-top:10px;"></div>
                        </div>
                    </div>

                    <!-- Destacada toggle -->
                    <div class="adm-card">
                        <div class="adm-card-body">
                            <div class="adm-switch-wrap">
                                <div class="adm-switch-info">
                                    <strong><i class="fas fa-fire" style="color:#e8a817;margin-right:6px"></i>Excursión destacada</strong>
                                    <span>Aparece primero y con badge "Popular"</span>
                                </div>
                                <label class="adm-switch">
                                    <input type="checkbox" name="featured" value="1" id="featSwitch">
                                    <span class="adm-switch-slider"></span>
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Publicar -->
                    <button type="submit" class="adm-submit-btn">
                        <i class="fas fa-rocket"></i>
                        <?= __('admin.excursions.create_button') ?>
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
        let pendingFiles = [];

        function updateGalleryPreview() {
            galleryPreview.innerHTML = '';
            if (pendingFiles.length === 0) {
                galleryPreview.style.display = 'none';
                if (galleryZone) galleryZone.querySelector('p').textContent = 'Clic para agregar fotos';
                galleryInput.files = (new DataTransfer()).files; 
                return;
            }

            galleryPreview.style.display = 'grid';
            galleryPreview.style.gridTemplateColumns = 'repeat(auto-fill, minmax(70px, 1fr))';
            galleryPreview.style.gap = '8px';

            const dt = new DataTransfer();

            pendingFiles.forEach((file, idx) => {
                dt.items.add(file);
                const reader = new FileReader();
                reader.onload = e => {
                    const wrap = document.createElement('div');
                    wrap.className = 'adm-img-prev-item';
                    wrap.style.position = 'relative';
                    wrap.innerHTML = `
                        <img src="${e.target.result}" alt="Preview" style="width:100%;aspect-ratio:4/3;object-fit:cover;border-radius:8px;border:1px solid rgba(0,119,182,.15);">
                        <button type="button" class="btn-remove-gal" data-index="${idx}" 
                                style="position:absolute; top:-5px; right:-5px; width:20px; height:20px; border-radius:50%; background:#dc3545; color:#fff; border:none; cursor:pointer; display:flex; align-items:center; justify-content:center; font-size:12px; z-index:10;">
                            &times;
                        </button>
                    `;
                    galleryPreview.appendChild(wrap);
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

        galleryPreview?.addEventListener('click', e => {
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