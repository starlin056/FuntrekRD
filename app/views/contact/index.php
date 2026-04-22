<?php $customCss = ['modules/contact.css']; ?>
<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<!-- =====================================================
     HERO CONTACTO – versión sutil pero coherente
===================================================== -->
<section class="contact-hero">
    <div class="contact-hero-bg" id="contactHeroBg"></div>
    <div class="contact-grain" aria-hidden="true"></div>

    <div class="container contact-hero-content">
        <div class="contact-hero-inner text-center" data-anim="fadeUp" data-delay="0">
            <div class="contact-eyebrow">
                <span class="contact-pulse"></span>
                <i class="fas fa-envelope"></i>
                <?= Translator::getInstance()->get('contact') ?? 'Contacto' ?>
            </div>
            <h1 class="contact-title">
                <?= Translator::getInstance()->get('contact_title') ?? 'Contact FunTrek RD' ?>
                <span class="contact-title-bar"></span>
            </h1>
            <p class="contact-subtitle">
                <?= Translator::getInstance()->get('contact_subtitle') ?? 'Private Transfers & Tours in Punta Cana, Dominican Republic' ?>
            </p>
        </div>
    </div>

    <!-- Olas decorativas -->
    <div class="contact-waves" aria-hidden="true">
        <svg class="contact-wave contact-wave-back" viewBox="0 0 1440 90" preserveAspectRatio="none">
            <path fill="rgba(202,240,248,.45)" d="M0,45 C240,80 480,10 720,45 C960,80 1200,20 1440,45 L1440,90 L0,90 Z" />
        </svg>
        <svg class="contact-wave contact-wave-front" viewBox="0 0 1440 90" preserveAspectRatio="none">
            <path fill="#EAF6FF" d="M0,58 C180,28 360,78 540,58 C720,38 900,72 1080,56 C1260,40 1380,62 1440,58 L1440,90 L0,90 Z" />
        </svg>
    </div>
</section>

<!-- =====================================================
     CONTENIDO PRINCIPAL (tarjetas gemelas)
