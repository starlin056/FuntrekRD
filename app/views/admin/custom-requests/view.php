<?php $customCss = ['modules/custom-requests.css']; ?>
<?php require_once APP_ROOT . '/app/views/layouts/header.php'; ?>
<?php require_once APP_ROOT . '/app/views/layouts/navigation.php'; ?>
<?php
// app/views/admin/custom-requests/view.php
// Variables esperadas: $request (array con todos los campos)
if (empty($request)) {
    echo '<div class="container py-5"><div class="alert alert-danger">Solicitud no encontrada.</div></div>';
    require_once APP_ROOT . '/app/views/layouts/footer.php';
    exit;
}

$statusMap = [
    'pending'   => ['label' => 'Pendiente',    'color' => '#9a6c00',  'bg' => 'rgba(249,199,79,.18)',  'icon' => 'fa-clock'],
    'reviewing' => ['label' => 'En revisión',  'color' => '#0077B6',  'bg' => 'rgba(0,119,182,.10)',   'icon' => 'fa-eye'],
    'approved'  => ['label' => 'Aprobada',     'color' => '#117a72',  'bg' => 'rgba(46,196,182,.12)',  'icon' => 'fa-check-circle'],
    'rejected'  => ['label' => 'Rechazada',    'color' => '#b91c1c',  'bg' => 'rgba(220,38,38,.08)',   'icon' => 'fa-times-circle'],
];
$st    = $request['status'] ?? 'pending';
$stCfg = $statusMap[$st] ?? $statusMap['pending'];

// Notas del admin (JSON)
$adminNotes = [];
if (!empty($request['admin_notes'])) {
    if (is_string($request['admin_notes'])) {
        $decoded = json_decode($request['admin_notes'], true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $adminNotes = $decoded;
        }
    } elseif (is_array($request['admin_notes'])) {
        $adminNotes = $request['admin_notes'];
    }
}

// REQUERIMIENTOS
$requirements = [];
if (!empty($request['requirements_checklist'])) {
    if (is_string($request['requirements_checklist'])) {
        $decoded = json_decode($request['requirements_checklist'], true);
        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
            $requirements = $decoded;
        }
    } elseif (is_array($request['requirements_checklist'])) {
        $requirements = $request['requirements_checklist'];
    }
}

