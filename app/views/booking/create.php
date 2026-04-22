<?php $customCss = ['modules/client.css']; ?>
<?php
// app/views/booking/create.php
$basePrice = (float) ((($item['discount_price'] ?? 0) > 0) ? $item['discount_price'] : $item['price']);
$priceType = $item['price_type'] ?? ($type === 'transfer' ? 'paquete' : 'persona');
$isTransfer = ($type === 'transfer');
$isPackage = ($type === 'package');
$isExcursion = ($type === 'excursion');
?>
<?php require APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<div class="bk-wrapper">
    <div class="container">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb" style="font-family:var(--fh);font-size:.77rem;">
                <li class="breadcrumb-item"><a href="<?= APP_URL ?>/" style="color:var(--c-ocean)">Inicio</a></li>
                <?php if ($isTransfer): ?>
                    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/transfers"
                            style="color:var(--c-ocean)">Transfers</a></li>
                <?php elseif ($isPackage): ?>
                    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/paquetes" style="color:var(--c-ocean)">Paquetes</a>
                    </li>
                <?php else: ?>
                    <li class="breadcrumb-item"><a href="<?= APP_URL ?>/excursions"
                            style="color:var(--c-ocean)">Excursiones</a></li>
                <?php endif; ?>
                <li class="breadcrumb-item active" style="color:var(--c-muted)">Reservar</li>
            </ol>
        </nav>

        <?php if (!empty($_SESSION['error'])): ?>
            <div class="alert alert-danger rounded-3 mb-4">
                <?= htmlspecialchars($_SESSION['error']) ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <div class="bk-grid">

            <!-- ======= FORMULARIO ======= -->
            <div class="bk-card">
                <h1 class="bk-title">Completar reserva</h1>
                <p class="bk-sub">Completa los datos del viajero y elige tu forma de pago.</p>

                <form method="POST" action="<?= APP_URL ?>/reserva/process" id="bookingForm">

                    <!-- Campos ocultos -->
                    <input type="hidden" name="item_type" value="<?= htmlspecialchars($type) ?>">
                    <input type="hidden" name="item_id" value="<?= (int) $item['id'] ?>">
                    <input type="hidden" name="total_price" id="totalPriceField"
                        value="<?= number_format($basePrice, 2, '.', '') ?>">
                    <input type="hidden" name="payment_method" id="paymentMethodField" value="paypal">

                    <!-- DATOS DEL VIAJERO -->
                    <div class="bk-section-label"><i class="fas fa-user"></i> Datos del viajero</div>

                    <div class="bk-group">
                        <label class="bk-label">Nombre completo <span class="req">*</span></label>
                        <input type="text" name="name" class="bk-input" required
                            value="<?= htmlspecialchars($userData['name'] ?? '') ?>" placeholder="Tu nombre completo">
                    </div>

                    <div class="bk-row-2">
                        <div class="bk-group">
                            <label class="bk-label">Correo electrónico <span class="req">*</span></label>
                            <input type="email" name="email" class="bk-input" required
                                value="<?= htmlspecialchars($userData['email'] ?? '') ?>"
                                placeholder="correo@ejemplo.com">
                        </div>
                        <!-- ✅ TELÉFONO AHORA OBLIGATORIO -->
                        <div class="bk-group">
                            <label class="bk-label">Teléfono / WhatsApp <span class="req">*</span></label>
                            <input type="tel" name="phone" class="bk-input" required pattern="^[\d\+\-\s\(\)]{7,20}$"
                                title="Ingresa un teléfono válido (mínimo 7 dígitos). Ej: +1 829 555 1234"
                                value="<?= htmlspecialchars($userData['phone'] ?? '') ?>" placeholder="+1 829 555 1234">
                            <small style="font-size:.72rem;color:var(--c-muted);margin-top:3px;">
                                <i class="fas fa-info-circle me-1"></i>Necesario para confirmaciones y emergencias
                            </small>
                        </div>
                    </div>

                    <hr class="bk-divider">

                    <!-- DETALLES DEL VIAJE -->
                    <div class="bk-section-label"><i class="fas fa-calendar-alt"></i> Detalles del viaje</div>

                    <div class="bk-row-3">
                        <div class="bk-group">
                            <label class="bk-label">Fecha del viaje <span class="req">*</span></label>
                            <input type="date" name="travel_date" id="travelDate" class="bk-input" required
                                min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="bk-group">
                            <label class="bk-label">Adultos</label>
                            <select name="adults" id="adultsSelect" class="bk-select">
                                <?php for ($i = 1; $i <= 10; $i++): ?>
                                    <option value="<?= $i ?>" <?= (isset($adults) && $i == $adults) ? 'selected' : '' ?>>
                                        <?= $i ?> adulto<?= $i > 1 ? 's' : '' ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="bk-group">
                            <label class="bk-label">Niños (0–12 años)</label>
                            <select name="children" id="childrenSelect" class="bk-select">
                                <?php for ($i = 0; $i <= 10; $i++): ?>
                                    <option value="<?= $i ?>" <?= (isset($children) && $i == $children) ? 'selected' : '' ?>>
                                        <?= $i ?> niño<?= $i > 1 ? 's' : '' ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                    </div>

                    <div class="bk-group">
                        <label class="bk-label">Peticiones especiales</label>
                        <textarea name="requests" class="bk-textarea"
                            placeholder="Número de vuelo, silla de bebé, necesidades especiales…"></textarea>
                    </div>

                    <hr class="bk-divider">

                    <!-- MÉTODO DE PAGO -->
                    <div class="bk-section-label"><i class="fas fa-credit-card"></i> Método de pago</div>

                    <div class="bk-pay-grid">
                        <!-- Efectivo -->
                        <label class="bk-pay-opt" data-method="cash">
                            <input type="radio" name="pay_method_ui" value="cash" id="payCash">
                            <div class="bk-pay-label">
                                <div class="bk-pay-icon"><i class="fas fa-money-bill-wave"></i></div>
                                <span class="bk-pay-name">Efectivo</span>
                                <span class="bk-pay-desc">Paga al conductor el día del servicio</span>
                            </div>
                        </label>

                        <!-- PayPal -->
                        <label class="bk-pay-opt" data-method="paypal">
                            <input type="radio" name="pay_method_ui" value="paypal" id="payPaypal" checked>
                            <div class="bk-pay-label">
                                <div class="bk-pay-icon"><i class="fab fa-paypal"></i></div>
                                <span class="bk-pay-name">PayPal</span>
                                <span class="bk-pay-desc">Pago seguro en línea</span>
                            </div>
                        </label>
                    </div>

                    <!-- Avisos contextales -->
                    <div class="bk-notice bk-notice-cash" id="noticeCash">
                        <i class="fas fa-info-circle"></i>
                        <span>
                            <strong>Pago en efectivo:</strong> Tu reserva quedará <em>pendiente de confirmación</em>.
                            Un agente la revisará y te contactará. El pago se realiza al conductor o representante el
                            día del servicio.
                        </span>
                    </div>
                    <div class="bk-notice bk-notice-paypal active" id="noticePaypal">
                        <i class="fab fa-paypal"></i>
                        <span>
                            <strong>PayPal:</strong> Serás redirigido de forma segura a PayPal. La reserva quedará
                            <em>pendiente de confirmación</em> por nuestro equipo tras verificar el pago.
                        </span>
                    </div>

                    <!-- Botón dinámico -->
                    <button type="submit" class="bk-btn bk-btn-paypal" id="submitBtn">
                        <i class="fab fa-paypal"></i> Continuar a PayPal
                    </button>

                </form>
            </div>

            <!-- ======= SIDEBAR ======= -->
            <aside class="bk-sidebar">

                <div class="bk-item-card">
                    <?php
                    $imgPath = '';
                    if ($isTransfer && !empty($item['image']))
                        $imgPath = APP_URL . '/assets/uploads/transfers/' . $item['image'];
                    if ($isPackage && !empty($item['image']))
                        $imgPath = APP_URL . '/assets/uploads/packages/' . $item['image'];
                    if ($isExcursion && !empty($item['image']))
                        $imgPath = APP_URL . '/assets/uploads/excursions/' . $item['image'];
                    ?>
                    <?php if ($imgPath): ?>
                        <img src="<?= htmlspecialchars($imgPath) ?>" class="bk-item-img"
                            alt="<?= htmlspecialchars($item['name']) ?>">
                    <?php else: ?>
                        <div class="bk-item-ph">
                            <i
                                class="fas <?= $isTransfer ? 'fa-van-shuttle' : ($isPackage ? 'fa-suitcase' : 'fa-map-marked-alt') ?>"></i>
                        </div>
                    <?php endif; ?>

                    <div class="bk-item-body">
                        <span class="bk-badge bk-badge-<?= $type ?>">
                            <?= $isTransfer ? 'Transfer' : ($isPackage ? 'Paquete' : 'Excursión') ?>
                        </span>
                        <div class="bk-item-name"><?= htmlspecialchars($item['name']) ?></div>

                        <?php if ($isTransfer && !empty($item['from_location']) && !empty($item['to_location'])): ?>
                            <div class="bk-route">
                                <span class="bk-rv-dot bk-rv-o"></span>
                                <span class="bk-rv-label"><?= htmlspecialchars($item['from_location']) ?></span>
                                <div class="bk-rv-line"></div>
                                <i class="fas fa-arrow-right"
                                    style="color:var(--c-ocean);font-size:.75rem;flex-shrink:0;"></i>
                                <div class="bk-rv-line"></div>
                                <span class="bk-rv-label"
                                    style="text-align:right"><?= htmlspecialchars($item['to_location']) ?></span>
                                <span class="bk-rv-dot bk-rv-d"></span>
                            </div>
                            <div class="bk-meta">
                                <span><i
                                        class="fas fa-car"></i><?= htmlspecialchars($item['vehicle_type'] ?? 'Privado') ?></span>
                                <span><i class="fas fa-users"></i>Hasta <?= (int) ($item['max_passengers'] ?? 4) ?>
                                    pax</span>
                                <span><i class="fas fa-clock"></i>Disponible 24/7</span>
                            </div>
                        <?php elseif ($isPackage): ?>
                            <div class="bk-meta">
                                <span><i class="fas fa-sun"></i><?= (int) ($item['days'] ?? 0) ?> días</span>
                                <span><i class="fas fa-moon"></i><?= (int) ($item['nights'] ?? 0) ?> noches</span>
                                <?php if (!empty($item['hotel_category'])): ?>
                                    <span><i class="fas fa-star"></i><?= htmlspecialchars($item['hotel_category']) ?></span>
                                <?php endif; ?>
                            </div>
                        <?php elseif ($isExcursion): ?>
                            <div class="bk-meta">
                                <span><i class="fas fa-clock"></i><?= htmlspecialchars($item['duration'] ?? '') ?></span>
                                <span><i
                                        class="fas fa-map-marker-alt"></i><?= htmlspecialchars($item['location'] ?? '') ?></span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- Resumen precio -->
                <div class="bk-summary">
                    <div class="bk-sum-row">
                        <span
                            class="bk-sum-key"><?= ($priceType === 'paquete' ? 'Precio Total' : 'Precio base') ?></span>
                        <span class="bk-sum-val">$<?= number_format($basePrice, 2) ?></span>
                    </div>
                    <div class="bk-sum-row" id="rowAdults" style="display:none;">
                        <span class="bk-sum-key" id="lblAdults"></span>
                        <span class="bk-sum-val" id="valAdults"></span>
                    </div>
                    <div class="bk-sum-row" id="rowChildren" style="display:none;">
                        <span class="bk-sum-key" id="lblChildren"></span>
                        <span class="bk-sum-val" id="valChildren"></span>
                    </div>
                    <div class="bk-sum-row bk-sum-total">
                        <span>Total a pagar</span>
                        <span id="totalDisplay">$<?= number_format($basePrice, 2) ?></span>
                    </div>
                </div>

                <div class="bk-secure">
                    <i class="fas fa-shield-halved"></i>
                    <span>Reserva protegida. Cancela contactando a nuestro equipo con anticipación.</span>
                </div>

            </aside>
        </div>

    </div>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>

