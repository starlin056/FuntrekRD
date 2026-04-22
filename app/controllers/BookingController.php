<?php

class BookingController extends Controller
{
    protected $bookingModel;
    protected $userModel;

    public function __construct()
    {
        $this->bookingModel = new Booking();
        $this->userModel    = new User();
    }

    public function index()
    {
        $this->redirect('/');
    }

    /* =====================================================
       MOSTRAR FORMULARIO DE RESERVA
    ===================================================== */
    public function create($type, $itemId)
    {
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            $_SESSION['booking_redirect'] = "/reserva/create/{$type}/{$itemId}";
            $this->redirect('/auth/login');
        }

        $item = $this->getItemDetails($type, $itemId);
        if (!$item) {
            $_SESSION['error'] = 'Servicio no encontrado.';
            $this->redirect('/');
        }

        $userData = [
            'name'  => $_SESSION['user_name']  ?? '',
            'email' => $_SESSION['user_email'] ?? '',
            'phone' => ''
        ];

        $this->view('booking/create', [
            'title'    => 'Reservar ' . htmlspecialchars($item['name']),
            'item'     => $item,
            'type'     => $type,
            'userData' => $userData,
            'adults'   => (int)($_GET['adults'] ?? 1),
            'children' => (int)($_GET['children'] ?? 0)
        ]);
    }

    /* =====================================================
       PROCESAR LA RESERVA (POST)
    ===================================================== */
    public function process()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
        }

        // ✅ VALIDAR CAMPOS OBLIGATORIOS (incluye phone ahora)
        $required = ['name', 'email', 'phone', 'travel_date', 'item_type', 'item_id', 'total_price'];
        foreach ($required as $field) {
            if (empty($_POST[$field])) {
                $_SESSION['error'] = 'Por favor complete todos los campos obligatorios.';
                $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
            }
        }

        // ✅ VALIDAR FORMATO DE TELÉFONO (opcional pero recomendado)
        $phone = trim($_POST['phone']);
        // Permite: números, +, -, espacios, paréntesis. Mínimo 7 caracteres, máximo 20
        if (!preg_match('/^[\d\+\-\s\(\)]{7,20}$/', $phone)) {
            $_SESSION['error'] = 'Por favor ingresa un número de teléfono válido (ej: +1 829 555 1234).';
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }

        // Leer método de pago del POST (cash | paypal)
        $paymentMethod = in_array($_POST['payment_method'] ?? '', ['paypal', 'cash'])
            ? $_POST['payment_method']
            : 'paypal';

        // Autologin si no hay sesión activa
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            $email        = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $existingUser = $this->userModel->findBy('email', $email);

            if ($existingUser) {
                $_SESSION['logged_in']  = true;
                $_SESSION['user_id']    = $existingUser['id'];
                $_SESSION['user_name']  = $existingUser['full_name'] ?? $existingUser['username'];
                $_SESSION['user_email'] = $existingUser['email'];
                $_SESSION['user_role']  = $existingUser['role'] ?? 'client';
            } else {
                $newUserId = $this->userModel->create([
                    'username'  => substr(explode('@', $email)[0], 0, 50),
                    'email'     => $email,
                    'password'  => password_hash(bin2hex(random_bytes(16)), PASSWORD_DEFAULT),
                    'full_name' => trim($_POST['name']),
                    'role'      => 'client'
                ]);

                if (!$newUserId) {
                    $_SESSION['error'] = 'Error al crear su cuenta.';
                    $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
                }

                $_SESSION['logged_in']  = true;
                $_SESSION['user_id']    = $newUserId;
                $_SESSION['user_name']  = trim($_POST['name']);
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role']  = 'client';
            }
        }

        // Fetch item to recalculate price for security
        $item = $this->getItemDetails($_POST['item_type'], (int)$_POST['item_id']);
        if (!$item) {
            $_SESSION['error'] = 'Servicio no encontrado.';
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }

        // Price calculation logic
        $priceType = $item['price_type'] ?? 'persona';
        $itemPrice = (float)($item['discount_price'] ?: $item['price']);
        
        $adults = max(1, (int)($_POST['adults'] ?? 1));
        $children = max(0, (int)($_POST['children'] ?? 0));
        
        if ($priceType === 'paquete') {
            $totalPrice = $itemPrice;
        } else {
            // Logic: Adults pay full price, children pay 50%
            $totalPrice = ($itemPrice * $adults) + ($itemPrice * 0.5 * $children);
        }

        // Construir datos de la reserva
        $bookingData = [
            'customer_name'    => trim($_POST['name']),
            'customer_email'   => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
            // ✅ SANITIZAR TELÉFONO: eliminar espacios múltiples, mantener formato legible
            'customer_phone'   => preg_replace('/\s+/', ' ', $phone),
            'travel_date'      => $_POST['travel_date'],
            'adults'           => $adults,
            'children'         => $children,
            'special_requests' => trim($_POST['requests'] ?? ''),
            'item_type'        => $_POST['item_type'],
            'item_id'          => (int)$_POST['item_id'],
            'total_price'      => $totalPrice, // Use backend-calculated price
            'status'           => 'pending',
            'payment_status'   => 'pending',
            'payment_method'   => $paymentMethod
        ];

        $bookingId = $this->bookingModel->create($bookingData);
        if (!$bookingId) {
            $_SESSION['error'] = 'Error al procesar la reserva. Intente nuevamente.';
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }

        // Enviar notificación por email (sin bloquear el flujo si falla)
        if (defined('SEND_EMAILS') && SEND_EMAILS) {
            $this->sendNewBookingEmail($bookingId);
        }

        // Redirigir según método de pago
        if ($paymentMethod === 'cash') {
            $this->redirect("/reserva/success/{$bookingId}?method=cash");
        } else {
            $this->redirectToPayPal(
                $bookingId,
                $bookingData['total_price'],
                $bookingData['customer_name']
            );
        }

        // Construir datos de la reserva
        // TODAS las reservas nacen en pending/pending — el admin confirma
        $bookingData = [
            'customer_name'    => trim($_POST['name']),
            'customer_email'   => filter_var($_POST['email'], FILTER_SANITIZE_EMAIL),
            'customer_phone'   => trim($_POST['phone'] ?? ''),
            'travel_date'      => $_POST['travel_date'],
            'adults'           => max(1, (int)($_POST['adults']   ?? 1)),
            'children'         => max(0, (int)($_POST['children'] ?? 0)),
            'special_requests' => trim($_POST['requests'] ?? ''),
            'item_type'        => $_POST['item_type'],
            'item_id'          => (int)$_POST['item_id'],
            'total_price'      => (float)$_POST['total_price'],
            'status'           => 'pending',          // Siempre pending al crear
            'payment_status'   => 'pending',          // Siempre pending al crear
            'payment_method'   => $paymentMethod      // 'cash' o 'paypal'
        ];

        $bookingId = $this->bookingModel->create($bookingData);
        if (!$bookingId) {
            $_SESSION['error'] = 'Error al procesar la reserva. Intente nuevamente.';
            $this->redirect($_SERVER['HTTP_REFERER'] ?? '/');
        }

        // Enviar notificación por email (sin bloquear el flujo si falla)
        if (defined('SEND_EMAILS') && SEND_EMAILS) {
            $this->sendNewBookingEmail($bookingId);
        }

        // Redirigir según método de pago
        if ($paymentMethod === 'cash') {
            // Efectivo: ir directo a página de confirmación
            $this->redirect("/reserva/success/{$bookingId}?method=cash");
        } else {
            // PayPal: redirigir a pasarela de pago
            $this->redirectToPayPal(
                $bookingId,
                $bookingData['total_price'],
                $bookingData['customer_name']
            );
        }
    }

    /* =====================================================
       PÁGINA DE CONFIRMACIÓN / ÉXITO
    ===================================================== */
    public function success($bookingId)
    {
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            $this->redirect('/auth/login');
        }

        $booking = $this->bookingModel->findById($bookingId);

        // Seguridad: solo el dueño puede ver su confirmación
        if (!$booking || $booking['customer_email'] !== $_SESSION['user_email']) {
            $_SESSION['error'] = 'Acceso no autorizado.';
            $this->redirect('/dashboard');
        }

        // Leer método desde la URL (más fiable que leer solo la BD en este punto)
        // pero siempre validar contra lo guardado en BD
        $methodFromUrl = $_GET['method'] ?? 'paypal';
        $methodFromDb  = $booking['payment_method'] ?? 'paypal';

        // El método real es el guardado en BD — la URL solo indica el origen del redirect
        $paymentMethod = $methodFromDb;

        // Si viene de PayPal (retorno exitoso), actualizar payment_status = 'paid'
        // El status de la reserva sigue en 'pending' hasta que el admin confirme
        if ($methodFromUrl === 'paypal' && $methodFromDb === 'paypal') {
            $this->bookingModel->update($bookingId, [
                'payment_status' => 'paid'
            ]);
            $booking['payment_status'] = 'paid'; // Actualizar array local

            if (defined('SEND_EMAILS') && SEND_EMAILS) {
                $this->sendPaymentEmail($booking);
            }
        }

        $this->view('booking/success', [
            'booking'        => $booking,
            'payment_method' => $paymentMethod   // 'cash' o 'paypal' — desde la BD
        ]);
    }

    /* =====================================================
       CANCELACIÓN DESDE PAYPAL
    ===================================================== */
    public function cancel($bookingId)
    {
        if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
            $this->redirect('/auth/login');
        }

        $booking = $this->bookingModel->findById($bookingId);
        if (!$booking || $booking['customer_email'] !== $_SESSION['user_email']) {
            $_SESSION['error'] = 'Acceso no autorizado.';
            $this->redirect('/dashboard');
        }

        $this->bookingModel->update($bookingId, [
            'status'         => 'cancelled',
            'payment_status' => 'pending'
        ]);

        $this->view('booking/cancel', ['bookingId' => $bookingId]);
    }

    /* =====================================================
       MÉTODOS PRIVADOS AUXILIARES
    ===================================================== */

    private function getItemDetails($type, $id)
    {
        switch ($type) {
            case 'package':
                return (new Package())->findById($id);
            case 'excursion':
                return (new Excursion())->findById($id);
            case 'transfer':
                return (new Transfer())->findById($id);
            default:
                return null;
        }
    }

    private function redirectToPayPal($bookingId, $amount, $customerName)
    {
        $params = [
            'cmd'           => '_xclick',
            'business'      => 'JNM7JT8TTJGWA',
            'item_name'     => 'Reserva #' . $bookingId . ' - FUNTREK RD',
            'amount'        => number_format($amount, 2, '.', ''),
            'currency_code' => 'USD',
            'return'        => APP_URL . "/reserva/success/{$bookingId}?method=paypal",
            'cancel_return' => APP_URL . "/reserva/cancel/{$bookingId}",
            'custom'        => $bookingId,
            'no_shipping'   => 1
        ];

        header('Location: https://www.paypal.com/cgi-bin/webscr?' . http_build_query($params));
        exit;
    }

    private function sendNewBookingEmail($bookingId)
    {
        try {
            require_once APP_ROOT . '/app/core/Email.php';
            $emailService = new Email();
            $booking      = $this->bookingModel->findById($bookingId);
            if ($booking) {
                $emailService->sendNewBookingNotification($booking);
            }
        } catch (Exception $e) {
            error_log('[BookingController] Email nueva reserva: ' . $e->getMessage());
        }
    }

    private function sendPaymentEmail($booking)
    {
        try {
            require_once APP_ROOT . '/app/core/Email.php';
            $emailService = new Email();
            $emailService->sendPaymentConfirmation($booking);
        } catch (Exception $e) {
            error_log('[BookingController] Email pago: ' . $e->getMessage());
        }
    }

    /* =====================================================
       HELPERS HEREDADOS DEL CONTROLLER BASE
    ===================================================== */

    protected function redirect($url)
    {
        if (strpos($url, 'http') !== 0 && strpos($url, APP_URL) === false) {
            $url = APP_URL . $url;
        }
        header('Location: ' . $url);
        exit;
    }

    protected function translate($key, $default = '')
    {
        return Translator::getInstance()->get($key, $default);
    }
}
