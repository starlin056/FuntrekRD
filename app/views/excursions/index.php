<?php $customCss = ['modules/public-excursions.css']; ?>
<?php include APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php include APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<?php
/* ─────────────────────────────────────────────
   HELPERS DE VISTA
───────────────────────────────────────────── */
$appUrl = defined('APP_URL') ? APP_URL : '';

function excCatColor(string $cat): string
{
    $map = [
        'aventura' => '#e74c3c',
        'adventure' => '#e74c3c',
        'cultura' => '#9b59b6',
        'culture' => '#9b59b6',
        'cultural' => '#9b59b6',
        'playa' => '#00B4D8',
        'beach' => '#00B4D8',
        'naturaleza' => '#2EC4B6',
        'nature' => '#2EC4B6',
        'familia' => '#F9C74F',
        'familiar' => '#F9C74F',
        'family' => '#F9C74F',
        'nocturno' => '#2c3e50',
        'nightlife' => '#2c3e50',
        'gastronomía' => '#e67e22',
        'gastronomia' => '#e67e22',
        'deporte' => '#27ae60',
        'sport' => '#27ae60',
    ];
    return $map[strtolower(trim($cat))] ?? '#0077B6';
}

function excCatIcon(string $cat): string
{
    $map = [
        'aventura' => 'fa-mountain',
        'adventure' => 'fa-mountain',
        'cultura' => 'fa-landmark',
        'culture' => 'fa-landmark',
        'cultural' => 'fa-landmark',
        'playa' => 'fa-umbrella-beach',
        'beach' => 'fa-umbrella-beach',
        'naturaleza' => 'fa-tree',
        'nature' => 'fa-tree',
        'familia' => 'fa-people-roof',
        'familiar' => 'fa-people-roof',
        'family' => 'fa-people-roof',
        'nocturno' => 'fa-moon',
        'nightlife' => 'fa-moon',
        'gastronomía' => 'fa-utensils',
        'gastronomia' => 'fa-utensils',
        'deporte' => 'fa-bicycle',
        'sport' => 'fa-bicycle',
    ];
    return $map[strtolower(trim($cat))] ?? 'fa-compass';
}

function starRating(float $r): string
{
    $f = floor($r);
    $h = ($r - $f) >= 0.5 ? 1 : 0;
    $e = 5 - $f - $h;
    $o = '';
    for ($i = 0; $i < $f; $i++)
        $o .= '<i class="fas fa-star"></i>';
    if ($h)
        $o .= '<i class="fas fa-star-half-alt"></i>';
    for ($i = 0; $i < $e; $i++)
        $o .= '<i class="far fa-star"></i>';
    return $o;
}

function buildGallery($exc, string $appUrl): array
{
    $imgs = [];
    $gallery = $exc['gallery'] ?? [];

    // Si es un string JSON, lo decodificamos
    if (is_string($gallery)) {
        $gallery = json_decode($gallery, true) ?: [];
    }

    if (!empty($gallery) && is_array($gallery)) {
        foreach ($gallery as $g) {
            $g = trim($g);
            if ($g)
                $imgs[] = $appUrl . '/assets/uploads/excursions/' . $g;
        }
    }
    if (!empty($exc['image'])) {
        $main = $appUrl . '/assets/uploads/excursions/' . $exc['image'];
        if (!in_array($main, $imgs))
            array_unshift($imgs, $main);
    }
    return $imgs;
}

/* Sugerencias para autocompletado */
$allNames = array_column($suggestions ?? [], 'name');
$allLocs = array_column($suggestions ?? [], 'location');
$allCats = array_map(fn($c) => $c['category'], $categories ?? []);
$suggestJson = json_encode(array_values(array_unique(array_merge($allNames, $allLocs, $allCats))));

/* Categorías para chips del planificador */
$catChips = array_map(fn($c) => $c['category'], $categories ?? []);

/* Filtros activos */
$fKeyword = htmlspecialchars($filters['keyword'] ?? '');
$fCat = htmlspecialchars($filters['category'] ?? '');
$fMinP = htmlspecialchars($filters['min_price'] ?? '');
$fMaxP = htmlspecialchars($filters['max_price'] ?? '');
$fSort = htmlspecialchars($filters['sort'] ?? '');
$maxPrice = number_format((float) ($priceRange['max_price'] ?? 1000), 0);
?>

<!-- ════════════════════════════════════════
     HERO
════════════════════════════════════════ -->
<section class="exc-hero">
    <div class="exc-hero-bg" id="excHeroBg"></div>
    <div class="exc-grain" aria-hidden="true"></div>
    <div class="container exc-hero-content">
        <div class="exc-hero-inner">
            <div class="exc-eyebrow" data-anim data-delay="0">
                <span class="exc-pulse"></span>
                <i class="fas fa-mountain-sun"></i>
                <?= function_exists('__') ? __('nav_excursions') : 'Excursiones' ?>
            </div>
            <h1 class="exc-title" data-anim data-delay="120">
                <?= function_exists('__') ? __('excursion_title') : 'Aventuras Únicas en República Dominicana' ?>
                <span class="exc-title-bar"></span>
            </h1>
            <p class="exc-subtitle" data-anim data-delay="240">
                <?= function_exists('__') ? __('excursion_subtitle') : 'Descubre paraísos naturales, cultura local y emociones únicas con nuestros guías expertos.' ?>
            </p>
            <div class="exc-hero-chips" data-anim data-delay="320">
                <div class="exc-hchip"><i class="fas fa-sun"></i><span>Clima tropical</span></div>
                <div class="exc-hchip"><i class="fas fa-shield-halved"></i><span>Guías certificados</span></div>
                <div class="exc-hchip"><i class="fas fa-users"></i><span>Grupos pequeños</span></div>
                <div class="exc-hchip"><i class="fas fa-star"></i><span>4.8★ Calificación</span></div>
            </div>
        </div>
    </div>
    <div class="exc-waves" aria-hidden="true">
        <svg class="exc-wave exc-wave-b" viewBox="0 0 1440 90" preserveAspectRatio="none">
            <path fill="rgba(202,240,248,.45)"
                d="M0,45 C240,80 480,10 720,45 C960,80 1200,20 1440,45 L1440,90 L0,90 Z" />
        </svg>
        <svg class="exc-wave exc-wave-f" viewBox="0 0 1440 90" preserveAspectRatio="none">
            <path fill="#EAF6FF"
                d="M0,58 C180,28 360,78 540,58 C720,38 900,72 1080,56 C1260,40 1380,62 1440,58 L1440,90 L0,90 Z" />
        </svg>
    </div>
