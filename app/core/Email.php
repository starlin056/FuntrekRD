<?php
// app/core/Email.php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


require_once APP_ROOT . '/app/core/PHPMailer/src/Exception.php';
require_once APP_ROOT . '/app/core/PHPMailer/src/PHPMailer.php';
require_once APP_ROOT . '/app/core/PHPMailer/src/SMTP.php';

class Email
{
    private $mail;
    private $adminEmail = 'starlin056@gmail.com'; // Correo del admin

    public function __construct()
    {
        $this->mail = new PHPMailer(true);

        // Codificación correcta para acentos y emojis
        $this->mail->CharSet = 'UTF-8';
        $this->mail->Encoding = 'base64';

        // Configuración SMTP con Gmail
        $this->mail->isSMTP();
        $this->mail->Host       = 'smtp.gmail.com';
        $this->mail->SMTPAuth   = true;
        $this->mail->Username   = 'funtrekrd@gmail.com'; // tu cuenta Gmail
        $this->mail->Password   = 'dxruxghdkarrpyra';    // contraseña de aplicación
        $this->mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $this->mail->Port       = 587;

        // Remitente debe coincidir con la cuenta SMTP
        $this->mail->setFrom('funtrekrd@gmail.com', 'FUNTREK RD');

        $this->mail->isHTML(true);
        $this->mail->SMTPDebug = 0; // pon 2 para depurar
    }


    /* =====================================================
       EMAILS AL CLIENTE
    ===================================================== */

    /**
     * Nueva reserva recibida (cliente) — status: pending
     */
    public function sendNewBookingNotification($booking)
    {
        $isCash   = ($booking['payment_method'] ?? '') === 'cash';
        $subject  = $isCash
            ? '📋 Reserva registrada - FUNTREK RD'
            : '📋 Reserva recibida - FUNTREK RD';
        $body = $this->buildTemplate($booking, 'new', $isCash);

        // Notificar al admin también
        $this->sendEmail($this->adminEmail, '🔔 Nueva reserva: ' . ($booking['booking_reference'] ?? ''), $body);

        return $this->sendEmail($booking['customer_email'], $subject, $body);
    }

    /**
     * Confirmación de reserva — status: confirmed (admin la aprueba)
     */
    public function sendBookingConfirmation($booking)
    {
        $subject = '✅ ¡Reserva Confirmada! - FUNTREK RD';
        $body    = $this->buildTemplate($booking, 'confirmed');
        return $this->sendEmail($booking['customer_email'], $subject, $body);
    }

    /**
     * Cancelación de reserva
     */
    public function sendBookingCancellation($booking)
    {
        $subject = '❌ Reserva Cancelada - FUNTREK RD';
        $body    = $this->buildTemplate($booking, 'cancelled');
        return $this->sendEmail($booking['customer_email'], $subject, $body);
    }

    /**
     * Pago confirmado (PayPal recibido)
     */
    public function sendPaymentConfirmation($booking)
    {
        $subject = '💳 Pago Recibido - FUNTREK RD';
        $body    = $this->buildTemplate($booking, 'paid');

        // Notificar al admin del pago
        $this->sendEmail($this->adminEmail, '💳 Pago recibido: ' . ($booking['booking_reference'] ?? ''), $body);

        return $this->sendEmail($booking['customer_email'], $subject, $body);
    }

    /**
     * Reembolso procesado
     */
    public function sendRefundNotification($booking)
    {
        $subject = '💰 Reembolso Procesado - FUNTREK RD';
        $body    = $this->buildTemplate($booking, 'refunded');
        return $this->sendEmail($booking['customer_email'], $subject, $body);
    }

    /* =====================================================
       EMAILS PERSONALIZADOS
    ===================================================== */

    /**
     * Envío genérico sin formato especial (texto plano o HTML)
     */
    public function sendRaw($to, $subject, $message)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $message;
            $this->mail->AltBody = strip_tags($message);

