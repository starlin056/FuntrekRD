<?php $customCss = ['modules/public-home.css']; ?>
<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<!-- =====================================================
     MARQUEE SOCIAL
===================================================== -->
<div class="social-marquee" aria-label="Mensaje de impacto social">
    <div class="marquee-track">
        <div class="marquee-group">
            <span class="marquee-item"><i class="fas fa-heart text-danger me-2"></i>Por cada reserva, una familia recibe
                alimentos 🍽️</span>
            <span class="marquee-item"><i class="fas fa-seedling text-success me-2"></i>Viaja con propósito: tu aventura
                transforma vidas ✨</span>
            <span class="marquee-item"><i class="fas fa-hand-holding-heart text-warning me-2"></i>Cada paquete = comidas
                para familias en RD ❤️</span>
            <span class="marquee-item"><i class="fas fa-globe-americas text-info me-2"></i>Turismo responsable que
                construye futuro 🌎</span>
        </div>
        <div class="marquee-group" aria-hidden="true">
            <span class="marquee-item"><i class="fas fa-heart text-danger me-2"></i>Por cada reserva, una familia recibe
                alimentos 🍽️</span>
            <span class="marquee-item"><i class="fas fa-seedling text-success me-2"></i>Viaja con propósito: tu aventura
                transforma vidas ✨</span>
            <span class="marquee-item"><i class="fas fa-hand-holding-heart text-warning me-2"></i>Cada paquete = comidas
                para familias en RD ❤️</span>
            <span class="marquee-item"><i class="fas fa-globe-americas text-info me-2"></i>Turismo responsable que
                construye futuro 🌎</span>
        </div>
    </div>
</div>

<!-- =====================================================
     HERO SECTION