</section>

<!-- ════════════════════════════════════════
     BANNER RESULTADOS
════════════════════════════════════════ -->
<?php if ($hasFilters): ?>
    <section class="exc-results-banner">
        <div class="container">
            <div class="exc-rb-inner">
                <span class="exc-rb-count"><i class="fas fa-list-check me-2"></i><?= $totalCount ?>
                    resultado<?= $totalCount !== 1 ? 's' : '' ?></span>
                <?php if ($fKeyword): ?><span class="exc-rb-tag">«<?= $fKeyword ?>»</span><?php endif; ?>
                <?php if ($fCat): ?><span class="exc-rb-tag"><?= $fCat ?></span><?php endif; ?>
                <?php if ($fMinP || $fMaxP): ?><span class="exc-rb-tag">$<?= $fMinP ?: '0' ?> –
                        $<?= $fMaxP ?: '∞' ?></span><?php endif; ?>
                <a href="<?= $appUrl ?>/excursions" class="exc-rb-clear"><i class="fas fa-times me-1"></i>Limpiar</a>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- ════════════════════════════════════════
     TABS (sin filtros activos)
════════════════════════════════════════ -->
<?php if (!$hasFilters && !empty($categories)): ?>
    <section class="exc-cats-section" id="cats-nav" aria-label="Filtro de categorías">
        <div class="container">
            <div class="exc-cats-wrapper">
                <div class="exc-cats-scroll" role="tablist">
                    <button class="exc-cat-tab active" data-cat="all" role="tab" aria-selected="true">
                        <i class="fas fa-globe me-2" aria-hidden="true"></i>Todas
                        <span class="exc-cat-count" data-count="<?= count($excursions) ?>"><?= count($excursions) ?></span>
                    </button>
                    <?php foreach ($categories as $cat):
                        $catName = htmlspecialchars($cat['category']);
                        $catSlug = htmlspecialchars(strtolower(trim($cat['category'])));
                        $catColor = excCatColor($cat['category']);
                        $catIcon = excCatIcon($cat['category']);
                        $catCount = (int) $cat['count'];
                        ?>
                        <button class="exc-cat-tab" data-cat="<?= $catSlug ?>" data-color="<?= $catColor ?>" role="tab"
                            aria-selected="false">
                            <i class="fas <?= $catIcon ?> me-2" aria-hidden="true"></i>
                            <?= $catName ?>
                            <span class="exc-cat-count" data-count="<?= $catCount ?>"><?= $catCount ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- ════════════════════════════════════════
     GRID DE EXCURSIONES