            return $this->mail->send();
        } catch (Exception $e) {
            error_log('Email raw error [' . $to . ']: ' . $e->getMessage());
            return false;
        }
    }

    /* =====================================================
       HELPERS PRIVADOS
    ===================================================== */

    private function sendEmail($to, $subject, $body)
    {
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to);
            $this->mail->Subject = $subject;
            $this->mail->Body    = $body;
            $this->mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '</p>'], "\n", $body));
            return $this->mail->send();
        } catch (Exception $e) {
            error_log('Email error [' . $to . ']: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Envía correo para solicitud de excursión personalizada
     *
     * @param string $to        Destinatario
     * @param array  $data      Datos de la solicitud
     * @param int    $requestId ID de la solicitud
     * @param bool   $isAdmin   true = correo para admin, false = para cliente
     * @return bool
     */
    public function sendCustomRequest(string $to, array $data, int $requestId, bool $isAdmin = false): bool
    {
        if ($isAdmin) {
            $subject = "🛠️ Nueva solicitud personalizada #{$requestId} - FUNTREK RD";
        } else {
            $subject = "✅ ¡Recibimos tu solicitud de excursión personalizada! - FUNTREK RD";
        }

        $body = $this->buildCustomRequestTemplate($data, $requestId, $isAdmin, $to);

        return $this->sendEmail($to, $subject, $body);
    }

    /**
     * Envía correo para restablecer contraseña
     */
    public function sendPasswordResetEmail($to, $resetLink)
    {
        $subject = '🔐 Restablecer tu contraseña - FUNTREK RD';
        
        $body = '<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Restablecer Contraseña - FUNTREK RD</title>
</head>
<body style="margin:0;padding:0;background:#f0f7fc;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f7fc;padding:30px 0;">
    <tr><td align="center">
        <table width="580" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,45,79,.12);">
            <tr>
                <td style="background:linear-gradient(135deg,#002D4F,#0077B6);padding:30px 40px;text-align:center;">
                    <div style="font-size:2rem;margin-bottom:8px;">🔐</div>
                    <h1 style="color:#fff;margin:0;font-size:22px;font-weight:800;letter-spacing:-.5px;">FUNTREK RD</h1>
                    <p style="color:rgba(255,255,255,.75);margin:4px 0 0;font-size:13px;">Seguridad de la cuenta</p>
                </td>
            </tr>
            <tr>
                <td style="padding:40px;text-align:center;">
                    <h2 style="color:#002D4F;margin:0 0 16px;font-size:20px;font-weight:800;">¿Olvidaste tu contraseña?</h2>
                    <p style="color:#555;margin:0 0 24px;font-size:15px;line-height:1.6;">
                        Hemos recibido una solicitud para restablecer la contraseña de tu cuenta. 
                        Haz clic en el botón de abajo para elegir una nueva. 
                        <strong>Este enlace expirará en 1 hora.</strong>
                    </p>
                    <a href="' . $resetLink . '" 
                       style="display:inline-block;background:linear-gradient(135deg,#0077B6,#00B4D8);color:#fff;text-decoration:none;font-weight:700;font-size:14px;padding:14px 36px;border-radius:999px;box-shadow:0 4px 12px rgba(0,119,182,0.2);">
                        Restablecer Contraseña
                    </a>
                    <p style="color:#888;margin:24px 0 0;font-size:13px;">
                        Si no solicitaste este cambio, puedes ignorar este correo de forma segura.
                    </p>
                </td>
            </tr>
            <tr>
                <td style="background:#f0f7fc;padding:20px 40px;text-align:center;border-top:1px solid rgba(0,119,182,.10);">
                    <p style="color:#6E8FA5;font-size:12px;margin:0;">© ' . date('Y') . ' FUNTREK RD — República Dominicana</p>
                </td>
            </tr>
        </table>
    </td></tr>
</table>
</body>
</html>';

        return $this->sendEmail($to, $subject, $body);
    }

    /**
     * Envía una notificación de que la contraseña ha sido cambiada
     */
    public function sendPasswordChangedNotification($to)
    {
        $subject = '✅ Contraseña actualizada - FUNTREK RD';
        
        $body = '<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Contraseña Actualizada - FUNTREK RD</title>
</head>
<body style="margin:0;padding:0;background:#f0f7fc;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f7fc;padding:30px 0;">
    <tr><td align="center">
        <table width="580" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,45,79,.12);">
            <tr>
                <td style="background:linear-gradient(135deg,#16A34A,#2EC4B6);padding:30px 40px;text-align:center;">
                    <div style="font-size:2rem;margin-bottom:8px;">✅</div>
                    <h1 style="color:#fff;margin:0;font-size:22px;font-weight:800;letter-spacing:-.5px;">FUNTREK RD</h1>
                    <p style="color:rgba(255,255,255,.75);margin:4px 0 0;font-size:13px;">Seguridad de tu cuenta</p>
                </td>
            </tr>
            <tr>
                <td style="padding:40px;text-align:center;">
                    <h2 style="color:#0D1B2A;margin:0 0 16px;font-size:20px;font-weight:800;">¡Contraseña Cambiada!</h2>
                    <p style="color:#555;margin:0 0 24px;font-size:15px;line-height:1.6;">
                        Este es un mensaje de confirmación para informarte que la contraseña de tu cuenta en <strong>FUNTREK RD</strong> ha sido cambiada recientemente.
                    </p>
                    <div style="background:#f8fffe;border:1px solid rgba(46,196,182,.20);border-radius:12px;padding:15px;display:inline-block;">
                        <p style="color:#117a72;margin:0;font-size:13px;font-weight:700;">
                            Si tú realizaste este cambio, puedes ignorar este mensaje.
                        </p>
                    </div>
                    <hr style="border:none;border-top:1px solid #eee;margin:30px 0;">
                    <p style="color:#dc2626;margin:0;font-size:14px;font-weight:600;">
                        ¿No fuiste tú?
                    </p>
                    <p style="color:#777;margin:8px 0 0;font-size:13px;line-height:1.5;">
                        Si no has autorizado este cambio, te recomendamos restablecer tu contraseña inmediatamente y contactar a nuestro equipo de soporte.
                    </p>
                </td>
            </tr>
            <tr>
                <td style="background:#f0f7fc;padding:20px 40px;text-align:center;border-top:1px solid rgba(0,119,182,.10);">
                    <p style="color:#6E8FA5;font-size:12px;margin:0;">© ' . date('Y') . ' FUNTREK RD — Punta Cana, RD</p>
                </td>
            </tr>
        </table>
    </td></tr>
</table>
</body>
</html>';

        return $this->sendEmail($to, $subject, $body);
    }

        /* =====================================================
       WRAPPER PARA SOLICITUDES PERSONALIZADAS
       (Compatibilidad con controlador simplificado)
    ===================================================== */

    /**
     * Envía emails para solicitud de excursión personalizada
     * Versión simplificada: auto-detecta admin/cliente
     *
     * @param int   $requestId ID de la solicitud
     * @param array $data      Datos de la solicitud
     * @return array ['client' => bool, 'admin' => bool]
     */
    public function sendCustomExcursionRequest(int $requestId, array $data): array
    {
        $results = ['client' => false, 'admin' => false];

        try {
            // ✅ Email al cliente (confirmación de recepción)
            if (!empty($data['customer_email'])) {
                $results['client'] = $this->sendCustomRequest(
                    $data['customer_email'],
                    $data,
                    $requestId,
                    false // isAdmin = false
                );
            }

            // ✅ Email al admin (notificación nueva solicitud)
            $results['admin'] = $this->sendCustomRequest(
                $this->adminEmail,
                $data,
                $requestId,
                true // isAdmin = true
            );
        } catch (Exception $e) {
            error_log('[Email] Custom request error: ' . $e->getMessage());
        }

        return $results;
    }

    /**
     * Construye la plantilla HTML para solicitudes personalizadas
     *
     * @param array  $data      Datos de la solicitud
     * @param int    $requestId ID de la solicitud
     * @param bool   $isAdmin   true = para admin, false = para cliente
     * @param string $to        Dirección del destinatario (para mostrar en footer)
     * @return string
     */
    private function buildCustomRequestTemplate(array $data, int $requestId, bool $isAdmin, string $to): string
    {
        // Colores y textos según destinatario
        if ($isAdmin) {
            $icon      = '🛠️';
            $title     = 'Nueva solicitud personalizada';
            $message   = 'Un cliente ha solicitado una excursión a medida. Revisa los detalles y ponte en contacto.';
            $color     = '#e67e22';
            $statusText = 'Pendiente de revisión';
            // Usamos MAIL_URL para que el enlace en el correo vaya al sitio en línea
            $link       = MAIL_URL . '/auth/login?redirect=' . urlencode('/excursions#custom-form');
            $linkText   = 'Ver en el panel de administración';
        } else {
            $icon       = '✅';
            $title      = 'Solicitud recibida';
            $message    = 'Gracias por confiar en FUNTREK RD. Hemos recibido tu solicitud de excursión personalizada y nuestro equipo la revisará en las próximas 24 horas. Te contactaremos por email o teléfono.';
            $color      = '#0077B6';
            $statusText = 'En revisión';
            $link       = MAIL_URL . '/auth/login';
            $linkText   = 'Volver al sitio';
        }
        // Datos del cliente (escapados)
        $customerName  = htmlspecialchars($data['customer_name']   ?? '');
        $customerEmail = htmlspecialchars($data['customer_email']  ?? '');
        $customerPhone = htmlspecialchars($data['customer_phone']  ?? '—');
        $destinations  = htmlspecialchars($data['destinations']    ?? '—');
        $activities    = htmlspecialchars($data['activities']      ?? '—');
        $travelDate    = !empty($data['travel_date']) ? date('d/m/Y', strtotime($data['travel_date'])) : '—';
        $peopleCount   = (int)($data['people_count'] ?? 1);
        $budget        = htmlspecialchars($data['budget']          ?? '—');
        $notes         = htmlspecialchars($data['additional_notes'] ?? 'Ninguna');

        $paxStr = $peopleCount . ' persona' . ($peopleCount > 1 ? 's' : '');

        return '<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>' . htmlspecialchars($title) . ' - FUNTREK RD</title>
</head>
<body style="margin:0;padding:0;background:#f0f7fc;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f7fc;padding:30px 0;">
     <tr>
        <td align="center">
            <table width="580" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,45,79,.12);">

                <!-- Header -->
                <tr>
                    <td style="background:linear-gradient(135deg,#002D4F,#0077B6);padding:30px 40px;text-align:center;">
                        <div style="font-size:2rem;margin-bottom:8px;">' . $icon . '</div>
                        <h1 style="color:#fff;margin:0;font-size:22px;font-weight:800;letter-spacing:-.5px;">FUNTREK RD</h1>
                        <p style="color:rgba(255,255,255,.75);margin:4px 0 0;font-size:13px;">Excursiones personalizadas</p>
                    </td>
                </tr>

                <!-- Estado -->
                <tr>
                    <td style="padding:28px 40px 10px;text-align:center;">
                        <div style="display:inline-block;background:' . $color . '18;border:2px solid ' . $color . ';border-radius:12px;padding:16px 28px;">
                            <h2 style="color:' . $color . ';margin:0;font-size:18px;font-weight:800;">' . $title . '</h2>
                            <p style="color:#555;margin:8px 0 0;font-size:14px;line-height:1.6;">' . $message . '</p>
                        </div>
                    </td>
                </tr>

                <!-- Referencia -->
                <tr>
                    <td style="padding:16px 40px;text-align:center;">
                        <div style="display:inline-block;background:#EAF6FF;border:1.5px dashed #0077B6;border-radius:10px;padding:10px 24px;">
                            <div style="font-size:11px;color:#6E8FA5;text-transform:uppercase;letter-spacing:.1em;font-weight:700;">ID Solicitud</div>
                            <div style="font-size:18px;font-weight:800;color:#0077B6;margin-top:2px;">#' . $requestId . '</div>
                        </div>
                    </td>
                </tr>

                <!-- Detalles -->
                <tr>
                    <td style="padding:10px 40px 28px;">
                        <table width="100%" cellpadding="0" cellspacing="0" style="background:#EAF6FF;border-radius:12px;overflow:hidden;">
                            <tr><td colspan="2" style="padding:14px 18px;background:#ddf0fb;font-weight:800;font-size:13px;color:#002D4F;text-transform:uppercase;letter-spacing:.06em;">Detalles de la solicitud</td></tr>
                            <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
                                <td style="padding:10px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Cliente</td>
                                <td style="padding:10px 18px;color:#0D1B2A;font-size:13px;font-weight:700;">' . $customerName . '</td>
                            </tr>
                            <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
                                <td style="padding:10px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Email</td>
                                <td style="padding:10px 18px;color:#0D1B2A;font-size:13px;font-weight:700;">' . $customerEmail . '</td>
                            </tr>
                            <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
                                <td style="padding:10px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Teléfono</td>
                                <td style="padding:10px 18px;color:#0D1B2A;font-size:13px;font-weight:700;">' . $customerPhone . '</td>
                            </tr>
                            <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
                                <td style="padding:10px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Destinos</td>
                                <td style="padding:10px 18px;color:#0D1B2A;font-size:13px;font-weight:700;">' . $destinations . '</td>
                            </tr>
                            <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
                                <td style="padding:10px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Actividades de interés</td>
                                <td style="padding:10px 18px;color:#0D1B2A;font-size:13px;font-weight:700;">' . ($activities !== '—' ? $activities : 'No especificadas') . '</td>
                            </tr>
                            <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
                                <td style="padding:10px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Fecha deseada</td>
                                <td style="padding:10px 18px;color:#0D1B2A;font-size:13px;font-weight:700;">' . $travelDate . '</td>
                            </tr>
                            <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
                                <td style="padding:10px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Personas</td>
                                <td style="padding:10px 18px;color:#0D1B2A;font-size:13px;font-weight:700;">' . $paxStr . '</td>
                            </tr>
                            <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
                                <td style="padding:10px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Presupuesto</td>
                                <td style="padding:10px 18px;color:#0D1B2A;font-size:13px;font-weight:700;">' . $budget . '</td>
                            </tr>
                            <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
                                <td style="padding:10px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Notas adicionales</td>
                                <td style="padding:10px 18px;color:#0D1B2A;font-size:13px;font-style:italic;">' . $notes . '</td>
                            </tr>
                            <tr>
                                <td style="padding:12px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Estado</td>
                                <td style="padding:12px 18px;color:' . $color . ';font-size:13px;font-weight:700;">' . $statusText . '</td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <!-- CTA -->
                <tr>
                    <td style="padding:0 40px 28px;text-align:center;">
                        <a href="' . $link . '"
                           style="display:inline-block;background:linear-gradient(135deg,' . $color . ',#00B4D8);color:#fff;text-decoration:none;font-weight:700;font-size:14px;padding:13px 32px;border-radius:999px;">
                            ' . $linkText . '
                        </a>
                    </td>
                </tr>

                <!-- Footer -->
                <tr>
                    <td style="background:#f0f7fc;padding:20px 40px;text-align:center;border-top:1px solid rgba(0,119,182,.10);">
                        <p style="color:#6E8FA5;font-size:12px;margin:0;">© ' . date('Y') . ' FUNTREK RD — Punta Cana, República Dominicana</p>
                        <p style="color:#6E8FA5;font-size:12px;margin:4px 0 0;">Este correo fue enviado a ' . htmlspecialchars($to) . '</p>
                    </td>
                </tr>

            </table>
        </td>
    </tr>