===================================================== -->
<section class="hero-section" id="heroSection">
    <div class="hero-bg-parallax" id="heroBg"></div>
    <div class="hero-grain" aria-hidden="true"></div>
    <canvas class="bubble-canvas" id="bubbleCanvas" aria-hidden="true"></canvas>

    <div class="hero-split-container">
        <div class="hero-split-content">
            <div class="hero-split-text" id="splitText">
                <div class="eyebrow-chip" data-anim="fadeUp" data-delay="0">
                    <span class="chip-pulse"></span>
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?= Translator::getInstance()->get('hero_country') ?></span>
                </div>
                <h1 class="hero-title" data-anim="fadeUp" data-delay="120">
                    <span class="hero-title-line"><?= Translator::getInstance()->get('hero_title') ?></span>
                    <span class="hero-title-wave">
                        <?= Translator::getInstance()->get('hero_country') ?>
                        <svg class="title-underline" viewBox="0 0 300 18" preserveAspectRatio="none">
                            <defs>
                                <linearGradient id="underlineGrad" x1="0%" y1="0%" x2="100%" y2="0%">
                                    <stop offset="0%" style="stop-color:#F9C74F" />
                                    <stop offset="50%" style="stop-color:#FFE082" />
                                    <stop offset="100%" style="stop-color:#E8A817" />
                                </linearGradient>
                            </defs>
                            <path d="M0,12 C50,2 100,18 150,8 C200,-2 250,14 300,6" fill="none"
                                stroke="url(#underlineGrad)" stroke-width="3.5" stroke-linecap="round" />
                        </svg>
                    </span>
                </h1>
                <p class="hero-subtitle" data-anim="fadeUp" data-delay="240">
                    <?= Translator::getInstance()->get('hero_subtitle') ?></p>

                <div class="hero-cta-row" data-anim="fadeUp" data-delay="360">
                    <a href="#paquetes" class="btn-primary-hero">
                        <span class="btn-content">
                            <span><?= Translator::getInstance()->get('view_packages') ?></span>
                            <span class="btn-arrow">
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2.5">
                                    <path d="M5 12h14M12 5l7 7-7 7" stroke-linecap="round" />
                                </svg>
                            </span>
                        </span>
                    </a>
                    <a href="#destinos" class="btn-ghost-hero">
                        <i class="fas fa-compass me-2"></i><?= Translator::getInstance()->get('destinations') ?>
                    </a>
                </div>

                <div class="hero-stats" data-anim="fadeUp" data-delay="480">
                    <div class="hstat">
                        <div class="hstat-row"><span class="hstat-num" data-target="500" data-suffix="+">0</span></div>
                        <span class="hstat-label">Paquetes</span>
                    </div>
                    <div class="hstat-sep"></div>
                    <div class="hstat">
                        <div class="hstat-row"><span class="hstat-num" data-target="98" data-suffix="%">0</span></div>
                        <span class="hstat-label">Satisfacción</span>
                    </div>
                    <div class="hstat-sep"></div>
                    <div class="hstat">
                        <div class="hstat-row"><span class="hstat-num" data-target="12" data-suffix="k+">0</span></div>
                        <span class="hstat-label">Viajeros</span>
                    </div>
                </div>

                <div class="hero-trust" data-anim="fadeUp" data-delay="600">
                    <span class="trust-badge"><i class="fas fa-shield-check"></i> Pago seguro</span>
                    <span class="trust-badge"><i class="fas fa-headset"></i> Soporte 24/7</span>
                    <span class="trust-badge"><i class="fas fa-undo"></i> Cancelación flexible</span>
                </div>
            </div>

            <div class="hero-split-image" id="splitImage">
                <div class="hero-scene">
                    <div class="orbital-ring ring-1"></div>
                    <div class="orbital-ring ring-2"></div>
                    <div class="img-frame">
                        <div class="img-frame-inner">
                            <img id="heroFrontImg"
                                src="https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=900&q=80"
                                alt="República Dominicana" class="hero-photo" loading="eager">
                            <div class="img-shimmer"></div>
                        </div>
                    </div>
                    <div class="float-badge badge-top" style="--delay: 0s">
                        <div class="fb-icon">🌊</div>
                        <div class="fb-info">
                            <div class="fb-label">Destino top</div>
                            <div class="fb-val">Playa Bávaro</div>
                        </div>
                    </div>
                    <div class="float-badge badge-bot" style="--delay: 0.3s">
                        <div class="fb-icon">⭐</div>
                        <div class="fb-info">
                            <div class="fb-label">Calificación</div>
                            <div class="fb-val">4.9 / 5.0</div>
                        </div>
                    </div>
                    <div class="float-badge badge-side" style="--delay: 0.6s">
                        <div class="fb-icon">🏖️</div>
                        <div class="fb-info">
                            <div class="fb-label">Viajaron hoy</div>
                            <div class="fb-val">+24 personas</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="waves-container">
        <svg class="wave wave-1" viewBox="0 0 1440 200" preserveAspectRatio="none">
            <path class="wave-path-1" d="M0,100 C360,80 720,120 1080,90 C1200,80 1320,95 1440,100 L1440,200 L0,200 Z" />
        </svg>
        <svg class="wave wave-2" viewBox="0 0 1440 200" preserveAspectRatio="none">
            <path class="wave-path-2"
                d="M0,120 C360,100 720,140 1080,110 C1200,100 1320,115 1440,120 L1440,200 L0,200 Z" />
        </svg>
        <svg class="wave wave-3" viewBox="0 0 1440 200" preserveAspectRatio="none">
            <path class="wave-path-3"
                d="M0,140 C360,120 720,160 1080,130 C1200,120 1320,135 1440,140 L1440,200 L0,200 Z" />
        </svg>
    </div>

    <a href="#paquetes" class="scroll-indicator">
        <span class="scroll-mouse"><span class="scroll-wheel"></span></span>
        <span class="scroll-text">Explorar</span>
    </a>
</section>

<!-- =====================================================
     CATEGORÍAS
===================================================== -->
<section class="categories-strip" id="categoriesStrip">
    <div class="container">
        <div class="cats-row">
            <a href="<?= APP_URL ?>/paquetes?cat=playa" class="cat-chip"><span
                    class="cat-emoji">🏖️</span><span>Playas</span></a>
            <a href="<?= APP_URL ?>/excursiones?cat=naturaleza" class="cat-chip"><span
                    class="cat-emoji">🌿</span><span>Ecoturismo</span></a>
            <a href="<?= APP_URL ?>/transfers" class="cat-chip"><span
                    class="cat-emoji">🚐</span><span>Transfers</span></a>
            <a href="<?= APP_URL ?>/paquetes?cat=aventura" class="cat-chip"><span
                    class="cat-emoji">🤿</span><span>Aventura</span></a>
            <a href="<?= APP_URL ?>/paquetes?cat=allinclusive" class="cat-chip"><span
                    class="cat-emoji">🍹</span><span>Todo incluido</span></a>
            <a href="<?= APP_URL ?>/paquetes?cat=romantico" class="cat-chip"><span
                    class="cat-emoji">💑</span><span>Romántico</span></a>
        </div>
    </div>
</section>

<!-- =====================================================
     PAQUETES DESTACADOS - SERA UI CAROUSEL
