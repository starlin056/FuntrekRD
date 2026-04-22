<?php
// ARCHIVO DE EJEMPLO PARA CONFIGURACIÓN
// Renombrar a config.php y completar con los datos reales

if (!defined('APP_NAME')) {
    define('APP_NAME', 'Dominican Travel Agency');
    define('APP_VERSION', '5.0.0');

    // Detección de entorno (Local vs Producción)
    if ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1') {
        define('APP_URL', 'http://localhost/dominican_travel/public');
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'tu_base_de_datos_local');
        define('DB_USER', 'root');
        define('DB_PASS', '');
        define('APP_DEBUG', true);
    } else {
        define('APP_URL', 'https://tu-dominio.com');
        define('DB_HOST', 'localhost');
        define('DB_NAME', 'tu_base_de_datos_produccion');
        define('DB_USER', 'tu_usuario_produccion');
        define('DB_PASS', 'tu_password_produccion');
        define('APP_DEBUG', false);
    }

    define('MAIL_URL', 'https://tu-dominio.com');
    define('ADMIN_EMAIL', 'tu-email@gmail.com');
    define('DEBUG', APP_DEBUG);

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

error_reporting(E_ALL);
if (APP_DEBUG) {
    ini_set('display_errors', 1);
} else {
    ini_set('display_errors', 0);
}
ini_set('log_errors', 1);
ini_set('error_log', APP_ROOT . '/logs/error.log');