════════════════════════════════════════ -->
<section class="exc-grid-section">
    <div class="container">
        <?php if (empty($excursions)): ?>
            <div class="exc-empty" data-reveal>
                <div class="exc-empty-icon">🌿</div>
                <h3>Sin resultados</h3>
                <p>No encontramos excursiones con esos criterios. Prueba con otros filtros.</p>
                <a href="<?= $appUrl ?>/excursions" class="exc-empty-btn"><i class="fas fa-redo me-2"></i>Ver todas</a>
            </div>
        <?php else: ?>
            <?php if (!$hasFilters): ?>
                <div class="exc-sec-head" data-reveal>
                    <div class="exc-sec-eyebrow">Catálogo completo</div>
                    <h2 class="exc-sec-title" id="gridTitle"><?= count($excursions) ?> experiencias disponibles</h2>
                    <p class="exc-sec-sub">Cada excursión incluye guía local certificado, transporte y seguro básico</p>
                </div>
            <?php endif; ?>

            <div class="row g-4" id="excursionsGrid">
                <?php foreach ($excursions as $i => $exc):
                    $gallery = buildGallery($exc, $appUrl);
                    $hasGal = count($gallery) > 1;
                    $mainImg = $gallery[0] ?? null;
                    $cc = excCatColor($exc['category'] ?? '');
                    $ci = excCatIcon($exc['category'] ?? '');
                    $rating = (float) ($exc['rating'] ?? 5.0);
                    $reviews = (int) ($exc['reviews_count'] ?? 0);
                    $excCat = strtolower(trim($exc['category'] ?? 'general'));
                    $maxPeople = (int) ($exc['max_people'] ?? 0);
                    $gJson = htmlspecialchars(json_encode($gallery));
                    ?>
                    <div class="col-lg-4 col-md-6 exc-card-col" data-reveal data-reveal-delay="<?= ($i % 3) * 80 ?>"
                        data-cat="<?= htmlspecialchars($excCat) ?>">
                        <article class="exc-card">
                            <!-- IMAGEN -->
                            <div class="exc-img-wrap">
                                <?php if ($hasGal): ?>
                                    <div class="exc-carousel" id="carousel-<?= $exc['id'] ?>">
                                        <div class="exc-carousel-track">
                                            <?php foreach ($gallery as $gi => $imgUrl): ?>
                                                <div class="exc-carousel-slide <?= $gi === 0 ? 'active' : '' ?>">
                                                    <img src="<?= htmlspecialchars($imgUrl) ?>"
                                                        alt="<?= htmlspecialchars($exc['name']) ?>"
                                                        loading="<?= $i < 3 ? 'eager' : 'lazy' ?>" class="exc-card-img exc-lb-trigger"
                                                        data-gallery="<?= $gJson ?>" data-index="<?= $gi ?>" title="Clic para ampliar">
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <button class="exc-car-btn exc-car-prev" aria-label="Anterior"><i
                                                class="fas fa-chevron-left"></i></button>
                                        <button class="exc-car-btn exc-car-next" aria-label="Siguiente"><i
                                                class="fas fa-chevron-right"></i></button>
                                        <div class="exc-car-dots">
                                            <?php foreach ($gallery as $gi => $_): ?>
                                                <span class="exc-car-dot <?= $gi === 0 ? 'active' : '' ?>"
                                                    data-index="<?= $gi ?>"></span>
                                            <?php endforeach; ?>
                                        </div>
                                        <span class="exc-img-count"><i class="fas fa-images me-1"></i><span
                                                class="cur">1</span>/<?= count($gallery) ?></span>
                                        <button class="exc-expand-btn exc-lb-trigger" data-gallery="<?= $gJson ?>" data-index="0"
                                            aria-label="Ver a pantalla completa">
                                            <i class="fas fa-expand-alt"></i>
                                        </button>
                                    </div>
                                <?php elseif ($mainImg): ?>
                                    <img src="<?= htmlspecialchars($mainImg) ?>" alt="<?= htmlspecialchars($exc['name']) ?>"
                                        loading="<?= $i < 3 ? 'eager' : 'lazy' ?>"
                                        class="exc-card-img exc-img-single exc-lb-trigger" data-gallery="<?= $gJson ?>"
                                        data-index="0" title="Clic para ampliar">
                                    <button class="exc-expand-btn exc-lb-trigger" data-gallery="<?= $gJson ?>" data-index="0"
                                        aria-label="Ampliar">
                                        <i class="fas fa-expand-alt"></i>
                                    </button>
                                <?php else: ?>
                                    <div class="exc-img-ph"><i class="fas fa-mountain" style="color:<?= $cc ?>;font-size:3rem;"></i>
                                    </div>
                                <?php endif; ?>
                                <div class="exc-img-veil"></div>
                                <span class="exc-cat-badge"
                                    style="background:<?= $cc ?>20;color:<?= $cc ?>;border:1px solid <?= $cc ?>40">
                                    <i class="fas <?= $ci ?> me-1"></i><?= htmlspecialchars($exc['category'] ?? 'General') ?>
                                </span>
                                <?php if (!empty($exc['featured'])): ?>
                                    <span class="exc-feat-badge"><i class="fas fa-fire me-1"></i>Popular</span>
                                <?php endif; ?>
                                <div class="exc-hover-overlay">
                                    <div class="exc-ho-price">
                                        <span>desde</span>
                                        <strong>$<?= number_format((float) $exc['price'], 0) ?></strong>
                                        <small><?= (trim(strtolower($exc['price_type'] ?? '')) === 'paquete' ? 'Precio Total' : 'por persona') ?></small>
                                    </div>
                                    <a href="<?= $appUrl ?>/reserva/create/excursion/<?= (int) $exc['id'] ?>" class="exc-ho-btn">
                                        <i class="fas fa-calendar-check me-1"></i>Reservar ahora
                                    </a>
                                </div>
                            </div>
                            <!-- CUERPO -->
                            <div class="exc-card-body">
                                <div class="exc-rating-row">
                                    <div class="exc-stars" style="color:<?= $cc ?>"><?= starRating($rating) ?></div>
                                    <span class="exc-rating-num"><?= number_format($rating, 1) ?></span>
                                    <?php if ($reviews > 0): ?><span class="exc-reviews">(<?= $reviews ?>
                                            reseñas)</span><?php endif; ?>
                                </div>
                                <div class="exc-meta-row">
                                    <span class="exc-meta"><i
                                            class="fas fa-map-marker-alt"></i><?= htmlspecialchars($exc['location'] ?? '—') ?></span>
                                    <?php if (!empty($exc['duration'])): ?><span class="exc-meta"><i
                                                class="fas fa-clock"></i><?= htmlspecialchars($exc['duration']) ?></span><?php endif; ?>
                                    <?php if ($maxPeople > 0): ?><span class="exc-meta"><i class="fas fa-users"></i>Máx.
                                            <?= $maxPeople ?></span><?php endif; ?>
                                </div>
                                <h3 class="exc-card-title"><?= htmlspecialchars($exc['name']) ?></h3>
                                <?php if (!empty($exc['description'])): ?>
                                    <div class="exc-desc-wrap">
                                        <p class="exc-desc" id="exc-desc-<?= $exc['id'] ?>">
                                            <?= htmlspecialchars(substr($exc['description'], 0, 130)) ?>            <?= strlen($exc['description']) > 130 ? '…' : '' ?>
                                        </p>
                                        <?php if (strlen($exc['description']) > 130): ?>
                                            <button class="exc-read-more" data-id="<?= $exc['id'] ?>"
                                                data-full="<?= htmlspecialchars($exc['description']) ?>"
                                                data-short="<?= htmlspecialchars(substr($exc['description'], 0, 130)) ?>…"
                                                style="--cat-color:<?= $cc ?>">
                                                <span class="exc-rm-left">
                                                    <span class="exc-rm-icon"><i class="fas fa-book-open"></i></span>
                                                    <span class="exc-rm-text">Leer descripción completa</span>
                                                </span>
                                                <i class="fas fa-chevron-down exc-rm-arrow"></i>
                                            </button>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <?php
                                $rawExcInc = $exc['includes'] ?? '';
                                $excIncludes = is_string($rawExcInc) ? (json_decode($rawExcInc, true) ?: array_filter(array_map('trim', explode(',', $rawExcInc)))) : (is_array($rawExcInc) ? $rawExcInc : []);
                                if (!empty($excIncludes)):
                                    ?>
                                    <div class="exc-includes">
                                        <?php foreach (array_slice($excIncludes, 0, 3) as $inc):
                                            $c = trim($inc);
                                            if (!$c)
                                                continue; ?>
                                            <span class="exc-inc-chip"><i class="fas fa-check-circle"
                                                    style="color:<?= $cc ?>"></i><?= htmlspecialchars($c) ?></span>
                                        <?php endforeach; ?>
                                        <?php if (count($excIncludes) > 3): ?>
                                            <span class="exc-inc-chip exc-more-inc">+<?= count($excIncludes) - 3 ?> más</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                                <div class="exc-card-footer">
                                    <div class="exc-price-block">
                                        <span class="exc-price-label"><?= (trim(strtolower($exc['price_type'] ?? '')) === 'paquete' ? 'Precio Total' : 'por persona') ?></span>
                                        <strong class="exc-price"
                                            style="color:<?= $cc ?>">$<?= number_format((float) $exc['price'], 2) ?></strong>
                                    </div>
                                    <a href="<?= $appUrl ?>/reserva/create/excursion/<?= (int) $exc['id'] ?>"
                                        class="exc-reserve-btn" style="background:<?= $cc ?>">
                                        <i
                                            class="fas fa-calendar-plus me-1"></i><?= function_exists('__') ? __('reserve') : 'Reservar' ?>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="exc-no-cat-results" id="noCatResults" style="display:none">
                <i class="fas fa-search"></i>
                <p>No hay excursiones en esta categoría por el momento.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ════════════════════════════════════════
     EXCURSIÓN PERSONALIZADA - COMPLETO Y CORREGIDO
