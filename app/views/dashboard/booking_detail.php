<?php 
$customCss = ['modules/admin-dashboard.css'];
require_once APP_ROOT . '/app/views/layouts/header.php'; 
require_once APP_ROOT . '/app/views/layouts/navigation.php'; 

// 1. Data derivation & Normalization
$itemType = $booking['item_type'] ?? 'package';
$sName = ($itemType === 'package') ? ($booking['package_name'] ?? 'Paquete') :
    (($itemType === 'excursion') ? ($booking['excursion_name'] ?? 'Excursión') :
        ($booking['transfer_name'] ?? 'Transfer'));

$sTypeLabel = [
    'package' => 'Paquete Vacacional',
    'excursion' => 'Excursión / Tour',
    'transfer' => 'Transporte / Transfer'
][$itemType] ?? 'Servicio';

// 2. Status styling
$status = $booking['status'] ?? 'pending';
$statusClass = [
    'pending' => 'bg-warning',
    'confirmed' => 'bg-success',
    'cancelled' => 'bg-danger',
    'completed' => 'bg-info'
][$status] ?? 'bg-secondary';
?>

<style>
    /* Estilos para impresión (Factura) */
    @media print {
        header, .navbar, footer, .adm-sidebar-sticky, .adm-btn-outline, .adm-new-btn, .no-print, .btn, .breadcrumb-nav {
            display: none !important;
        }

        body, .adm-page, .container-fluid, .col-lg-9 {
            margin: 0 !important;
            padding: 0 !important;
            width: 100% !important;
            max-width: 100% !important;
        }

        .row {
            display: block !important;
        }

        .col-lg-8, .col-lg-4 {
            width: 100% !important;
            float: none !important;
        }

        .adm-card {
            box-shadow: none !important;
            border: 1px solid #eee !important;
            margin-bottom: 20px !important;
        }

        .card-header {
            background-color: #f8f9fa !important;
            border-bottom: 2px solid #333 !important;
        }

        .text-primary {
            color: #000 !important;
        }

        .badge {
            border: 1px solid #000 !important;
            color: #000 !important;
            background: none !important;
        }

        .adm-title {
            font-size: 24px !important;
            margin-bottom: 10px !important;
        }
        
        .print-only {
            display: block !important;
        }
        
        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #000;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
    }
    
    .print-only {
        display: none;
    }
</style>

