<?php
define('APP_ROOT', dirname(__DIR__));
require_once __DIR__ . '/../config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Iniciando migración...\n";

    $queries = [
        "ALTER TABLE `packages` ADD `price_type` ENUM('persona', 'paquete') NOT NULL DEFAULT 'persona' AFTER `price` ;",
        "ALTER TABLE `excursions` ADD `price_type` ENUM('persona', 'paquete') NOT NULL DEFAULT 'persona' AFTER `price` ;",
        "ALTER TABLE `transfers` ADD `price_type` ENUM('persona', 'paquete') NOT NULL DEFAULT 'paquete' AFTER `price` ;"
    ];

    foreach ($queries as $query) {
        try {
            $pdo->exec($query);
            echo "Ejecutado: $query\n";
        } catch (PDOException $e) {
            echo "Error en query: " . $e->getMessage() . "\n";
        }
    }

    echo "Migración completada.\n";

} catch (PDOException $e) {
    echo "Error de conexión: " . $e->getMessage() . "\n";
}
