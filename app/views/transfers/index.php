<?php $customCss = ['modules/public-transfers.css']; ?>
<?php
// app/views/transfers/index.php
require_once APP_ROOT . '/app/views/layouts/header.php';
require_once APP_ROOT . '/app/views/layouts/navigation.php';

/* ─────────────────────────────────────────────
   Solo 10 en el catálogo visible;
   todos disponibles para el buscador JS.
───────────────────────────────────────────── */
$featuredTransfers = array_slice($transfers, 0, 10);
$totalTransfers = count($transfers);

$allOrigins = [];
$allDestinos = [];
foreach ($transfers as $tr) {
  $from = $tr['from_location'] ?? '';
  $to = $tr['to_location'] ?? '';
  if ($from && !in_array($from, $allOrigins))
    $allOrigins[] = $from;
  if ($to && !in_array($to, $allDestinos))
    $allDestinos[] = $to;
}
sort($allOrigins);
sort($allDestinos);

$transfersJson = json_encode(array_values(array_map(function ($tr) {
  // Decode gallery JSON so JS receives a proper array
  if (!empty($tr['gallery']) && is_string($tr['gallery'])) {
    $decoded = json_decode($tr['gallery'], true);
    $tr['gallery'] = is_array($decoded) ? $decoded : [];
  } elseif (!is_array($tr['gallery'] ?? null)) {
    $tr['gallery'] = [];
  }
  return $tr;
}, $transfers)), JSON_UNESCAPED_UNICODE);

