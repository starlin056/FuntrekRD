<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<!-- =====================================================
     HERO BROKERS
===================================================== -->
<section class="brokers-hero">
    <div class="brokers-hero-bg" id="brokersHeroBg"></div>
    <div class="brokers-grain" aria-hidden="true"></div>

    <div class="container brokers-hero-content">
        <div class="brokers-hero-inner text-center" data-anim="fadeUp" data-delay="0">
            <div class="brokers-eyebrow">
                <span class="brokers-pulse"></span>
                <i class="fas fa-handshake"></i>
                <?= Translator::getInstance()->get('brokers') ?? 'Brokers / Partners' ?>
            </div>
            <h1 class="brokers-title">
                <?= Translator::getInstance()->get('brokers_title') ?? 'Inversiones y Propiedades en República Dominicana' ?>
                <span class="brokers-title-bar"></span>
            </h1>
            <p class="brokers-subtitle">
                <?= Translator::getInstance()->get('brokers_subtitle') ?? 'Descubre cómo invertir en bienes raíces con el respaldo de FunTrek RD' ?>
            </p>
        </div>
    </div>

    <!-- Olas decorativas -->
    <div class="brokers-waves" aria-hidden="true">
        <svg class="brokers-wave brokers-wave-back" viewBox="0 0 1440 90" preserveAspectRatio="none">
            <path fill="rgba(202,240,248,.45)" d="M0,45 C240,80 480,10 720,45 C960,80 1200,20 1440,45 L1440,90 L0,90 Z" />
        </svg>
        <svg class="brokers-wave brokers-wave-front" viewBox="0 0 1440 90" preserveAspectRatio="none">
            <path fill="#EAF6FF" d="M0,58 C180,28 360,78 540,58 C720,38 900,72 1080,56 C1260,40 1380,62 1440,58 L1440,90 L0,90 Z" />
        </svg>
    </div>
</section>

<!-- =====================================================
     CONTENIDO PRINCIPAL