===================================================== -->
<section class="contact-main">
    <div class="container">
        <div class="row g-4">

            <!-- Tarjeta: Quiénes somos -->
            <div class="col-lg-7" data-reveal data-reveal-delay="0">
                <div class="contact-card">
                    <div class="contact-card-icon">
                        <i class="fas fa-flag-checkered"></i>
                    </div>
                    <h3 class="contact-card-title"><?= Translator::getInstance()->get('who_we_are') ?? 'Who We Are' ?></h3>
                    <div class="contact-card-body">
                        <p class="contact-text">
                            <strong>FunTrek RD</strong> is a professional transportation and excursion company
                            based in <strong>Punta Cana, Dominican Republic</strong>, specializing in
                            <strong>private airport transfers</strong>, hotel transportation, and island tours.
                        </p>
                        <p class="contact-text">
                            Our priority is <strong>safety, punctuality, comfort, and personalized service</strong>.
                            Whether you need a reliable airport transfer or want to explore the island,
                            <strong>FunTrek RD is your best choice</strong>.
                        </p>
                        <ul class="contact-list">
                            <li><i class="fas fa-check-circle"></i> 🚐 <strong>Private Airport Transfers</strong></li>
                            <li><i class="fas fa-check-circle"></i> 🌴 <strong>Tours & Island Experiences</strong></li>
                            <li><i class="fas fa-check-circle"></i> ⭐ <strong>Safe, Reliable & Friendly Service</strong></li>
                        </ul>
                        <p class="contact-quote">
                            <i class="fas fa-quote-left me-2"></i>Explore the island. Feel the joy.<i class="fas fa-quote-right ms-2"></i>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tarjeta: Información de contacto -->
            <div class="col-lg-5" data-reveal data-reveal-delay="100">
                <div class="contact-card">
                    <div class="contact-card-icon">
                        <i class="fas fa-address-card"></i>
                    </div>
                    <h3 class="contact-card-title"><?= Translator::getInstance()->get('contact_info') ?? 'Contact Information' ?></h3>
                    <div class="contact-card-body">
                        <div class="contact-info-item">
                            <div class="contact-info-icon"><i class="fas fa-user-tie"></i></div>
                            <div class="contact-info-content">
                                <strong>CEO:</strong><br>
                                Edwin Ariel Hernandez
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="contact-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="contact-info-content">
                                <strong>Location:</strong><br>
                                Punta Cana, Dominican Republic
                            </div>
                        </div>
                        <div class="contact-info-item">
                            <div class="contact-info-icon"><i class="fas fa-phone-alt"></i></div>
                            <div class="contact-info-content">
                                <strong>Phone / WhatsApp:</strong><br>
                                <a href="tel:18494570890" class="contact-link"> (829) 398-8953 / (849) 457-0890</a>
                            </div>
                        </div>

                        <div class="contact-buttons mt-4">
                            <a href="https://wa.me/18293988953?text=Hello%20FunTrek%20RD,%20I%20would%20like%20more%20information"
                                target="_blank"
                                class="contact-btn-wa">
                                <i class="fab fa-whatsapp me-2"></i> Chat on WhatsApp
                            </a>
                        </div>

                        <hr class="contact-divider">

                        <div class="contact-social">
                            <h6 class="contact-social-title">Follow Us</h6>

                            <a href="https://instagram.com/FunTrekRD" target="_blank" class="contact-social-link">
                                <i class="fab fa-instagram me-2"></i> @FunTrekRD
                            </a>

                            <a href="https://www.facebook.com/people/Funtrek-RD/pfbid0p5LwNvnSHpD8kpESwKWGtBBnnpCMeXuZyy94P165SDxkkxGyv35RsWXi9zUQ86HAl/?rdid=9xYV0CF7hSCg6hRo&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F15fJrXRSv34%2F"
                                target="_blank"
                                class="contact-social-link">
                                <i class="fab fa-facebook me-2"></i> FunTrek RD
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <!-- Mapa (tarjeta independiente) -->
    <div class="mt-5" data-reveal data-reveal-delay="200">
        <div class="contact-map-card">
            <div class="contact-map-header">
                <i class="fas fa-map-marked-alt"></i>
                <span><?= Translator::getInstance()->get('our_location') ?? 'Our Location' ?></span>
            </div>
            <div class="contact-map-body">
                <iframe
                    src="https://www.google.com/maps?q=Punta%20Cana%20Dominican%20Republic&output=embed"
                    width="100%"
                    height="380"
                    style="border:0; border-radius: 0 0 20px 20px;"
                    allowfullscreen=""
                    loading="lazy">
                </iframe>
            </div>
        </div>
    </div>
    </div>
</section>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>
<script>
    (function() {
        'use strict';

        // Animación de entrada del hero
        function initHeroAnims() {
            document.querySelectorAll('[data-anim]').forEach(el => {
                const delay = parseInt(el.dataset.delay || 0);
                setTimeout(() => el.classList.add('anim-in'), delay);
            });
        }

        // Olas
        function initWaves() {
            const waves = ['.contact-wave-back', '.contact-wave-front'];
            waves.forEach((sel, i) => {
                const el = document.querySelector(sel);
                if (el) setTimeout(() => el.classList.add('wave-enter'), i * 180);
            });
        }

        // Scroll reveal
        function initReveal() {
            const obs = new IntersectionObserver(entries => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        const delay = parseInt(e.target.dataset.revealDelay || 0);
                        setTimeout(() => e.target.classList.add('revealed'), delay);
                        obs.unobserve(e.target);
                    }
                });
            }, {
                threshold: .12,
                rootMargin: '0px 0px -40px 0px'
            });
            document.querySelectorAll('[data-reveal]').forEach(el => obs.observe(el));
        }

        // Parallax sutil en el fondo del hero
        function initParallax() {
            const bg = document.querySelector('.contact-hero-bg');
            if (!bg || window.matchMedia('(prefers-reduced-motion:reduce)').matches) return;
            let tick = false;
            window.addEventListener('scroll', () => {
                if (!tick) {
                    requestAnimationFrame(() => {
                        bg.style.transform = `translateY(${window.scrollY * 0.2}px)`;
                        tick = false;
                    });
                    tick = true;
                }
            }, {
                passive: true
            });
        }

        document.addEventListener('DOMContentLoaded', () => {
            initHeroAnims();
            initWaves();
            initReveal();
            initParallax();
        });
    })();
</script>