/* ── helper card ── */
function renderTrCard(array $tr, bool $highlighted = false): string
{
  $appUrl = defined('APP_URL') ? APP_URL : '';
  $price = number_format((float) ($tr['price'] ?? 0), 2);
  $name = htmlspecialchars($tr['name'] ?? 'Transfer');
  $from = htmlspecialchars($tr['from_location'] ?? '—');
  $to = htmlspecialchars($tr['to_location'] ?? '—');
  $veh = htmlspecialchars($tr['vehicle_type'] ?? 'Privado');
  $pax = (int) ($tr['max_passengers'] ?? 0);
  $id = (int) $tr['id'];
  $url = $appUrl . '/reserva/create/transfer/' . $id;
  $hClass = $highlighted ? ' tr-card--highlight' : '';
  $gallery = [];
  if (!empty($tr['gallery'])) {
    $decoded = is_array($tr['gallery']) ? $tr['gallery'] : json_decode($tr['gallery'], true);
    $gallery = is_array($decoded) ? $decoded : [];
  }
  $allImages = [];
  if (!empty($tr['image'])) $allImages[] = $tr['image'];
  foreach ($gallery as $g) if (!empty($g)) $allImages[] = $g;

  ob_start(); ?>
    <div class="tr-card<?= $hClass ?>">
      <?php if ($highlighted): ?>
          <div class="tr-card-match-badge"><i class="fas fa-check-circle me-1"></i>Tu ruta exacta</div>
      <?php endif; ?>
      <div class="tr-card-img-wrap">
        <div class="tr-card-img-container">
          <?php if (empty($allImages)): ?>
              <div class="tr-card-img tr-img-placeholder"><i class="fas fa-van-shuttle"></i></div>
          <?php else: ?>
            <?php foreach ($allImages as $idx => $imgFile): ?>
                <img src="<?= $appUrl ?>/assets/uploads/transfers/<?= htmlspecialchars($imgFile) ?>" 
                     class="tr-card-img <?= $idx === 0 ? 'active' : '' ?>" 
                     alt="<?= $name ?>" 
                     loading="lazy">
            <?php endforeach; ?>
          <?php endif; ?>
        </div>
        <div class="tr-card-veil"></div>
        <span class="tr-vehicle-tag"><i class="fas fa-car me-1"></i><?= $veh ?></span>
        <?php if ($pax > 0): ?>
            <span class="tr-cap-tag"><i class="fas fa-users me-1"></i><?= $pax ?> pax</span>
        <?php endif; ?>
        <?php if (!empty($gallery)): ?>
            <button type="button" class="tr-gallery-btn" data-id="<?= $id ?>" aria-label="Ver galería de fotos">
              <i class="fas fa-images"></i> <?= count($gallery) + (!empty($tr['image']) ? 1 : 0) ?> fotos
            </button>
        <?php endif; ?>
        <div class="tr-card-overlay">
          <div class="tr-overlay-price">
            <span><?= ($tr['price_type'] ?? 'paquete') === 'persona' ? 'por persona' : 'Precio por trayecto' ?></span>
            <strong>$<?= $price ?></strong>
          </div>
          <a href="<?= $url ?>" class="tr-overlay-btn">Reservar ahora →</a>
        </div>
      </div>
      <div class="tr-card-body">
        <h5 class="tr-card-title"><?= $name ?></h5>
        <div class="tr-route-visual">
          <div class="tr-rv-point">
            <span class="tr-rv-dot tr-rv-origin"></span>
            <span class="tr-rv-label"><?= $from ?></span>
          </div>
          <div class="tr-rv-line">
            <div class="tr-rv-line-inner"></div>
            <i class="fas fa-van-shuttle tr-rv-icon"></i>
          </div>
          <div class="tr-rv-point tr-rv-right">
            <span class="tr-rv-dot tr-rv-dest"></span>
            <span class="tr-rv-label"><?= $to ?></span>
          </div>
        </div>
        <ul class="tr-details-list">
          <li><span class="tr-detail-icon"><i class="fas fa-car"></i></span><span
              class="tr-detail-label">Vehículo</span><span class="tr-detail-val"><?= $veh ?></span></li>
          <?php if ($pax > 0): ?>
            <li><span class="tr-detail-icon"><i class="fas fa-users"></i></span><span
                class="tr-detail-label">Capacidad</span><span class="tr-detail-val"><?= $pax ?> pasajeros</span></li>
          <?php endif; ?>
          <li><span class="tr-detail-icon"><i class="fas fa-clock"></i></span><span
              class="tr-detail-label">Disponibilidad</span><span class="tr-detail-val">24 / 7</span></li>
          <li><span class="tr-detail-icon"><i class="fas fa-snowflake"></i></span><span
              class="tr-detail-label">Confort</span><span class="tr-detail-val">A/C · WiFi</span></li>
        </ul>
        <div class="tr-includes">
          <span class="tr-inc-item"><i class="fas fa-check-circle"></i> Recogida en aeropuerto</span>
          <span class="tr-inc-item"><i class="fas fa-check-circle"></i> Conductor certificado</span>
          <span class="tr-inc-item"><i class="fas fa-check-circle"></i> Seguimiento de vuelo</span>
        </div>
        <div class="tr-card-footer">
          <div class="tr-price-block">
            <span
              class="tr-price-label"><?= ($tr['price_type'] ?? 'paquete') === 'persona' ? 'por persona' : 'por trayecto' ?></span>
            <strong class="tr-price">$<?= $price ?></strong>
          </div>
          <a href="<?= $url ?>" class="tr-reserve-btn">
            <i class="fas fa-calendar-plus me-1"></i>Reservar
          </a>
        </div>
      </div>
    </div>
    <?php return ob_get_clean();
}
?>
<section class="tr-hero">
  <div class="tr-hero-bg" id="trHeroBg"></div>
  <div class="tr-hero-grain" aria-hidden="true"></div>
  <div class="container tr-hero-content">
    <div class="tr-hero-inner">
      <div class="tr-eyebrow" data-anim data-delay="0">
        <span class="tr-dot"></span>
        <i class="fas fa-van-shuttle"></i>Transfers
      </div>
      <h1 class="tr-hero-title" data-anim data-delay="120">
        Traslados privados<br>en República Dominicana
        <span class="tr-title-bar"></span>
      </h1>
      <p class="tr-hero-sub" data-anim data-delay="240">Transfers seguros y puntuales desde aeropuertos a hoteles en
        Punta Cana, Santo Domingo y toda la isla.</p>
      <div class="tr-chips" data-anim data-delay="320">
        <div class="tr-chip"><i class="fas fa-clock"></i><span>Puntualidad garantizada</span></div>
        <div class="tr-chip"><i class="fas fa-id-card"></i><span>Choferes certificados</span></div>
        <div class="tr-chip"><i class="fas fa-snowflake"></i><span>Vehículos A/C</span></div>
        <div class="tr-chip"><i class="fas fa-plane-arrival"></i><span>Traslado aeropuerto</span></div>
      </div>
    </div>
  </div>
  <div class="tr-waves" aria-hidden="true">
    <svg class="tr-wave" viewBox="0 0 1440 90" preserveAspectRatio="none">
      <path fill="rgba(202,240,248,.45)" d="M0,45 C240,80 480,10 720,45 C960,80 1200,20 1440,45 L1440,90 L0,90 Z" />
    </svg>
    <svg class="tr-wave" style="bottom:0" viewBox="0 0 1440 90" preserveAspectRatio="none">
      <path fill="#EAF6FF"
        d="M0,58 C180,28 360,78 540,58 C720,38 900,72 1080,56 C1260,40 1380,62 1440,58 L1440,90 L0,90 Z" />
    </svg>
  </div>
</section>


