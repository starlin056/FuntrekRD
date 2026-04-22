<?php
class Database {
    private static $instance = null;
    private $connection;
    
    private function __construct() {
        // Verificar que las constantes estén definidas
        if (!defined('DB_HOST') || !defined('DB_NAME') || !defined('DB_USER')) {
            throw new Exception("Las constantes de configuración de base de datos no están definidas. Verifica config.php");
        }
        
        try {
            // Crear conexión PDO
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
            
            $this->connection = new PDO($dsn, DB_USER, DB_PASS);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            
        } catch (PDOException $e) {
            // Log del error
            error_log("Error de conexión a BD: " . $e->getMessage());
            
            // Mostrar mensaje amigable en desarrollo
            if (defined('DEBUG') && DEBUG) {
                throw new Exception("Error de conexión a la base de datos: " . $e->getMessage());
            } else {
                throw new Exception("Error de conexión a la base de datos. Por favor, contacta al administrador.");
            }
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->connection;
    }
    
    // Método para verificar conexión
    public function testConnection() {
        try {
            $stmt = $this->connection->query("SELECT 1");
            return $stmt !== false;
        } catch (PDOException $e) {
            return false;
        }
    }
}