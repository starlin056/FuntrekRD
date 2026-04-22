<footer class="footer-modern text-white pt-5">
    <div class="container">
        <div class="row g-4">

            <div class="col-lg-4">
                <h5 class="fw-bold mb-3 text-warning">
                    <i class="fas fa-plane me-2"></i> FUNTREK RD
                </h5>
                <p class="opacity-75">
                    <?= __('footer_description') ?>
                </p>
            </div>

            <div class="col-lg-4">
                <h6 class="fw-bold mb-3 text-warning">
                    <?= __('footer_links') ?>
                </h6>
                <ul class="list-unstyled">
                    <li><a href="<?= APP_URL ?>/paquetes"><?= __('nav_packages') ?></a></li>
                    <li><a href="<?= APP_URL ?>/excursiones"><?= __('nav_excursions') ?></a></li>
                    <li><a href="<?= APP_URL ?>/transfers"><?= __('nav_transfers') ?></a></li>
                    <li><a href="<?= APP_URL ?>/contacto"><?= __('nav_contact') ?></a></li>
                </ul>
            </div>

            <div class="col-lg-4">
                <h6 class="fw-bold mb-3 text-warning">
                    <?= __('footer_contact') ?>
                </h6>
                <p><i class="fas fa-phone me-2"></i> (829) 398-8953 </p>
                <p><i class="fas fa-phone me-2"></i> (849) 457-0890</p>
                <p><i class="fas fa-envelope me-2"></i> info@FUNTREK.com</p>
                <p><i class="fas fa-map-marker-alt me-2"></i> Punta Cana, RD</p>
            </div>
        </div>

        <hr class="opacity-25 my-4">

        <div class="text-center opacity-75">
            &copy; <?= date('Y') ?> FUNTREK RD · <?= __('footer_rights') ?>
        </div>
    </div>
</footer>

<!-- PWA Install Prompt (Hidden by default) -->
<div id="pwa-install-prompt" style="display: none; position: fixed; bottom: 85px; left: 20px; right: 20px; background: #fff; padding: 15px; border-radius: 15px; box-shadow: 0 10px 30px rgba(0,0,0,0.2); z-index: 9999; border: 1px solid #eee;">
    <div style="display: flex; align-items: center; gap: 15px;">
        <img src="<?= APP_URL ?>/assets/images/pwa-icon-512.png" style="width: 50px; height: 50px; border-radius: 10px; object-fit: cover;" alt="App Icon">
        <div style="flex: 1;">
            <h6 style="margin: 0; font-weight: bold; color: #1D3557; font-size: 16px;">Instalar App</h6>
            <p style="margin: 0; color: #666; font-size: 13px;">Añade Dominican Travel a tu pantalla de inicio.</p>
        </div>
        <button id="pwa-install-btn" style="background: #0077B6; color: #fff; border: none; padding: 8px 18px; border-radius: 20px; font-weight: bold; font-size: 14px;">Instalar</button>
        <button id="pwa-close-btn" style="background: none; border: none; color: #999; font-size: 24px; padding: 0 5px;">&times;</button>
    </div>
</div>

<!-- jsPDF para exportar PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    // Register Service Worker
    if ('serviceWorker' in navigator) {
        window.addEventListener('load', () => {
            navigator.serviceWorker.register('<?= APP_URL ?>/sw.js')
                .then(reg => console.log('PWA Service Worker Registered'))
                .catch(err => console.log('PWA SW Error:', err));
        });
    }

    let deferredPrompt;
    const installPrompt = document.getElementById('pwa-install-prompt');
    const installBtn = document.getElementById('pwa-install-btn');
    const closeBtn = document.getElementById('pwa-close-btn');

    window.addEventListener('beforeinstallprompt', (e) => {
        // Prevent Chrome 67 and earlier from automatically showing the prompt
        e.preventDefault();
        // Stash the event so it can be triggered later.
        deferredPrompt = e;
        // Show our custom UI if it wasn't dismissed before
        if (!localStorage.getItem('pwa-prompt-dismissed')) {
            installPrompt.style.display = 'block';
        }
    });

    installBtn.addEventListener('click', (e) => {
        if (!deferredPrompt) return;
        // hide our install promotion
        installPrompt.style.display = 'none';
        // Show the prompt
        deferredPrompt.prompt();
        // Wait for the user to respond to the prompt
        deferredPrompt.userChoice.then((choiceResult) => {
            if (choiceResult.outcome === 'accepted') {
                console.log('User accepted the A2HS prompt');
            } else {
                console.log('User dismissed the A2HS prompt');
            }
            deferredPrompt = null;
        });
    });

    closeBtn.addEventListener('click', () => {
        installPrompt.style.display = 'none';
        localStorage.setItem('pwa-prompt-dismissed', 'true');
    });

    // Detectar si ya está instalada
    window.addEventListener('appinstalled', (evt) => {
        console.log('Dominican Travel App instalada con éxito');
        installPrompt.style.display = 'none';
    });
</script>
</body>
</html>