════════════════════════════════════════ -->
<section class="exc-custom-section" id="custom-form">
    <div class="container">
        <div class="exc-custom-wrap" data-reveal>
            <div class="exc-custom-left">
                <div class="exc-cl-eyebrow"><i class="fas fa-magic me-2"></i>Exclusivo</div>
                <h2 class="exc-cl-title">¿No encuentras<br>lo que buscas?</h2>
                <p class="exc-cl-desc">Creamos tu excursión perfecta a medida. Cuéntanos tus sueños y nuestros expertos
                    diseñarán una experiencia única solo para ti.</p>
                <ul class="exc-cl-perks">
                    <li><i class="fas fa-check-circle"></i>Itinerario 100% personalizado</li>
                    <li><i class="fas fa-check-circle"></i>Guía privado a tu disposición</li>
                    <li><i class="fas fa-check-circle"></i>Respuesta en menos de 24 horas</li>
                    <li><i class="fas fa-check-circle"></i>Sin compromiso de pago</li>
                </ul>
                <div class="exc-cl-tag"><i class="fas fa-clock me-1"></i>Respuesta garantizada en 24 h</div>
            </div>
            <div class="exc-custom-right">
                <?php if (!empty($_SESSION['custom_success'])): ?>
                    <div class="exc-flash exc-flash-ok"><i
                            class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['custom_success']) ?></div>
                    <?php unset($_SESSION['custom_success']); ?>
                <?php endif; ?>
                <?php if (!empty($_SESSION['custom_error'])): ?>
                    <div class="exc-flash exc-flash-err"><i
                            class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['custom_error']) ?>
                    </div>
                    <?php unset($_SESSION['custom_error']); ?>
                <?php endif; ?>

                <form method="POST" action="<?= $appUrl ?>/excursions/custom_request" id="customForm" novalidate>
                    <!-- DATOS DE CONTACTO -->
                    <div class="exc-cf-row">
                        <div class="exc-cf-group">
                            <label class="exc-cf-label">Nombre completo <span class="exc-req">*</span></label>
                            <input type="text" name="customer_name" class="exc-cf-input"
                                placeholder="Tu nombre completo"
                                value="<?= htmlspecialchars($_SESSION['user_name'] ?? $oldInput['customer_name'] ?? '') ?>"
                                required pattern="^[A-Za-zÁÉÍÓÚáéíóúÑñ\s]{3,100}$"
                                title="Ingresa tu nombre completo (mínimo 3 letras)">
                        </div>
                        <div class="exc-cf-group">
                            <label class="exc-cf-label">Email <span class="exc-req">*</span></label>
                            <input type="email" name="customer_email" class="exc-cf-input" placeholder="tu@correo.com"
                                value="<?= htmlspecialchars($_SESSION['user_email'] ?? $oldInput['customer_email'] ?? '') ?>"
                                required>
                        </div>
                    </div>

                    <!-- TELÉFONO OBLIGATORIO -->
                    <div class="exc-cf-row">
                        <div class="exc-cf-group">
                            <label class="exc-cf-label">Teléfono / WhatsApp <span class="exc-req">*</span></label>
                            <input type="tel" name="customer_phone" class="exc-cf-input" id="customPhoneInput"
                                placeholder="+1 829 555 1234"
                                value="<?= htmlspecialchars($_SESSION['user_phone'] ?? $oldInput['customer_phone'] ?? '') ?>"
                                required pattern="^[\d\+\-\s\(\)]{7,20}$"
                                title="Ingresa un teléfono válido (mínimo 7 dígitos). Ej: +1 829 555 1234">
                            <small class="exc-cf-hint"><i class="fas fa-info-circle me-1"></i>Necesario para coordinar
                                tu experiencia</small>
                        </div>
                        <div class="exc-cf-group">
                            <label class="exc-cf-label">Fecha deseada</label>
                            <input type="date" name="travel_date" class="exc-cf-input" id="customTravelDate"
                                min="<?= date('Y-m-d', strtotime('+1 day')) ?>"
                                value="<?= htmlspecialchars($oldInput['travel_date'] ?? '') ?>">
                        </div>
                    </div>

                    <!-- DESTINOS Y PERSONAS -->
                    <div class="exc-cf-row">
                        <div class="exc-cf-group">
                            <label class="exc-cf-label">Destinos de interés <span class="exc-req">*</span></label>
                            <input type="text" name="destinations" class="exc-cf-input"
                                placeholder="Ej: Samaná, Jarabacoa, Isla Saona…"
                                value="<?= htmlspecialchars($oldInput['destinations'] ?? '') ?>" required
                                list="destinationsList">
                            <datalist id="destinationsList">
                                <option value="Samaná">
                                <option value="Jarabacoa">
                                <option value="Punta Cana">
                                <option value="Santo Domingo">
                                <option value="Isla Saona">
                                <option value="Los Haitises">
                                <option value="Bahía de las Águilas">
                            </datalist>
                        </div>
                        <div class="exc-cf-group">
                            <label class="exc-cf-label">Número de personas</label>
                            <div class="exc-pax">
                                <button type="button" class="exc-pax-btn" id="cfPaxMinus">−</button>
                                <input type="number" name="people_count" id="cfPaxInput" class="exc-pax-val"
                                    value="<?= (int) ($oldInput['people_count'] ?? 2) ?>" min="1" max="50" readonly>
                                <button type="button" class="exc-pax-btn" id="cfPaxPlus">+</button>
                            </div>
                        </div>
                    </div>

                    <!-- ACTIVIDADES - CORREGIDO -->
                    <div class="exc-cf-group">
                        <label class="exc-cf-label">Actividades de interés</label>
                        <div class="exc-activity-chips" id="activityChips">
                            <?php foreach (['Senderismo', 'Snorkel', 'Surf', 'Rafting', 'Kayak', 'Tirolesa', 'Cultura local', 'Gastronomía', 'Avistamiento de aves', 'Playa', 'Cascadas', 'Cuevas'] as $act): ?>
                                <label class="exc-act-chip">
                                    <input type="checkbox" name="activities_check[]" value="<?= htmlspecialchars($act) ?>"
                                        hidden>
                                    <?= htmlspecialchars($act) ?>
                                </label>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="activities" id="activitiesHidden"
                            value="<?= htmlspecialchars($oldInput['activities'] ?? '') ?>">
                    </div>

                    <!-- PRESUPUESTO -->
                    <div class="exc-cf-row">
                        <div class="exc-cf-group">
                            <label class="exc-cf-label">Presupuesto estimado (USD)</label>
                            <select name="budget" class="exc-cf-input">
                                <option value="">Sin definir aún</option>
                                <?php $budgets = ['Menos de $100', '$100 - $250', '$250 - $500', '$500 - $1,000', 'Más de $1,000'];
                                foreach ($budgets as $b): ?>
                                    <option value="<?= htmlspecialchars($b) ?>" <?= ($oldInput['budget'] ?? '') === $b ? 'selected' : '' ?>><?= $b ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- NOTAS ADICIONALES -->
                    <div class="exc-cf-group">
                        <label class="exc-cf-label">Detalles adicionales</label>
                        <textarea name="additional_notes" class="exc-cf-input exc-cf-textarea" rows="3"
                            placeholder="Necesidades especiales, preferencias de horario, celebraciones…"><?= htmlspecialchars($oldInput['additional_notes'] ?? '') ?></textarea>
                    </div>

                    <!-- CONSENTIMIENTO CON MODAL DE POLÍTICA -->
                    <div class="exc-cf-group" style="margin:18px 0">
                        <label class="exc-act-chip" style="cursor:default;background:transparent;border:none;padding:0">
                            <input type="checkbox" name="consent" required style="width:auto;margin-right:8px">
                            <span style="font-size:.78rem;color:var(--c-muted)">
                                Acepto que mis datos sean usados para coordinar mi solicitud.
                                <a href="#" id="openPrivacyModal"
                                    style="color:var(--c-ocean);text-decoration:none;font-weight:600">Ver política de
                                    privacidad</a>
                            </span>
                        </label>
                    </div>

                    <!-- BOTÓN DE ENVÍO -->
                    <button type="submit" class="exc-cf-submit" id="customSubmit">
                        <i class="fas fa-paper-plane me-2"></i>Enviar solicitud gratuita
                        <span class="exc-cf-submit-sub">Te contactaremos en menos de 24 horas</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════
     MODAL DE POLÍTICA DE PRIVACIDAD (FLOTANTE)