===================================================== -->
<section id="paquetes" class="packages-modern">
    <div class="container">
        <div class="section-header" data-animate="fade-up">
            <span class="section-eyebrow">Exclusivos para ti</span>
            <h2 class="section-title"><?= Translator::getInstance()->get('featured_packages') ?></h2>
            <p class="section-subtitle"><?= Translator::getInstance()->get('best_experiences') ?></p>
        </div>

        <div class="sera-carousel-wrapper" id="seraCarousel">
            <ul class="sera-carousel" id="seraCarouselList">
                <?php if (!empty($featuredPackages)): ?>
                    <?php foreach ($featuredPackages as $i => $package):
                        $category = strtolower(trim($package['category'] ?? ''));
                        $catColors = [
                            'playa' => '#00B4D8',
                            'aventura' => '#E63946',
                            'romantico' => '#E84393',
                            'familiar' => '#F9C74F',
                            'luxury' => '#B8860B',
                            'cultural' => '#9B59B6',
                            'gastronomico' => '#E67E22',
                            'naturaleza' => '#2EC4B6'
                        ];
                        $catColor = $catColors[$category] ?? '#0077B6';
                        $imageUrl = !empty($package['image'])
                            ? APP_URL . '/assets/uploads/packages/' . htmlspecialchars($package['image'])
                            : 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=600&q=80';
                        ?>
                        <li class="sera-carousel-item <?= $i === 0 ? 'active' : '' ?>" data-index="<?= $i ?>"
                            aria-current="<?= $i === 0 ? 'true' : 'false' ?>">
                            <div class="sera-card">
                                <div class="sera-card-image">
                                    <img src="<?= $imageUrl ?>" alt="<?= htmlspecialchars($package['name']) ?>"
                                        loading="<?= $i < 2 ? 'eager' : 'lazy' ?>">
                                    <div class="sera-card-overlay"></div>
                                </div>
                                <div class="sera-card-content">
                                    <span class="sera-card-category" style="--cat-color: <?= $catColor ?>">
                                        <?= htmlspecialchars($package['category'] ?? '') ?>
                                    </span>
                                    <h3 class="sera-card-title"><?= htmlspecialchars($package['name']) ?></h3>
                                    <a href="<?= APP_URL ?>/paquetes#pkg-<?= $package['id'] ?>" class="sera-card-link">
                                        Ver detalles <i class="fas fa-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                <?php else: ?>
                    <li class="sera-carousel-item active" aria-current="true">
                        <div class="sera-card">
                            <div class="sera-card-placeholder">
                                <i class="fas fa-suitcase"></i>
                                <p><?= Translator::getInstance()->get('no_packages') ?></p>
                            </div>
                        </div>
                    </li>
                <?php endif; ?>
            </ul>

            <?php if (!empty($featuredPackages) && count($featuredPackages) > 1): ?>
                <div class="sera-carousel-controls">
                    <button class="sera-nav-btn sera-prev" aria-label="Anterior">
                        <i class="fas fa-chevron-left"></i>
                    </button>
                    <div class="sera-indicators" id="seraIndicators"></div>
                    <button class="sera-nav-btn sera-next" aria-label="Siguiente">
                        <i class="fas fa-chevron-right"></i>
                    </button>
                </div>
            <?php endif; ?>
        </div>

        <div class="packages-cta" data-animate="fade-up">
            <a href="<?= APP_URL ?>/paquetes" class="btn-view-all">
                <span>Ver todos los paquetes</span>
                <i class="fas fa-arrow-right-long"></i>
            </a>
        </div>
    </div>
</section>

<!-- =====================================================
     CTA FINAL
===================================================== -->
<section class="cta-section">
    <div class="container">
        <div class="cta-wrapper" data-reveal>
            <div class="cta-content">
                <h2 class="cta-title">¿Listo para tu próxima aventura?</h2>
                <p class="cta-subtitle">Únete a más de 12,000 viajeros que ya descubrieron el paraíso con nosotros</p>
                <div class="cta-actions">
                    <a href="<?= APP_URL ?>/paquetes" class="btn-cta-primary">
                        <span>Explorar paquetes</span><i class="fas fa-arrow-right"></i>
                    </a>
                    <a href="<?= APP_URL ?>/contacto" class="btn-cta-secondary">
                        <i class="fas fa-comment-dots"></i><span>Consultar con un asesor</span>
                    </a>
                </div>
                <div class="cta-trust">
                    <span class="trust-signal"><i class="fas fa-lock"></i> Pago seguro SSL</span>
                    <span class="trust-signal"><i class="fas fa-badge-check"></i> Certificado TripAdvisor</span>
                    <span class="trust-signal"><i class="fas fa-headset"></i> Soporte en español</span>
                </div>
            </div>
            <div class="cta-visual">
                <div class="cta-image-stack">
                    <img src="https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=400&q=80"
                        alt="" loading="lazy">
                    <img src="https://images.unsplash.com/photo-1506929562872-bb421503ef21?auto=format&fit=crop&w=400&q=80"
                        alt="" loading="lazy">
                    <img src="https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=400&q=80"
                        alt="" loading="lazy">
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =====================================================
     FLOATING WHATSAPP