</table>
</body>
</html>';
    }






    /*
 * MÉTODO NUEVO para Email.php
 * Añadir dentro de la clase Email
 */

    /**
     * Envía la cotización formal al cliente
     *
     * @param array  $request  Datos de la solicitud personalizada
     * @param float  $price    Precio cotizado
     * @param string|null $filePath Ruta del archivo adjunto (relativa a APP_ROOT/public)
     * @param string $message  Mensaje personalizado del admin
     * @return bool
     */
    public function sendQuoteToClient(array $request, float $price, ?string $filePath = null, string $message = ''): bool
    {
        $to         = $request['customer_email'] ?? '';
        $clientName = htmlspecialchars($request['customer_name'] ?? 'Cliente');
        $requestId  = (int)($request['id'] ?? 0);
        $destinations = htmlspecialchars($request['destinations'] ?? '—');
        $travelDate = !empty($request['travel_date'])
            ? date('d/m/Y', strtotime($request['travel_date']))
            : '—';
        $people     = (int)($request['people_count'] ?? 1);
        $formattedPrice = number_format($price, 2);
        $customMsg  = $message ? nl2br(htmlspecialchars($message)) : '';

        $appUrl = defined('MAIL_URL') ? MAIL_URL : (defined('APP_URL') ? APP_URL : '');

        $body = '<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Cotización Personalizada - FUNTREK RD</title>
</head>
<body style="margin:0;padding:0;background:#f0f7fc;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f7fc;padding:30px 0;">
<tr><td align="center">
<table width="580" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,45,79,.12);">
 
  <!-- HEADER -->
  <tr>
    <td style="background:linear-gradient(135deg,#002D4F,#0077B6);padding:32px 40px;text-align:center;">
      <div style="font-size:2.5rem;margin-bottom:8px;">💰</div>
      <h1 style="color:#fff;margin:0;font-size:24px;font-weight:800;letter-spacing:-.5px;">¡Tu cotización está lista!</h1>
      <p style="color:rgba(255,255,255,.75);margin:6px 0 0;font-size:13px;">FUNTREK RD — Excursiones Personalizadas</p>
    </td>
  </tr>
 
  <!-- SALUDO -->
  <tr>
    <td style="padding:28px 40px 0;">
      <p style="font-size:15px;color:#0D1B2A;line-height:1.65;">
        Hola <strong>' . $clientName . '</strong>,<br><br>
        Gracias por confiar en <strong>FUNTREK RD</strong>. Hemos revisado tu solicitud de excursión personalizada
        y estamos emocionados de presentarte nuestra propuesta a medida.
      </p>
    </td>
  </tr>
 
  <!-- PRECIO DESTACADO -->
  <tr>
    <td style="padding:24px 40px;">
      <div style="background:linear-gradient(135deg,rgba(0,119,182,.08),rgba(46,196,182,.06));border:2px solid rgba(46,196,182,.25);border-radius:16px;padding:24px;text-align:center;">
        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.12em;color:#6E8FA5;margin-bottom:8px;">Precio total estimado</div>
        <div style="font-size:2.8rem;font-weight:800;color:#0077B6;line-height:1;">$' . $formattedPrice . '</div>
        <div style="font-size:12px;color:#6E8FA5;margin-top:4px;">USD · por el grupo completo</div>
      </div>
    </td>
  </tr>
 
  <!-- DETALLES -->
  <tr>
    <td style="padding:0 40px 24px;">
      <table width="100%" cellpadding="0" cellspacing="0" style="background:#EAF6FF;border-radius:12px;overflow:hidden;">
        <tr><td colspan="2" style="padding:12px 18px;background:#ddf0fb;font-weight:800;font-size:12px;color:#002D4F;text-transform:uppercase;letter-spacing:.08em;">Resumen de tu solicitud #' . $requestId . '</td></tr>
        <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
          <td style="padding:11px 18px;color:#6E8FA5;font-size:13px;font-weight:500;width:40%;">📍 Destinos</td>
          <td style="padding:11px 18px;color:#0D1B2A;font-size:13px;font-weight:700;">' . $destinations . '</td>
        </tr>
        <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
          <td style="padding:11px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">📅 Fecha del viaje</td>
          <td style="padding:11px 18px;color:#0D1B2A;font-size:13px;font-weight:700;">' . $travelDate . '</td>
        </tr>
        <tr>
          <td style="padding:11px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">👥 Personas</td>
          <td style="padding:11px 18px;color:#0D1B2A;font-size:13px;font-weight:700;">' . $people . ' persona' . ($people > 1 ? 's' : '') . '</td>
        </tr>
      </table>
    </td>
  </tr>
 
  ' . ($customMsg ? '
  <!-- MENSAJE DEL EQUIPO -->
  <tr>
    <td style="padding:0 40px 24px;">
      <div style="background:#fff8e7;border:1.5px solid rgba(249,199,79,.35);border-radius:12px;padding:18px;border-left:4px solid #F9C74F;">
        <div style="font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#9a6c00;margin-bottom:8px;">💬 Mensaje de nuestro equipo</div>
        <p style="font-size:13px;color:#0D1B2A;line-height:1.65;margin:0;">' . $customMsg . '</p>
      </div>
    </td>
  </tr>' : '') . '
 
  ' . ($filePath ? '
  <!-- PROPUESTA ADJUNTA -->
  <tr>
    <td style="padding:0 40px 24px;">
      <div style="background:rgba(124,58,237,.06);border:1.5px solid rgba(124,58,237,.20);border-radius:12px;padding:16px;display:flex;align-items:center;gap:12px;">
        <span style="font-size:1.5rem;">📎</span>
        <div>
          <div style="font-size:12px;font-weight:700;color:#6D28D9;text-transform:uppercase;letter-spacing:.06em;">Propuesta adjunta</div>
          <p style="font-size:12px;color:#6E8FA5;margin:2px 0 0;">Hemos adjuntado un documento con más detalles de la propuesta al final del correo.</p>
        </div>
      </div>
    </td>
  </tr>' : '') . '
 
  <!-- PRÓXIMOS PASOS -->
  <tr>
    <td style="padding:0 40px 28px;">
      <div style="background:#f8fffe;border:1px solid rgba(46,196,182,.20);border-radius:12px;padding:20px;">
        <div style="font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.08em;color:#117a72;margin-bottom:12px;">✅ ¿Qué sigue?</div>
        <div style="font-size:13px;color:#0D1B2A;line-height:1.75;">
          1. Revisa esta cotización con tu grupo.<br>
          2. Si tienes preguntas, responde a este correo o escríbenos.<br>
          3. Una vez aceptes, coordinaremos los detalles del pago y logística.<br>
          4. ¡Tu aventura en la República Dominicana está a un paso! 🌴
        </div>
      </div>
    </td>
  </tr>
 
  <!-- CTA -->
  <tr>
    <td style="padding:0 40px 28px;text-align:center;">
      <a href="mailto:' . $this->adminEmail . '" style="display:inline-block;background:linear-gradient(135deg,#0077B6,#2EC4B6);color:#fff;text-decoration:none;font-weight:700;font-size:14px;padding:14px 36px;border-radius:999px;box-shadow:0 4px 16px rgba(0,119,182,.30);">
        Confirmar cotización
      </a>
    </td>
  </tr>
 
  <!-- FOOTER -->
  <tr>
    <td style="background:#f0f7fc;padding:20px 40px;text-align:center;border-top:1px solid rgba(0,119,182,.10);">
      <p style="color:#6E8FA5;font-size:12px;margin:0;">© ' . date('Y') . ' FUNTREK RD — Punta Cana, República Dominicana</p>
      <p style="color:#6E8FA5;font-size:12px;margin:4px 0 0;">Este correo fue enviado a ' . htmlspecialchars($to) . '</p>
      <p style="color:#6E8FA5;font-size:11px;margin:4px 0 0;">Cotización válida por 7 días a partir de la fecha de envío.</p>
    </td>
  </tr>
 