════════════════════════════════════════ -->
<div id="privacyModal" class="exc-modal" style="display:none" role="dialog" aria-modal="true"
    aria-labelledby="privacyTitle">
    <div class="exc-modal-backdrop" id="privacyBackdrop"></div>
    <div class="exc-modal-content">
        <div class="exc-modal-header">
            <h3 id="privacyTitle"><i class="fas fa-shield-alt me-2" style="color:var(--c-ocean)"></i>Política de
                Privacidad</h3>
            <button class="exc-modal-close" id="closePrivacyModal" aria-label="Cerrar">&times;</button>
        </div>
        <div class="exc-modal-body">
            <div class="exc-policy-content">
                <h4>1. Información que recopilamos</h4>
                <p>Al solicitar una excursión personalizada, recopilamos: nombre completo, email, teléfono, destinos de
                    interés, actividades preferidas, fecha deseada, número de personas, presupuesto estimado y notas
                    adicionales.</p>

                <h4>2. Uso de la información</h4>
                <p>Utilizamos tus datos exclusivamente para:</p>
                <ul>
                    <li>Coordinar y diseñar tu excursión personalizada</li>
                    <li>Contactarte para confirmar detalles o solicitar información adicional</li>
                    <li>Enviarte confirmaciones y actualizaciones sobre tu solicitud</li>
                    <li>Mejorar nuestros servicios basados en tus preferencias</li>
                </ul>

                <h4>3. Protección de datos</h4>
                <p>Implementamos medidas de seguridad técnicas y organizativas para proteger tu información personal
                    contra acceso no autorizado, alteración o destrucción.</p>

                <h4>4. Compartir información</h4>
                <p><strong>No vendemos ni compartimos</strong> tus datos personales con terceros. Solo podemos compartir
                    información con proveedores de servicios esenciales (ej: guías locales, transporte) estrictamente
                    para cumplir con tu solicitud, bajo acuerdos de confidencialidad.</p>

                <h4>5. Derechos del usuario</h4>
                <p>Tienes derecho a: acceder, rectificar, eliminar o limitar el tratamiento de tus datos. Para ejercer
                    estos derechos, contáctanos a <strong>funtrekrd@gmail.com</strong>.</p>

                <h4>6. Conservación de datos</h4>
                <p>Conservamos tu información mientras tu solicitud esté activa y hasta 12 meses después para fines de
                    seguimiento. Luego, los datos se anonimizan o eliminan de forma segura.</p>

                <h4>7. Cookies y tecnologías similares</h4>
                <p>Nuestro sitio utiliza cookies esenciales para su funcionamiento. Puedes gestionar tus preferencias
                    desde la configuración de tu navegador.</p>

                <h4>8. Cambios a esta política</h4>
                <p>Nos reservamos el derecho de actualizar esta política. Los cambios entrarán en vigor al publicarse en
                    esta página con fecha de actualización.</p>

                <p class="exc-policy-footer"><strong>Última actualización:</strong> <?= date('d/m/Y') ?><br>
                    <strong>Contacto:</strong> funtrekrd@gmail.com | +1 (829) 457-0890
                </p>
            </div>
        </div>
        <div class="exc-modal-footer">
            <button class="exc-modal-btn exc-modal-primary" id="acceptPrivacy">Entendido, cerrar</button>
        </div>
    </div>
</div>

<!-- ════════════════════════════════════════
     LIGHTBOX DE IMÁGENES