<div class="adm-page">
    <div class="container-fluid animate-fade-in">
        
        <!-- Header de impresión (Solo visible al imprimir) -->
        <div class="print-only invoice-header">
            <div>
                <h2 class="fw-bold text-primary mb-0">FUNTREK RD</h2>
                <p class="text-muted small mb-0">Agencia de Viajes y Tours</p>
            </div>
            <div class="text-end">
                <h3 class="mb-0">FACTURA</h3>
                <p class="text-muted mb-0">Ref: <?= htmlspecialchars($booking['booking_reference'] ?? '#' . $booking['id']) ?></p>
                <p class="text-muted mb-0">Fecha: <?= date('d/m/Y') ?></p>
            </div>
        </div>

        <div class="row g-4">
            
            <!-- Sidebar -->
            <div class="col-lg-3 no-print">
                <div class="adm-sidebar-sticky">
                    <div class="adm-sidebar">
                        <a href="<?= APP_URL ?>/dashboard/profile" class="adm-sidebar-header">
                            <div class="adm-avatar-small">
                                <?= mb_strtoupper(mb_substr($_SESSION['user_name'] ?? 'U', 0, 1)) ?>
                            </div>
                            <div class="adm-sidebar-info">
                                <span><?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?></span>
                                <small>Cliente FunTrek</small>
                            </div>
                        </a>
                        <nav class="adm-nav">
                            <a href="<?= APP_URL ?>/dashboard" class="adm-nav-link">
                                <i class="fas fa-th-large"></i> Resumen
                            </a>
                            <a href="<?= APP_URL ?>/dashboard/bookings" class="adm-nav-link active">
                                <i class="fas fa-calendar-check"></i> Mis Reservas
                            </a>
                            <a href="<?= APP_URL ?>/dashboard/profile" class="adm-nav-link">
                                <i class="fas fa-user-edit"></i> Editar Perfil
                            </a>
                            <hr>
                            <a href="<?= APP_URL ?>/auth/logout" class="adm-nav-link text-danger">
                                <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                            </a>
                        </nav>
                    </div>

                    <div class="card adm-card bg-primary text-white border-0 overflow-hidden shadow-lg"
                        style="animation-delay: 0.2s">
                        <div class="card-body p-4 position-relative">
                            <i class="fas fa-umbrella-beach position-absolute opacity-10"
                                style="font-size: 8rem; right: -20px; bottom: -20px;"></i>
                            <h6 class="fw-bold mb-2">¿Planeas otro viaje?</h6>
                            <p class="small mb-3 opacity-75">Descubre nuestras ofertas exclusivas en paquetes todo incluido.</p>
                            <a href="<?= APP_URL ?>/paquetes" class="btn btn-light btn-sm rounded-pill fw-bold px-3">Explorar más</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <!-- Breadcrumb & Header -->
                <div class="d-flex align-items-center justify-content-between mb-4 no-print">
                    <div class="d-flex align-items-center">
                        <a href="<?= APP_URL ?>/dashboard/bookings" class="adm-btn-outline me-3">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h1 class="adm-title mb-0">Detalle de Reserva</h1>
                            <p class="text-muted small mb-0">Ref: <span class="fw-bold text-primary"><?= htmlspecialchars($booking['booking_reference'] ?? '#' . $booking['id']) ?></span></p>
                        </div>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="adm-btn-outline" onclick="window.print()">
                            <i class="fas fa-print me-1"></i> Imprimir
                        </button>
                        <button class="adm-new-btn" onclick="downloadInvoicePDF()">
                            <i class="fas fa-file-pdf me-1"></i> Descargar PDF
                        </button>
                    </div>
                </div>

                <div id="invoice-content">
                    <div class="row g-4">
                        <!-- Column 1: Details -->
                        <div class="col-lg-8">
                            <div class="adm-card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white p-4 border-bottom border-light">
                                    <h5 class="fw-bold mb-0" style="color: var(--adm-dark);">
                                        <i class="fas fa-info-circle me-2 text-primary no-print"></i>Información del Servicio
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="row mb-4 align-items-center">
                                        <div class="col-md-7">
                                            <p class="text-muted small mb-1 uppercase tracking-wider">Servicio Reservado</p>
                                            <h3 class="fw-bold mb-2"><?= htmlspecialchars($sName) ?></h3>
                                            <span class="badge rounded-pill bg-light text-primary px-3 py-2 border border-primary border-opacity-10">
                                                <i class="fas fa-tag me-1 no-print"></i> <?= $sTypeLabel ?>
                                            </span>
                                        </div>
                                        <div class="col-md-5 text-md-end mt-3 mt-md-0">
                                            <p class="text-muted small mb-1 uppercase tracking-wider">Estado Actual</p>
                                            <span class="badge <?= $statusClass ?> fs-6 px-4 py-2 shadow-sm">
                                                <?= ucfirst($status) ?>
                                            </span>
                                        </div>
                                    </div>

                                    <hr class="opacity-10">

                                    <div class="row py-3 g-4">
                                        <div class="col-sm-6 col-md-4">
                                            <p class="text-muted mb-1 small"><i class="far fa-calendar-alt me-1 text-primary no-print"></i> Fecha del Viaje</p>
                                            <p class="fw-bold fs-5 mb-0"><?= date('d M, Y', strtotime($booking['travel_date'] ?? $booking['booking_date'] ?? 'now')) ?></p>
                                        </div>
                                        <div class="col-sm-6 col-md-4">
                                            <p class="text-muted mb-1 small"><i class="fas fa-users me-1 text-primary no-print"></i> Asistentes</p>
                                            <p class="fw-bold fs-5 mb-0">
                                                <?= ($booking['adults'] ?? 0) ?> Adulto(s)
                                                <?php if (($booking['children'] ?? 0) > 0): ?>
                                                    <small class="text-muted block">+ <?= $booking['children'] ?> Niños</small>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                        <div class="col-sm-12 col-md-4">
                                            <p class="text-muted mb-1 small"><i class="fas fa-credit-card me-1 text-primary no-print"></i> Método de Pago</p>
                                            <p class="fw-bold fs-5 mb-0"><?= ucfirst($booking['payment_method'] ?? 'PayPal') ?></p>
                                        </div>
                                    </div>

                                    <?php if (!empty($booking['special_requests'])): ?>
                                        <div class="mt-4 p-3 rounded-4 bg-light border-start border-primary border-4">
                                            <p class="fw-bold small mb-2"><i class="fas fa-comment-alt me-2 text-primary no-print"></i>Tus Solicitudes Especiales:</p>
                                            <p class="mb-0 text-muted italic">"<?= htmlspecialchars($booking['special_requests']) ?>"</p>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Column 2: Payment & Actions -->
                        <div class="col-lg-4">
                            <div class="adm-card border-0 shadow-sm mb-4">
                                <div class="card-header bg-white p-4 border-bottom border-light">
                                    <h5 class="fw-bold mb-0" style="color: var(--adm-dark);">
                                        <i class="fas fa-receipt me-2 text-primary no-print"></i>Resumen de Pago
                                    </h5>
                                </div>
                                <div class="card-body p-4">
                                    <div class="d-flex justify-content-between mb-3 text-muted">
                                        <span>Subtotal</span>
                                        <span>$<?= number_format($booking['total_price'] ?? 0, 2) ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between mb-3 text-muted">
                                        <span>Impuestos (ITBIS)</span>
                                        <span class="text-success">Incluido</span>
                                    </div>
                                    <hr class="opacity-10">
                                    <div class="d-flex justify-content-between align-items-center mt-3">
                                        <span class="h5 fw-bold mb-0">Total Pagado</span>
                                        <span class="h4 fw-bold mb-0 text-primary">$<?= number_format($booking['total_price'] ?? 0, 2) ?></span>
                                    </div>
                                    
                                    <div class="mt-4 p-3 rounded-3 bg-light text-center">
                                        <p class="small mb-1 text-muted">Estado del Pago</p>
                                        <span class="fw-bold text-<?= ($booking['payment_status'] ?? 'pending') === 'paid' ? 'success' : 'warning' ?>">
                                            <i class="fas fa-check-circle me-1 no-print"></i> <?= strtoupper($booking['payment_status'] ?? 'PENDIENTE') ?>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <?php if ($status !== 'cancelled' && $status !== 'completed'): ?>
                                <div class="adm-card border-0 shadow-sm p-4 bg-foam no-print mb-4">
                                    <h6 class="fw-bold mb-3"><i class="fas fa-exclamation-triangle me-2 text-danger"></i>Zona de Peligro</h6>
                                    <p class="small text-muted mb-4">Si deseas cancelar tu reserva, recuerda que aplican nuestras políticas de cancelación.</p>
                                    <form action="<?= APP_URL ?>/dashboard/requestCancelBooking/<?= $booking['id'] ?>" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas cancelar esta reserva?')">
                                        <button type="submit" class="btn btn-outline-danger w-100 rounded-pill py-2 font-sora fw-bold">
                                            Solicitar Cancelación
                                        </button>
                                    </form>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <!-- Support Card (Repositioned and Fixed) -->
                <div class="mt-4 mb-5 no-print">
                    <div class="card border-0 shadow-sm text-center" style="border-radius: 24px; background: rgba(255,255,255,0.8); backdrop-filter: blur(10px);">
                        <div class="card-body p-5">
                            <h5 class="fw-bold mb-2">¿Necesitas ayuda con tu reserva?</h5>
                            <p class="text-muted small mb-4">Nuestro equipo está disponible 24/7 para asistirte con cualquier cambio.</p>
                            <div class="d-flex justify-content-center gap-3 flex-wrap">
                                <a href="https://wa.me/18293988953" class="btn btn-success rounded-pill px-4 py-2 shadow-sm" target="_blank">
                                    <i class="fab fa-whatsapp me-2"></i> WhatsApp
                                </a>
                                <a href="<?= APP_URL ?>/contacto" class="btn btn-outline-primary rounded-pill px-4 py-2">
                                    Centro de Ayuda
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div> <!-- End col-lg-9 -->
        </div> <!-- End main row -->
    </div> <!-- End container-fluid -->
