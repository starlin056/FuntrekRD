<?php

/*****************************************************************
 * FRONT CONTROLLER
 * Dominican Travel
 *****************************************************************/

// =====================================================
// 1. ERRORES (solo desarrollo)
// =====================================================
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// =====================================================
// 2. SESIÓN (ANTES DE TODO)
// =====================================================
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Inicializar token CSRF para seguridad
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

// =====================================================
// 3. DEFINIR APP_ROOT
// =====================================================
if (!defined('APP_ROOT')) {
    define('APP_ROOT', dirname(__DIR__));
}



// =========================================================
// 4. CONFIGURACIÓN GENERAL
// =====================================================
require_once APP_ROOT . '/config/config.php';

// =====================================================
// 5. CORE
// =====================================================
require_once APP_ROOT . '/app/core/Translator.php';
require_once APP_ROOT . '/app/core/Controller.php';
require_once APP_ROOT . '/app/core/Email.php';
require_once APP_ROOT . '/app/core/Model.php';

// =====================================================
// 6. TRADUCTOR (INICIALIZAR + RECARGAR)
// =====================================================
$translator = Translator::getInstance();

// Si viene cambio de idioma → recargar
if (isset($_GET['lang'])) {
    $translator->reload();
}

// =====================================================
// 7. HELPERS
// =====================================================
require_once APP_ROOT . '/app/helpers/i18n.php';

// =====================================================
// 8. AUTOLOAD MVC
// =====================================================
spl_autoload_register(function ($className) {

    $paths = [
        APP_ROOT . '/app/core/' . $className . '.php',
        APP_ROOT . '/app/controllers/' . $className . '.php',
        APP_ROOT . '/app/models/' . $className . '.php',
    ];

    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }

    // Fallback mayúsculas
    $className = ucfirst($className);
    foreach ($paths as $path) {
        $alt = str_replace(basename($path), $className . '.php', $path);
        if (file_exists($alt)) {
            require_once $alt;
            return;
        }
    }
});

// =====================================================
// 9. ROUTING MEJORADO CON SOPORTE API
// =====================================================
$url = $_GET['url'] ?? 'home/index';
$url = rtrim($url, '/');
$url = filter_var($url, FILTER_SANITIZE_URL);
$urlParts = explode('/', $url);

// Mapeo base de controladores
$controllerMap = [
    // Frontend
    'home'        => 'HomeController',
    'paquetes'    => 'PaquetesController',
    'packages'    => 'PaquetesController',
    'transfers'   => 'TransfersController',
    'excursiones' => 'ExcursionsController',
    'excursions'  => 'ExcursionsController',
    'contacto'    => 'ContactController',
    'contact'     => 'ContactController',
    'brokers'     => 'BrokersController',
    'reserva'     => 'BookingController',
    'booking'     => 'BookingController',
    'auth'        => 'AuthController',
    'dashboard'   => 'DashboardController',
    'admin'       => 'AdminController',
];

$controllerKey = strtolower($urlParts[0] ?? 'home');

// ✅ Routing especial para API de Custom Requests
if ($controllerKey === 'api' && isset($urlParts[1]) && $urlParts[1] === 'custom-requests') {
    $controllerName = 'CustomRequestsApiController';
    $action = $urlParts[2] ?? 'show'; // show, addNote, updateRequirements, markContacted, quote
    $params = array_slice($urlParts, 3); // El ID y otros params vienen después
}
// Routing para admin
elseif ($controllerKey === 'admin' && isset($urlParts[1])) {
    $controllerName = $controllerMap[$controllerKey] ?? 'AdminController';
    $action = $urlParts[1];
    $params = array_slice($urlParts, 2);
}
// Routing estándar
else {
    $controllerName = $controllerMap[$controllerKey] ?? 'HomeController';
    $action = $urlParts[1] ?? 'index';
    $params = array_slice($urlParts, 2);
}

// =====================================================
// 10. EJECUCIÓN
// =====================================================
$controllerFile = APP_ROOT . '/app/controllers/' . $controllerName . '.php';

try {
    if (!file_exists($controllerFile)) {
        showError(404, "Controlador no encontrado: {$controllerName}");
    }
    require_once $controllerFile;

    if (!class_exists($controllerName)) {
        showError(500, "Clase no definida: {$controllerName}");
    }

    $controller = new $controllerName();

    // Middleware de Autenticación y Autorización
    $authMiddleware = new AuthMiddleware();
    $authMiddleware->handle($controllerName);

    if (!method_exists($controller, $action)) {
        showError(404, "Método no encontrado: {$action} en {$controllerName}");
    }

    call_user_func_array([$controller, $action], $params);
} catch (Throwable $e) {
    error_log('[Router Error] ' . $e->getMessage() . ' in ' . $e->getFile() . ':' . $e->getLine());
    showError(500, defined('APP_DEBUG') && APP_DEBUG ? $e->getMessage() : 'Error interno del servidor');
}

// =====================================================
// 11. ERRORES
// =====================================================
function showError($code, $message)
{
    http_response_code($code);
    if (defined('APP_DEBUG') && APP_DEBUG) {
        echo "<h1>Error $code</h1><p>" . htmlspecialchars($message) . "</p>";
    } else {
        echo "<h1>Error $code</h1><p>Ocurrió un error. Por favor intenta más tarde.</p>";
    }
    exit;
}