════════════════════════════════════════ -->
<div id="excLightbox" class="exc-lb" role="dialog" aria-modal="true" style="display:none">
    <div class="exc-lb-backdrop" id="excLbBackdrop"></div>
    <button class="exc-lb-close" id="excLbClose" aria-label="Cerrar"><i class="fas fa-times"></i></button>
    <button class="exc-lb-nav exc-lb-prev" id="excLbPrev" aria-label="Anterior"><i
            class="fas fa-chevron-left"></i></button>
    <button class="exc-lb-nav exc-lb-next" id="excLbNext" aria-label="Siguiente"><i
            class="fas fa-chevron-right"></i></button>
    <div class="exc-lb-stage">
        <div class="exc-lb-loader" id="excLbLoader">
            <div class="exc-lb-spin"></div>
        </div>
        <img id="excLbImg" src="" alt="Foto de excursión" class="exc-lb-image">
    </div>
    <div class="exc-lb-footer">
        <div class="exc-lb-counter" id="excLbCounter">1 / 1</div>
        <div class="exc-lb-thumbs" id="excLbThumbs"></div>
    </div>
</div>

<?php include APP_ROOT . '/app/views/layouts/footer.php'; ?>

<!-- ════════════════════════════════════════
     ESTILOS GLOBALES
════════════════════════════════════════ -->
<!-- ════════════════════════════════════════
     JAVASCRIPT COMPLETO Y CORREGIDO
