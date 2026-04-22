<!DOCTYPE html>

<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'funtrek RD' ?></title>

    <!-- Bootstrap 5.3.2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome 6.5.1 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&family=Montserrat:wght@700;800&display=swap"
        rel="stylesheet">

    <!-- CSS del proyecto -->
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/style.css">
    <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/custom.css">
    <?php if (isset($customCss) && is_array($customCss)): ?>
        <?php foreach ($customCss as $cssFile): ?>
            <link rel="stylesheet" href="<?= APP_URL ?>/assets/css/<?= $cssFile ?>?v=1.0.1">
        <?php endforeach; ?>
    <?php endif; ?>    <!-- Favicon -->
    <link rel="icon" href="<?= APP_URL ?>/assets/images/favicon.ico" type="image/x-icon">

    <!-- PWA Meta Tags -->
    <link rel="manifest" href="<?= APP_URL ?>/manifest.json">
    <meta name="theme-color" content="#0077B6">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="Dominican Travel">
    <link rel="apple-touch-icon" href="<?= APP_URL ?>/assets/images/pwa-icon-512.png">

    <!-- Google Translate API -->
    <style>
        /* Ocultamiento Extremo de Google Translate */
        iframe[id=":1.container"],
        iframe.skiptranslate,
        .skiptranslate > iframe,
        .goog-te-banner-frame,
        .goog-te-banner-frame.skiptranslate,
        .VIpgJd-Zvi9od-ORHb-OEVmcd,
        .goog-te-gadget-icon,
        #google_translate_element,
        #goog-gt-tt,
        .goog-tooltip,
        .goog-tooltip:hover {
            display: none !important;
            opacity: 0 !important;
            visibility: hidden !important;
            height: 0 !important;
            width: 0 !important;
        }

        /* Eliminar el resaltado automático de Google */
        body .goog-text-highlight {
            background-color: transparent !important;
            border: none !important;
            box-shadow: none !important;
        }

        /* Imposibilitar que body tome margen superior de Google */
        body, html {
            top: 0 !important;
            margin-top: 0 !important;
            position: relative !important;
        }
    </style>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({ pageLanguage: 'es', includedLanguages: 'en,es', autoDisplay: false }, 'google_translate_element');
        }
        
        // Destructor continuo de la barra
        const observer = new MutationObserver((mutations) => {
            const googBanner = document.querySelector('.skiptranslate iframe');
            if(googBanner) {
                googBanner.style.setProperty('display', 'none', 'important');
                document.body.style.setProperty('top', '0px', 'important');
            }
        });
        document.addEventListener("DOMContentLoaded", function() {
            observer.observe(document.body, { childList: true, subtree: true });
        });
    </script>
    <script type="text/javascript" src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</head>

<body>
    <div id="google_translate_element"></div>