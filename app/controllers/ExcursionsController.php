<?php
// app/controllers/ExcursionsController.php

class ExcursionsController
{
    private $excursionModel;

    public function __construct()
    {
        $this->excursionModel = new Excursion();
    }

    /* ─────────────────────────────────────────
       PÁGINA PRINCIPAL /excursions
    ───────────────────────────────────────── */
    public function index()
    {
        $filters = [
            'q'         => $_GET['q'] ?? '',
            'category'  => $_GET['category'] ?? '',
            'min_price' => $_GET['min_price'] ?? '',
            'max_price' => $_GET['max_price'] ?? '',
            'sort'      => $_GET['sort'] ?? '',
        ];

        $hasFilters = (bool)array_filter($filters, function ($v) {
            return $v !== '' && $v !== null;
        });

        if ($hasFilters) {
            $excursions = $this->excursionModel->search($filters);
        } else {
            $excursions = $this->excursionModel->getActive();
        }

        if (!$excursions) $excursions = [];

        foreach ($excursions as &$exc) {
            $exc['includes']     = $this->decodeJson($exc['includes'] ?? '[]');
            $exc['requirements'] = $this->decodeJson($exc['requirements'] ?? '[]');
            $exc['gallery']      = $this->decodeJson($exc['gallery'] ?? '[]');
        }
        unset($exc);

        $this->view('excursions/index', [
            'title'       => 'Excursiones en República Dominicana',
            'excursions'  => $excursions,
            'categories'  => $this->excursionModel->getCategories(),
            'priceRange'  => $this->excursionModel->getPriceRange(),
            'suggestions' => $this->excursionModel->getSearchSuggestions(),
            'filters'     => $filters,
            'hasFilters'  => $hasFilters,
            'totalCount'  => count($excursions),
        ]);
    }

    /* ─────────────────────────────────────────
       RECIBIR SOLICITUD PERSONALIZADA (POST)
       URL: /excursions/custom_request
    ───────────────────────────────────────── */

    public function custom_request()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/excursions');
        }

        // ✅ 1. VALIDAR CAMPOS REQUERIDOS (incluye phone)
        $required = ['customer_name', 'customer_email', 'customer_phone', 'destinations'];
        foreach ($required as $field) {
            if (empty(trim($_POST[$field] ?? ''))) {
                $_SESSION['custom_error'] = 'Por favor completa todos los campos obligatorios.';
                $this->redirect('/excursions#custom-form');
            }
        }

        // ✅ 2. VALIDAR EMAIL
        if (!filter_var($_POST['customer_email'], FILTER_VALIDATE_EMAIL)) {
            $_SESSION['custom_error'] = 'Por favor ingresa un email válido.';
            $this->redirect('/excursions#custom-form');
        }

        // ✅ 3. VALIDAR FORMATO DE TELÉFONO
        $phone = trim($_POST['customer_phone'] ?? '');
        $phoneClean = preg_replace('/\D/', '', $phone);
        if (strlen($phoneClean) < 7 || strlen($phoneClean) > 15) {
            $_SESSION['custom_error'] = 'Por favor ingresa un número de teléfono válido (mínimo 7 dígitos).';
            $this->redirect('/excursions#custom-form');
        }

        // ✅ 4. PROCESAR ACTIVIDADES: Priorizar array de checkboxes, fallback a string
        $activities = '';
        if (!empty($_POST['activities_check']) && is_array($_POST['activities_check'])) {
            // ✅ Usar array de checkboxes (más confiable)
            $activities = implode(', ', array_map('trim', $_POST['activities_check']));
        } elseif (!empty($_POST['activities']) && is_string($_POST['activities'])) {
            // Fallback: usar campo oculto si JS funcionó
            $activities = trim($_POST['activities']);
        }

        // ✅ 5. PREPARAR DATOS LIMPIOS
        $data = [
            'customer_name'    => trim($_POST['customer_name']),
            'customer_email'   => filter_var($_POST['customer_email'], FILTER_SANITIZE_EMAIL),
            'customer_phone'   => preg_replace('/\s+/', ' ', $phone), // Formato legible
            'destinations'     => trim($_POST['destinations']),
            'activities'       => $activities, // ✅ Ya procesado correctamente
            'travel_date'      => !empty($_POST['travel_date']) ? $_POST['travel_date'] : null,
            'people_count'     => max(1, (int)($_POST['people_count'] ?? 1)),
            'budget'           => trim($_POST['budget'] ?? ''),
            'additional_notes' => trim($_POST['additional_notes'] ?? ''),
            'status'           => 'pending'
        ];

        // ✅ 6. INSERTAR EN BD
        $requestId = $this->excursionModel->createCustomRequest($data);

        if (!$requestId) {
            $_SESSION['custom_error'] = 'Error al procesar tu solicitud. Intenta nuevamente.';
            $this->redirect('/excursions#custom-form');
        }

        // ✅ 7. ENVIAR EMAILS
        if (defined('SEND_EMAILS') && SEND_EMAILS) {
            try {
                require_once APP_ROOT . '/app/core/Email.php';
                $emailService = new Email();
                $emailService->sendCustomExcursionRequest((int)$requestId, $data);
            } catch (Exception $e) {
                error_log('[ExcursionsController] Email custom request: ' . $e->getMessage());
                // No bloquear flujo si falla email
            }
        }

        // ✅ 8. ÉXITO
        $_SESSION['custom_success'] = '¡Solicitud enviada! 🎉 Te contactaremos en menos de 24 horas.';
        $this->redirect('/excursions#custom-form');
    }

    /* ─────────────────────────────────────────
       HELPERS PRIVADOS
   ───────────────────────────────────────── */

    private function decodeJson($value)
    {
        // Si ya es array, lo devolvemos tal cual
        if (is_array($value)) {
            return $value;
        }

        // Si es string, intentamos decodificar
        if (is_string($value)) {
            $d = json_decode($value, true);
            return is_array($d) ? $d : [];
        }

        // Para cualquier otro tipo, devolvemos array vacío
        return [];
    }

    /**
     * Envía correo al administrador y al cliente con formato HTML (usando Email.php)
     */
    private function sendCustomRequestEmails(array $data, int $requestId): void
    {
        $email = new Email();

        // Correo al administrador
        $adminEmail = defined('ADMIN_EMAIL') ? ADMIN_EMAIL : null;
        if ($adminEmail) {
            $email->sendCustomRequest($adminEmail, $data, $requestId, true);
        }

        // Correo al cliente
        if (!empty($data['customer_email'])) {
            $email->sendCustomRequest($data['customer_email'], $data, $requestId, false);
        }
    }

    /* ─────────────────────────────────────────
       VIEW / REDIRECT
    ───────────────────────────────────────── */

    protected function view($view, $data = [])
    {
        extract($data);
        $viewPath = APP_ROOT . '/app/views/' . $view . '.php';
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die('Vista no encontrada: ' . htmlspecialchars($viewPath));
        }
    }

    protected function redirect($url)
    {
        header('Location: ' . APP_URL . $url);
        exit;
    }

    protected function translate($key, $default = '')
    {
        return Translator::getInstance()->get($key, $default);
    }
}