</div> <!-- End adm-page -->

<div class="no-print" style="margin-bottom: 80px;"></div>

<script>
function downloadInvoicePDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    // Configuración de fuentes y colores (simplificado para jsPDF)
    doc.setFont("helvetica", "bold");
    doc.setTextColor(0, 119, 182);
    doc.setFontSize(22);
    doc.text("FUNTREK RD", 20, 30);

    doc.setFontSize(10);
    doc.setTextColor(100);
    doc.setFont("helvetica", "normal");
    doc.text("Agencia de Viajes y Tours", 20, 37);

    doc.setFontSize(18);
    doc.setTextColor(0);
    doc.text("FACTURA DE RESERVA", 130, 30);

    doc.setFontSize(10);
    doc.text("Referencia: <?= $booking['booking_reference'] ?? '#' . $booking['id'] ?>", 130, 37);
    doc.text("Fecha Emisión: <?= date('d/m/Y') ?>", 130, 42);

    // Línea separadora
    doc.setDrawColor(0);
    doc.setLineWidth(0.5);
    doc.line(20, 50, 190, 50);

    // Datos del Cliente
    doc.setFontSize(12);
    doc.setFont("helvetica", "bold");
    doc.text("Cliente:", 20, 65);
    doc.setFont("helvetica", "normal");
    doc.text("<?= htmlspecialchars($_SESSION['user_name'] ?? 'Usuario') ?>", 40, 65);
    doc.text("<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>", 40, 70);

    // Detalles del Servicio
    doc.setFillColor(245, 245, 245);
    doc.rect(20, 80, 170, 10, 'F');
    doc.setFont("helvetica", "bold");
    doc.text("DETALLES DEL SERVICIO", 25, 87);

    doc.setFont("helvetica", "normal");
    doc.text("Servicio:", 20, 100);
    doc.setFont("helvetica", "bold");
    doc.text("<?= addslashes($sName) ?>", 50, 100);
    
    doc.setFont("helvetica", "normal");
    doc.text("Tipo:", 20, 110);
    doc.text("<?= $sTypeLabel ?>", 50, 110);

    doc.text("Fecha Viaje:", 20, 120);
    doc.text("<?= date('d M, Y', strtotime($booking['travel_date'] ?? $booking['booking_date'])) ?>", 50, 120);

    doc.text("Asistentes:", 20, 130);
    doc.text("<?= $booking['adults'] ?> Adulto(s) <?= $booking['children'] > 0 ? '+ ' . $booking['children'] . ' Niños' : '' ?>", 50, 130);

    doc.text("Método Pago:", 20, 140);
    doc.text("<?= ucfirst($booking['payment_method']) ?>", 50, 140);

    // Resumen de Pago
    doc.setFillColor(245, 245, 245);
    doc.rect(20, 155, 170, 10, 'F');
    doc.setFont("helvetica", "bold");
    doc.text("RESUMEN DE PAGO", 25, 162);

    doc.setFont("helvetica", "normal");
    doc.text("Subtotal:", 130, 175);
    doc.text("$<?= number_format($booking['total_price'], 2) ?>", 170, 175);
    
    doc.text("Impuestos:", 130, 182);
    doc.text("Incluido", 170, 182);

    doc.setDrawColor(200);
    doc.line(130, 187, 190, 187);
    
    doc.setFontSize(14);
    doc.setFont("helvetica", "bold");
    doc.setTextColor(0, 119, 182);
    doc.text("TOTAL:", 130, 197);
    doc.text("$<?= number_format($booking['total_price'], 2) ?>", 170, 197);

    // Footer del PDF
    doc.setFontSize(8);
    doc.setTextColor(150);
    doc.setFont("helvetica", "italic");
    doc.text("Gracias por elegir FUNTREK RD. ¡Disfruta tu viaje!", 105, 280, { align: "center" });

    // Descargar
    doc.save("Factura_Funtrek_<?= $booking['booking_reference'] ?? $booking['id'] ?>.pdf");
}
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>