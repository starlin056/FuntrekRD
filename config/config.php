<?php
// Definir constantes SI NO están ya definidas
if (!defined('APP_NAME')) {
    define('APP_NAME', 'Dominican Travel Agency');
    define('APP_VERSION', '1.0.0');

    // Detección de entorno (Local vs Producción)
    if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1' || strpos($_SERVER['HTTP_HOST'], '192.168.') !== false) {
        // --- CONFIGURACIÓN LOCAL (XAMPP) ---
        define('APP_URL', 'http://' . $_SERVER['HTTP_HOST'] . '/dominican_travel/public');
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'dominican_travel_db');
        define('DB_USER', 'root');
        define('DB_PASS', '');
        define('APP_DEBUG', true);
    } else {
        // --- CONFIGURACIÓN PRODUCCIÓN (HOSTINGER) ---
        define('APP_URL', 'https://funtrekrd.com');
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'u792059717_dominican_trav');
        define('DB_USER', 'u792059717_pedro056');
        define('DB_PASS', 'Lorent07@@');
        define('APP_DEBUG', false); // Desactivar errores visibles en producción
    }

    // URL pública para correos y servicios
    define('MAIL_URL', 'https://funtrekrd.com');
    define('ADMIN_EMAIL', 'starlin056@gmail.com');
    define('DEBUG', APP_DEBUG);

    // Configuración de Rutas Protegidas (Roles permitidos)
    define('PROTECTED_ROUTES', [
        'AdminController'   => ['admin'],
        'UserController'    => ['admin'],
        'BookingController' => ['admin', 'client'],
        'ProfileController' => ['admin', 'client'],
        'SettingsController' => ['admin'],
        'PackageController' => ['admin', 'client'],
        'Auth'              => ['guest', 'user', 'admin'],
        'AuthController'    => ['guest', 'user', 'admin'],
    ]);

    define('SEND_EMAILS', true);
    date_default_timezone_set('America/Santo_Domingo');
}

// Configurar manejo de errores según el entorno
error_reporting(E_ALL);
if (APP_DEBUG) {
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
}
ini_set('log_errors', 1);
ini_set('error_log', APP_ROOT . '/logs/error.log');
