<nav class="navbar navbar-expand-lg navbar-modern fixed-top">
    <div class="container-fluid px-6">

        <!-- LOGO -->
        <a class="navbar-brand fw-bold d-flex align-items-center text-white" href="<?= APP_URL ?>/">
            <img src="<?= APP_URL ?>/assets/images/logo-mini1.png" alt="" class="navbar-logo me-8">
            <!-- <i class="fas fa-plane-departure me-6 text-warning"></i>

            &nbsp;<span class="text-warning"></span> -->

        </a>

        <!-- TOGGLE MOBILE -->
        <button class="navbar-toggler border-0 text-white" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- MENU -->
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-3">

                <li class="nav-item">
                    <a class="nav-link nav-link-modern" href="<?= APP_URL ?>/">
                        <?= __('nav_home') ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-link-modern" href="<?= APP_URL ?>/paquetes">
                        <?= __('nav_packages') ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-link-modern" href="<?= APP_URL ?>/excursiones">
                        <?= __('nav_excursions') ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-link-modern" href="<?= APP_URL ?>/transfers">
                        <?= __('nav_transfers') ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-link-modern" href="<?= APP_URL ?>/Brokers">
                        <?= __('nav_brokers') ?>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link nav-link-modern" href="<?= APP_URL ?>/contacto">
                        <?= __('nav_contact') ?>
                    </a>
                </li>


                <!-- USUARIO -->
                <?php if (!empty($_SESSION['logged_in'])): ?>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle nav-link-modern" href="#" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <i class="fas fa-user-circle me-1"></i>
                            <?= htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8') ?>
                        </a>

                        <ul class="dropdown-menu dropdown-menu-end shadow border-0">

                            <?php if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                                <li>
                                    <a class="dropdown-item" href="<?= APP_URL ?>/admin/dashboard">
                                        <i class="fas fa-gauge-high me-2"></i>
                                        <?= __('nav.admin_panel') ?>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="<?= APP_URL ?>/admin/quotations">
                                        <i class="fas fa-file-invoice-dollar me-2"></i>
                                        Cotizaciones
                                    </a>
                                </li>
                            <?php else: ?>
                                <li>
                                    <a class="dropdown-item" href="<?= APP_URL ?>/dashboard">
                                        <i class="fas fa-gauge me-2"></i>
                                        <?= __('nav.dashboard') ?>
                                    </a>
                                </li>
                            <?php endif; ?>

                            <li>
                                <hr class="dropdown-divider">
                            </li>

                            <li>
                                <a class="dropdown-item text-danger" href="<?= APP_URL ?>/auth/logout">
                                    <i class="fas fa-right-from-bracket me-2"></i>
                                    <?= __('nav.logout') ?>
                                </a>
                            </li>
                        </ul>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link nav-link-modern" href="<?= APP_URL ?>/auth/login">
                            <i class="fas fa-user me-1"></i>
                            <?= __('nav.login') ?>
                        </a>
                    </li>
                <?php endif; ?>

                <!-- CAMBIO DE IDIOMA -->
                <li class="nav-item dropdown ms-lg-3 mt-3 mt-lg-0">
                    <a class="btn btn-cta px-4 dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown"
                        aria-expanded="false" id="current_language_display">
                        <i class="fas fa-globe me-2"></i> ES
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow border-0">
                        <li>
                            <a class="dropdown-item fw-semibold" href="javascript:void(0);"
                                onclick="changeLanguage('es')">
                                Español
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item fw-semibold" href="javascript:void(0);"
                                onclick="changeLanguage('en')">
                                English
                            </a>
                        </li>
                    </ul>
                </li>
                <script>
                    function changeLanguage(langCode) {
                        var selectField = document.querySelector("#google_translate_element select");
                        if (!selectField) {
                            console.warn("Google Translate no está listo aún");
                            return;
                        }
                        selectField.value = langCode;
                        selectField.dispatchEvent(new Event('change'));
                        document.getElementById('current_language_display').innerHTML = '<i class="fas fa-globe me-2"></i> ' + langCode.toUpperCase();
                    }

                    document.addEventListener("DOMContentLoaded", function () {
                        var match = document.cookie.match(/googtrans=\/es\/([a-z]{2})/);
                        if (match && match[1]) {
                            document.getElementById('current_language_display').innerHTML = '<i class="fas fa-globe me-2"></i> ' + match[1].toUpperCase();
                        }
                    });
                </script>

            </ul>
        </div>
    </div>
</nav>