</table>
</td></tr>
</table>
</body>
</html>';

        // Adjuntar archivo si existe
        try {
            $this->mail->clearAddresses();
            $this->mail->addAddress($to);
            $this->mail->Subject = '💰 Cotización para tu excursión personalizada - FUNTREK RD';
            $this->mail->Body    = $body;
            $this->mail->AltBody = strip_tags(str_replace(['<br>', '</p>', '</td>'], "\n", $body));

            if ($filePath && file_exists(APP_ROOT . '/public/' . $filePath)) {
                $this->mail->addAttachment(
                    APP_ROOT . '/public/' . $filePath,
                    'Propuesta_FUNTREKRD_' . $requestId . '.' . pathinfo($filePath, PATHINFO_EXTENSION)
                );
            }

            $result = $this->mail->send();

            // Notificar al admin también
            $this->mail->clearAddresses();
            $this->mail->clearAttachments();
            $this->mail->addAddress($this->adminEmail);
            $this->mail->Subject = '✅ Cotización enviada — Solicitud #' . $requestId;
            $this->mail->Body    = '<p>Se ha enviado la cotización de <strong>$' . $formattedPrice . ' USD</strong> al cliente <strong>' . $clientName . '</strong> (' . htmlspecialchars($to) . ') para la solicitud #' . $requestId . '.</p>';
            $this->mail->AltBody = 'Cotización de $' . $formattedPrice . ' USD enviada a ' . $to . ' para solicitud #' . $requestId;
            $this->mail->send();

            return $result;
        } catch (\Exception $e) {
            error_log('[Email] sendQuoteToClient error: ' . $e->getMessage());
            return false;
        }
    }
    /**
     * Construye la plantilla HTML del email para reservas
     *
     * @param array  $booking  Datos de la reserva
     * @param string $action   new | confirmed | cancelled | paid | refunded
     * @param bool   $isCash   Solo relevante para 'new'
     * @return string
     */
    private function buildTemplate(array $booking, string $action, bool $isCash = false): string
    {
        $configs = [
            'new' => [
                'icon'    => $isCash ? '📋' : '📋',
                'title'   => $isCash
                    ? '¡Tu reserva fue registrada!'
                    : '¡Recibimos tu solicitud de reserva!',
                'message' => $isCash
                    ? 'Tu reserva está <strong>pendiente de confirmación</strong>. Un agente de FUNTREK RD la revisará y te contactará pronto para coordinar el pago en efectivo.'
                    : 'Tu pago fue recibido y tu reserva está <strong>pendiente de confirmación</strong> por parte de nuestro equipo. Te notificaremos una vez confirmada.',
                'color'   => '#0077B6',
            ],
            'confirmed' => [
                'icon'    => '✅',
                'title'   => '¡Tu reserva está confirmada!',
                'message' => 'Gracias por confiar en FUNTREK RD. Tu aventura en República Dominicana está lista. ¡Te esperamos!',
                'color'   => '#16a34a',
            ],
            'cancelled' => [
                'icon'    => '❌',
                'title'   => 'Reserva Cancelada',
                'message' => 'Lamentamos que hayas cancelado tu reserva. Si fue un error o deseas reprogramar, contáctanos y con gusto te ayudamos.',
                'color'   => '#dc2626',
            ],
            'paid' => [
                'icon'    => '💳',
                'title'   => 'Pago Recibido',
                'message' => '¡Excelente! Hemos recibido tu pago correctamente. Tu reserva está siendo revisada por nuestro equipo y recibirás una confirmación muy pronto.',
                'color'   => '#0077B6',
            ],
            'refunded' => [
                'icon'    => '💰',
                'title'   => 'Reembolso Procesado',
                'message' => 'Hemos procesado el reembolso de tu reserva. El monto será acreditado en 3–5 días hábiles según tu banco.',
                'color'   => '#ca8a04',
            ],
        ];

        $cfg = $configs[$action] ?? $configs['new'];

        // Nombre del servicio
        $serviceName = '—';
        if (!empty($booking['package_name']))    $serviceName = $booking['package_name'];
        elseif (!empty($booking['excursion_name'])) $serviceName = $booking['excursion_name'];
        elseif (!empty($booking['transfer_name']))  $serviceName = $booking['transfer_name'];

        $travelDate = !empty($booking['travel_date'])
            ? date('d/m/Y', strtotime($booking['travel_date']))
            : '—';

        $adults   = (int)($booking['adults']   ?? 1);
        $children = (int)($booking['children'] ?? 0);
        $paxStr   = $adults . ' adulto' . ($adults > 1 ? 's' : '');
        if ($children > 0) $paxStr .= ' + ' . $children . ' niño' . ($children > 1 ? 's' : '');

        $payLabel = ($booking['payment_method'] ?? '') === 'cash' ? 'Efectivo' : 'PayPal';
        $total    = number_format((float)($booking['total_price'] ?? 0), 2);

        $statusLabels = [
            'pending'   => 'Pendiente de confirmación',
            'confirmed' => 'Confirmada',
            'cancelled' => 'Cancelada',
            'completed' => 'Completada',
        ];
        $statusStr = $statusLabels[$booking['status'] ?? 'pending'] ?? 'Pendiente';

        return '<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>' . htmlspecialchars($cfg['title']) . '</title>
</head>
<body style="margin:0;padding:0;background:#f0f7fc;font-family:Arial,sans-serif;">
<table width="100%" cellpadding="0" cellspacing="0" style="background:#f0f7fc;padding:30px 0;">
    <tr><td align="center">
        <table width="580" cellpadding="0" cellspacing="0" style="background:#fff;border-radius:16px;overflow:hidden;box-shadow:0 4px 20px rgba(0,45,79,.12);">

            <!-- Header -->
            <tr>
                <td style="background:linear-gradient(135deg,#002D4F,#0077B6);padding:30px 40px;text-align:center;">
                    <div style="font-size:2rem;margin-bottom:8px;">' . $cfg['icon'] . '</div>
                    <h1 style="color:#fff;margin:0;font-size:22px;font-weight:800;letter-spacing:-.5px;">FUNTREK RD</h1>
                    <p style="color:rgba(255,255,255,.75);margin:4px 0 0;font-size:13px;">Agencia de Viajes · República Dominicana</p>
                </td>
            </tr>

            <!-- Estado -->
            <tr>
                <td style="padding:28px 40px 10px;text-align:center;">
                    <div style="display:inline-block;background:' . $cfg['color'] . '18;border:2px solid ' . $cfg['color'] . ';border-radius:12px;padding:16px 28px;">
                        <h2 style="color:' . $cfg['color'] . ';margin:0;font-size:18px;font-weight:800;">' . $cfg['title'] . '</h2>
                        <p style="color:#555;margin:8px 0 0;font-size:14px;line-height:1.6;">' . $cfg['message'] . '</p>
                    </div>
                </td>
            </tr>

            <!-- Referencia -->
            <tr>
                <td style="padding:16px 40px;text-align:center;">
                    <div style="display:inline-block;background:#EAF6FF;border:1.5px dashed #0077B6;border-radius:10px;padding:10px 24px;">
                        <div style="font-size:11px;color:#6E8FA5;text-transform:uppercase;letter-spacing:.1em;font-weight:700;">Número de reserva</div>
                        <div style="font-size:18px;font-weight:800;color:#0077B6;margin-top:2px;">' . htmlspecialchars($booking['booking_reference'] ?? '') . '</div>
                    </div>
                </td>
            </tr>

            <!-- Detalles -->
            <tr>
                <td style="padding:10px 40px 28px;">
                    <table width="100%" cellpadding="0" cellspacing="0" style="background:#EAF6FF;border-radius:12px;overflow:hidden;">
                        <tr><td colspan="2" style="padding:14px 18px;background:#ddf0fb;font-weight:800;font-size:13px;color:#002D4F;text-transform:uppercase;letter-spacing:.06em;">Detalles de la Reserva</td></tr>
                        <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
                            <td style="padding:10px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Cliente</td>
                            <td style="padding:10px 18px;color:#0D1B2A;font-size:13px;font-weight:700;">' . htmlspecialchars($booking['customer_name'] ?? '') . '</td>
                        </tr>
                        ' . ($serviceName !== '—' ? '
                        <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
                            <td style="padding:10px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Servicio</td>
                            <td style="padding:10px 18px;color:#0D1B2A;font-size:13px;font-weight:700;">' . htmlspecialchars($serviceName) . '</td>
                        </tr>' : '') . '
                        <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
                            <td style="padding:10px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Fecha del viaje</td>
                            <td style="padding:10px 18px;color:#0D1B2A;font-size:13px;font-weight:700;">' . $travelDate . '</td>
                        </tr>
                        <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
                            <td style="padding:10px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Pasajeros</td>
                            <td style="padding:10px 18px;color:#0D1B2A;font-size:13px;font-weight:700;">' . $paxStr . '</td>
                        </tr>
                        <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
                            <td style="padding:10px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Método de pago</td>
                            <td style="padding:10px 18px;color:#0D1B2A;font-size:13px;font-weight:700;">' . $payLabel . '</td>
                        </tr>
                        <tr style="border-bottom:1px solid rgba(0,119,182,.10);">
                            <td style="padding:10px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Estado</td>
                            <td style="padding:10px 18px;font-size:13px;font-weight:700;color:' . $cfg['color'] . ';">' . $statusStr . '</td>
                        </tr>
                        <tr>
                            <td style="padding:12px 18px;color:#6E8FA5;font-size:13px;font-weight:500;">Total</td>
                            <td style="padding:12px 18px;color:#0077B6;font-size:16px;font-weight:800;">$' . $total . ' USD</td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- CTA -->
            <tr>
                <td style="padding:0 40px 28px;text-align:center;">
                    <a href="' . (defined('APP_URL') ? APP_URL : 'http://www.FUNTREKrd.com') . '/dashboard/bookings"
                       style="display:inline-block;background:linear-gradient(135deg,#0077B6,#00B4D8);color:#fff;text-decoration:none;font-weight:700;font-size:14px;padding:13px 32px;border-radius:999px;">
                        Ver mis reservas
                    </a>
                </td>
            </tr>

            <!-- Footer -->
            <tr>
                <td style="background:#f0f7fc;padding:20px 40px;text-align:center;border-top:1px solid rgba(0,119,182,.10);">
                    <p style="color:#6E8FA5;font-size:12px;margin:0;">© ' . date('Y') . ' FUNTREK RD — Punta Cana, República Dominicana</p>
                    <p style="color:#6E8FA5;font-size:12px;margin:4px 0 0;">Este correo fue enviado a ' . htmlspecialchars($booking['customer_email'] ?? '') . '</p>
                </td>
            </tr>

        </table>
    </td></tr>
</table>
</body>
</html>';
    }
}
