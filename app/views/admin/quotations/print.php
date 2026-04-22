<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= $title ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary: #0077B6;
            --secondary: #2EC4B6;
            --dark: #1D3557;
            --gray: #6C757D;
            --light-bg: #F8F9FA;
            --print-font-size: 14px;
        }
        * { box-sizing: border-box; }
        body {
            font-family: 'Outfit', sans-serif;
            margin: 0;
            padding: 0;
            background: #e0e0e0;
            color: var(--dark);
            font-size: var(--print-font-size);
        }
        .page {
            width: 210mm;
            min-height: 297mm;
            padding: 20mm;
            margin: 10mm auto;
            background: white;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            position: relative;
        }
        
        /* Customization Classes */
        .page.text-small { --print-font-size: 12px; }
        .page.text-large { --print-font-size: 16px; }
        .page.theme-dark { --primary: #333; --dark: #000; }

        @media print {
            body { background: none; }
            .page { margin: 0; box-shadow: none; width: 100%; border: none; }
            .no-print { display: none; }
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            border-bottom: 3px solid var(--primary);
            padding-bottom: 25px;
            margin-bottom: 40px;
        }
        .agency-info h1 {
            margin: 0;
            font-size: 1.8em;
            color: var(--primary);
            text-transform: uppercase;
        }
        .agency-info p { margin: 4px 0; color: var(--gray); font-size: 0.9em; }
        .logo { max-width: 220px; max-height: 110px; object-fit: contain; }

        .quote-meta {
            display: flex;
            justify-content: space-between;
            margin-bottom: 40px;
            gap: 20px;
        }
        .info-box { flex: 1; }
        .info-box h3 { 
            margin: 0 0 12px 0; 
            font-size: 1.1em; 
            color: var(--primary); 
            border-bottom: 2px solid var(--light-bg); 
            padding-bottom: 5px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .info-box p { margin: 5px 0; line-height: 1.4; }

        .number-box { text-align: right; border-left: 4px solid var(--light-bg); padding-left: 25px; }
        .number-box h2 { margin: 0; color: var(--dark); font-size: 2.2em; font-weight: 800; }
        .number-box p { margin: 5px 0; font-weight: 600; color: var(--gray); }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 40px;
        }
        table th {
            background: var(--primary);
            color: white;
            padding: 14px 12px;
            text-align: left;
            text-transform: uppercase;
            font-size: 0.85em;
            letter-spacing: 1px;
        }
        table td {
            padding: 18px 12px;
            border-bottom: 1px solid #edf2f7;
            font-size: 1em;
            vertical-align: top;
        }
        
        .totals-section {
            float: right;
            width: 300px;
            margin-top: 20px;
        }
        .total-line {
            display: flex;
            justify-content: space-between;
            margin-bottom: 12px;
            font-size: 1.1em;
            padding: 0 10px;
        }
        .final-total {
            background: var(--dark);
            color: white;
            padding: 15px 12px;
            border-radius: 8px;
            margin-top: 15px;
        }

        .notes {
            margin-top: 100px;
            clear: both;
            background: var(--light-bg);
            padding: 25px;
            border-radius: 12px;
            border-left: 5px solid var(--primary);
        }
        .notes h4 { margin: 0 0 15px 0; color: var(--primary); display: flex; align-items: center; gap: 8px; }
        .notes p { font-size: 0.95em; color: #4a5568; line-height: 1.6; white-space: pre-wrap; margin: 0; }

        footer {
            margin-top: 60px;
            text-align: center;
            font-size: 0.85em;
            color: var(--gray);
            border-top: 1px solid #eee;
            padding-top: 25px;
        }

        /* Toolbar */
        .print-toolbar {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: white;
            padding: 15px 30px;
            border-radius: 50px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.2);
            z-index: 9999;
            display: flex;
            align-items: center;
            gap: 20px;
        }
        .toolbar-btn {
            background: var(--primary);
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }
        .toolbar-btn:hover { background: var(--dark); transform: translateY(-2px); }
        .toolbar-btn.btn-secondary { background: #64748b; }
        
        .custom-select {
            border: 1px solid #e2e8f0;
            padding: 8px 12px;
            border-radius: 8px;
            font-family: inherit;
        }
    </style>
</head>
<body>
    <div class="print-toolbar no-print">
        <div class="d-flex align-items-center gap-2">
            <span class="small fw-bold">Tamaño:</span>
            <select class="custom-select" onchange="changeSize(this.value)">
                <option value="normal">Normal</option>
                <option value="small">Pequeño</option>
                <option value="large">Grande</option>
            </select>
        </div>
        <div class="d-flex align-items-center gap-2">
            <span class="small fw-bold">Estilo:</span>
            <select class="custom-select" onchange="changeTheme(this.value)">
                <option value="default">Color (Azul)</option>
                <option value="dark">Blanco y Negro</option>
            </select>
        </div>
        <button class="toolbar-btn" onclick="window.print()">
            <i class="fas fa-print"></i> IMPRIMIR
        </button>
        <button class="toolbar-btn btn-secondary" onclick="window.close()">
            <i class="fas fa-times"></i> CERRAR
        </button>
    </div>

    <div class="page" id="printablePage">
        <header>
            <div class="logo-container">
                <?php if (!empty($settings['company_logo'])): ?>
                    <img src="<?= APP_URL ?>/assets/uploads/agency/<?= htmlspecialchars($settings['company_logo']) ?>" class="logo">
                <?php else: ?>
                    <div style="font-size: 32px; font-weight: 800; color: var(--primary); letter-spacing: -1px;">
                        <?= strtoupper($settings['company_name'] ?? 'Dominican Travel') ?>
                    </div>
                <?php endif; ?>
            </div>
            <div class="agency-info" style="text-align: right;">
                <h1><?= htmlspecialchars($settings['company_name'] ?? 'Dominican Travel Agency') ?></h1>
                <p><i class="fas fa-map-marker-alt fa-fw me-1"></i> <?= htmlspecialchars($settings['company_address'] ?? '') ?></p>
                <p><i class="fas fa-phone fa-fw me-1"></i> <?= htmlspecialchars($settings['company_phone'] ?? '') ?></p>
                <p><i class="fas fa-envelope fa-fw me-1"></i> <?= htmlspecialchars($settings['company_email'] ?? '') ?></p>
            </div>
        </header>

        <div class="quote-meta">
            <div class="info-box client-box">
                <h3>Preparado para:</h3>
                <p><strong>Sr(a). <?= htmlspecialchars($quotation['customer_name']) ?></strong></p>
                <p><i class="fas fa-envelope fa-fw me-1 opacity-50"></i> <?= htmlspecialchars($quotation['customer_email']) ?></p>
                <p><i class="fas fa-phone fa-fw me-1 opacity-50"></i> <?= htmlspecialchars($quotation['customer_phone']) ?></p>
                <?php if ($quotation['travel_date']): ?>
                    <p><i class="fas fa-plane-departure fa-fw me-1 opacity-50"></i> <strong>Fecha de Viaje:</strong> <?= date('d/m/Y', strtotime($quotation['travel_date'])) ?></p>
                <?php endif; ?>
            </div>
            <div class="info-box number-box">
                <p class="text-uppercase small fw-bold opacity-50 mb-0">Contización</p>
                <h2>#<?= htmlspecialchars($quotation['quote_number']) ?></h2>
                <p><strong>Fecha:</strong> <?= date('d/m/Y', strtotime($quotation['created_at'])) ?></p>
                <p><strong>Vence:</strong> <?= date('d/m/Y', strtotime($quotation['created_at'] . ' + 30 days')) ?></p>
                <?php if (!empty($quotation['agent_name'])): ?>
                    <p><i class="fas fa-user-tie me-1"></i> <strong>Atendido por:</strong> <?= htmlspecialchars($quotation['agent_name']) ?></p>
                <?php endif; ?>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Descripción detallada de los servicios</th>
                    <th style="text-align: center;">Cantidad</th>
                    <th style="text-align: right;">Precio Unit.</th>
                    <th style="text-align: right;">Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($quotation['items'] as $item): ?>
                    <tr>
                        <td>
                            <div style="font-weight: 700; color: var(--primary); margin-bottom: 4px; font-size: 0.9em;"><?= strtoupper($item['item_type']) ?></div>
                            <div style="font-weight: 600;"><?= htmlspecialchars($item['description']) ?></div>
                        </td>
                        <td style="text-align: center;"><?= (int)$item['quantity'] ?></td>
                        <td style="text-align: right;">$<?= number_format($item['unit_price'], 2) ?></td>
                        <td style="text-align: right; font-weight: 600;">$<?= number_format($item['total'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <div class="totals-section">
            <div class="total-line">
                <span class="text-muted fw-bold">Sub-Total:</span>
                <span class="fw-bold">$<?= number_format($quotation['subtotal'], 2) ?></span>
            </div>
            
            <?php if (!empty($quotation['is_tax_enabled'])): ?>
            <div class="total-line">
                <span class="text-muted fw-bold">ITBIS (<?= $settings['default_tax_rate'] ?? 18 ?>%):</span>
                <span class="fw-bold">$<?= number_format($quotation['tax_amount'], 2) ?></span>
            </div>
            <?php endif; ?>

            <div class="total-line final-total">
                <strong>VALOR TOTAL:</strong>
                <strong style="font-size: 1.4em;">$<?= number_format($quotation['total_price'], 2) ?></strong>
            </div>
        </div>

        <div class="notes">
            <h4><i class="fas fa-info-circle"></i> Términos & Condiciones</h4>
            <p><?= !empty($quotation['notes']) ? htmlspecialchars($quotation['notes']) : "• Cotización válida por 30 días.\n• Precios sujetos a cambios según disponibilidad.\n• No se garantiza reserva sin depósito previo." ?></p>
        </div>

        <footer>
            <p>¡Gracias por confiar en <strong><?= htmlspecialchars($settings['company_name'] ?? 'Dominican Travel') ?></strong>!</p>
            <p style="font-size: 0.8em; margin-top: 10px; opacity: 0.6;">Este documento es una cotización informativa y no representa una confirmación de reserva definitiva.</p>
        </footer>
    </div>

    <script>
    function changeSize(val) {
        const page = document.getElementById('printablePage');
        page.classList.remove('text-small', 'text-large');
        if (val === 'small') page.classList.add('text-small');
        if (val === 'large') page.classList.add('text-large');
    }
    
    function changeTheme(val) {
        const page = document.getElementById('printablePage');
        if (val === 'dark') page.classList.add('theme-dark');
        else page.classList.remove('theme-dark');
    }
    </script>
</body>
</html>