===================================================== -->
<div class="whatsapp-float-container">
    <a href="https://wa.me/18293988953?text=Hello%20FunTrek%20RD,%20I%20would%20like%20more%20information"
        target="_blank"
        class="whatsapp-float-btn">
        <i class="fab fa-whatsapp"></i>
        <span>Chat on WhatsApp</span>
    </a>
</div>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>


<!-- =====================================================
     JAVASCRIPT UNIFICADO Y OPTIMIZADO (sin Lightswind)
===================================================== -->
<script>
    (function () {
        'use strict';
        const prm = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        /* ---------- Hero parallax ---------- */
        function initParallax() {
            const bg = document.getElementById('heroBg');
            if (!bg || prm) return;
            let ticking = false;
            window.addEventListener('scroll', () => {
                if (!ticking) {
                    requestAnimationFrame(() => {
                        bg.style.transform = `translateY(${window.scrollY * 0.32}px)`;
                        ticking = false;
                    });
                    ticking = true;
                }
            }, {
                passive: true
            });
        }

        /* ---------- Bubbles canvas ---------- */
        function initBubbles() {
            const canvas = document.getElementById('bubbleCanvas');
            if (!canvas) return;
            const ctx = canvas.getContext('2d');
            let width, height, bubbles = [];

            function resize() {
                width = canvas.width = canvas.offsetWidth;
                height = canvas.height = canvas.offsetHeight;
            }
            window.addEventListener('resize', resize);
            resize();

            function createBubble() {
                return {
                    x: Math.random() * width,
                    y: height + 20,
                    r: Math.random() * 4 + 2,
                    speed: Math.random() * 0.5 + 0.2,
                    drift: (Math.random() - 0.5) * 0.3,
                    opacity: Math.random() * 0.5 + 0.1
                };
            }

            for (let i = 0; i < 40; i++) {
                const b = createBubble();
                b.y = Math.random() * height;
                bubbles.push(b);
            }

            function draw() {
                if (!ctx) return;
                ctx.clearRect(0, 0, width, height);
                bubbles.forEach((b, i) => {
                    ctx.beginPath();
                    ctx.arc(b.x, b.y, b.r, 0, Math.PI * 2);
                    ctx.strokeStyle = `rgba(255,255,255,${b.opacity})`;
                    ctx.lineWidth = 1;
                    ctx.stroke();
                    b.y -= b.speed;
                    b.x += b.drift;
                    b.drift += (Math.random() - 0.5) * 0.04;
                    if (b.y < -20) bubbles[i] = createBubble();
                });
                requestAnimationFrame(draw);
            }
            draw();
        }

        /* ---------- Fade-in animations ---------- */
        function initHeroAnims() {
            document.querySelectorAll('[data-anim]').forEach(el => {
                const delay = parseInt(el.dataset.delay || 0);
                setTimeout(() => el.classList.add('anim-in'), delay);
            });
        }

        /* ---------- Counters (IntersectionObserver) ---------- */
        function initCounters() {
            const counters = document.querySelectorAll('.hstat-num');
            if (!counters.length) return;

            const animateCounter = (el) => {
                const target = parseInt(el.dataset.target);
                const suffix = el.dataset.suffix || '';
                const duration = 1600;
                const startTime = performance.now();

                const update = (now) => {
                    const elapsed = now - startTime;
                    const progress = Math.min(elapsed / duration, 1);
                    const eased = 1 - Math.pow(1 - progress, 3);
                    el.textContent = Math.floor(eased * target) + suffix;
                    if (progress < 1) requestAnimationFrame(update);
                    else el.textContent = target + suffix;
                };
                requestAnimationFrame(update);
            };

            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        animateCounter(entry.target);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.6
            });

            counters.forEach(c => observer.observe(c));
        }

        /* ---------- Hero image rotation ---------- */
        function initImgRotation() {
            const img = document.getElementById('heroFrontImg');
            if (!img) return;
            const srcs = [
                'https://images.unsplash.com/photo-1506929562872-bb421503ef21?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1501785888041-af3ef285b470?auto=format&fit=crop&w=900&q=80',
                'https://images.unsplash.com/photo-1559494007-9f5847c49d94?auto=format&fit=crop&w=900&q=80'
            ];
            let idx = 0;
            setInterval(() => {
                idx = (idx + 1) % srcs.length;
                img.style.opacity = '0';
                setTimeout(() => {
                    img.src = srcs[idx];
                    img.style.opacity = '1';
                }, 500);
            }, 5000);
        }

        /* ---------- Reveal on scroll ---------- */
        function initReveal() {
            const elements = document.querySelectorAll('[data-reveal]');
            if (!elements.length) return;
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const delay = parseInt(entry.target.dataset.revealDelay || 0);
                        setTimeout(() => entry.target.classList.add('revealed'), delay);
                        observer.unobserve(entry.target);
                    }
                });
            }, {
                threshold: 0.12,
                rootMargin: '0px 0px -40px 0px'
            });
            elements.forEach(el => observer.observe(el));
        }

        /* ---------- Sticky categories strip ---------- */
        function initStickyCategories() {
            const strip = document.getElementById('categoriesStrip');
            if (!strip) return;
            const observer = new IntersectionObserver(([entry]) => {
                strip.classList.toggle('sticky-blur', !entry.isIntersecting);
            }, {
                threshold: 0,
                rootMargin: '-1px 0px 0px 0px'
            });
            observer.observe(strip);
        }

        /* ---------- SERA CAROUSEL (UI principal) ---------- */
        function initSeraCarousel() {
            const carousel = document.getElementById('seraCarouselList');
            if (!carousel) return;
            const items = Array.from(carousel.querySelectorAll('.sera-carousel-item'));
            if (items.length === 0) return;

            let activeIndex = 0;
            const indicatorsContainer = document.getElementById('seraIndicators');
            const prevBtn = document.querySelector('.sera-prev');
            const nextBtn = document.querySelector('.sera-next');

            // Create indicators if multiple items
            if (indicatorsContainer && items.length > 1) {
                indicatorsContainer.innerHTML = '';
                items.forEach((_, idx) => {
                    const dot = document.createElement('button');
                    dot.className = 'sera-indicator' + (idx === 0 ? ' active' : '');
                    dot.addEventListener('click', () => setActiveItem(idx));
                    indicatorsContainer.appendChild(dot);
                });
            }

            function updateIndicators() {
                if (!indicatorsContainer) return;
                const dots = indicatorsContainer.querySelectorAll('.sera-indicator');
                dots.forEach((dot, i) => {
                    dot.classList.toggle('active', i === activeIndex);
                });
            }

            function setActiveItem(index) {
                activeIndex = index;
                items.forEach((item, i) => {
                    const isCurrent = i === activeIndex;
                    item.setAttribute('aria-current', isCurrent ? 'true' : 'false');
                    item.classList.toggle('active', isCurrent);
                });
                updateIndicators();
            }

            // Click on items
            items.forEach((item, idx) => {
                item.addEventListener('click', () => setActiveItem(idx));
            });

            // Navigation buttons
            if (prevBtn) {
                prevBtn.addEventListener('click', () => {
                    const newIndex = activeIndex === 0 ? items.length - 1 : activeIndex - 1;
                    setActiveItem(newIndex);
                });
            }
            if (nextBtn) {
                nextBtn.addEventListener('click', () => {
                    const newIndex = activeIndex === items.length - 1 ? 0 : activeIndex + 1;
                    setActiveItem(newIndex);
                });
            }

            // Touch support
            let touchStartX = 0;
            carousel.addEventListener('touchstart', (e) => {
                touchStartX = e.touches[0].clientX;
            }, {
                passive: true
            });
            carousel.addEventListener('touchend', (e) => {
                const diff = touchStartX - e.changedTouches[0].clientX;
                if (Math.abs(diff) > 50) {
                    if (diff > 0) {
                        const newIndex = activeIndex === items.length - 1 ? 0 : activeIndex + 1;
                        setActiveItem(newIndex);
                    } else {
                        const newIndex = activeIndex === 0 ? items.length - 1 : activeIndex - 1;
                        setActiveItem(newIndex);
                    }
                }
            }, {
                passive: true
            });
        }

        /* ---------- Initialize all ---------- */
        document.addEventListener('DOMContentLoaded', () => {
            initParallax();
            initBubbles();
            initHeroAnims();
            initCounters();
            initImgRotation();
            initReveal();
            initStickyCategories();
            initSeraCarousel();
        });
    })();
</script>