<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Letrero Aeropuerto - <?= htmlspecialchars($quotation['customer_name']) ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@700;900&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
    <style>
        body {
            margin: 0;
            padding: 0;
            background: #f4f7f6;
            font-family: 'Outfit', sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            width: 100vw;
            overflow: hidden;
            text-align: center;
        }

        .sign-container {
            width: 95%;
            height: 95%;
            border: 30px solid #1D3557;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 40px;
            box-sizing: border-box;
            position: relative;
            background: #fff;
            transition: border-color 0.3s;
        }

        .welcome {
            font-size: 50px;
            color: #6C757D;
            margin-bottom: 20px;
            text-transform: uppercase;
            letter-spacing: 12px;
            font-weight: 700;
        }

        .client-name {
            font-size: 160px; 
            font-weight: 900;
            color: #1D3557;
            line-height: 0.9;
            margin: 0 auto;
            text-transform: uppercase;
            word-wrap: break-word;
            max-width: 100%;
            outline: none;
            cursor: text;
            display: inline-block;
            transition: all 0.2s;
        }

        .client-name:hover {
            background-color: rgba(0,119,182,0.05);
        }

        .footer {
            margin-top: auto;
            width: 100%;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 60px;
            padding-bottom: 30px;
        }

        .logo {
            max-width: 450px;
            max-height: 200px;
            object-fit: contain;
        }

        .agency-details {
            text-align: left;
            border-left: 5px solid #1D3557;
            padding-left: 40px;
        }

        .agency-name {
            font-size: 38px;
            color: #1D3557;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .agency-phone {
            font-size: 28px;
            color: #0077B6;
            font-weight: 700;
        }

        /* TOOLBAR STYLES */
        .btn-controls {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 15px;
            z-index: 1000;
            background: rgba(255,255,255,0.9);
            padding: 15px 25px;
            border-radius: 50px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.15);
            backdrop-filter: blur(5px);
            border: 1px solid #dee2e6;
        }

        .btn-action {
            padding: 12px 24px;
            background: #1D3557;
            color: white;
            border: none;
            border-radius: 30px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: all 0.2s;
        }

        .btn-action:hover { background: #0077B6; transform: translateY(-2px); }
        .btn-print { background: #28a745; }
        .btn-print:hover { background: #218838; }
        .btn-close { background: #6c757d; }

        .edit-hint {
            position: absolute;
            top: -30px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 14px;
            color: var(--adm-blue);
            font-weight: 600;
            opacity: 0.6;
        }

        /* PRINT STYLES */
        @media print {
            .btn-controls, .edit-hint { display: none !important; }
            body { 
                background: white; 
                margin: 0; 
                padding: 0; 
                height: 100vh;
                width: 100vw;
            }
            .sign-container {
                width: 100%;
                height: 100%;
                border: 40px solid #1D3557 !important;
                margin: 0 !important;
                padding: 40px !important;
            }
        }
    </style>
</head>
<body>
    <div class="btn-controls">
        <button class="btn-action" onclick="adjustSize(1.1)"><i class="fas fa-search-plus"></i> Aumentar</button>
        <button class="btn-action" onclick="adjustSize(0.9)"><i class="fas fa-search-minus"></i> Disminuir</button>
        <button class="btn-action btn-print" onclick="window.print()"><i class="fas fa-print"></i> IMPRIMIR</button>
        <button class="btn-action btn-close" onclick="window.close()"><i class="fas fa-times"></i> CERRAR</button>
    </div>

    <div class="sign-container">
        <div class="welcome">BIENVENIDO / WELCOME</div>
        <div style="position:relative; width:100%;">
            <span class="edit-hint">Haga clic en el nombre para editar</span>
            <h1 class="client-name" id="nameText" contenteditable="true" spellcheck="false">
                <?= htmlspecialchars($quotation['customer_name'] ?? 'NOMBRE DEL CLIENTE') ?>
            </h1>
        </div>
        
        <div class="footer">
            <?php if (!empty($settings['company_logo'])): ?>
                <img src="<?= APP_URL ?>/assets/uploads/agency/<?= htmlspecialchars($settings['company_logo']) ?>" class="logo">
            <?php endif; ?>
            <div class="agency-details">
                <div class="agency-name"><?= htmlspecialchars($settings['company_name'] ?? 'DOMINICAN TRAVEL') ?></div>
                <div class="agency-phone"><i class="fas fa-phone-alt"></i> <?= htmlspecialchars($settings['company_phone'] ?? '') ?></div>
            </div>
        </div>
    </div>

    <script>
    let currentScale = 160;
    function adjustSize(factor) {
        currentScale = Math.round(currentScale * factor);
        document.getElementById('nameText').style.fontSize = currentScale + 'px';
    }

    // Prevenir saltos de línea en el nombre (solo texto en una línea usualmente)
    document.getElementById('nameText').addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            this.blur();
        }
    });
    </script>
</body>
</html>
