<?php $customCss = ['modules/admin-forms.css']; ?>
<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<div class="adm-page">
    <div class="container-fluid">

        <div class="adm-ph">
            <div>
                <h1><i class="fas fa-edit me-2" style="color:var(--adm-teal)"></i><?= __('admin.transfers.edit_title') ?></h1>
                <p>Modifica los datos del transfer y guarda los cambios</p>
            </div>
            <a href="<?= APP_URL ?>/admin/transfers" class="adm-back-btn"><i class="fas fa-arrow-left"></i><?= __('common.back') ?></a>
        </div>

        <?php if (!empty($error)): ?>
            <div class="adm-alert-err"><i class="fas fa-exclamation-triangle"></i><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <?php if (!empty($transfer)): ?>
        <form method="POST" action="<?= APP_URL ?>/admin/transfers_edit/<?= (int)$transfer['id'] ?>" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= (int)$transfer['id'] ?>">
            <div class="adm-form-grid">

                <!-- Columna principal -->
                <div>
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon" style="background:rgba(0,119,182,.10);color:var(--adm-blue)"><i class="fas fa-info-circle"></i></div>
                            <div><h5>Información del transfer</h5><p>Detalles del servicio de transporte</p></div>
                        </div>
                        <div class="adm-card-body">
                            <div class="adm-field-row">
                                <div class="adm-field">
                                    <label class="adm-label"><?= __('admin.transfers.name') ?> <span class="adm-req">*</span></label>
                                    <input type="text" name="name" class="adm-input" value="<?= htmlspecialchars($transfer['name'] ?? '') ?>" required>
                                </div>
                                <div class="adm-field">
                                    <label class="adm-label"><?= __('admin.transfers.vehicle_type') ?></label>
                                    <input type="text" name="vehicle_type" class="adm-input" value="<?= htmlspecialchars($transfer['vehicle_type'] ?? '') ?>">
                                </div>
                            </div>
                            <div class="adm-field-row">
                                <div class="adm-field">
                                    <label class="adm-label"><?= __('admin.transfers.from') ?> <span class="adm-req">*</span></label>
                                    <input type="text" name="from_location" class="adm-input" value="<?= htmlspecialchars($transfer['from_location'] ?? '') ?>" required>
                                </div>
                                <div class="adm-field">
                                    <label class="adm-label"><?= __('admin.transfers.to') ?> <span class="adm-req">*</span></label>
                                    <input type="text" name="to_location" class="adm-input" value="<?= htmlspecialchars($transfer['to_location'] ?? '') ?>" required>
                                </div>
                            </div>
                            <div class="adm-field-row">
                                <div class="adm-field">
                                    <label class="adm-label"><?= __('admin.transfers.max_passengers') ?></label>
                                    <input type="number" name="max_passengers" class="adm-input" value="<?= (int)($transfer['max_passengers'] ?? 0) ?>" min="0">
                                    <div class="adm-input-hint">(0 = Sin límite visible)</div>
                                </div>
                                <div class="adm-field">
                                    <label class="adm-label"><?= __('admin.transfers.price') ?> (USD) <span class="adm-req">*</span></label>
                                    <input type="number" step="0.01" name="price" class="adm-input" value="<?= number_format((float)($transfer['price'] ?? 0), 2, '.', '') ?>" required>
                                </div>
                            </div>
                            <div class="adm-field">
                                <label class="adm-label">Tipo de precio <span class="adm-req">*</span></label>
                                <select class="adm-select" name="price_type" required>
                                    <option value="paquete" <?= ($transfer['price_type'] ?? '') === 'paquete' ? 'selected' : '' ?>>Precio por Vehículo / Paquete (Fijo)</option>
                                    <option value="persona" <?= ($transfer['price_type'] ?? '') === 'persona' ? 'selected' : '' ?>>Precio por Persona</option>
                                </select>
                            </div>
                            <div class="adm-switch-wrap">
                                <div class="adm-switch-info">
                                    <strong><i class="fas fa-power-off me-1"></i>Estado del servicio</strong>
                                    <span>Activo para mostrar en el sitio web</span>
                                </div>
                                <label class="adm-switch">
                                    <input type="checkbox" name="active" value="1" <?= !empty($transfer['active']) ? 'checked' : '' ?>>
                                    <span class="adm-switch-slider"></span>
                                </label>
                            </div>
                            <div class="adm-field">
                                <label class="adm-label"><?= __('admin.transfers.description') ?></label>
                                <textarea name="description" class="adm-textarea" rows="3"><?= htmlspecialchars($transfer['description'] ?? '') ?></textarea>
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
                            <div><h5><?= __('admin.transfers.image') ?></h5><p>Foto de portada del vehículo</p></div>
                        </div>
                        <div class="adm-card-body">
                            <?php if (!empty($transfer['image'])): ?>
                                <div class="adm-field">
                                    <div class="adm-img-preview">
                                        <div class="adm-img-prev-item">
                                            <img src="<?= APP_URL ?>/assets/uploads/transfers/<?= htmlspecialchars($transfer['image']) ?>" alt="Imagen actual" class="main-img">
                                            <span class="adm-img-prev-label">Actual</span>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="adm-field">
                                <label class="adm-label"><?= !empty($transfer['image']) ? __('common.change_image') : __('admin.transfers.image') ?></label>
                                <div class="adm-upload-zone" id="mainImgZone" onclick="document.getElementById('mainImgInput').click()">
                                    <i class="fas fa-cloud-arrow-up"></i>
                                    <p>Clic para <?= !empty($transfer['image']) ? 'reemplazar' : 'subir' ?> imagen</p>
                                    <small>JPG, PNG, WEBP · Máx. 5 MB</small>
                                </div>
                                <input type="file" id="mainImgInput" name="image" accept="image/*" style="display:none">
                                <div id="mainPreview" class="adm-img-preview-wrap" style="display:none">
                                    <div class="adm-img-preview">
                                        <div class="adm-img-prev-item"><img id="mainPreviewImg" src="" alt="Nueva preview" class="main-img"><span class="adm-img-prev-label">Principal</span></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Galería existente -->
                    <?php $gallery = is_array($transfer['gallery']) ? $transfer['gallery'] : []; ?>
                    <div class="adm-card">
                        <div class="adm-card-header">
                            <div class="adm-card-header-icon" style="background:rgba(249,199,79,.18);color:#b8860b"><i class="fas fa-images"></i></div>
                            <div>
                                <h5>Galería de fotos del vehículo</h5>
                                <p><?= count($gallery) ?> foto(s) en la galería</p>
                            </div>
                        </div>
                        <div class="adm-card-body">
                            <?php if (!empty($gallery)): ?>
                                <label class="adm-label">Fotos actuales</label>
                                <div class="adm-gallery-grid">
                                    <?php foreach ($gallery as $gImg): ?>
                                        <div class="adm-gallery-item">
                                            <img src="<?= APP_URL ?>/assets/uploads/transfers/<?= htmlspecialchars($gImg) ?>" alt="Galería">
                                            <input type="checkbox" name="delete_gallery[]" value="<?= htmlspecialchars($gImg) ?>" title="Marcar para eliminar">
                                            <div class="del-overlay"><i class="fas fa-trash" style="color:#fff;font-size:1.1rem;opacity:.9"></i></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <p class="adm-gallery-del-hint"><i class="fas fa-info-circle"></i> Marca la foto para eliminarla al guardar</p>
                            <?php endif; ?>

                            <!-- Nuevas fotos -->
                            <div style="margin-top:<?= !empty($gallery) ? '16px' : '0' ?>">
                                <label class="adm-label">Agregar más fotos</label>
                                <div class="adm-upload-zone" id="galleryZone" onclick="document.getElementById('galleryInput').click()">
                                    <i class="fas fa-folder-plus"></i>
                                    <p>Clic para agregar fotos a la galería</p>
                                    <small>JPG, PNG, WEBP · Máx. 5 MB cada una · Múltiples archivos</small>
                                </div>
                                <input type="file" id="galleryInput" name="gallery_files[]" accept="image/*" multiple style="display:none">
                                <div id="galleryPreview" class="adm-img-preview-wrap" style="display:none;margin-top:12px">
                                    <div class="adm-label" style="margin-bottom:6px">Nuevas fotos seleccionadas</div>
                                    <div class="adm-img-preview" id="galleryPreviewGrid"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="adm-submit-btn">
                        <i class="fas fa-save"></i>
                        <?= __('common.update') ?>
                    </button>
                </div>

            </div>
        </form>
        <?php else: ?>
            <div class="adm-alert-err"><i class="fas fa-exclamation-triangle"></i><?= __('admin.transfers.not_found') ?></div>
            <a href="<?= APP_URL ?>/admin/transfers" class="adm-back-btn">Volver a la lista</a>
        <?php endif; ?>

    </div>