════════════════════════════════════════ -->
<script>
    (function () {
        'use strict';
        const SUGGEST = <?= $suggestJson ?>;
        const APP_JS = '<?= addslashes($appUrl) ?>';

        /* ══ INICIALIZACIONES ══ */
        function initWaves() {
            ['.exc-wave-b', '.exc-wave-f'].forEach((s, i) => {
                const el = document.querySelector(s);
                if (el) setTimeout(() => el.classList.add('w-in'), i * 180);
            });
        }

        function initParallax() {
            const bg = document.getElementById('excHeroBg');
            if (!bg || window.matchMedia('(prefers-reduced-motion:reduce)').matches) return;
            let t = false;
            window.addEventListener('scroll', () => {
                if (!t) {
                    requestAnimationFrame(() => {
                        bg.style.transform = `translateY(${window.scrollY * .26}px)`;
                        t = false;
                    });
                    t = true;
                }
            }, {
                passive: true
            });
        }

        function initHeroAnims() {
            document.querySelectorAll('[data-anim]').forEach(el =>
                setTimeout(() => el.classList.add('anim-in'), parseInt(el.dataset.delay || 0)));
        }

        function initReveal() {
            const obs = new IntersectionObserver(es => {
                es.forEach(e => {
                    if (e.isIntersecting) {
                        setTimeout(() => e.target.classList.add('revealed'), parseInt(e.target.dataset.revealDelay || 0));
                        obs.unobserve(e.target);
                    }
                });
            }, {
                threshold: .10,
                rootMargin: '0px 0px -24px 0px'
            });
            document.querySelectorAll('[data-reveal]').forEach(el => obs.observe(el));
        }

        /* ══ AUTCOMPLETADO (si existiera) ══ */
        function initSearchAC() {
            const inp = document.getElementById('excSearchInput'),
                drop = document.getElementById('excSearchDrop');
            if (!inp || !drop) return;

            function show(q) {
                const m = q.length < 1 ? [] : SUGGEST.filter(s => s && s.toLowerCase().includes(q.toLowerCase())).slice(0, 8);
                if (!m.length) {
                    drop.classList.remove('open');
                    return;
                }
                drop.innerHTML = m.map(s => `<li><i class="fas fa-search"></i>${s}</li>`).join('');
                drop.classList.add('open');
                drop.querySelectorAll('li').forEach(li => {
                    li.addEventListener('mousedown', e => {
                        e.preventDefault();
                        inp.value = li.textContent.trim();
                        drop.classList.remove('open');
                    });
                });
            }
            inp.addEventListener('input', () => show(inp.value));
            inp.addEventListener('focus', () => show(inp.value));
            document.addEventListener('click', e => {
                if (!inp.contains(e.target) && !drop.contains(e.target)) drop.classList.remove('open');
            });
        }

        /* ══ BOTONES GRANDES DE CATEGORÍA (si existieran) ══ */
        function initCatBigBtns() {
            const btns = document.querySelectorAll('.exc-cat-big-btn');
            const sel = document.getElementById('excCatSelect');
            const form = document.getElementById('excPlannerForm');
            if (!btns.length || !sel || !form) return;
            btns.forEach(btn => {
                btn.addEventListener('click', () => {
                    const cat = btn.dataset.cat || '';
                    sel.value = cat;
                    btns.forEach(b => b.classList.remove('active'));
                    btn.classList.add('active');
                    btn.style.transform = 'scale(.97)';
                    setTimeout(() => form.submit(), 120);
                });
            });
        }

        /* ══ TABS DE CATEGORÍAS (filtrado JS) ══ */
        function initCategoryTabs() {
            const tabs = document.querySelectorAll('.exc-cat-tab'),
                cards = document.querySelectorAll('.exc-card-col'),
                noRes = document.getElementById('noCatResults'),
                title = document.getElementById('gridTitle');

            if (!tabs.length) return;

            function updateCounters() {
                tabs.forEach(tab => {
                    const cat = tab.dataset.cat;
                    const countSpan = tab.querySelector('.exc-cat-count');
                    if (!countSpan) return;

                    let visibleCount = 0;
                    if (cat === 'all') {
                        visibleCount = cards.length;
                    } else {
                        cards.forEach(card => {
                            if (card.dataset.cat === cat && card.style.display !== 'none') visibleCount++;
                        });
                    }
                    const oldCount = parseInt(countSpan.textContent);
                    countSpan.textContent = visibleCount;
                    if (oldCount !== visibleCount) {
                        countSpan.classList.add('pulse');
                        setTimeout(() => countSpan.classList.remove('pulse'), 300);
                    }
                });
            }

            function setActiveTab(activeTab) {
                tabs.forEach(tab => {
                    const isActive = tab === activeTab;
                    tab.classList.toggle('active', isActive);
                    tab.setAttribute('aria-selected', isActive ? 'true' : 'false');
                    if (isActive) {
                        const scrollContainer = tab.closest('.exc-cats-scroll');
                        if (scrollContainer) {
                            const tabRect = tab.getBoundingClientRect();
                            const containerRect = scrollContainer.getBoundingClientRect();
                            const offset = tabRect.left - containerRect.left + scrollContainer.scrollLeft - (containerRect.width / 2) + (tabRect.width / 2);
                            scrollContainer.scrollTo({
                                left: offset,
                                behavior: 'smooth'
                            });
                        }
                    }
                });
            }

            function filterByTab(tab) {
                const cat = tab.dataset.cat;
                let visible = 0;
                cards.forEach(card => {
                    const show = cat === 'all' || card.dataset.cat === cat;
                    card.style.display = show ? '' : 'none';
                    if (show) visible++;
                });

                if (noRes) noRes.style.display = visible === 0 ? 'block' : 'none';
                if (title) title.textContent = visible + ' experiencia' + (visible !== 1 ? 's' : '') + ' disponible' + (visible !== 1 ? 's' : '');

                updateCounters();
                setActiveTab(tab);
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', () => filterByTab(tab));
            });

            updateCounters();
            const activeTab = document.querySelector('.exc-cat-tab.active');
            if (activeTab) setActiveTab(activeTab);
        }

        /* ══ CARRUSELES ══ */
        function initCarousels() {
            document.querySelectorAll('.exc-carousel').forEach(car => {
                const slides = car.querySelectorAll('.exc-carousel-slide'),
                    dots = car.querySelectorAll('.exc-car-dot'),
                    prev = car.querySelector('.exc-car-prev'),
                    next = car.querySelector('.exc-car-next'),
                    counter = car.querySelector('.cur');
                if (!slides.length) return;
                let cur = 0,
                    timer = null;

                function goTo(i) {
                    slides[cur].classList.remove('active');
                    dots[cur]?.classList.remove('active');
                    cur = (i + slides.length) % slides.length;
                    slides[cur].classList.add('active');
                    dots[cur]?.classList.add('active');
                    if (counter) counter.textContent = cur + 1;
                }

                function start() {
                    timer = setInterval(() => goTo(cur + 1), 3500);
                }

                function stop() {
                    clearInterval(timer);
                }

                prev?.addEventListener('click', e => {
                    e.stopPropagation();
                    stop();
                    goTo(cur - 1);
                    start();
                });
                next?.addEventListener('click', e => {
                    e.stopPropagation();
                    stop();
                    goTo(cur + 1);
                    start();
                });
                dots.forEach((d, i) => d.addEventListener('click', e => {
                    e.stopPropagation();
                    stop();
                    goTo(i);
                    start();
                }));
                car.addEventListener('mouseenter', stop);
                car.addEventListener('mouseleave', start);
                let tx = 0;
                car.addEventListener('touchstart', e => {
                    tx = e.touches[0].clientX;
                }, {
                    passive: true
                });
                car.addEventListener('touchend', e => {
                    const d = tx - e.changedTouches[0].clientX;
                    if (Math.abs(d) > 40) {
                        stop();
                        goTo(cur + (d > 0 ? 1 : -1));
                        start();
                    }
                }, {
                    passive: true
                });
                start();
            });
        }

        /* ══ LEER MÁS (descripción) ══ */
        function initDescToggle() {
            document.querySelectorAll('.exc-read-more').forEach(btn => {
                const id = btn.dataset.id,
                    full = btn.dataset.full,
                    short = btn.dataset.short,
                    desc = document.getElementById('exc-desc-' + id);
                if (!desc) return;
                desc.textContent = short;
                let ex = false;
                btn.addEventListener('click', () => {
                    ex = !ex;
                    desc.textContent = ex ? full : short;
                    btn.classList.toggle('open', ex);
                    btn.querySelector('.exc-rm-text').textContent = ex ? 'Ocultar descripción' : 'Leer descripción completa';
                    const ico = btn.querySelector('.exc-rm-icon i');
                    if (ico) ico.className = ex ? 'fas fa-book' : 'fas fa-book-open';
                });
            });
        }

        /* ══ ACTIVIDADES (chips) ══ */
        function initActivityChips() {
            const chips = document.querySelectorAll('.exc-act-chip'),
                hidden = document.getElementById('activitiesHidden');
            if (!chips.length || !hidden) return;

            function updateHiddenField() {
                const selected = [];
                chips.forEach(chip => {
                    const checkbox = chip.querySelector('input[type="checkbox"]');
                    if (checkbox?.checked) selected.push(checkbox.value);
                });
                hidden.value = selected.join(', ');
            }

            chips.forEach(chip => {
                const checkbox = chip.querySelector('input[type="checkbox"]');
                if (!checkbox) return;

                chip.addEventListener('click', (e) => {
                    if (e.target !== checkbox) {
                        e.preventDefault();
                        checkbox.checked = !checkbox.checked;
                    }
                    chip.classList.toggle('selected', checkbox.checked);
                    updateHiddenField();
                });
                checkbox.addEventListener('change', () => {
                    chip.classList.toggle('selected', checkbox.checked);
                    updateHiddenField();
                });
            });

            document.getElementById('customForm')?.addEventListener('submit', updateHiddenField);

            chips.forEach(chip => {
                const cb = chip.querySelector('input[type="checkbox"]');
                if (cb?.checked) chip.classList.add('selected');
            });
            updateHiddenField();
        }

        /* ══ MODAL DE POLÍTICA ══ */
        function initPrivacyModal() {
            const modal = document.getElementById('privacyModal'),
                backdrop = document.getElementById('privacyBackdrop'),
                openBtn = document.getElementById('openPrivacyModal'),
                closeBtn = document.getElementById('closePrivacyModal'),
                acceptBtn = document.getElementById('acceptPrivacy');

            if (!modal || !openBtn) return;

            function open() {
                modal.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                closeBtn?.focus();
            }

            function close() {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }

            openBtn?.addEventListener('click', (e) => {
                e.preventDefault();
                open();
            });
            closeBtn?.addEventListener('click', close);
            acceptBtn?.addEventListener('click', close);
            backdrop?.addEventListener('click', close);

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && modal.style.display !== 'none') close();
            });
        }

        /* ══ FORMULARIO PERSONALIZADO ══ */
        function initCustomForm() {
            const form = document.getElementById('customForm'),
                btn = document.getElementById('customSubmit'),
                phone = document.getElementById('customPhoneInput'),
                travelDate = document.getElementById('customTravelDate');
            if (!form) return;

            if (phone) {
                phone.addEventListener('input', function () {
                    this.value = this.value.replace(/[^\d\+\-\s\(\)]/g, '');
                    const clean = this.value.replace(/\D/g, '');
                    if (clean.length > 0 && clean.length < 7) {
                        this.setCustomValidity('Mínimo 7 dígitos');
                        this.classList.add('input-error');
                    } else {
                        this.setCustomValidity('');
                        this.classList.remove('input-error');
                    }
                });
                phone.addEventListener('invalid', function (e) {
                    e.preventDefault();
                    if (this.validity.valueMissing) alert('📞 El teléfono es obligatorio');
                    else if (this.validity.patternMismatch) alert('Formato inválido. Ej: +1 829 555 1234');
                    this.focus();
                });
            }

            if (travelDate) {
                travelDate.addEventListener('change', function () {
                    const today = new Date();
                    today.setHours(0, 0, 0, 0);
                    const selected = new Date(this.value);
                    if (selected < today) {
                        this.setCustomValidity('La fecha debe ser hoy o en el futuro');
                    } else {
                        this.setCustomValidity('');
                    }
                });
            }

            form.addEventListener('submit', function (e) {
                const phoneVal = phone?.value.trim();
                if (phoneVal && phoneVal.replace(/\D/g, '').length < 7) {
                    e.preventDefault();
                    alert('Por favor ingresa un teléfono válido');
                    phone?.focus();
                    return false;
                }
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando…';
                    btn.style.opacity = '0.85';
                }
            });
        }

        /* ══ PAX COUNTER ══ */
        function initCustomPax() {
            const m = document.getElementById('cfPaxMinus'),
                p = document.getElementById('cfPaxPlus'),
                inp = document.getElementById('cfPaxInput');
            if (!m || !p || !inp) return;
            m.addEventListener('click', () => {
                let v = parseInt(inp.value);
                if (v > 1) inp.value = v - 1;
            });
            p.addEventListener('click', () => {
                let v = parseInt(inp.value);
                if (v < 50) inp.value = v + 1;
            });
        }

        /* ══ LIGHTBOX ══ */
        const LB = {
            el: null,
            img: null,
            loader: null,
            counter: null,
            thumbs: null,
            prev: null,
            next: null,
            gallery: [],
            cur: 0,
            init() {
                this.el = document.getElementById('excLightbox');
                if (!this.el) return;
                this.img = document.getElementById('excLbImg');
                this.loader = document.getElementById('excLbLoader');
                this.counter = document.getElementById('excLbCounter');
                this.thumbs = document.getElementById('excLbThumbs');
                this.prev = document.getElementById('excLbPrev');
                this.next = document.getElementById('excLbNext');

                document.addEventListener('click', e => {
                    const t = e.target.closest('.exc-lb-trigger');
                    if (!t) return;
                    e.preventDefault();
                    e.stopPropagation();
                    try {
                        this.open(JSON.parse(t.dataset.gallery || '[]'), parseInt(t.dataset.index || 0));
                    } catch (_) { }
                });
                document.getElementById('excLbClose')?.addEventListener('click', () => this.exit());
                document.getElementById('excLbBackdrop')?.addEventListener('click', () => this.exit());
                this.prev?.addEventListener('click', () => this.go(this.cur - 1));
                this.next?.addEventListener('click', () => this.go(this.cur + 1));
                document.addEventListener('keydown', e => {
                    if (!this.el || this.el.style.display === 'none') return;
                    if (e.key === 'Escape') this.exit();
                    if (e.key === 'ArrowLeft') this.go(this.cur - 1);
                    if (e.key === 'ArrowRight') this.go(this.cur + 1);
                });
            },
            open(gallery, idx) {
                if (!gallery.length) return;
                this.gallery = gallery;
                this.el.style.display = 'flex';
                document.body.style.overflow = 'hidden';
                this.go(idx);
                const single = gallery.length <= 1;
                if (this.prev) this.prev.style.display = single ? 'none' : '';
                if (this.next) this.next.style.display = single ? 'none' : '';
                if (this.thumbs) this.thumbs.style.display = single ? 'none' : '';
            },
            go(idx) {
                const len = this.gallery.length;
                if (!len) return;
                this.cur = ((idx % len) + len) % len;
                const url = this.gallery[this.cur];
                this.loader.style.display = 'flex';
                this.img.classList.remove('lb-loaded');
                const tmp = new Image();
                const done = () => {
                    this.img.src = url;
                    this.loader.style.display = 'none';
                    this.img.classList.add('lb-loaded');
                };
                tmp.onload = done;
                tmp.onerror = done;
                tmp.src = url;
                if (this.counter) this.counter.textContent = (this.cur + 1) + ' / ' + len;
                this.renderThumbs();
            },
            renderThumbs() {
                if (!this.thumbs) return;
                this.thumbs.innerHTML = '';
                this.gallery.forEach((url, i) => {
                    const img = document.createElement('img');
                    img.src = url;
                    img.alt = 'Miniatura ' + (i + 1);
                    img.className = 'exc-lb-thumb' + (i === this.cur ? ' lb-active' : '');
                    img.addEventListener('click', () => this.go(i));
                    this.thumbs.appendChild(img);
                });
                this.thumbs.querySelector('.lb-active')?.scrollIntoView({
                    behavior: 'smooth',
                    block: 'nearest',
                    inline: 'center'
                });
            },
            exit() {
                if (!this.el) return;
                this.el.style.display = 'none';
                document.body.style.overflow = '';
                this.img.src = '';
                this.img.classList.remove('lb-loaded');
            }
        };

        /* ══ BOOT ══ */
        document.addEventListener('DOMContentLoaded', () => {
            initWaves();
            initParallax();
            initHeroAnims();
            initReveal();
            initSearchAC();
            initCatBigBtns();
            initCategoryTabs();
            initCarousels();
            initDescToggle();
            initActivityChips();
            initPrivacyModal();
            initCustomForm();
            initCustomPax();
            LB.init();
        });
    })();
</script>
