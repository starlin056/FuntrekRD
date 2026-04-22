<?php $customCss = ['modules/public-packages.css']; ?>
<?php include APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php include APP_ROOT . '/app/views/layouts/navigation.php'; ?>

<?php
/* ─────────────────────────────────────────────
   HELPERS DE VISTA (adaptados para paquetes)
───────────────────────────────────────────── */
$appUrl = defined('APP_URL') ? APP_URL : '';

function pkgCatColor(string $cat): string
{
    $map = [
        'playa' => '#00B4D8',
        'aventura' => '#e74c3c',
        'romantico' => '#e84393',
        'familiar' => '#F9C74F',
        'luxury' => '#b8860b',
        'cultural' => '#9b59b6',
        'gastronomico' => '#e67e22',
        'naturaleza' => '#2EC4B6',
        'deporte' => '#27ae60',
        'relax' => '#6E8FA5',
    ];
    return $map[strtolower(trim($cat))] ?? '#0077B6';
}

function pkgCatIcon(string $cat): string
{
    $map = [
        'playa' => 'fa-umbrella-beach',
        'aventura' => 'fa-mountain',
        'romantico' => 'fa-heart',
        'familiar' => 'fa-people-roof',
        'luxury' => 'fa-gem',
        'cultural' => 'fa-landmark',
        'gastronomico' => 'fa-utensils',
        'naturaleza' => 'fa-tree',
        'deporte' => 'fa-bicycle',
        'relax' => 'fa-spa',
    ];
    return $map[strtolower(trim($cat))] ?? 'fa-suitcase';
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

function buildPkgGallery($pkg, string $appUrl): array
{
    $imgs = [];
    $gallery = $pkg['gallery'] ?? [];

    // Si es un string JSON, lo decodificamos
    if (is_string($gallery)) {
        $gallery = json_decode($gallery, true) ?: [];
    }

    if (!empty($gallery) && is_array($gallery)) {
        foreach ($gallery as $g) {
            $g = trim($g);
            if ($g)
                $imgs[] = $appUrl . '/assets/uploads/packages/' . $g;
        }
    }
    if (!empty($pkg['image'])) {
        $main = $appUrl . '/assets/uploads/packages/' . $pkg['image'];
        if (!in_array($main, $imgs))
            array_unshift($imgs, $main);
    }
    return $imgs;
}

$allCategories = [];
foreach ($packages as $pkg) {
    $cat = trim($pkg['category'] ?? '');
    if ($cat)
        $allCategories[$cat] = ($allCategories[$cat] ?? 0) + 1;
}
$categories = array_map(fn($cat, $cnt) => ['category' => $cat, 'count' => $cnt], array_keys($allCategories), $allCategories);

$allNames = array_column($packages, 'name');
$allLocs = array_column($packages, 'location') ?: [];
$allCats = array_keys($allCategories);
$suggestJson = json_encode(array_values(array_unique(array_merge($allNames, $allLocs, $allCats))));

$hasFilters = false;
?>

<!-- ════════════════════════════════════════
     HERO (con fondo de avión restaurado)
════════════════════════════════════════ -->
<section class="pkg-hero">
    <div class="pkg-hero-bg" id="pkgHeroBg"></div>
    <div class="pkg-grain" aria-hidden="true"></div>
    <div class="container pkg-hero-content">
        <div class="pkg-hero-inner">
            <div class="pkg-eyebrow" data-anim data-delay="0">
                <span class="pkg-pulse"></span>
                <i class="fas fa-suitcase-rolling"></i>
                <?= function_exists('__') ? __('nav_packages') : 'Paquetes Turísticos' ?>
            </div>
            <h1 class="pkg-title" data-anim data-delay="120">
                <?= function_exists('__') ? __('packages_title') : 'Descubre los Mejores Paquetes' ?>
                <span class="pkg-title-bar"></span>
            </h1>
            <p class="pkg-subtitle" data-anim data-delay="240">
                <?= function_exists('__') ? __('packages_subtitle') : 'Todo incluido, hoteles de lujo y experiencias inolvidables.' ?>
            </p>
            <div class="pkg-hero-chips" data-anim data-delay="320">
                <div class="pkg-hchip"><i class="fas fa-hotel"></i><span>Hoteles 5 estrellas</span></div>
                <div class="pkg-hchip"><i class="fas fa-utensils"></i><span>Todo incluido</span></div>
                <div class="pkg-hchip"><i class="fas fa-headset"></i><span>Soporte 24/7</span></div>
                <div class="pkg-hchip"><i class="fas fa-star"></i><span>4.9★ valoración</span></div>
            </div>
        </div>
    </div>
    <div class="pkg-waves" aria-hidden="true">
        <svg class="pkg-wave pkg-wave-b" viewBox="0 0 1440 90" preserveAspectRatio="none">
            <path fill="rgba(202,240,248,.45)"
                d="M0,45 C240,80 480,10 720,45 C960,80 1200,20 1440,45 L1440,90 L0,90 Z" />
        </svg>
        <svg class="pkg-wave pkg-wave-f" viewBox="0 0 1440 90" preserveAspectRatio="none">
            <path fill="#EAF6FF"
                d="M0,58 C180,28 360,78 540,58 C720,38 900,72 1080,56 C1260,40 1380,62 1440,58 L1440,90 L0,90 Z" />
        </svg>
    </div>
</section>

<!-- ════════════════════════════════════════
     VENTAJAS (por qué elegirnos)
════════════════════════════════════════ -->
<section class="why-section">
    <div class="container">
        <div class="why-grid">
            <div class="why-item" data-reveal data-reveal-delay="0">
                <div class="why-icon-wrap"><i class="fas fa-tag"></i></div>
                <div>
                    <h5>Mejor precio garantizado</h5>
                    <p>Si encuentras igual o más barato, te devolvemos la diferencia.</p>
                </div>
            </div>
            <div class="why-sep" aria-hidden="true"></div>
            <div class="why-item" data-reveal data-reveal-delay="80">
                <div class="why-icon-wrap"><i class="fas fa-rotate-left"></i></div>
                <div>
                    <h5>Cancelación flexible</h5>
                    <p>Cancela hasta 48 h antes sin cargos adicionales.</p>
                </div>
            </div>
            <div class="why-sep" aria-hidden="true"></div>
            <div class="why-item" data-reveal data-reveal-delay="160">
                <div class="why-icon-wrap"><i class="fas fa-user-tie"></i></div>
                <div>
                    <h5>Asesor personal</h5>
                    <p>Un experto te guía en cada paso de tu reserva.</p>
                </div>
            </div>
            <div class="why-sep" aria-hidden="true"></div>
            <div class="why-item" data-reveal data-reveal-delay="240">
                <div class="why-icon-wrap"><i class="fas fa-shield-halved"></i></div>
                <div>
                    <h5>Reserva segura</h5>
                    <p>Pago cifrado y confirmación inmediata en tu correo.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- ════════════════════════════════════════
     TABS DE CATEGORÍAS
════════════════════════════════════════ -->
<?php if (count($categories) > 1): ?>
    <section class="pkg-cats-section" id="cats-nav" aria-label="Filtro de categorías">
        <div class="container">
            <div class="pkg-cats-wrapper">
                <div class="pkg-cats-scroll" role="tablist">
                    <button class="pkg-cat-tab active" data-cat="all" role="tab" aria-selected="true">
                        <i class="fas fa-globe me-2" aria-hidden="true"></i>Todas
                        <span class="pkg-cat-count" data-count="<?= count($packages) ?>"><?= count($packages) ?></span>
                    </button>
                    <?php foreach ($categories as $cat):
                        $catName = htmlspecialchars($cat['category']);
                        $catSlug = htmlspecialchars(strtolower(trim($cat['category'])));
                        $catColor = pkgCatColor($cat['category']);
                        $catIcon = pkgCatIcon($cat['category']);
                        $catCount = (int) $cat['count'];
                        ?>
                        <button class="pkg-cat-tab" data-cat="<?= $catSlug ?>" data-color="<?= $catColor ?>" role="tab"
                            aria-selected="false">
                            <i class="fas <?= $catIcon ?> me-2" aria-hidden="true"></i>
                            <?= $catName ?>
                            <span class="pkg-cat-count" data-count="<?= $catCount ?>"><?= $catCount ?></span>
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </section>
<?php endif; ?>

<!-- ════════════════════════════════════════
     GRID DE PAQUETES
════════════════════════════════════════ -->
<section class="pkg-grid-section">
    <div class="container">
        <?php if (empty($packages)): ?>
            <div class="pkg-empty" data-reveal>
                <div class="pkg-empty-icon">🧳</div>
                <h3>Próximamente nuevos paquetes</h3>
                <p><?= __('packages_empty') ?></p>
                <a href="<?= APP_URL ?>/" class="pkg-empty-btn"><i
                        class="fas fa-arrow-left me-2"></i><?= __('back_home') ?></a>
            </div>
        <?php else: ?>
            <div class="pkg-sec-head" data-reveal>
                <div class="pkg-sec-eyebrow">Ofertas exclusivas</div>
                <h2 class="pkg-sec-title" id="gridTitle"><?= count($packages) ?> paquetes disponibles</h2>
                <p class="pkg-sec-sub">Disfruta de experiencias únicas con todo incluido y atención personalizada</p>
            </div>

            <div class="row g-4" id="packagesGrid">
                <?php foreach ($packages as $i => $pkg):
                    $gallery = buildPkgGallery($pkg, $appUrl);
                    $hasGal = count($gallery) > 1;
                    $mainImg = $gallery[0] ?? null;
                    $cc = pkgCatColor($pkg['category'] ?? '');
                    $ci = pkgCatIcon($pkg['category'] ?? '');
                    $rating = (float) ($pkg['rating'] ?? 4.9);
                    $reviews = (int) ($pkg['reviews_count'] ?? 0);
                    $pkgCat = strtolower(trim($pkg['category'] ?? 'general'));
                    $maxPeople = (int) ($pkg['max_people'] ?? 0);
                    $gJson = htmlspecialchars(json_encode($gallery));
                    $hasDiscount = !empty($pkg['discount_price']) && $pkg['discount_price'] < $pkg['price'];
                    $discountPercent = $hasDiscount ? round((1 - $pkg['discount_price'] / $pkg['price']) * 100) : 0;
                    ?>
                    <div class="col-lg-4 col-md-6 pkg-card-col" data-reveal data-reveal-delay="<?= ($i % 3) * 80 ?>"
                        data-cat="<?= htmlspecialchars($pkgCat) ?>">
                        <article class="pkg-card">

                            <!-- IMAGEN CON CARRUSEL -->
                            <div class="pkg-img-wrap">
                                <?php if ($hasGal): ?>
                                    <div class="pkg-carousel" id="carousel-<?= $pkg['id'] ?>">
                                        <div class="pkg-carousel-track">
                                            <?php foreach ($gallery as $gi => $imgUrl): ?>
                                                <div class="pkg-carousel-slide <?= $gi === 0 ? 'active' : '' ?>">
                                                    <img src="<?= htmlspecialchars($imgUrl) ?>"
                                                        alt="<?= htmlspecialchars($pkg['name']) ?>"
                                                        loading="<?= $i < 3 ? 'eager' : 'lazy' ?>" class="pkg-card-img pkg-lb-trigger"
                                                        data-gallery="<?= $gJson ?>" data-index="<?= $gi ?>" title="Clic para ampliar">
                                                </div>
                                            <?php endforeach; ?>
                                        </div>
                                        <button class="pkg-car-btn pkg-car-prev" aria-label="Anterior"><i
                                                class="fas fa-chevron-left"></i></button>
                                        <button class="pkg-car-btn pkg-car-next" aria-label="Siguiente"><i
                                                class="fas fa-chevron-right"></i></button>
                                        <div class="pkg-car-dots">
                                            <?php foreach ($gallery as $gi => $_): ?>
                                                <span class="pkg-car-dot <?= $gi === 0 ? 'active' : '' ?>"
                                                    data-index="<?= $gi ?>"></span>
                                            <?php endforeach; ?>
                                        </div>
                                        <span class="pkg-img-count"><i class="fas fa-images me-1"></i><span
                                                class="cur">1</span>/<?= count($gallery) ?></span>
                                        <button class="pkg-expand-btn pkg-lb-trigger" data-gallery="<?= $gJson ?>" data-index="0"
                                            aria-label="Ver a pantalla completa">
                                            <i class="fas fa-expand-alt"></i>
                                        </button>
                                    </div>
                                <?php elseif ($mainImg): ?>
                                    <img src="<?= htmlspecialchars($mainImg) ?>" alt="<?= htmlspecialchars($pkg['name']) ?>"
                                        loading="<?= $i < 3 ? 'eager' : 'lazy' ?>"
                                        class="pkg-card-img pkg-img-single pkg-lb-trigger" data-gallery="<?= $gJson ?>"
                                        data-index="0" title="Clic para ampliar">
                                    <button class="pkg-expand-btn pkg-lb-trigger" data-gallery="<?= $gJson ?>" data-index="0"
                                        aria-label="Ampliar">
                                        <i class="fas fa-expand-alt"></i>
                                    </button>
                                <?php else: ?>
                                    <div class="pkg-img-ph"><i class="fas fa-suitcase" style="color:<?= $cc ?>;font-size:3rem;"></i>
                                    </div>
                                <?php endif; ?>

                                <div class="pkg-img-veil"></div>

                                <!-- Badges -->
                                <span class="pkg-cat-badge"
                                    style="background:<?= $cc ?>20;color:<?= $cc ?>;border:1px solid <?= $cc ?>40">
                                    <i class="fas <?= $ci ?> me-1"></i><?= htmlspecialchars($pkg['category'] ?? 'General') ?>
                                </span>
                                <?php if ($hasDiscount): ?>
                                    <span class="pkg-discount-badge">-<?= $discountPercent ?>%</span>
                                <?php endif; ?>
                                <?php if (!empty($pkg['featured'])): ?>
                                    <span class="pkg-feat-badge"><i class="fas fa-crown me-1"></i>Destacado</span>
                                <?php endif; ?>

                                <div class="pkg-hover-overlay">
                                    <div class="pkg-ho-price">
                                        <span>desde</span>
                                        <?php if ($hasDiscount): ?>
                                            <strong>$<?= number_format((float) $pkg['discount_price'], 0) ?></strong>
                                        <?php else: ?>
                                            <strong>$<?= number_format((float) $pkg['price'], 0) ?></strong>
                                        <?php endif; ?>
                                        <small><?= (trim(strtolower($pkg['price_type'] ?? '')) === 'paquete' ? 'Precio Total' : 'por persona') ?></small>
                                    </div>
                                    <a href="<?= $appUrl ?>/reserva/create/package/<?= (int) $pkg['id'] ?>" class="pkg-ho-btn">
                                        <i class="fas fa-calendar-check me-1"></i>Reservar ahora
                                    </a>
                                </div>
                            </div>

                            <!-- CUERPO CARD -->
                            <div class="pkg-card-body">
                                <div class="pkg-rating-row">
                                    <div class="pkg-stars" style="color:<?= $cc ?>"><?= starRating($rating) ?></div>
                                    <span class="pkg-rating-num"><?= number_format($rating, 1) ?></span>
                                    <?php if ($reviews > 0): ?><span class="pkg-reviews">(<?= $reviews ?>
                                            reseñas)</span><?php endif; ?>
                                </div>
                                <div class="pkg-meta-row">
                                    <span class="pkg-meta"><i class="fas fa-calendar-alt"></i><?= (int) $pkg['days'] ?>d /
                                        <?= (int) $pkg['nights'] ?>n</span>
                                    <?php if ($maxPeople > 0): ?><span class="pkg-meta"><i class="fas fa-users"></i>Máx.
                                            <?= $maxPeople ?> pers.</span><?php endif; ?>
                                    <?php if (!empty($pkg['location'])): ?>
                                        <span class="pkg-meta"><i
                                                class="fas fa-map-marker-alt"></i><?= htmlspecialchars($pkg['location']) ?></span>
                                    <?php endif; ?>
                                </div>
                                <h3 class="pkg-card-title"><?= htmlspecialchars($pkg['name']) ?></h3>

                                <!-- Descripción con toggle -->
                                <div class="pkg-desc-wrap">
                                    <p class="pkg-desc" id="pkg-desc-<?= $pkg['id'] ?>">
                                        <?= htmlspecialchars(substr($pkg['description'], 0, 130)) ?>
                                        <?= strlen($pkg['description']) > 130 ? '…' : '' ?>
                                    </p>
                                    <?php if (strlen($pkg['description']) > 130): ?>
                                        <button class="pkg-read-more" data-id="<?= $pkg['id'] ?>"
                                            data-full="<?= htmlspecialchars($pkg['description']) ?>"
                                            data-short="<?= htmlspecialchars(substr($pkg['description'], 0, 130)) ?>…"
                                            style="--cat-color:<?= $cc ?>">
                                            <span class="pkg-rm-left">
                                                <span class="pkg-rm-icon"><i class="fas fa-book-open"></i></span>
                                                <span class="pkg-rm-text">Leer descripción completa</span>
                                            </span>
                                            <i class="fas fa-chevron-down pkg-rm-arrow"></i>
                                        </button>
                                    <?php endif; ?>
                                </div>

                                <!-- Incluidos -->
                                <?php
                                $rawInc = $pkg['includes'] ?? [];
                                $pkgIncludes = [];
                                if (is_array($rawInc)) {
                                    $pkgIncludes = $rawInc;
                                } elseif (!empty($rawInc) && is_string($rawInc)) {
                                    $decodedPkg = json_decode($rawInc, true);
                                    $pkgIncludes = is_array($decodedPkg) ? $decodedPkg : array_filter(array_map('trim', explode(',', $rawInc)));
                                }
                                if (!empty($pkgIncludes)):
                                    ?>
                                    <div class="pkg-includes">
                                        <?php foreach (array_slice($pkgIncludes, 0, 3) as $inc):
                                            $c = trim($inc);
                                            if (!$c)
                                                continue; ?>
                                            <span class="pkg-inc-chip"><i class="fas fa-check-circle"
                                                    style="color:<?= $cc ?>"></i><?= htmlspecialchars($c) ?></span>
                                        <?php endforeach; ?>
                                        <?php if (count($pkgIncludes) > 3): ?>
                                            <span class="pkg-inc-chip pkg-more-inc">+<?= count($pkgIncludes) - 3 ?> más</span>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>

                                <div class="pkg-card-footer">
                                    <div class="pkg-price-block">
                                        <span
                                            class="pkg-price-label"><?= (trim(strtolower($pkg['price_type'] ?? '')) === 'paquete' ? 'Precio Total' : 'por persona') ?></span>
                                        <?php if ($hasDiscount): ?>
                                            <span class="pkg-price-old">$<?= number_format((float) $pkg['price'], 2) ?></span>
                                            <strong class="pkg-price"
                                                style="color:<?= $cc ?>">$<?= number_format((float) $pkg['discount_price'], 2) ?></strong>
                                        <?php else: ?>
                                            <strong class="pkg-price"
                                                style="color:<?= $cc ?>">$<?= number_format((float) $pkg['price'], 2) ?></strong>
                                        <?php endif; ?>
                                    </div>
                                    <a href="<?= $appUrl ?>/reserva/create/package/<?= (int) $pkg['id'] ?>"
                                        class="pkg-reserve-btn" style="background:<?= $cc ?>">
                                        <i
                                            class="fas fa-calendar-plus me-1"></i><?= function_exists('__') ? __('reserve') : 'Reservar' ?>
                                    </a>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="pkg-no-cat-results" id="noCatResults" style="display:none">
                <i class="fas fa-search"></i>
                <p>No hay paquetes en esta categoría por el momento.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- ════════════════════════════════════════
     PAQUETE PERSONALIZADO
════════════════════════════════════════ -->
<!-- <section class="pkg-custom-section" id="custom-form">
    <div class="container">
        <div class="pkg-custom-wrap" data-reveal>
            <div class="pkg-custom-left">
                <div class="pkg-cl-eyebrow"><i class="fas fa-magic me-2"></i>Exclusivo</div>
                <h2 class="pkg-cl-title">¿No encuentras<br>el paquete ideal?</h2>
                <p class="pkg-cl-desc">Creamos tu viaje a medida. Cuéntanos tus preferencias y diseñaremos un paquete único solo para ti.</p>
                <ul class="pkg-cl-perks">
                    <li><i class="fas fa-check-circle"></i>Itinerario 100% personalizado</li>
                    <li><i class="fas fa-check-circle"></i>Asesor experto dedicado</li>
                    <li><i class="fas fa-check-circle"></i>Respuesta en menos de 24 horas</li>
                    <li><i class="fas fa-check-circle"></i>Sin compromiso de pago</li>
                </ul>
                <div class="pkg-cl-tag"><i class="fas fa-clock me-1"></i>Respuesta garantizada en 24 h</div>
            </div>
            <div class="pkg-custom-right">
                <?php if (!empty($_SESSION['custom_pkg_success'])): ?>
                    <div class="pkg-flash pkg-flash-ok"><i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($_SESSION['custom_pkg_success']) ?></div>
                    <?php unset($_SESSION['custom_pkg_success']); ?>
                <?php endif; ?>
                <?php if (!empty($_SESSION['custom_pkg_error'])): ?>
                    <div class="pkg-flash pkg-flash-err"><i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($_SESSION['custom_pkg_error']) ?></div>
                    <?php unset($_SESSION['custom_pkg_error']); ?>
                <?php endif; ?>
                <form method="POST" action="<?= $appUrl ?>/packages/custom_request" id="customPkgForm">
                    <div class="pkg-cf-row">
                        <div class="pkg-cf-group"><label class="pkg-cf-label">Nombre <span class="pkg-req">*</span></label><input type="text" name="customer_name" class="pkg-cf-input" placeholder="Tu nombre" value="<?= htmlspecialchars($_SESSION['user_name'] ?? '') ?>" required></div>
                        <div class="pkg-cf-group"><label class="pkg-cf-label">Email <span class="pkg-req">*</span></label><input type="email" name="customer_email" class="pkg-cf-input" placeholder="tu@correo.com" value="<?= htmlspecialchars($_SESSION['user_email'] ?? '') ?>" required></div>
                    </div>
                    <div class="pkg-cf-row">
                        <div class="pkg-cf-group"><label class="pkg-cf-label">Teléfono / WhatsApp</label><input type="tel" name="customer_phone" class="pkg-cf-input" placeholder="+1 809 000 0000"></div>
                        <div class="pkg-cf-group"><label class="pkg-cf-label">Fecha deseada</label><input type="date" name="travel_date" class="pkg-cf-input" min="<?= date('Y-m-d', strtotime('+1 day')) ?>"></div>
                    </div>
                    <div class="pkg-cf-row">
                        <div class="pkg-cf-group"><label class="pkg-cf-label">Destinos / Regiones <span class="pkg-req">*</span></label><input type="text" name="destinations" class="pkg-cf-input" placeholder="Punta Cana, Samaná, Puerto Plata…" required></div>
                        <div class="pkg-cf-group"><label class="pkg-cf-label">Duración (noches)</label>
                            <div class="pkg-pax"><button type="button" class="pkg-pax-btn" id="cfNightsMinus">−</button><input type="number" name="nights_count" id="cfNightsInput" class="pkg-pax-val" value="4" min="1" max="30" readonly><button type="button" class="pkg-pax-btn" id="cfNightsPlus">+</button></div>
                        </div>
                    </div>
                    <div class="pkg-cf-group">
                        <label class="pkg-cf-label">Tipo de alojamiento</label>
                        <div class="pkg-activity-chips" id="accommodationChips">
                            <?php foreach (['Económico', '3 estrellas', '4 estrellas', '5 estrellas', 'Lujo', 'Todo incluido', 'Solo alojamiento'] as $acc): ?>
                                <label class="pkg-act-chip"><input type="checkbox" name="accommodation_check[]" value="<?= htmlspecialchars($acc) ?>" hidden><?= htmlspecialchars($acc) ?></label>
                            <?php endforeach; ?>
                        </div>
                        <input type="hidden" name="accommodation" id="accommodationHidden">
                    </div>
                    <div class="pkg-cf-row">
                        <div class="pkg-cf-group"><label class="pkg-cf-label">Presupuesto (USD)</label>
                            <select name="budget" class="pkg-cf-input">
                                <option value="">No definido</option>
                                <option>Menos de $500</option>
                                <option>$500-$1000</option>
                                <option>$1000-$2000</option>
                                <option>$2000-$4000</option>
                                <option>Más de $4000</option>
                            </select>
                        </div>
                    </div>
                    <div class="pkg-cf-group"><label class="pkg-cf-label">Notas adicionales</label><textarea name="additional_notes" class="pkg-cf-input pkg-cf-textarea" rows="3" placeholder="Necesidades especiales, actividades preferidas…"></textarea></div>
                    <button type="submit" class="pkg-cf-submit" id="customPkgSubmit">
                        <i class="fas fa-paper-plane me-2"></i>Enviar solicitud
                        <span class="pkg-cf-submit-sub">Gratis y sin compromiso</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</section> -->

<!-- ════════════════════════════════════════
     CTA FINAL
════════════════════════════════════════ -->
<section class="pkg-cta-section" data-reveal>
    <div class="container text-center">
        <h2 class="pkg-cta-title">¿Listo para tu próxima escapada?</h2>
        <p class="pkg-cta-sub">Más viajeros han confiado en nosotros para sus vacaciones</p>
        <a href="<?= $appUrl ?>/excursiones" class="pkg-cta-btn me-3"><i class="fas fa-hiking me-2"></i>Ver
            excursiones</a>
        <!-- <a href="#custom-form" class="pkg-cta-btn-outline"><i class="fas fa-magic me-2"></i>Paquete a medida</a> -->
    </div>
</section>

<!-- ════════════════════════════════════════
     LIGHTBOX
════════════════════════════════════════ -->
<div id="pkgLightbox" class="pkg-lb" role="dialog" aria-modal="true" style="display:none">
    <div class="pkg-lb-backdrop" id="pkgLbBackdrop"></div>
    <button class="pkg-lb-close" id="pkgLbClose" aria-label="Cerrar"><i class="fas fa-times"></i></button>
    <button class="pkg-lb-nav pkg-lb-prev" id="pkgLbPrev" aria-label="Anterior"><i
            class="fas fa-chevron-left"></i></button>
    <button class="pkg-lb-nav pkg-lb-next" id="pkgLbNext" aria-label="Siguiente"><i
            class="fas fa-chevron-right"></i></button>
    <div class="pkg-lb-stage">
        <div class="pkg-lb-loader" id="pkgLbLoader">
            <div class="pkg-lb-spin"></div>
        </div>
        <img id="pkgLbImg" src="" alt="Foto del paquete" class="pkg-lb-image">
    </div>
    <div class="pkg-lb-footer">
        <div class="pkg-lb-counter" id="pkgLbCounter">1 / 1</div>
        <div class="pkg-lb-thumbs" id="pkgLbThumbs"></div>
    </div>
</div>

<?php include APP_ROOT . '/app/views/layouts/footer.php'; ?>


<!-- ════════════════════════════════════════
     JAVASCRIPT (idéntico al de excursiones, con nombres adaptados)
════════════════════════════════════════ -->
<script>
    (function () {
        'use strict';

        function initWaves() {
            ['.pkg-wave-b', '.pkg-wave-f'].forEach((s, i) => {
                const el = document.querySelector(s);
                if (el) setTimeout(() => el.classList.add('w-in'), i * 180);
            });
        }

        function initParallax() {
            const bg = document.getElementById('pkgHeroBg');
            if (!bg || window.matchMedia('(prefers-reduced-motion:reduce)').matches) return;
            let tick = false;
            window.addEventListener('scroll', () => {
                if (!tick) {
                    requestAnimationFrame(() => {
                        bg.style.transform = `translateY(${window.scrollY * 0.28}px)`;
                        tick = false;
                    });
                    tick = true;
                }
            }, {
                passive: true
            });
        }

        function initHeroAnims() {
            document.querySelectorAll('[data-anim]').forEach(el => {
                setTimeout(() => el.classList.add('anim-in'), parseInt(el.dataset.delay || 0));
            });
        }

        function initReveal() {
            const obs = new IntersectionObserver(entries => {
                entries.forEach(e => {
                    if (e.isIntersecting) {
                        setTimeout(() => e.target.classList.add('revealed'), parseInt(e.target.dataset.revealDelay || 0));
                        obs.unobserve(e.target);
                    }
                });
            }, {
                threshold: .12,
                rootMargin: '0px 0px -30px 0px'
            });
            document.querySelectorAll('[data-reveal]').forEach(el => obs.observe(el));
        }

        function initCarousels() {
            document.querySelectorAll('.pkg-carousel').forEach(car => {
                const slides = car.querySelectorAll('.pkg-carousel-slide'),
                    dots = car.querySelectorAll('.pkg-car-dot'),
                    prev = car.querySelector('.pkg-car-prev'),
                    next = car.querySelector('.pkg-car-next'),
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

        function initDescToggle() {
            document.querySelectorAll('.pkg-read-more').forEach(btn => {
                const id = btn.dataset.id,
                    full = btn.dataset.full,
                    short = btn.dataset.short,
                    desc = document.getElementById('pkg-desc-' + id);
                if (!desc) return;
                desc.textContent = short;
                let ex = false;
                btn.addEventListener('click', () => {
                    ex = !ex;
                    desc.textContent = ex ? full : short;
                    btn.classList.toggle('open', ex);
                    btn.querySelector('.pkg-rm-text').textContent = ex ? 'Ocultar descripción' : 'Leer descripción completa';
                    const ico = btn.querySelector('.pkg-rm-icon i');
                    if (ico) ico.className = ex ? 'fas fa-book' : 'fas fa-book-open';
                });
            });
        }

        function initCategoryTabs() {
            const tabs = document.querySelectorAll('.pkg-cat-tab'),
                cards = document.querySelectorAll('.pkg-card-col'),
                noRes = document.getElementById('noCatResults'),
                title = document.getElementById('gridTitle');
            if (!tabs.length) return;

            function updateCounters() {
                tabs.forEach(tab => {
                    const cat = tab.dataset.cat;
                    const countSpan = tab.querySelector('.pkg-cat-count');
                    if (!countSpan) return;
                    let visibleCount = 0;
                    if (cat === 'all') visibleCount = cards.length;
                    else cards.forEach(card => {
                        if (card.dataset.cat === cat && card.style.display !== 'none') visibleCount++;
                    });
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
                        const scrollContainer = tab.closest('.pkg-cats-scroll');
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
                if (title) title.textContent = visible + ' paquete' + (visible !== 1 ? 's' : '') + ' disponible' + (visible !== 1 ? 's' : '');
                updateCounters();
                setActiveTab(tab);
            }
            tabs.forEach(tab => {
                tab.addEventListener('click', () => filterByTab(tab));
            });
            updateCounters();
            const activeTab = document.querySelector('.pkg-cat-tab.active');
            if (activeTab) setActiveTab(activeTab);
        }

        function initActivityChips() {
            const chips = document.querySelectorAll('.pkg-act-chip'),
                hidden = document.getElementById('accommodationHidden');
            if (!chips.length || !hidden) return;
            chips.forEach(chip => {
                const cb = chip.querySelector('input');
                chip.addEventListener('click', () => {
                    cb.checked = !cb.checked;
                    chip.classList.toggle('selected', cb.checked);
                    const sel = [];
                    chips.forEach(c => {
                        if (c.querySelector('input').checked) sel.push(c.querySelector('input').value);
                    });
                    hidden.value = sel.join(', ');
                });
            });
        }

        function initPaxControls() {
            const m = document.getElementById('cfNightsMinus'),
                p = document.getElementById('cfNightsPlus'),
                inp = document.getElementById('cfNightsInput');
            if (!m || !p || !inp) return;
            m.addEventListener('click', () => {
                let v = parseInt(inp.value);
                if (v > 1) inp.value = v - 1;
            });
            p.addEventListener('click', () => {
                let v = parseInt(inp.value);
                if (v < 30) inp.value = v + 1;
            });
        }

        function initCustomForm() {
            const form = document.getElementById('customPkgForm'),
                btn = document.getElementById('customPkgSubmit');
            if (!form) return;
            form.addEventListener('submit', () => {
                const h = document.getElementById('accommodationHidden'),
                    s = document.querySelectorAll('.pkg-act-chip.selected');
                if (h && s.length) h.value = Array.from(s).map(c => c.textContent.trim()).join(', ');
                if (btn) {
                    btn.disabled = true;
                    btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Enviando…<span class="pkg-cf-submit-sub">Un momento</span>';
                }
            });
        }

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
                this.el = document.getElementById('pkgLightbox');
                this.img = document.getElementById('pkgLbImg');
                this.loader = document.getElementById('pkgLbLoader');
                this.counter = document.getElementById('pkgLbCounter');
                this.thumbs = document.getElementById('pkgLbThumbs');
                this.prev = document.getElementById('pkgLbPrev');
                this.next = document.getElementById('pkgLbNext');
                if (!this.el) return;
                document.addEventListener('click', e => {
                    const t = e.target.closest('.pkg-lb-trigger');
                    if (!t) return;
                    e.preventDefault();
                    try {
                        this.open(JSON.parse(t.dataset.gallery || '[]'), parseInt(t.dataset.index || 0));
                    } catch (_) { }
                });
                document.getElementById('pkgLbClose')?.addEventListener('click', () => this.exit());
                document.getElementById('pkgLbBackdrop')?.addEventListener('click', () => this.exit());
                this.prev?.addEventListener('click', () => this.go(this.cur - 1));
                this.next?.addEventListener('click', () => this.go(this.cur + 1));
                document.addEventListener('keydown', e => {
                    if (!this.el || this.el.style.display === 'none') return;
                    if (e.key === 'Escape') this.exit();
                    if (e.key === 'ArrowLeft') this.go(this.cur - 1);
                    if (e.key === 'ArrowRight') this.go(this.cur + 1);
                });
                let tx = 0;
                this.el.addEventListener('touchstart', e => {
                    tx = e.touches[0].clientX;
                }, {
                    passive: true
                });
                this.el.addEventListener('touchend', e => {
                    const d = tx - e.changedTouches[0].clientX;
                    if (Math.abs(d) > 50) this.go(this.cur + (d > 0 ? 1 : -1));
                }, {
                    passive: true
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
                    img.className = 'pkg-lb-thumb' + (i === this.cur ? ' lb-active' : '');
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

        document.addEventListener('DOMContentLoaded', () => {
            initWaves();
            initParallax();
            initHeroAnims();
            initReveal();
            initCarousels();
            initDescToggle();
            initCategoryTabs();
            initActivityChips();
            initPaxControls();
            initCustomForm();
            LB.init();
        });
    })();
</script>