// Actividades
$activities = $request['activities'] ?? '';
if (strpos($activities, 'Acepto que') !== false) {
    $activities = preg_replace('/Acepto que.*$/s', '', $activities);
}
$activities = trim($activities, " \t\n\r\0\x0B,");
?>
<div class="cr-view-page">
    <div class="container-fluid">

        <?php if (!empty($_SESSION['success'])): ?>
            <div class="crv-alert crv-alert-ok no-print"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($_SESSION['success']) ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>
        <?php if (!empty($_SESSION['error'])): ?>
            <div class="crv-alert crv-alert-err no-print"><i class="fas fa-exclamation-circle"></i> <?= htmlspecialchars($_SESSION['error']) ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <!-- PAGE HEADER BAR -->
        <div class="crv-header no-print">
            <div class="crv-header-left">
                <h1><i class="fas fa-magic"></i> Solicitud #<?= (int)$request['id'] ?></h1>
                <p>
                    <span class="crv-status-badge">
                        <i class="fas <?= $stCfg['icon'] ?>"></i> <?= $stCfg['label'] ?>
                    </span>
                    &nbsp;·&nbsp;
                    <?= !empty($request['created_at']) ? date('d/m/Y H:i', strtotime($request['created_at'])) : '—' ?>
                    <?php if (!empty($request['last_contacted_at'])): ?>
                        &nbsp;·&nbsp; <i class="fas fa-phone-alt" style="color:var(--teal)"></i>
                        Contactado el <?= date('d/m/Y H:i', strtotime($request['last_contacted_at'])) ?>
                    <?php endif; ?>
                </p>
            </div>
            <div class="crv-header-right">
                <?php if (!empty($request['customer_phone'])): ?>
                    <a href="https://wa.me/<?= preg_replace('/\D/', '', $request['customer_phone']) ?>?text=<?= urlencode('Hola ' . ($request['customer_name'] ?? '') . ',  soy de FUNTREK RD. Vi tu solicitud de excursión personalizada y quiero ayudarte. ¿En qué puedo asistirte?') ?>"
                        target="_blank" class="btn-crv btn-wa no-print">
                        <i class="fab fa-whatsapp"></i> WhatsApp
                    </a>
                <?php endif; ?>
                <button type="button" class="btn-crv btn-outline no-print" onclick="printRequest()">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <a href="<?= APP_URL ?>/admin/custom_excursion_requests" class="btn-crv btn-back">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </div>

        <!-- MAIN LAYOUT -->
        <div class="crv-layout">

            <!-- ═══ COLUMNA PRINCIPAL ═══ -->
            <div class="crv-main">

                <!-- CLIENTE -->
                <div class="crv-card">
                    <div class="crv-card-header">
                        <span class="crv-card-title"><i class="fas fa-user"></i> Datos del Cliente</span>
                        <a href="mailto:<?= htmlspecialchars($request['customer_email'] ?? '') ?>"
                            class="btn-crv btn-outline no-print" style="padding:6px 13px;font-size:.75rem">
                            <i class="fas fa-envelope"></i> Enviar email
                        </a>
                    </div>
                    <div class="crv-card-body">
                        <div class="crv-info-grid">
                            <div class="crv-info-item">
                                <span class="crv-info-label">Nombre completo</span>
                                <span class="crv-info-value"><?= htmlspecialchars($request['customer_name'] ?? '—') ?></span>
                            </div>
                            <div class="crv-info-item">
                                <span class="crv-info-label">Correo electrónico</span>
                                <span class="crv-info-value">
                                    <a href="mailto:<?= htmlspecialchars($request['customer_email'] ?? '') ?>">
                                        <?= htmlspecialchars($request['customer_email'] ?? '—') ?>
                                    </a>
                                </span>
                            </div>
                            <div class="crv-info-item">
                                <span class="crv-info-label">Teléfono / WhatsApp</span>
                                <span class="crv-info-value">
                                    <?php if (!empty($request['customer_phone'])): ?>
                                        <a href="tel:<?= htmlspecialchars($request['customer_phone']) ?>">
                                            <?= htmlspecialchars($request['customer_phone']) ?>
                                        </a>
                                    <?php else: ?> — <?php endif; ?>
                                </span>
                            </div>
                            <div class="crv-info-item">
                                <span class="crv-info-label">Fecha de solicitud</span>
                                <span class="crv-info-value">
                                    <?= !empty($request['created_at']) ? date('d/m/Y H:i', strtotime($request['created_at'])) : '—' ?>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- DETALLES DEL VIAJE -->
                <div class="crv-card">
                    <div class="crv-card-header">
                        <span class="crv-card-title"><i class="fas fa-map-marked-alt"></i> Detalles del Viaje</span>
                        <?php if (!empty($request['budget'])): ?>
                            <span class="crv-budget-pill"><i class="fas fa-wallet"></i> <?= htmlspecialchars($request['budget']) ?></span>
                        <?php endif; ?>
                    </div>
                    <div class="crv-card-body">
                        <div class="crv-info-grid">
                            <div class="crv-info-item">
                                <span class="crv-info-label">Destinos deseados</span>
                                <span class="crv-info-value" style="font-weight:700;color:var(--ocean)">
                                    <i class="fas fa-map-marker-alt" style="font-size:.8rem;margin-right:4px"></i>
                                    <?= htmlspecialchars($request['destinations'] ?? '—') ?>
                                </span>
                            </div>
                            <div class="crv-info-item">
                                <span class="crv-info-label">Fecha de viaje</span>
                                <span class="crv-info-value">
                                    <?= !empty($request['travel_date']) ? date('d \d\e F \d\e Y', strtotime($request['travel_date'])) : '—' ?>
                                </span>
                            </div>
                            <div class="crv-info-item">
                                <span class="crv-info-label">Número de personas</span>
                                <span class="crv-info-value">
                                    <i class="fas fa-users" style="color:var(--muted);font-size:.8rem;margin-right:4px"></i>
                                    <strong><?= (int)($request['people_count'] ?? 0) ?></strong> persona<?= (int)($request['people_count'] ?? 0) > 1 ? 's' : '' ?>
                                </span>
                            </div>
                            <div class="crv-info-item">
                                <span class="crv-info-label">Presupuesto estimado</span>
                                <span class="crv-info-value">
                                    <?= !empty($request['budget']) ? '<strong style="color:var(--teal)">' . htmlspecialchars($request['budget']) . '</strong>' : 'No especificado' ?>
                                </span>
                            </div>
                        </div>

                        <?php if ($activities): ?>
                            <hr class="crv-divider">
                            <div class="crv-info-item">
                                <span class="crv-info-label">Actividades de interés</span>
                                <div class="crv-tags" style="margin-top:8px">
                                    <?php foreach (array_filter(array_map('trim', explode(',', $activities))) as $act): ?>
                                        <span class="crv-tag"><?= htmlspecialchars($act) ?></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($request['additional_notes'])): ?>
                            <hr class="crv-divider">
                            <div class="crv-info-item">
                                <span class="crv-info-label">Notas adicionales del cliente</span>
                                <div style="background:var(--foam);border-radius:10px;padding:13px 15px;margin-top:8px;font-size:.87rem;line-height:1.6;border-left:3px solid var(--ocean)">
                                    <?= nl2br(htmlspecialchars($request['additional_notes'])) ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- REQUERIMIENTOS CONFIRMADOS -->
                <div class="crv-card">
                    <div class="crv-card-header">
                        <span class="crv-card-title"><i class="fas fa-tasks"></i> Requerimientos Confirmados</span>
                    </div>
                    <div class="crv-card-body">
                        <form method="POST" action="<?= APP_URL ?>/admin/custom_request_save_requirements/<?= (int)$request['id'] ?>">
                            <div class="crv-checklist">
                                <?php
                                $reqOptions = [
                                    'transporte' => ['🚐', 'Transporte'],
                                    'guia'       => ['👨‍🏫', 'Guía turístico'],
                                    'comida'     => ['🍽️', 'Alimentación'],
                                    'entradas'   => ['🎫', 'Entradas/boletos'],
                                    'seguro'     => ['🛡️', 'Seguro de viaje'],
                                    'equipo'     => ['🎒', 'Equipo especial'],
                                    'hospedaje'  => ['🏨', 'Hospedaje'],
                                    'traslados'  => ['🚌', 'Traslados internos'],
                                ];
                                foreach ($reqOptions as $key => [$emoji, $label]): ?>
                                    <label class="crv-check-item">
                                        <input type="checkbox" name="requirements[]" value="<?= $key ?>"
                                            <?= in_array($key, $requirements) ? 'checked' : '' ?>>
                                        <?= $emoji ?> <?= $label ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                            <div style="margin-top:16px">
                                <button type="submit" class="btn-crv btn-primary no-print">
                                    <i class="fas fa-save"></i> Guardar requerimientos
                                </button>
                            </div>
                        </form>
                        <?php if (!empty($requirements)): ?>
                            <hr class="crv-divider">
                            <span class="crv-info-label">Confirmados actualmente</span>
                            <div class="crv-tags" style="margin-top:8px">
                                <?php foreach ($requirements as $req_key):
                                    [$emoji, $rlabel] = $reqOptions[$req_key] ?? ['✅', $req_key];
                                ?>
                                    <span class="crv-tag crv-tag-req"><?= $emoji ?> <?= $rlabel ?></span>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- NOTAS INTERNAS -->
                <div class="crv-card">
                    <div class="crv-card-header">
                        <span class="crv-card-title"><i class="fas fa-comment-alt"></i> Notas del Equipo</span>
                        <span style="font-size:.74rem;color:var(--muted)"><?= count($adminNotes) ?> nota<?= count($adminNotes) !== 1 ? 's' : '' ?></span>
                    </div>
                    <div class="crv-card-body">
                        <!-- Formulario nueva nota -->
                        <form method="POST" action="<?= APP_URL ?>/admin/custom_request_add_note/<?= (int)$request['id'] ?>" class="crv-note-form no-print">
                            <textarea class="crv-textarea" name="note_content" placeholder="Escribe una nota interna o mensaje para el cliente…" required></textarea>
                            <div class="crv-note-opts">
                                <label class="crv-check-label">
                                    <input type="checkbox" name="visible_to_client" value="1">
                                    <i class="fas fa-eye" style="color:var(--teal)"></i> Visible para el cliente
                                </label>
                                <button type="submit" class="btn-crv btn-primary" style="border-radius:8px">
                                    <i class="fas fa-paper-plane"></i> Guardar nota
                                </button>
                            </div>
                        </form>

                        <?php if (!empty($adminNotes)): ?>
                            <hr class="crv-divider">
                            <div style="display:flex;flex-direction:column;gap:10px">
                                <?php foreach (array_reverse($adminNotes) as $note): ?>
                                    <div class="crv-note <?= !empty($note['visible_to_client']) ? 'crv-note-visible' : 'crv-note-internal' ?>">
                                        <div class="crv-note-text"><?= nl2br(htmlspecialchars($note['content'] ?? '')) ?></div>
                                        <div class="crv-note-meta">
                                            <span><?= !empty($note['created_at']) ? date('d/m/Y H:i', strtotime($note['created_at'])) : '' ?></span>
                                            <?php if (!empty($note['visible_to_client'])): ?>
                                                <span class="crv-note-vis-tag"><i class="fas fa-eye"></i> Visible al cliente</span>
                                            <?php else: ?>
                                                <span><i class="fas fa-lock" style="font-size:.65rem"></i> Interna</span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php else: ?>
                            <p style="color:var(--muted);font-size:.84rem;text-align:center;padding:20px 0">Sin notas todavía.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- HISTORIAL DE ACTIVIDADES -->
                <?php if (!empty($request['activity_history'])): ?>
                    <div class="crv-card">
                        <div class="crv-card-header">
                            <span class="crv-card-title"><i class="fas fa-history"></i> Historial de Actividades</span>
                        </div>
                        <div class="crv-card-body">
                            <div class="crv-timeline">
                                <?php
                                $actLabels = [
                                    'note_added'     => ['fa-comment', 'Nota agregada'],
                                    'status_changed' => ['fa-flag', 'Estado cambiado'],
                                    'contacted'      => ['fa-phone-alt', 'Cliente contactado'],
                                    'quote_sent'     => ['fa-file-invoice-dollar', 'Cotización enviada'],
                                    'file_attached'  => ['fa-paperclip', 'Archivo adjuntado'],
                                ];
                                foreach ($request['activity_history'] as $act):
                                    [$actIcon, $actLabel] = $actLabels[$act['type'] ?? ''] ?? ['fa-circle', 'Actividad'];
                                ?>
                                    <div class="crv-tl-item">
                                        <div class="crv-tl-dot <?= htmlspecialchars($act['type'] ?? '') ?>"></div>
                                        <div class="crv-tl-time"><?= !empty($act['created_at']) ? date('d/m/Y H:i', strtotime($act['created_at'])) : '' ?></div>
                                        <div class="crv-tl-content">
                                            <strong><i class="fas <?= $actIcon ?>" style="margin-right:5px;color:var(--ocean)"></i><?= $actLabel ?></strong>
                                            <?php
                                            $details = $act['details'] ?? [];
                                            if (!empty($details['from']) && !empty($details['to'])):
                                            ?>
                                                <span style="color:var(--muted)"> · <?= htmlspecialchars($details['from']) ?> → <?= htmlspecialchars($details['to']) ?></span>
                                            <?php endif; ?>
                                            <?php if (!empty($details['content'])): ?>
                                                <div style="color:var(--muted);font-size:.82rem;margin-top:3px"><?= htmlspecialchars(mb_strimwidth($details['content'], 0, 80, '…')) ?></div>
                                            <?php endif; ?>
                                            <?php if (!empty($details['price'])): ?>
                                                <span style="color:var(--teal);font-weight:700"> · $<?= number_format((float)$details['price'], 2) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

            </div><!-- /crv-main -->

            <!-- ═══ SIDEBAR ═══ -->
            <div class="crv-sidebar">

                <!-- CAMBIAR ESTADO -->
                <div class="crv-card">
                    <div class="crv-card-header">
                        <span class="crv-card-title"><i class="fas fa-flag"></i> Estado</span>
                    </div>
                    <div class="crv-card-body">
                        <form method="POST" action="<?= APP_URL ?>/admin/updateCustomRequestStatus/<?= (int)$request['id'] ?>">
                            <div class="crv-status-form">
                                <select name="status">
                                    <?php foreach ($statusMap as $val => $cfg): ?>
                                        <option value="<?= $val ?>" <?= $st === $val ? 'selected' : '' ?>>
                                            <?= $cfg['label'] ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <button type="submit" class="btn-crv btn-primary" style="width:100%;border-radius:10px;justify-content:center">
                                    <i class="fas fa-check"></i> Actualizar estado
                                </button>
                            </div>
                        </form>
                        <hr class="crv-divider">
                        <!-- Marcar contactado -->
                        <form method="POST" action="<?= APP_URL ?>/admin/custom_request_mark_contacted/<?= (int)$request['id'] ?>">
                            <button type="submit" class="btn-crv btn-outline" style="width:100%;border-radius:10px;justify-content:center">
                                <i class="fas fa-phone-alt"></i> Marcar como contactado
                            </button>
                        </form>
                    </div>
                </div>

                <!-- COTIZACIÓN -->
                <div class="crv-card">
                    <div class="crv-card-header">
                        <span class="crv-card-title"><i class="fas fa-file-invoice-dollar"></i> Cotización</span>
                        <?php if (!empty($request['quoted_price'])): ?>
                            <span style="font-family:var(--fm);font-weight:700;color:var(--teal);font-size:.85rem">
                                $<?= number_format((float)$request['quoted_price'], 2) ?>
                            </span>
                        <?php endif; ?>
                    </div>
                    <div class="crv-card-body">
                        <?php if (!empty($request['quoted_price'])): ?>
                            <div class="crv-alert crv-alert-ok" style="margin-bottom:14px">
                                <i class="fas fa-check-circle"></i>
                                Cotización enviada: <strong>$<?= number_format((float)$request['quoted_price'], 2) ?> USD</strong>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($request['proposal_attachment'])): ?>
                            <div class="crv-alert crv-alert-info" style="margin-bottom:14px">
                                <i class="fas fa-paperclip"></i>
                                Propuesta adjunta: <a href="<?= APP_URL ?>/<?= htmlspecialchars($request['proposal_attachment']) ?>" target="_blank" style="color:var(--ocean)">Ver archivo</a>
                            </div>
                        <?php endif; ?>
                        <form method="POST" action="<?= APP_URL ?>/admin/custom_request_send_quote/<?= (int)$request['id'] ?>"
                            enctype="multipart/form-data" class="crv-quote-form">
                            <div>
                                <label class="crv-info-label" style="display:block;margin-bottom:5px">Precio propuesto (USD)</label>
                                <input type="number" name="quoted_price" class="crv-input"
                                    step="0.01" min="0" placeholder="0.00"
                                    value="<?= !empty($request['quoted_price']) ? htmlspecialchars($request['quoted_price']) : '' ?>">
                            </div>
                            <div>
                                <label class="crv-info-label" style="display:block;margin-bottom:5px">Adjuntar propuesta (PDF/DOC)</label>
                                <input type="file" name="proposal" class="crv-file-input" accept=".pdf,.doc,.docx">
                                <small style="color:var(--muted);font-size:.72rem;display:block;margin-top:4px">Máx. 5MB · PDF, DOC, DOCX</small>
                            </div>
                            <div>
                                <label class="crv-info-label" style="display:block;margin-bottom:5px">Mensaje personalizado (opcional)</label>
                                <textarea class="crv-textarea" name="quote_message" rows="3"
                                    placeholder="Mensaje adicional para el cliente…"
                                    style="min-height:70px"></textarea>
                            </div>
                            <button type="submit" class="btn-crv btn-success" style="width:100%;border-radius:10px;justify-content:center">
                                <i class="fas fa-paper-plane"></i> Enviar cotización al cliente
                            </button>
                        </form>
                    </div>
                </div>

                <!-- ACCIONES RÁPIDAS -->
                <div class="crv-card no-print">
                    <div class="crv-card-header">
                        <span class="crv-card-title"><i class="fas fa-bolt"></i> Acciones Rápidas</span>
                    </div>
                    <div class="crv-card-body" style="display:flex;flex-direction:column;gap:9px">
                        <?php if (!empty($request['customer_phone'])): ?>
                            <a href="https://wa.me/<?= preg_replace('/\D/', '', $request['customer_phone']) ?>?text=<?= urlencode('Hola ' . ($request['customer_name'] ?? '') . ', soy de FUNTREK RD. Te contactamos respecto a tu solicitud de excursión personalizada #' . (int)$request['id'] . '. ¿En qué podemos ayudarte?') ?>"
                                target="_blank" class="btn-crv btn-wa" style="width:100%;border-radius:10px;justify-content:center">
                                <i class="fab fa-whatsapp"></i> Contactar por WhatsApp
                            </a>
                        <?php endif; ?>
                        <a href="mailto:<?= htmlspecialchars($request['customer_email'] ?? '') ?>"
                            class="btn-crv btn-outline" style="width:100%;border-radius:10px;justify-content:center">
                            <i class="fas fa-envelope"></i> Enviar email
                        </a>
                        <button type="button" onclick="printRequest()"
                            class="btn-crv btn-outline" style="width:100%;border-radius:10px;justify-content:center">
                            <i class="fas fa-print"></i> Imprimir / Guardar PDF
                        </button>
                        <?php if ($st === 'approved'): ?>
                            <hr class="crv-divider">
                            <div class="crv-alert crv-alert-ok">
                                <i class="fas fa-check-circle"></i>
                                Esta solicitud está aprobada y lista para convertir en reserva.
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <!-- INFO RÁPIDA -->
                <div class="crv-card">
                    <div class="crv-card-header">
                        <span class="crv-card-title"><i class="fas fa-info-circle"></i> Resumen</span>
                    </div>
                    <div class="crv-card-body" style="display:flex;flex-direction:column;gap:12px">
                        <div class="crv-info-item">
                            <span class="crv-info-label">ID Solicitud</span>
                            <span class="crv-info-value" style="font-family:var(--fm)">#<?= (int)$request['id'] ?></span>
                        </div>
                        <div class="crv-info-item">
                            <span class="crv-info-label">Recibida</span>
                            <span class="crv-info-value"><?= !empty($request['created_at']) ? date('d/m/Y H:i', strtotime($request['created_at'])) : '—' ?></span>
                        </div>
                        <div class="crv-info-item">
                            <span class="crv-info-label">Última actualización</span>
                            <span class="crv-info-value"><?= !empty($request['updated_at']) ? date('d/m/Y H:i', strtotime($request['updated_at'])) : '—' ?></span>
                        </div>
                        <div class="crv-info-item">
                            <span class="crv-info-label">Contactado</span>
                            <span class="crv-info-value">
                                <?php if (!empty($request['last_contacted_at'])): ?>
                                    <span style="color:var(--teal)"><i class="fas fa-check"></i> <?= date('d/m/Y', strtotime($request['last_contacted_at'])) ?></span>
                                <?php else: ?>
                                    <span style="color:var(--muted)">No contactado</span>
                                <?php endif; ?>
                            </span>
                        </div>
                        <?php if (!empty($request['quoted_price'])): ?>
                            <div class="crv-info-item">
                                <span class="crv-info-label">Cotización</span>
                                <span class="crv-info-value" style="color:var(--teal);font-family:var(--fm)">$<?= number_format((float)$request['quoted_price'], 2) ?> USD</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

            </div><!-- /sidebar -->
        </div><!-- /layout -->
    </div><!-- /container -->
</div><!-- /page -->

<script>
    function printRequest() {
        window.print();
    }
</script>

<?php require_once APP_ROOT . '/app/views/layouts/footer.php'; ?>