===================================================== -->
<section class="brokers-main">
    <div class="container">
        <div class="row g-4">

            <!-- Tarjeta: Guía para comprar propiedades en RD -->
            <div class="col-lg-7" data-reveal data-reveal-delay="0">
                <div class="brokers-card">
                    <div class="brokers-card-icon">
                        <i class="fas fa-home"></i>
                    </div>
                    <h3 class="brokers-card-title"><?= Translator::getInstance()->get('buy_property_guide') ?? 'Cómo comprar una casa en República Dominicana siendo extranjero' ?></h3>
                    <div class="brokers-card-body">
                        <p class="brokers-text">
                            A diferencia de otros países, en <strong>República Dominicana los extranjeros pueden comprar propiedades con los mismos derechos que los ciudadanos locales</strong>. No se requiere residencia ni ciudadanía para adquirir bienes inmuebles. A continuación, te explicamos los pasos generales:
                        </p>

                        <div class="brokers-step">
                            <div class="brokers-step-number">1</div>
                            <div class="brokers-step-content">
                                <strong>Elegir la propiedad adecuada</strong><br>
                                Encontrarás diversos proyectos adaptados a todos los bolsillos y en diferentes ubicaciones del país. Es recomendable contar con un <strong>agente inmobiliario con experiencia</strong> en el mercado local.
                            </div>
                        </div>

                        <div class="brokers-step">
                            <div class="brokers-step-number">2</div>
                            <div class="brokers-step-content">
                                <strong>Revisión legal y debida diligencia</strong><br>
                                Es fundamental contratar a un <strong>abogado local</strong> para verificar el título de propiedad, la existencia de gravámenes y el historial legal del inmueble.
                            </div>
                        </div>

                        <div class="brokers-step">
                            <div class="brokers-step-number">3</div>
                            <div class="brokers-step-content">
                                <strong>Firma del contrato de compraventa</strong><br>
                                Una vez confirmada la legalidad, se redacta y firma un contrato que incluye precio, condiciones y plazos.
                            </div>
                        </div>

                        <div class="brokers-step">
                            <div class="brokers-step-number">4</div>
                            <div class="brokers-step-content">
                                <strong>Pago y transferencia de propiedad</strong><br>
                                El comprador transfiere el pago, normalmente mediante una cuenta bancaria en República Dominicana. Posteriormente, el abogado se encarga de registrar el inmueble en la Jurisdicción Inmobiliaria.
                            </div>
                        </div>

                        <div class="brokers-step">
                            <div class="brokers-step-number">5</div>
                            <div class="brokers-step-content">
                                <strong>Registro de la propiedad</strong><br>
                                El último paso es registrar la propiedad a nombre del comprador extranjero. Este proceso suele tardar unas semanas y garantiza tu titularidad legal.
                            </div>
                        </div>

                        <p class="brokers-quote mt-4">
                            <i class="fas fa-quote-left me-2"></i>Confía en FunTrek RD para asesorarte en todo el proceso.<i class="fas fa-quote-right ms-2"></i>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tarjeta: Información de contacto (WhatsApp) -->
            <div class="col-lg-5" data-reveal data-reveal-delay="100">
                <div class="brokers-card">
                    <div class="brokers-card-icon">
                        <i class="fas fa-address-card"></i>
                    </div>
                    <h3 class="brokers-card-title"><?= Translator::getInstance()->get('contact_info') ?? 'Contact Information' ?></h3>
                    <div class="brokers-card-body">
                        <div class="brokers-info-item">
                            <div class="brokers-info-icon"><i class="fas fa-user-tie"></i></div>
                            <div class="brokers-info-content">
                                <strong>CEO:</strong><br>
                                Edwin Ariel Hernandez
                            </div>
                        </div>
                        <div class="brokers-info-item">
                            <div class="brokers-info-icon"><i class="fas fa-map-marker-alt"></i></div>
                            <div class="brokers-info-content">
                                <strong>Location:</strong><br>
                                Punta Cana, Dominican Republic
                            </div>
                        </div>
                        <div class="brokers-info-item">
                            <div class="brokers-info-icon"><i class="fas fa-phone-alt"></i></div>
                            <div class="brokers-info-content">
                                <strong>Phone / WhatsApp:</strong><br>
                                <a href="tel:18494570890" class="brokers-link"> (829) 398-8953 / (849) 457-0890</a>
                            </div>
                        </div>

                        <div class="brokers-buttons mt-4">
                            <a href="https://wa.me/18293988953?text=Hola%20FunTrek%20RD,%20quiero%20información%20sobre%20compra%20de%20propiedades"
                                target="_blank"
                                class="brokers-btn-wa">
                                <i class="fab fa-whatsapp me-2"></i> Consultar por WhatsApp
                            </a>
                        </div>

                        <hr class="brokers-divider">

                        <div class="brokers-social">
                            <h6 class="brokers-social-title">Follow Us</h6>

                            <a href="https://instagram.com/FunTrekRD" target="_blank" class="brokers-social-link">
                                <i class="fab fa-instagram me-2"></i> @FunTrekRD
                            </a>

                            <a href="https://www.facebook.com/people/Funtrek-RD/pfbid0p5LwNvnSHpD8kpESwKWGtBBnnpCMeXuZyy94P165SDxkkxGyv35RsWXi9zUQ86HAl/?rdid=9xYV0CF7hSCg6hRo&share_url=https%3A%2F%2Fwww.facebook.com%2Fshare%2F15fJrXRSv34%2F"
                                target="_blank"
                                class="brokers-social-link">
                                <i class="fab fa-facebook me-2"></i> FunTrek RD
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <!-- (Opcional) Se puede mantener tabla de comisiones si aún aplica, o eliminarla. Por ahora la dejo comentada.
        <div class="mt-5" data-reveal data-reveal-delay="200">
            <div class="brokers-commission-card">
                ...
            </div>
        </div>
        -->
    </div>
</section>



<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>

<!-- =====================================================
     ESTILOS (iguales a la versión anterior, más estilos para los pasos)