<script>
    (function () {
        'use strict';

        /* Valores desde PHP — sin ternarios PHP dentro de bloques JS */
        var BASE = <?= json_encode(round($basePrice, 2)) ?>;
        var IS_TRANSFER = <?= json_encode((bool) $isTransfer) ?>;
        var PRICE_TYPE = <?= json_encode($priceType) ?>;

        var adultsEl = document.getElementById('adultsSelect');
        var childrenEl = document.getElementById('childrenSelect');
        var totalField = document.getElementById('totalPriceField');
        var totalDisp = document.getElementById('totalDisplay');
        var rowAdults = document.getElementById('rowAdults');
        var rowChildren = document.getElementById('rowChildren');
        var lblAdults = document.getElementById('lblAdults');
        var lblChildren = document.getElementById('lblChildren');
        var valAdults = document.getElementById('valAdults');
        var valChildren = document.getElementById('valChildren');

        function fmt(n) {
            return '$' + n.toFixed(2).replace(/\B(?=(\d{3})+(?!\d))/g, ',');
        }

        function calcTotal() {
            var adults = adultsEl ? (parseInt(adultsEl.value) || 1) : 1;
            var children = childrenEl ? (parseInt(childrenEl.value) || 0) : 0;
            var total;

            if (PRICE_TYPE === 'paquete') {
                /* Precio fijo por paquete/vehículo, sin multiplicar por personas */
                total = BASE;
                if (rowAdults) {
                    rowAdults.style.display = 'flex';
                    lblAdults.textContent = 'Precio total fijo';
                    valAdults.textContent = fmt(BASE);
                }
                if (rowChildren) {
                    if (children > 0) {
                        rowChildren.style.display = 'flex';
                        lblChildren.textContent = children + ' niño' + (children > 1 ? 's' : '');
                        valChildren.textContent = fmt(0); // Incluido
                    } else {
                        rowChildren.style.display = 'none';
                    }
                }
            } else {
                /* Precio por persona: BASE × adultos + BASE × 0.5 × niños */
                var costAdults = BASE * adults;
                var costChildren = BASE * 0.5 * children;
                total = costAdults + costChildren;

                if (rowAdults) {
                    rowAdults.style.display = 'flex';
                    lblAdults.textContent = adults + ' adulto' + (adults > 1 ? 's' : '');
                    valAdults.textContent = fmt(costAdults);
                }
                if (rowChildren) {
                    if (children > 0) {
                        rowChildren.style.display = 'flex';
                        lblChildren.textContent = children + ' niño' + (children > 1 ? 's' : '') + ' (50%)';
                        valChildren.textContent = fmt(costChildren);
                    } else {
                        rowChildren.style.display = 'none';
                    }
                }
            }

            totalDisp.textContent = fmt(total);
            totalField.value = total.toFixed(2);
        }

        if (adultsEl) adultsEl.addEventListener('change', calcTotal);
        if (childrenEl) childrenEl.addEventListener('change', calcTotal);
        calcTotal();

        /* ---- Selector de método de pago ---- */
        var payRadios = document.querySelectorAll('input[name="pay_method_ui"]');
        var methodField = document.getElementById('paymentMethodField');
        var submitBtn = document.getElementById('submitBtn');
        var noticeCash = document.getElementById('noticeCash');
        var noticePaypal = document.getElementById('noticePaypal');

        function updatePayMethod() {
            var checked = document.querySelector('input[name="pay_method_ui"]:checked');
            var method = checked ? checked.value : 'paypal';

            /* Actualizar el campo hidden que se envía al servidor */
            methodField.value = method;

            if (noticeCash) noticeCash.classList.toggle('active', method === 'cash');
            if (noticePaypal) noticePaypal.classList.toggle('active', method === 'paypal');

            if (method === 'cash') {
                submitBtn.className = 'bk-btn bk-btn-cash';
                submitBtn.innerHTML = '<i class="fas fa-money-bill-wave"></i> Confirmar reserva (efectivo)';
            } else {
                submitBtn.className = 'bk-btn bk-btn-paypal';
                submitBtn.innerHTML = '<i class="fab fa-paypal"></i> Continuar a PayPal';
            }
        }

        payRadios.forEach(function (r) {
            r.addEventListener('change', updatePayMethod);
        });
        updatePayMethod();

        /* ---- Validación mínima de fecha ---- */
        var travelDateEl = document.getElementById('travelDate');
        if (travelDateEl) {
            travelDateEl.addEventListener('change', function () {
                var sel = new Date(this.value + 'T00:00:00');
                var today = new Date();
                today.setHours(0, 0, 0, 0);
                if (sel < today) {
                    this.setCustomValidity('La fecha debe ser hoy o en el futuro.');
                    this.reportValidity();
                    this.value = '';
                } else {
                    this.setCustomValidity('');
                }
            });
        }

        /* ✅ NUEVO: Validación de formato de teléfono */
        var phoneEl = document.querySelector('input[name="phone"]');
        if (phoneEl) {
            phoneEl.addEventListener('input', function () {
                // Limpiar mensaje de error personalizado si existe
                if (this.validity.patternMismatch) {
                    this.setCustomValidity('Formato inválido. Ej: +1 829 555 1234');
                } else {
                    this.setCustomValidity('');
                }
            });
            phoneEl.addEventListener('blur', function () {
                // Forzar validación al perder foco
                if (this.value && !this.checkValidity()) {
                    this.reportValidity();
                }
            });
        }

        /* ---- Prevenir doble envío ---- */
        document.getElementById('bookingForm').addEventListener('submit', function () {
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.7';
        });

    }());
</script>