</div>

<script>
(function() {
    const mainInput = document.getElementById('mainImgInput');
    const mainZone  = document.getElementById('mainImgZone');
    const mainPrev  = document.getElementById('mainPreview');
    const mainImg   = document.getElementById('mainPreviewImg');

    mainInput?.addEventListener('change', function() {
        if (this.files[0]) {
            const r = new FileReader();
            r.onload = e => { mainImg.src = e.target.result; mainPrev.style.display = 'block'; mainZone.style.display = 'none'; };
            r.readAsDataURL(this.files[0]);
        }
    });
    const dnd = (zone, input) => {
        zone?.addEventListener('dragover', e => { e.preventDefault(); zone.classList.add('drag-over'); });
        zone?.addEventListener('dragleave', () => zone.classList.remove('drag-over'));
        zone?.addEventListener('drop', e => { e.preventDefault(); zone.classList.remove('drag-over'); input.files = e.dataTransfer.files; input.dispatchEvent(new Event('change', {bubbles:true})); });
    };
    dnd(mainZone, mainInput);

    const galleryInput       = document.getElementById('galleryInput');
    const galleryZone        = document.getElementById('galleryZone');
    const galleryPreview     = document.getElementById('galleryPreview');
    const galleryPreviewGrid = document.getElementById('galleryPreviewGrid');

    galleryInput?.addEventListener('change', function() {
        if (!this.files.length) return;
        galleryPreviewGrid.innerHTML = '';
        Array.from(this.files).forEach((file, idx) => {
            const r = new FileReader();
            r.onload = e => {
                const item = document.createElement('div');
                item.className = 'adm-img-prev-item';
                item.innerHTML = `<img src="${e.target.result}" alt="#${idx+1}"><span class="adm-img-prev-label">#${idx+1}</span>`;
                galleryPreviewGrid.appendChild(item);
            };
            r.readAsDataURL(file);
        });
        galleryPreview.style.display = 'block';
        galleryZone.querySelector('p').textContent = this.files.length + ' foto(s) seleccionada(s)';
    });
    dnd(galleryZone, galleryInput);
})();
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>