===================================================== -->
<style>
    @import url('https://fonts.googleapis.com/css2?family=Sora:wght@300;400;600;700;800&family=DM+Sans:opsz,wght@9..40,300;9..40,400;9..40,500&display=swap');

    :root {
        --c-deep: #002D4F;
        --c-ocean: #0077B6;
        --c-light: #00B4D8;
        --c-pale: #CAF0F8;
        --c-foam: #EAF6FF;
        --c-sand: #F9C74F;
        --c-sand-d: #E8A817;
        --c-green: #2EC4B6;
        --c-dark: #0D1B2A;
        --c-mid: #3A5A72;
        --c-muted: #6E8FA5;
        --ease: cubic-bezier(.22, 1, .36, 1);
        --fh: 'Sora', sans-serif;
        --fb: 'DM Sans', sans-serif;
    }

    body {
        font-family: var(--fb);
        background: #F0F7FC;
        margin: 0;
    }

    /* ========== HERO BROKERS ========== */
    .brokers-hero {
        position: relative;
        min-height: 50vh;
        display: flex;
        flex-direction: column;
        overflow: hidden;
        background: linear-gradient(135deg, rgba(0, 28, 52, .92) 0%, rgba(0, 100, 160, .74) 55%, rgba(0, 165, 200, .52) 100%);
    }

    .brokers-hero-bg {
        position: absolute;
        inset: -10% 0;
        background: url('https://images.homify.com/v1443654404/p/photo/image/960358/Imativa_Casa_Carrasco_0016.jpg') center/cover no-repeat;
        will-change: transform;
        opacity: .3;
        mix-blend-mode: overlay;
    }

    .brokers-grain {
        position: absolute;
        inset: 0;
        opacity: .04;
        pointer-events: none;
        background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        background-size: 180px;
        z-index: 1;
    }

    .brokers-hero-content {
        position: relative;
        z-index: 10;
        flex: 1;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 100px 0 95px;
    }

    .brokers-hero-inner {
        max-width: 800px;
    }

    [data-anim] {
        opacity: 0;
        transform: translateY(24px);
    }

    [data-anim].anim-in {
        animation: brokersIn .7s var(--ease) forwards;
    }

    @keyframes brokersIn {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .brokers-eyebrow {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, .13);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, .28);
        color: #fff;
        font-family: var(--fh);
        font-size: .72rem;
        font-weight: 700;
        letter-spacing: .10em;
        text-transform: uppercase;
        padding: 7px 16px;
        border-radius: 999px;
        margin-bottom: 20px;
    }

    .brokers-pulse {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: var(--c-sand);
        animation: pulseDot 2.2s ease-in-out infinite;
    }

    @keyframes pulseDot {

        0%,
        100% {
            box-shadow: 0 0 0 0 rgba(249, 199, 79, .7);
        }

        50% {
            box-shadow: 0 0 0 7px rgba(249, 199, 79, 0);
        }
    }

    .brokers-title {
        font-family: var(--fh);
        font-size: clamp(2rem, 4.2vw, 3.1rem);
        font-weight: 800;
        color: #fff;
        line-height: 1.1;
        letter-spacing: -.025em;
        margin-bottom: 14px;
    }

    .brokers-title-bar {
        display: block;
        width: 64px;
        height: 4px;
        background: linear-gradient(90deg, var(--c-sand), var(--c-light));
        border-radius: 99px;
        margin: 12px auto 0;
    }

    .brokers-subtitle {
        font-size: clamp(.93rem, 1.4vw, 1.05rem);
        font-weight: 300;
        color: rgba(255, 255, 255, .80);
        max-width: 620px;
        margin: 0 auto;
        line-height: 1.75;
    }

    /* Olas */
    .brokers-waves {
        position: absolute;
        bottom: 0;
        left: 0;
        width: 200%;
        height: 90px;
        z-index: 5;
        pointer-events: none;
    }

    .brokers-wave {
        position: absolute;
        bottom: 0;
        width: 100%;
        height: 90px;
        transform: translateY(90px);
    }

    .brokers-wave.wave-enter {
        animation: waveRise var(--rd, .9s) var(--ease) forwards, waveScroll var(--sd, 14s) linear var(--rd, .9s) infinite;
    }

    .brokers-wave-back {
        --rd: .85s;
        --sd: 20s;
    }

    .brokers-wave-front {
        --rd: 1.1s;
        --sd: 12s;
    }

    @keyframes waveRise {
        from {
            transform: translateY(90px);
        }

        to {
            transform: translateY(0);
        }
    }

    @keyframes waveScroll {
        from {
            transform: translateX(0);
        }

        to {
            transform: translateX(-50%);
        }
    }

    /* ========== TARJETAS ========== */
    .brokers-main {
        padding: 80px 0 100px;
        background: #F0F7FC;
    }

    [data-reveal] {
        opacity: 0;
        transform: translateY(32px);
        transition: opacity .65s var(--ease), transform .65s var(--ease);
    }

    [data-reveal].revealed {
        opacity: 1;
        transform: translateY(0);
    }

    .brokers-card {
        background: #fff;
        border-radius: 24px;
        overflow: hidden;
        box-shadow: 0 8px 32px rgba(0, 45, 79, .08);
        border: 1px solid rgba(0, 119, 182, .08);
        height: 100%;
        transition: transform .35s var(--ease), box-shadow .35s var(--ease);
    }

    .brokers-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 20px 48px rgba(0, 45, 79, .14);
    }

    .brokers-card-icon {
        background: linear-gradient(135deg, var(--c-ocean), var(--c-light));
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 32px auto 20px;
        font-size: 2rem;
        color: #fff;
        box-shadow: 0 10px 20px rgba(0, 119, 182, .25);
    }

    .brokers-card-title {
        font-family: var(--fh);
        font-size: 1.4rem;
        font-weight: 700;
        color: var(--c-dark);
        text-align: center;
        margin-bottom: 24px;
    }

    .brokers-card-body {
        padding: 0 28px 32px;
    }

    .brokers-text {
        color: var(--c-mid);
        line-height: 1.65;
        margin-bottom: 1rem;
    }

    /* Estilos para los pasos numerados */
    .brokers-step {
        display: flex;
        gap: 16px;
        margin-bottom: 20px;
        align-items: flex-start;
    }

    .brokers-step-number {
        background: linear-gradient(135deg, var(--c-ocean), var(--c-light));
        color: #fff;
        font-family: var(--fh);
        font-weight: 800;
        font-size: 1.1rem;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        box-shadow: 0 4px 10px rgba(0, 119, 182, .3);
    }

    .brokers-step-content {
        color: var(--c-mid);
        line-height: 1.55;
        font-size: .93rem;
    }

    .brokers-step-content strong {
        color: var(--c-dark);
        font-weight: 700;
    }

    .brokers-quote {
        font-family: var(--fh);
        font-style: italic;
        font-size: .95rem;
        color: var(--c-ocean);
        text-align: center;
        margin-top: 20px;
        padding-top: 12px;
        border-top: 1px dashed rgba(0, 119, 182, .2);
    }

    /* Items de contacto */
    .brokers-info-item {
        display: flex;
        gap: 16px;
        margin-bottom: 24px;
    }

    .brokers-info-icon {
        width: 40px;
        height: 40px;
        background: rgba(0, 119, 182, .08);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.2rem;
        color: var(--c-ocean);
        flex-shrink: 0;
    }

    .brokers-info-content {
        font-size: .93rem;
        color: var(--c-mid);
        line-height: 1.5;
    }

    .brokers-info-content strong {
        color: var(--c-dark);
        font-weight: 700;
    }

    .brokers-link {
        color: var(--c-ocean);
        text-decoration: none;
        font-weight: 600;
        transition: color .2s;
    }

    .brokers-link:hover {
        color: var(--c-deep);
    }

    /* Botón WhatsApp */
    .brokers-buttons {
        margin: 28px 0 20px;
    }

    .brokers-btn-wa {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        background: #25D366;
        color: #fff;
        font-family: var(--fh);
        font-weight: 700;
        font-size: .95rem;
        padding: 14px 24px;
        border-radius: 999px;
        text-decoration: none;
        transition: all .25s var(--ease);
        box-shadow: 0 6px 22px rgba(37, 211, 102, .32);
    }

    .brokers-btn-wa:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 28px rgba(37, 211, 102, .44);
        color: #fff;
        filter: brightness(1.02);
    }

    .brokers-divider {
        margin: 28px 0 20px;
        border-color: rgba(0, 119, 182, .1);
    }

    .brokers-social-title {
        font-family: var(--fh);
        font-size: .85rem;
        font-weight: 700;
        color: var(--c-muted);
        text-transform: uppercase;
        letter-spacing: .08em;
        margin-bottom: 12px;
    }

    .brokers-social-link {
        display: inline-flex;
        align-items: center;
        background: linear-gradient(135deg, #833AB4, #E1306C, #FD1D1D);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-family: var(--fh);
        font-weight: 700;
        font-size: 1rem;
        text-decoration: none;
        transition: opacity .2s;
    }

    .brokers-social-link i {
        color: #E1306C;
        background: none;
        -webkit-background-clip: unset;
        background-clip: unset;
    }

    .brokers-social-link:hover {
        opacity: .8;
    }

    /* Responsive */
    @media (max-width: 991px) {
        .brokers-hero-content {
            padding: 80px 0 70px;
        }

        .brokers-card-icon {
            width: 60px;
            height: 60px;
            font-size: 1.6rem;
        }

        .brokers-card-title {
            font-size: 1.25rem;
        }

        .brokers-card-body {
            padding: 0 20px 28px;
        }

        .brokers-step {
            gap: 12px;
        }

        .brokers-step-number {
            width: 28px;
            height: 28px;
            font-size: 1rem;
        }
    }

    @media (max-width: 768px) {
        .brokers-main {
            padding: 56px 0 70px;
        }

        .brokers-title {
            font-size: 1.9rem;
        }

        .brokers-info-item {
            gap: 12px;
        }

        .brokers-info-icon {
            width: 36px;
            height: 36px;
            font-size: 1rem;
        }
    }
</style>

<script>
    (function() {
        'use strict';

        function initHeroAnims() {
            document.querySelectorAll('[data-anim]').forEach(el => {
                const delay = parseInt(el.dataset.delay || 0);
                setTimeout(() => el.classList.add('anim-in'), delay);
            });
        }

        function initWaves() {
            const waves = ['.brokers-wave-back', '.brokers-wave-front'];
            waves.forEach((sel, i) => {
                const el = document.querySelector(sel);
                if (el) setTimeout(() => el.classList.add('wave-enter'), i * 180);
            });
        }

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

        function initParallax() {
            const bg = document.querySelector('.brokers-hero-bg');
            if (!bg || window.matchMedia('(prefers-reduced-motion:reduce)').matches) return;
            let ticking = false;
            window.addEventListener('scroll', () => {
                if (!ticking) {
                    requestAnimationFrame(() => {
                        bg.style.transform = `translateY(${window.scrollY * 0.2}px)`;
                        ticking = false;
                    });
                    ticking = true;
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