<section class="tr-planner-section">
  <div class="container">
    <div class="tr-planner-card" data-reveal>
      <div class="tr-planner-header">
        <div class="tr-planner-icon"><i class="fas fa-route"></i></div>
        <div>
          <h2 class="tr-planner-title">Busca tu traslado</h2>
          <p class="tr-planner-sub">Escribe el aeropuerto de origen y tu hotel de destino para encontrar el transfer
            ideal para tu ruta.</p>
        </div>
      </div>
      <div class="tr-planner-form">
        <div class="tr-pf-group">
          <label class="tr-pf-label"><i class="fas fa-plane-departure me-1"></i>Desde (origen)</label>
          <div class="tr-ac-wrap">
            <input type="text" id="originInput" class="tr-pf-input" placeholder="Ej: PUJ, SDQ, aeropuerto…"
              autocomplete="off">
            <ul class="tr-ac-drop" id="originDrop"></ul>
          </div>
        </div>
        <button class="tr-swap-btn" id="swapBtn" type="button" title="Invertir ruta">
          <i class="fas fa-arrow-right-arrow-left"></i>
        </button>
        <div class="tr-pf-group">
          <label class="tr-pf-label"><i class="fas fa-hotel me-1"></i>Hasta (destino)</label>
          <div class="tr-ac-wrap">
            <input type="text" id="destInput" class="tr-pf-input" placeholder="Ej: Barceló, Hard Rock, Bávaro…"
              autocomplete="off">
            <ul class="tr-ac-drop" id="destDrop"></ul>
          </div>
        </div>
        <div class="tr-pf-group tr-pf-sm">
          <label class="tr-pf-label"><i class="fas fa-calendar me-1"></i>Fecha</label>
          <input type="date" id="dateInput" class="tr-pf-input" min="<?= date('Y-m-d') ?>">
        </div>
        <div class="tr-pf-group tr-pf-sm">
          <label class="tr-pf-label"><i class="fas fa-user me-1"></i>Adultos</label>
          <div class="tr-pax-counter">
            <button type="button" class="tr-pax-btn" id="paxAdultMinus">−</button>
            <span id="paxAdultCount">1</span>
            <button type="button" class="tr-pax-btn" id="paxAdultPlus">+</button>
          </div>
        </div>
        <div class="tr-pf-group tr-pf-sm">
          <label class="tr-pf-label"><i class="fas fa-child me-1"></i>Niños</label>
          <div class="tr-pax-counter">
            <button type="button" class="tr-pax-btn" id="paxChildMinus">−</button>
            <span id="paxChildCount">0</span>
            <button type="button" class="tr-pax-btn" id="paxChildPlus">+</button>
          </div>
        </div>
        <button class="tr-search-btn" id="searchBtn" type="button">
          <i class="fas fa-magnifying-glass me-2"></i>Buscar transfers
        </button>
      </div>
      <?php if (!empty($allOrigins)): ?>
          <div class="tr-planner-hint">
            <strong style="color:var(--c-dark);margin-right:6px;">Rutas desde:</strong>
            <?php foreach ($allOrigins as $o): ?>
                <span class="tr-hint-tag" onclick="setOriginAndSearch('<?= htmlspecialchars($o, ENT_QUOTES) ?>')">
                  <?= htmlspecialchars($o) ?>
                </span>
            <?php endforeach; ?>
          </div>
      <?php endif; ?>
    </div>
  </div>
</section>


<section class="tr-main-section" id="mainSection">
  <div class="container">

    <!-- Cabecera dinámica (cambia entre modo catálogo y resultados) -->
    <div class="tr-main-head" id="mainHead">
      <div class="tr-main-eyebrow mode-catalog" id="mainEyebrow">Transfers populares</div>
      <h2 class="tr-main-title" id="mainTitle">Rutas más solicitadas</h2>
      <p class="tr-main-sub" id="mainSub">
        Nuestros transfers más populares.
        ¿No encuentras tu hotel?
        <strong style="color:var(--c-ocean);cursor:pointer;" onclick="document.getElementById('originInput').focus()">
          Usa el buscador
        </strong>
        para ver las <?= $totalTransfers ?> rutas disponibles.
      </p>
    </div>

    <!-- Barra de estado de búsqueda activa -->
    <div class="tr-search-bar" id="searchBar">
      <div class="tr-search-bar-info">
        <div class="tr-search-bar-icon"><i class="fas fa-magnifying-glass"></i></div>
        <div class="tr-search-bar-text">
          <div class="tr-search-bar-count" id="searchBarCount"></div>
          <div class="tr-search-bar-query" id="searchBarQuery"></div>
        </div>
      </div>
      <button class="tr-search-bar-close" id="clearSearchBtn">
        <i class="fas fa-times"></i> Limpiar búsqueda
      </button>
    </div>

    <!-- Sin resultados -->
    <div class="tr-no-results" id="noResults">
      <div class="tr-nr-icon">🔍</div>
      <h4>Sin resultados para esta ruta</h4>
      <p>Intenta con términos más cortos o contáctanos para un traslado personalizado.</p>
      <button class="tr-nr-btn" onclick="clearSearch()">Ver transfers populares</button>
    </div>

    <!-- Grid (catálogo o resultados — siempre el mismo elemento) -->
    <div class="row g-4" id="cardsGrid">
      <?php foreach ($featuredTransfers as $i => $tr): ?>
          <div class="col-lg-4 col-md-6" data-reveal data-reveal-delay="<?= ($i % 3) * 90 ?>">
            <?= renderTrCard($tr) ?>
          </div>
      <?php endforeach; ?>
    </div>

    <!-- Banner "más rutas" — visible solo en modo catálogo -->
    <?php if ($totalTransfers > 10): ?>
        <div class="tr-more-banner" id="moreBanner" data-reveal>
          <div class="tr-more-banner-text">
            <h3><i class="fas fa-map-marked-alt me-2"></i><?= $totalTransfers - 10 ?> rutas adicionales disponibles</h3>
            <p>Tenemos transfers a todos los hoteles de Punta Cana, Bávaro y Santo Domingo. Busca tu hotel para ver precio
              al instante.</p>
          </div>
          <button class="tr-more-banner-btn" onclick="focusSearch()">
            <i class="fas fa-magnifying-glass"></i> Buscar mi hotel
          </button>
        </div>
    <?php endif; ?>

    <div class="tr-back-row" data-reveal>
      <a href="<?= APP_URL ?>/" class="tr-back-btn">
        <i class="fas fa-arrow-left me-2"></i>Volver al inicio
      </a>
    </div>

  </div>
