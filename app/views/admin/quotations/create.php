<?php $customCss = ['modules/admin-dashboard.css']; ?>
<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<!-- Choices.js CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/choices.js/public/assets/styles/choices.min.css" />

<style>
    /* ===== VARIABLES Y CONFIGURACIÓN GLOBAL ===== */
    :root {
        --adm-primary: #0a58ca;
        --adm-primary-light: #e6f0ff;
        --adm-secondary: #6c757d;
        --adm-success: #198754;
        --adm-danger: #dc3545;
        --adm-warning: #ffc107;
        --adm-info: #0dcaf0;
        --adm-light: #f8f9fa;
        --adm-dark: #212529;
        --adm-border-radius: 1rem;
        --adm-box-shadow: 0 10px 30px -5px rgba(0, 0, 0, 0.05);
        --adm-transition: all 0.25s ease-in-out;
    }

    .adm-page {
        padding-bottom: 15rem; /* Aumentado significativamente para evitar colisión con el footer */
        min-height: calc(100vh - 100px); /* Asegura que la página ocupe casi todo el alto */
        display: block;
        width: 100%;
        position: relative;
    }

    /* ===== BOTONES Y ENLACES ===== */
    .adm-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.6rem 1.2rem;
        background: white;
        border: 1.5px solid #e9ecef;
        border-radius: 2.5rem;
        color: var(--adm-dark);
        font-weight: 600;
        font-size: 0.9rem;
        text-decoration: none;
        transition: var(--adm-transition);
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.02);
    }

    .adm-back-btn:hover {
        background: var(--adm-light);
        border-color: var(--adm-primary);
        color: var(--adm-primary);
        transform: translateX(-4px);
        box-shadow: 0 4px 10px rgba(13, 110, 253, 0.1);
    }

    /* ===== TARJETAS ===== */
    .card-pro {
        border: none;
        border-radius: var(--adm-border-radius);
        background: #ffffff;
        box-shadow: var(--adm-box-shadow);
        transition: var(--adm-transition);
        margin-bottom: 2rem;
    }

    .card-pro:hover {
        box-shadow: 0 20px 35px -8px rgba(0, 0, 0, 0.08);
    }

    .card-header-pro {
        padding: 1.5rem 1.5rem 1rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.03);
    }

    .card-body-pro {
        padding: 1.5rem;
    }

    /* ===== FORMULARIOS ===== */
    .form-label-pro {
        font-size: 0.7rem;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #5b6c7e;
        margin-bottom: 0.5rem;
        display: block;
    }

    .form-control-pro {
        border: 1.5px solid #eef2f6;
        border-radius: 0.8rem;
        padding: 0.6rem 1rem;
        font-size: 0.95rem;
        transition: var(--adm-transition);
        background-color: #ffffff;
    }

    .form-control-pro:focus {
        border-color: var(--adm-primary);
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.1);
        outline: none;
    }

    /* Ajuste para Choices.js */
    .choices__inner {
        background-color: #fff !important;
        border: 1.5px solid #eef2f6 !important;
        border-radius: 0.8rem !important;
        padding: 0.3rem 0.5rem !important;
        min-height: 45px !important;
    }
    .choices__list--dropdown .choices__item--selectable {
        padding-right: 1.5rem !important;
    }

    /* ===== TABLA DE ITEMS ===== */
    .items-table {
        border-collapse: separate;
        border-spacing: 0 0.5rem;
        margin-bottom: 0;
    }

    .items-table thead th {
        background-color: transparent;
        font-weight: 600;
        font-size: 0.8rem;
        text-transform: uppercase;
        letter-spacing: 0.03em;
        color: #5b6c7e;
        border-bottom: 2px solid #eef2f6;
        padding-bottom: 0.75rem;
    }

    .items-table tbody tr {
        background-color: #ffffff;
        border-radius: 1rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
        transition: var(--adm-transition);
    }

    .items-table tbody tr:hover {
        box-shadow: 0 6px 14px rgba(0, 0, 0, 0.04);
        background-color: #fdfdfe;
    }

    .items-table td {
        padding: 1rem 0.75rem;
        vertical-align: middle;
        border: none;
    }

    .qty-input,
    .price-input {
        border: 1px solid #e9ecef;
        border-radius: 0.6rem;
        padding: 0.5rem;
        text-align: center;
        font-weight: 500;
        transition: var(--adm-transition);
    }

    /* ===== STICKY SUMMARY ===== */
    .sticky-summary {
        position: sticky;
        top: 2rem;
    }

    .tax-card {
        background: linear-gradient(145deg, #ffffff, #f4f9ff);
        border: 1px solid rgba(13, 110, 253, 0.08);
    }

    /* ===== ANIMACIONES ===== */
    @keyframes fadeSlide {
        from {
            opacity: 0;
            transform: translateY(12px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .fade-in {
        animation: fadeSlide 0.35s ease-out forwards;
    }

    /* ===== UTILIDADES ===== */
    .fw-800 {
        font-weight: 800;
    }

    /* ===== SWITCH PERSONALIZADO ===== */
    .form-switch .form-check-input {
        width: 3.2rem;
        height: 1.6rem;
        margin-left: 0;
        cursor: pointer;
        background-color: #dee2e6;
        border: none;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e");
    }

    .form-switch .form-check-input:checked {
        background-color: var(--adm-primary);
        border-color: var(--adm-primary);
    }
</style>

<div class="adm-page">
    <div class="container-xl py-4">
        <!-- Header -->
        <div class="d-flex flex-wrap align-items-center justify-content-between mb-4">
            <div>
                <div class="mb-3">
                    <a href="<?= APP_URL ?>/admin/quotations" class="adm-back-btn">
                        <i class="fas fa-arrow-left"></i> Volver al Listado
                    </a>
                </div>
                <h1 class="display-6 fw-bold mb-1">
                    <i class="fas fa-file-invoice-dollar me-2" style="color: var(--adm-primary)"></i>Nueva Cotización
                </h1>
                <p class="text-secondary-emphasis">Configura los detalles del presupuesto para tu cliente</p>
            </div>
            <div class="mt-3 mt-sm-0">
                <span class="badge bg-primary-subtle text-primary-emphasis py-2 px-4 rounded-pill">
                    <i class="far fa-calendar-alt me-1"></i> Validez 30 días
                </span>
            </div>
        </div>

        <form action="<?= APP_URL ?>/admin/quotations_store" method="POST" id="quoteForm">
            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
            
            <div class="row g-4">
                <!-- COLUMNA IZQUIERDA: CLIENTE Y CONFIG -->
                <div class="col-lg-4">
                    <div class="sticky-summary">
                        <!-- Tarjeta Cliente -->
                        <div class="card-pro mb-4">
                            <div class="card-header-pro d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                    <i class="fas fa-user-circle text-primary fa-xl"></i>
                                </div>
                                <h5 class="mb-0 fw-bold">Perfil del Cliente</h5>
                            </div>
                            <div class="card-body-pro">
                                <div class="mb-4">
                                    <label class="form-label-pro">
                                        <i class="far fa-address-card me-1"></i> Cargar Cliente Existente
                                    </label>
                                    <select class="form-select" id="clientSelect" onchange="fillClientData(this)">
                                        <option value="">-- Nuevo Registro --</option>
                                        <?php if (!empty($clients)): ?>
                                            <?php foreach ($clients as $client): ?>
                                                <option value="<?= $client['id'] ?>" 
                                                        data-name="<?= htmlspecialchars($client['full_name'] ?? '') ?>" 
                                                        data-email="<?= htmlspecialchars($client['email'] ?? '') ?>" 
                                                        data-phone="<?= htmlspecialchars($client['phone'] ?? '') ?>">
                                                    <?= htmlspecialchars($client['full_name'] ?? 'Usuario') ?> (<?= htmlspecialchars($client['email'] ?? '') ?>)
                                                </option>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </select>
                                </div>

                                <hr class="my-4 opacity-25">

                                <div class="row g-3">
                                    <div class="col-12">
                                        <label class="form-label-pro">Número de Referencia</label>
                                        <input type="text" name="quote_number" class="form-control form-control-pro bg-light fw-semibold" 
                                               value="<?= $nextQuoteNumber ?>" readonly>
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label-pro">Nombre Completo <span class="text-danger">*</span></label>
                                        <input type="text" name="customer_name" id="customer_name" class="form-control form-control-pro" 
                                               required placeholder="Ej: Juan Pérez">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label-pro">Correo Electrónico</label>
                                        <input type="email" name="customer_email" id="customer_email" class="form-control form-control-pro" 
                                               placeholder="ejemplo@correo.com">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label-pro">Teléfono de Contacto</label>
                                        <input type="text" name="customer_phone" id="customer_phone" class="form-control form-control-pro" 
                                               placeholder="809-000-0000">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label-pro">Agente Responsable</label>
                                        <input type="text" name="agent_name" class="form-control form-control-pro" 
                                               value="<?= $_SESSION['user_name'] ?? '' ?>">
                                    </div>
                                    <div class="col-12">
                                        <label class="form-label-pro">Fecha Estimada de Viaje</label>
                                        <input type="date" name="travel_date" class="form-control form-control-pro">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta Impuestos -->
                        <div class="card-pro tax-card">
                            <div class="card-body-pro">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <label class="form-label-pro mb-1" for="is_tax_enabled">
                                            <i class="fas fa-percent me-1"></i>Cálculo de ITBIS
                                        </label>
                                        <p class="small text-secondary mb-0">Tasa aplicable: <?= $settings['default_tax_rate'] ?? 18 ?>%</p>
                                    </div>
                                    <div class="form-check form-switch p-0">
                                        <input class="form-check-input" type="checkbox" name="is_tax_enabled" id="is_tax_enabled" 
                                               checked onchange="calculateTotals()">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- COLUMNA DERECHA: SERVICIOS Y NOTAS -->
                <div class="col-lg-8">
                    <div class="card-pro mb-4">
                        <div class="card-header-pro d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 p-2 rounded-3 me-3">
                                    <i class="fas fa-layer-group text-primary fa-lg"></i>
                                </div>
                                <h5 class="mb-0 fw-bold">Desglose de Servicios</h5>
                            </div>
                            <div class="dropdown">
                                <button class="btn btn-primary rounded-pill px-4 py-2 shadow-sm" type="button" data-bs-toggle="dropdown">
                                    <i class="fas fa-plus-circle me-1"></i> Agregar Item
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end shadow border-0 rounded-3 py-2">
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="addItem('package')">
                                        <i class="fas fa-suitcase me-2 text-primary"></i>Paquete Turístico
                                    </a></li>
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="addItem('excursion')">
                                        <i class="fas fa-map-marked-alt me-2 text-success"></i>Excursión
                                    </a></li>
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="addItem('transfer')">
                                        <i class="fas fa-car me-2 text-info"></i>Traslado (Transfer)
                                    </a></li>
                                    <li><hr class="dropdown-divider my-1"></li>
                                    <li><a class="dropdown-item py-2" href="javascript:void(0)" onclick="addItem('custom')">
                                        <i class="fas fa-pen-fancy me-2 text-warning"></i>Item Personalizado
                                    </a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <div class="table-responsive px-3">
                            <table class="table items-table" id="itemsTable">
                                <thead>
                                    <tr>
                                        <th class="ps-3">Descripción del Servicio</th>
                                        <th class="text-center" style="width: 90px;">Cant.</th>
                                        <th class="text-center" style="width: 140px;">Precio Unit.</th>
                                        <th class="text-center" style="width: 140px;">Subtotal</th>
                                        <th class="pe-3 text-end" style="width: 50px;"></th>
                                    </tr>
                                </thead>
                                <tbody id="itemsBody">
                                    <tr id="emptyRow">
                                        <td colspan="5" class="text-center py-5 text-secondary opacity-50">
                                            <i class="fas fa-receipt fa-3x mb-3 d-block opacity-50"></i>
                                            Selecciona un tipo de servicio para comenzar la cotización
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Totales -->
                        <div class="card-body-pro border-top bg-light bg-opacity-25 mt-2">
                            <div class="row justify-content-end">
                                <div class="col-md-6 col-lg-5">
                                    <div class="d-flex justify-content-between mb-2">
                                        <span class="text-secondary fw-semibold text-uppercase small">Subtotal:</span>
                                        <span class="fw-bold fs-5" id="subtotalDisplay">$0.00</span>
                                        <input type="hidden" name="subtotal" id="subtotalInput" value="0">
                                    </div>
                                    <div class="d-flex justify-content-between mb-3 align-items-center">
                                        <span class="text-secondary fw-semibold text-uppercase small" id="taxLabel">
                                            ITBIS (<?= $settings['default_tax_rate'] ?? 18 ?>%):
                                        </span>
                                        <span class="fw-bold fs-5" id="taxDisplay">$0.00</span>
                                        <input type="hidden" name="tax_amount" id="taxInput" value="0">
                                    </div>
                                    <hr class="my-3">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h4 mb-0 fw-800">TOTAL USD</span>
                                        <span class="h4 mb-0 fw-800 text-primary" id="totalDisplay">$0.00</span>
                                        <input type="hidden" name="total_price" id="totalInput" value="0">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Notas y Términos -->
                    <div class="card-pro">
                        <div class="card-header-pro d-flex align-items-center">
                            <div class="bg-info bg-opacity-10 p-2 rounded-3 me-3">
                                <i class="fas fa-info-circle text-info fa-lg"></i>
                            </div>
                            <h5 class="mb-0 fw-bold">Términos, Condiciones y Notas</h5>
                        </div>
                        <div class="card-body-pro">
                            <textarea name="notes" id="notesTextarea" class="form-control form-control-pro" rows="8" 
                                      placeholder="Ej: Políticas de pago, cancelación, notas adicionales para el cliente..."></textarea>
                            
                            <div class="d-flex flex-wrap align-items-center justify-content-between mt-4 pt-3 border-top">
                                <div class="text-secondary small mb-3 mb-sm-0">
                                    <i class="fas fa-shield-alt me-1"></i> 
                                    Precios expresados en Dólares Estadounidenses (USD).
                                </div>
                                <button type="submit" class="btn btn-primary btn-lg px-5 shadow rounded-pill fw-bold">
                                    <i class="fas fa-file-invoice me-2"></i> FINALIZAR Y GUARDAR
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Choices.js JS -->
<script src="https://cdn.jsdelivr.net/npm/choices.js/public/assets/scripts/choices.min.js"></script>

<!-- TEMPLATES Y LÓGICA JS -->
<script>
    const packages = <?= json_encode($packages) ?>;
    const excursions = <?= json_encode($excursions) ?>;
    const transfers = <?= json_encode($transfers) ?>;
    const defaultTaxRate = <?= $settings['default_tax_rate'] ?? 18 ?>;
    let itemCount = 0;

    // Inicializar Choices.js para el select de cliente
    const clientChoices = new Choices('#clientSelect', {
        searchEnabled: true,
        itemSelectText: '',
        placeholder: true,
        placeholderValue: '-- Nuevo Registro --'
    });

    // Términos y condiciones predeterminados
    const defaultTerms = `TÉRMINOS Y CONDICIONES:
- Esta cotización tiene una validez de 30 días a partir de la fecha de emisión.
- Los precios están sujetos a cambios sin previo aviso según la disponibilidad de los prestadores de servicios.
- Para confirmar cualquier reserva, se requiere un depósito del 50% del total.
- El saldo restante debe ser liquidado al menos 15 días antes de la fecha de inicio del servicio.
- Políticas de cancelación: Reembolso del 100% si se cancela con más de 30 días de anticipación.`;

    document.getElementById('notesTextarea').value = defaultTerms;

    function fillClientData(select) {
        const option = select.options[select.selectedIndex];
        if (!option.value) {
            document.getElementById('customer_name').value = "";
            document.getElementById('customer_email').value = "";
            document.getElementById('customer_phone').value = "";
        } else {
            document.getElementById('customer_name').value = option.dataset.name;
            document.getElementById('customer_email').value = option.dataset.email;
            document.getElementById('customer_phone').value = option.dataset.phone;
        }
    }

    // Escuchar el evento change de Choices.js para el cliente
    document.getElementById('clientSelect').addEventListener('change', function(e) {
        const value = e.detail.value;
        const selectedOption = Array.from(this.options).find(opt => opt.value == value);
        if (selectedOption) {
            fillClientData(this);
        }
    });

    function addItem(type) {
        const emptyRow = document.getElementById('emptyRow');
        if (emptyRow) emptyRow.remove();

        itemCount++;
        const tbody = document.getElementById('itemsBody');
        const row = document.createElement('tr');
        row.id = `item-row-${itemCount}`;
        row.classList.add('fade-in');

        let options = '';
        let data = [];
        let icon = '';
        let colorClass = '';

        if (type === 'package') { data = packages; icon = 'suitcase'; colorClass = 'text-primary'; }
        else if (type === 'excursion') { data = excursions; icon = 'map-marked-alt'; colorClass = 'text-success'; }
        else if (type === 'transfer') { data = transfers; icon = 'car'; colorClass = 'text-info'; }
        else { icon = 'pen-fancy'; colorClass = 'text-warning'; }

        const selectId = `item-select-${itemCount}`;

        if (type !== 'custom') {
            options = `<select class="form-select" id="${selectId}" name="items[${itemCount}][id]" onchange="updateItemPrice(this, ${itemCount}, '${type}')">
                <option value="">-- Seleccionar ${type} --</option>
                ${data.map(i => `<option value="${i.id}" data-price="${i.price}" data-name="${i.name}">${i.name} ($${i.price})</option>`).join('')}
            </select>
            <input type="hidden" name="items[${itemCount}][description]" id="desc-${itemCount}">`;
        } else {
            options = `<input type="text" name="items[${itemCount}][description]" class="form-control form-control-pro" placeholder="Descripción del servicio personalizado..." required>`;
        }

        row.innerHTML = `
        <td class="ps-3">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-light border rounded-3 p-2 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="fas fa-${icon} ${colorClass} fa-lg"></i>
                </div>
                <div class="flex-grow-1">${options}</div>
                <input type="hidden" name="items[${itemCount}][type]" value="${type}">
            </div>
        </td>
        <td><input type="number" name="items[${itemCount}][quantity]" class="form-control text-center qty-input" value="1" min="1" onchange="calculateTotals()"></td>
        <td>
           <div class="input-group">
               <span class="input-group-text bg-light border-0 pe-0 text-muted">$</span>
               <input type="number" name="items[${itemCount}][unit_price]" class="form-control border-0 bg-light price-input" value="0" step="0.01" onchange="calculateTotals()">
           </div>
        </td>
        <td class="text-center fw-bold text-dark subtotal-cell">$0.00</td>
        <input type="hidden" name="items[${itemCount}][total]" class="row-total-input">
        <td class="pe-3 text-end">
            <button type="button" class="btn btn-outline-danger border-0 p-2 rounded-circle" onclick="removeItem(${itemCount})">
                <i class="fas fa-trash-alt"></i>
            </button>
        </td>
    `;
        tbody.appendChild(row);

        // Inicializar Choices.js para el nuevo select de item
        if (type !== 'custom') {
            const newItemChoices = new Choices(`#${selectId}`, {
                searchEnabled: true,
                itemSelectText: '',
                placeholder: true,
                placeholderValue: `-- Seleccionar ${type} --`
            });

            document.getElementById(selectId).addEventListener('change', function(e) {
                updateItemPrice(this, itemCount, type);
            });
        }
    }

    function updateItemPrice(select, count, type) {
        const selectedOption = select.options[select.selectedIndex];
        if (!selectedOption || !selectedOption.value) return;

        const price = selectedOption.getAttribute('data-price') || 0;
        const name = selectedOption.getAttribute('data-name') || '';

        const row = document.getElementById(`item-row-${count}`);
        if (!row) return;

        row.querySelector('.price-input').value = price;
        const descInput = row.querySelector(`#desc-${count}`);
        if (descInput) {
            descInput.value = `${type.toUpperCase()}: ${name}`;
        }
        calculateTotals();
    }

    function removeItem(count) {
        document.getElementById(`item-row-${count}`).remove();
        calculateTotals();

        if (document.querySelectorAll('#itemsBody tr:not(#emptyRow)').length === 0) {
            const tbody = document.getElementById('itemsBody');
            tbody.innerHTML = `<tr id="emptyRow"><td colspan="5" class="text-center py-5 text-secondary opacity-50"><i class="fas fa-receipt fa-3x mb-3 d-block opacity-50"></i>Selecciona un tipo de servicio para comenzar la cotización</td></tr>`;
        }
    }

    function calculateTotals() {
        let subtotal = 0;
        document.querySelectorAll('#itemsBody tr:not(#emptyRow)').forEach(row => {
            const qty = parseFloat(row.querySelector('.qty-input').value) || 0;
            const price = parseFloat(row.querySelector('.price-input').value) || 0;
            const rowTotal = qty * price;
            subtotal += rowTotal;

            row.querySelector('.subtotal-cell').textContent = `$${rowTotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
            row.querySelector('.row-total-input').value = rowTotal.toFixed(2);
        });

        const isTaxEnabled = document.getElementById('is_tax_enabled').checked;
        const taxRate = isTaxEnabled ? defaultTaxRate : 0;
        const tax = subtotal * (taxRate / 100);
        const total = subtotal + tax;

        document.getElementById('subtotalDisplay').textContent = `$${subtotal.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        document.getElementById('subtotalInput').value = subtotal.toFixed(2);

        document.getElementById('taxDisplay').textContent = `$${tax.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        document.getElementById('taxDisplay').style.opacity = isTaxEnabled ? "1" : "0.5";
        document.getElementById('taxLabel').style.opacity = isTaxEnabled ? "1" : "0.5";
        document.getElementById('taxInput').value = tax.toFixed(2);

        document.getElementById('totalDisplay').textContent = `$${total.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
        document.getElementById('totalInput').value = total.toFixed(2);
    }
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>