</section>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>

<script>
  var TR_ORIGINS = <?= json_encode($allOrigins, JSON_UNESCAPED_UNICODE) ?>;
  var TR_DESTS = <?= json_encode($allDestinos, JSON_UNESCAPED_UNICODE) ?>;
  var TR_DATA = <?= $transfersJson ?>;
  var APP_URL_JS = '<?= rtrim(APP_URL, '/') ?>';
</script>

<script>
  (function () {
    'use strict';

    /* ── Estado de la búsqueda ── */
    var searchActive = false;

    /* ── Elementos del DOM ── */
    var grid = document.getElementById('cardsGrid');
    var searchBar = document.getElementById('searchBar');
    var noResults = document.getElementById('noResults');
    var moreBanner = document.getElementById('moreBanner');
    var mainEyebrow = document.getElementById('mainEyebrow');
    var mainTitle = document.getElementById('mainTitle');
    var mainSub = document.getElementById('mainSub');
    var searchBarCount = document.getElementById('searchBarCount');
    var searchBarQuery = document.getElementById('searchBarQuery');

    /* ── Parallax ── */
    var heroBg = document.getElementById('trHeroBg');
    if (heroBg && !window.matchMedia('(prefers-reduced-motion:reduce)').matches) {
      var ticking = false;
      window.addEventListener('scroll', function () {
        if (!ticking) {
          requestAnimationFrame(function () {
            heroBg.style.transform = 'translateY(' + (window.scrollY * 0.28) + 'px)';
            ticking = false;
          });
          ticking = true;
        }
      }, {
        passive: true
      });
    }

    /* ── Hero anims ── */
    document.querySelectorAll('[data-anim]').forEach(function (el) {
      setTimeout(function () {
        el.classList.add('anim-in');
      }, parseInt(el.dataset.delay || 0));
    });

    /* ── Scroll reveal ── */
    var ro = new IntersectionObserver(function (entries) {
      entries.forEach(function (e) {
        if (e.isIntersecting) {
          setTimeout(function () {
            e.target.classList.add('revealed');
          }, parseInt(e.target.dataset.revealDelay || 0));
          ro.unobserve(e.target);
        }
      });
    }, {
      threshold: 0.1,
      rootMargin: '0px 0px -30px 0px'
    });
    document.querySelectorAll('[data-reveal]').forEach(function (el) {
      ro.observe(el);
    });

    /* ── Autocomplete ── */
    function makeAC(inputId, dropId, suggestions, icon) {
      var input = document.getElementById(inputId);
      var drop = document.getElementById(dropId);
      if (!input || !drop) return;

      function show(q) {
        var lq = q.toLowerCase().trim();
        var matches = lq.length === 0 ? suggestions : suggestions.filter(function (s) {
          return s.toLowerCase().includes(lq);
        });
        if (!matches.length) {
          drop.classList.remove('open');
          return;
        }
        drop.innerHTML = matches.slice(0, 12).map(function (s) {
          return '<li data-val="' + s.replace(/"/g, '&quot;') + '"><i class="fas ' + icon + '"></i>' + s + '</li>';
        }).join('');
        drop.classList.add('open');
        drop.querySelectorAll('li').forEach(function (li) {
          li.addEventListener('mousedown', function (e) {
            e.preventDefault();
            input.value = li.dataset.val;
            drop.classList.remove('open');
          });
        });
      }
      input.addEventListener('input', function () {
        show(input.value);
      });
      input.addEventListener('focus', function () {
        show(input.value);
      });
      document.addEventListener('click', function (e) {
        if (!input.contains(e.target) && !drop.contains(e.target)) drop.classList.remove('open');
      });
    }
    makeAC('originInput', 'originDrop', TR_ORIGINS, 'fa-plane-departure');
    makeAC('destInput', 'destDrop', TR_DESTS, 'fa-hotel');

    /* ── Adultos ── */
    var paxAdultN = 1;
    var paxAdultDisp = document.getElementById('paxAdultCount');
    document.getElementById('paxAdultMinus').addEventListener('click', function () {
      if (paxAdultN > 1) {
        paxAdultN--;
        paxAdultDisp.textContent = paxAdultN;
      }
    });
    document.getElementById('paxAdultPlus').addEventListener('click', function () {
      if (paxAdultN < 20) {
        paxAdultN++;
        paxAdultDisp.textContent = paxAdultN;
      }
    });

    /* ── Niños ── */
    var paxChildN = 0;
    var paxChildDisp = document.getElementById('paxChildCount');
    document.getElementById('paxChildMinus').addEventListener('click', function () {
      if (paxChildN > 0) {
        paxChildN--;
        paxChildDisp.textContent = paxChildN;
      }
    });
    document.getElementById('paxChildPlus').addEventListener('click', function () {
      if (paxChildN < 20) {
        paxChildN++;
        paxChildDisp.textContent = paxChildN;
      }
    });

    /* ── Swap ── */
    document.getElementById('swapBtn').addEventListener('click', function () {
      var o = document.getElementById('originInput'),
        d = document.getElementById('destInput');
      var tmp = o.value;
      o.value = d.value;
      d.value = tmp;
    });

    /* ── Build card JS ── */
    function buildCard(tr, idx, isFirst) {
      var price = parseFloat(tr.price || 0).toFixed(2);
      var name = (tr.name || 'Transfer').replace(/</g, '&lt;');
      var from = (tr.from_location || '—').replace(/</g, '&lt;');
      var to = (tr.to_location || '—').replace(/</g, '&lt;');
      var veh = (tr.vehicle_type || 'Privado').replace(/</g, '&lt;');
      var pax = parseInt(tr.max_passengers || 4);
      var adultsCount = parseInt(paxAdultDisp.textContent) || 1;
      var childrenCount = parseInt(paxChildDisp.textContent) || 0;
      var id = tr.id;
      var url = APP_URL_JS + '/reserva/create/transfer/' + id + '?adults=' + adultsCount + '&children=' + childrenCount;
      var hClass = isFirst ? ' tr-card--highlight' : '';
      var badge = isFirst ? '<div class="tr-card-match-badge"><i class="fas fa-check-circle me-1"></i>Tu ruta exacta</div>' : '';
      
      var allImgs = [];
      if (tr.image) allImgs.push(tr.image);
      if (Array.isArray(tr.gallery)) tr.gallery.forEach(function(g) { if(g) allImgs.push(g); });

      var imgHtml = '';
      if (allImgs.length === 0) {
          imgHtml = '<div class="tr-card-img tr-img-placeholder"><i class="fas fa-van-shuttle"></i></div>';
      } else {
          imgHtml = '<div class="tr-card-img-container">';
          allImgs.forEach(function(imgFile, i) {
              imgHtml += '<img src="' + APP_URL_JS + '/assets/uploads/transfers/' + imgFile + '" class="tr-card-img' + (i === 0 ? ' active' : '') + '" alt="' + name + '" loading="lazy">';
          });
          imgHtml += '</div>';
      }

      var delay = Math.min(idx, 5) * 70; // máx 350ms stagger

      return '<div class="col-lg-4 col-md-6 tr-result-card-wrap" style="animation-delay:' + delay + 'ms">' +
        '<div class="tr-card' + hClass + '">' + badge +
        '<div class="tr-card-img-wrap">' + imgHtml +
        '<div class="tr-card-veil"></div>' +
        '<span class="tr-vehicle-tag"><i class="fas fa-car me-1"></i>' + veh + '</span>' +
        (pax > 0 ? '<span class="tr-cap-tag"><i class="fas fa-users me-1"></i>' + pax + ' pax</span>' : '') +
        (Array.isArray(tr.gallery) && tr.gallery.length > 0 ?
          '<button type="button" class="tr-gallery-btn" data-id="' + tr.id + '"><i class="fas fa-images"></i> ' + (tr.gallery.length + (tr.image ? 1 : 0)) + ' fotos</button>' : '') +
        '<div class="tr-card-overlay">' +
        '<div class="tr-overlay-price"><span>' + ((tr.price_type === 'persona') ? 'por persona' : 'Precio por trayecto') + '</span><strong>$' + price + '</strong></div>' +
        '<a href="' + url + '" class="tr-overlay-btn">Reservar ahora →</a>' +
        '</div></div>' +
        '<div class="tr-card-body">' +
        '<h5 class="tr-card-title">' + name + '</h5>' +
        '<div class="tr-route-visual">' +
        '<div class="tr-rv-point"><span class="tr-rv-dot tr-rv-origin"></span><span class="tr-rv-label">' + from + '</span></div>' +
        '<div class="tr-rv-line"><div class="tr-rv-line-inner"></div><i class="fas fa-van-shuttle tr-rv-icon"></i></div>' +
        '<div class="tr-rv-point tr-rv-right"><span class="tr-rv-dot tr-rv-dest"></span><span class="tr-rv-label">' + to + '</span></div>' +
        '</div>' +
        '<ul class="tr-details-list">' +
        '<li><span class="tr-detail-icon"><i class="fas fa-car"></i></span><span class="tr-detail-label">Vehículo</span><span class="tr-detail-val">' + veh + '</span></li>' +
        (pax > 0 ? '<li><span class="tr-detail-icon"><i class="fas fa-users"></i></span><span class="tr-detail-label">Capacidad</span><span class="tr-detail-val">' + pax + ' pasajeros</span></li>' : '') +
        '<li><span class="tr-detail-icon"><i class="fas fa-clock"></i></span><span class="tr-detail-label">Disponibilidad</span><span class="tr-detail-val">24 / 7</span></li>' +
        '<li><span class="tr-detail-icon"><i class="fas fa-snowflake"></i></span><span class="tr-detail-label">Confort</span><span class="tr-detail-val">A/C · WiFi</span></li>' +
        '</ul>' +
        '<div class="tr-includes">' +
        '<span class="tr-inc-item"><i class="fas fa-check-circle"></i> Recogida en aeropuerto</span>' +
        '<span class="tr-inc-item"><i class="fas fa-check-circle"></i> Conductor certificado</span>' +
        '<span class="tr-inc-item"><i class="fas fa-check-circle"></i> Seguimiento de vuelo</span>' +
        '</div>' +
        '<div class="tr-card-footer">' +
        '<div class="tr-price-block"><span class="tr-price-label">' + ((tr.price_type === 'persona') ? 'por persona' : 'por trayecto') + '</span><strong class="tr-price">$' + price + '</strong></div>' +
        '<a href="' + url + '" class="tr-reserve-btn"><i class="fas fa-calendar-plus me-1"></i>Reservar</a>' +
        '</div></div></div></div>';
    }

    /* ── Mostrar resultados (reemplaza el catálogo) ── */
    function showResults(results, originVal, destVal, dateVal, pax) {
      searchActive = true;

      /* Actualizar cabecera */
      mainEyebrow.className = 'tr-main-eyebrow mode-results';
      mainEyebrow.innerHTML = '<i class="fas fa-magnifying-glass me-1"></i> Resultados de búsqueda';

      var count = results.length;
      mainTitle.textContent = count + ' transfer' + (count !== 1 ? 's' : '') + ' encontrado' + (count !== 1 ? 's' : '');

      /* Construir texto de la query */
      var parts = [];
      if (originVal) parts.push('desde <strong>"' + originVal + '"</strong>');
      if (destVal) parts.push('hasta <strong>"' + destVal + '"</strong>');
      if (dateVal) {
        var d = new Date(dateVal + 'T00:00:00');
        parts.push(d.toLocaleDateString('es-ES', {
          weekday: 'long',
          day: 'numeric',
          month: 'long'
        }));
      }
      if (pax > 0) {
        parts.push(pax + ' pasajero' + (pax > 1 ? 's' : ''));
      }
      mainSub.innerHTML = parts.join(' · ');

      /* Barra de estado */
      searchBar.classList.add('visible');
      searchBarCount.textContent = count + ' transfer' + (count !== 1 ? 's' : '') + ' encontrado' + (count !== 1 ? 's' : '');
      searchBarQuery.innerHTML = parts.join(' · ');

      /* Grid */
      grid.innerHTML = results.map(function (tr, i) {
        return buildCard(tr, i, i === 0);
      }).join('');

      /* Ocultar banner de "más rutas" */
      if (moreBanner) moreBanner.style.display = 'none';
      noResults.classList.remove('visible');

      /* Scroll a la cabecera de la sección — el resultado es lo primero que ve el usuario */
      setTimeout(function () {
        document.getElementById('mainSection').scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }, 60);
    }

    /* ── Sin resultados ── */
    function showNoResults() {
      searchActive = true;
      mainEyebrow.className = 'tr-main-eyebrow mode-results';
      mainEyebrow.textContent = 'Sin resultados';
      mainTitle.textContent = 'No encontramos esta ruta';
      mainSub.innerHTML = 'Prueba con términos más cortos o contáctanos para un traslado personalizado.';
      searchBar.classList.add('visible');
      searchBarCount.textContent = '0 resultados';
      searchBarQuery.textContent = '';
      grid.innerHTML = '';
      noResults.classList.add('visible');
      if (moreBanner) moreBanner.style.display = 'none';
      setTimeout(function () {
        document.getElementById('mainSection').scrollIntoView({
          behavior: 'smooth',
          block: 'start'
        });
      }, 60);
    }

    /* ── Limpiar búsqueda (volver al catálogo) ── */
    window.clearSearch = function () {
      searchActive = false;
      document.getElementById('originInput').value = '';
      document.getElementById('destInput').value = '';
      document.getElementById('dateInput').value = '';
      paxAdultN = 1;
      paxAdultDisp.textContent = 1;
      paxChildN = 0;
      paxChildDisp.textContent = 0;

      mainEyebrow.className = 'tr-main-eyebrow mode-catalog';
      mainEyebrow.textContent = 'Transfers populares';
      mainTitle.textContent = 'Rutas más solicitadas';
      mainSub.innerHTML = 'Nuestros transfers más populares. ¿No encuentras tu hotel? <strong style="color:var(--c-ocean);cursor:pointer" onclick="document.getElementById(\'originInput\').focus()">Usa el buscador</strong> para ver las <?= $totalTransfers ?> rutas disponibles.';

      searchBar.classList.remove('visible');
      noResults.classList.remove('visible');
      if (moreBanner) moreBanner.style.display = '';

      /* Restaurar catálogo estático */
      grid.innerHTML = <?= json_encode(implode('', array_map(function ($tr) {
        return renderTrCard($tr);
      }, $featuredTransfers)), JSON_UNESCAPED_UNICODE) ?>.replace ?
        <?= json_encode(implode('', array_map(function ($tr) {
          return '<div class="col-lg-4 col-md-6">' . renderTrCard($tr) . '</div>';
        }, $featuredTransfers)), JSON_UNESCAPED_UNICODE) ?> :
        '';
    };

    document.getElementById('clearSearchBtn').addEventListener('click', window.clearSearch);

    /* ── Búsqueda ── */
    function runSearch() {
      var originVal = document.getElementById('originInput').value.trim();
      var destVal = document.getElementById('destInput').value.trim();
      var dateVal = document.getElementById('dateInput').value;
      var adults = parseInt(paxAdultDisp.textContent) || 1;
      var children = parseInt(paxChildDisp.textContent) || 0;
      var totalPax = adults + children;

      var origin = originVal.toLowerCase();
      var dest = destVal.toLowerCase();

      if (!origin && !dest) {
        var inp = document.getElementById('originInput');
        inp.classList.add('shake');
        inp.focus();
        setTimeout(function () {
          inp.classList.remove('shake');
        }, 500);
        return;
      }

      var btn = document.getElementById('searchBtn');
      btn.classList.add('loading');
      btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Buscando…';

      setTimeout(function () {
        btn.classList.remove('loading');
        btn.innerHTML = '<i class="fas fa-magnifying-glass me-2"></i>Buscar transfers';

        var results = TR_DATA.filter(function (tr) {
          var fromStr = (tr.from_location || '').toLowerCase();
          var toStr = (tr.to_location || '').toLowerCase();
          var maxPax = parseInt(tr.max_passengers) || 0;

          var matchOrigin = !origin || fromStr.includes(origin) ||
            origin.split(' ').some(function (w) {
              return w.length > 1 && fromStr.includes(w);
            });
          var matchDest = !dest || toStr.includes(dest) ||
            dest.split(' ').some(function (w) {
              return w.length > 1 && toStr.includes(w);
            });
          var matchPax = (maxPax === 0 || maxPax >= totalPax);

          return matchOrigin && matchDest && matchPax;
        });

        /* Ordenar: coincidencias exactas primero */
        results.sort(function (a, b) {
          var aExact = (a.from_location || '').toLowerCase().includes(origin) &&
            (a.to_location || '').toLowerCase().includes(dest) ? 0 : 1;
          var bExact = (b.from_location || '').toLowerCase().includes(origin) &&
            (b.to_location || '').toLowerCase().includes(dest) ? 0 : 1;
          return aExact - bExact;
        });

        if (results.length === 0) {
          showNoResults();
        } else {
          showResults(results, originVal, destVal, dateVal, totalPax);
        }
      }, 300);
    }

    document.getElementById('searchBtn').addEventListener('click', runSearch);
    ['originInput', 'destInput'].forEach(function (id) {
      document.getElementById(id).addEventListener('keydown', function (e) {
        if (e.key === 'Enter') runSearch();
      });
    });

    /* ── Helpers globales ── */
    window.setOriginAndSearch = function (val) {
      document.getElementById('originInput').value = val;
      runSearch();
    };

    window.focusSearch = function () {
      document.querySelector('.tr-planner-section').scrollIntoView({
        behavior: 'smooth',
        block: 'start'
      });
      setTimeout(function () {
        document.getElementById('originInput').focus();
      }, 500);
    };

    /* ── Card Gallery Loop ── */
    function cycleCardImages() {
        document.querySelectorAll('.tr-card-img-container').forEach(function(cont) {
            var imgs = cont.querySelectorAll('img');
            if (imgs.length <= 1) return;
            var activeImg = cont.querySelector('img.active');
            if(!activeImg) {
                imgs[0].classList.add('active');
                return;
            }
            var activeIdx = Array.from(imgs).indexOf(activeImg);
            activeImg.classList.remove('active');
            var nextIdx = (activeIdx + 1) % imgs.length;
            imgs[nextIdx].classList.add('active');
        });
    }
    setInterval(cycleCardImages, 4000);

  }());
</script>

<!-- ═══════════════════════════════════════
     TRANSFER GALLERY LIGHTBOX
════════════════════════════════════════ -->
<div id="trLightbox" class="tr-lb" role="dialog" aria-modal="true" style="display:none">
  <div class="tr-lb-backdrop" id="trLbBackdrop"></div>
  <button class="tr-lb-close" id="trLbClose" aria-label="Cerrar"><i class="fas fa-times"></i></button>
  <button class="tr-lb-nav tr-lb-prev" id="trLbPrev" aria-label="Anterior"><i class="fas fa-chevron-left"></i></button>
  <button class="tr-lb-nav tr-lb-next" id="trLbNext" aria-label="Siguiente"><i
      class="fas fa-chevron-right"></i></button>
  <div class="tr-lb-stage">
    <div class="tr-lb-loader" id="trLbLoader">
      <div class="tr-lb-spin"></div>
    </div>
    <img id="trLbImg" src="" alt="Foto del vehículo" class="tr-lb-image">
  </div>
  <div class="tr-lb-footer">
    <div class="tr-lb-counter" id="trLbCounter">1 / 1</div>
    <div class="tr-lb-thumbs" id="trLbThumbs"></div>
  </div>
</div>

<script>
    (function () {
    'use strict';
    var appUrl = <?= json_encode(defined('APP_URL') ? APP_URL : '') ?>;
    var trData = <?= $transfersJson ?>;

    // Build gallery map: id -> array of image URLs
    var galleryMap = {};
    trData.forEach(function (tr) {
      var imgs = [];
      if (tr.image) imgs.push(appUrl + '/assets/uploads/transfers/' + tr.image);
      var g = tr.gallery;
      if (Array.isArray(g)) {
        g.forEach(function (f) { if (f) imgs.push(appUrl + '/assets/uploads/transfers/' + f); });
      }
      galleryMap[tr.id] = imgs;
    });

    var lb = document.getElementById('trLightbox');
    var lbImg = document.getElementById('trLbImg');
    var lbLoader = document.getElementById('trLbLoader');
    var lbClose = document.getElementById('trLbClose');
    var lbPrev = document.getElementById('trLbPrev');
    var lbNext = document.getElementById('trLbNext');
    var lbCounter = document.getElementById('trLbCounter');
    var lbThumbs = document.getElementById('trLbThumbs');
    var lbBack = document.getElementById('trLbBackdrop');

    var curImages = [];
    var curIdx = 0;
    var slideTimer = null;
    var isPaused = false;
    var slideDuration = 4000;

    function startSlideshow() {
      if (slideTimer) clearInterval(slideTimer);
      if (isPaused || curImages.length <= 1) return;
      slideTimer = setInterval(function() {
        nextSlide();
      }, slideDuration);
    }

    function stopSlideshow() {
      if (slideTimer) clearInterval(slideTimer);
    }

    function openLightbox(images, startIdx) {
      curImages = images;
      curIdx = startIdx || 0;
      isPaused = false;
      lb.style.display = 'flex';
      document.body.style.overflow = 'hidden';
      buildThumbs();
      showImg(curIdx);
      startSlideshow();
    }

    function closeLightbox() {
      lb.style.display = 'none';
      document.body.style.overflow = '';
      lbImg.src = '';
      stopSlideshow();
    }

    function showImg(idx) {
      lbImg.style.opacity = '0';
      lbLoader.style.display = 'flex';
      var src = curImages[idx];
      var tmp = new Image();
      tmp.onload = function () {
        lbImg.src = src;
        lbImg.style.opacity = '1';
        lbLoader.style.display = 'none';
      };
      tmp.src = src;
      lbCounter.textContent = (idx + 1) + ' / ' + curImages.length;
      document.querySelectorAll('.tr-lb-thumb').forEach(function (t, i) {
        t.classList.toggle('active', i === idx);
      });
      lbPrev.style.display = curImages.length > 1 ? 'flex' : 'none';
      lbNext.style.display = curImages.length > 1 ? 'flex' : 'none';
    }

    function nextSlide() {
      curIdx = (curIdx + 1) % curImages.length;
      showImg(curIdx);
    }

    function prevSlide() {
      curIdx = (curIdx - 1 + curImages.length) % curImages.length;
      showImg(curIdx);
    }

    function buildThumbs() {
      lbThumbs.innerHTML = '';
      if (curImages.length <= 1) return;
      curImages.forEach(function (src, i) {
        var t = document.createElement('img');
        t.src = src; t.className = 'tr-lb-thumb'; t.alt = 'Foto ' + (i + 1);
        t.addEventListener('click', function () { 
          curIdx = i; 
          showImg(curIdx);
          if (!isPaused) startSlideshow(); 
        });
        lbThumbs.appendChild(t);
      });
    }

    // Event delegation — works for static AND dynamic cards
    document.addEventListener('click', function (e) {
      var btn = e.target.closest('.tr-gallery-btn');
      if (!btn) return;
      e.stopPropagation();
      var id = parseInt(btn.dataset.id);
      var imgs = galleryMap[id] || [];
      if (!imgs.length) return;
      openLightbox(imgs, 0);
    });

    lbClose.addEventListener('click', closeLightbox);
    lbBack.addEventListener('click', closeLightbox);
    lbPrev.addEventListener('click', function () { prevSlide(); if (!isPaused) startSlideshow(); });
    lbNext.addEventListener('click', function () { nextSlide(); if (!isPaused) startSlideshow(); });

    lb.addEventListener('mouseenter', function() { stopSlideshow(); });
    lb.addEventListener('mouseleave', function() { if (!isPaused) startSlideshow(); });

    document.addEventListener('keydown', function (e) {
      if (lb.style.display === 'none') return;
      if (e.key === 'Escape') closeLightbox();
      if (e.key === 'ArrowLeft') { prevSlide(); if (!isPaused) startSlideshow(); }
      if (e.key === 'ArrowRight') { nextSlide(); if (!isPaused) startSlideshow(); }
    });
  }());
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>
