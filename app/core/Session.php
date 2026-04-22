<?php

class Session
{
    /**
     * Inicia la sesión de forma segura.
     */
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            // Configuración de seguridad de cookies de sesión
            session_set_cookie_params([
                'lifetime' => 0,
                'path' => '/',
                'domain' => '', // Ajustar según dominio
                'secure' => isset($_SERVER['HTTPS']),
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            session_start();
            
             // Prevenir fijación de sesión regenerando el ID al iniciar
             if (!isset($_SESSION['initialized'])) {
                 self::regenerate();
                 $_SESSION['initialized'] = true;
             }
        }
    }

    /**
     * Regenera el ID de la sesión para prevenir ataques de fijación de sesión.
     * Debe llamarse después de un cambio de nivel de privilegio (ej. login).
     */
    public static function regenerate(): void
    {
        session_regenerate_id(true);
    }

    /**
     * Obtiene un valor de la sesión.
     */
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Establece un valor en la sesión.
     */
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    /**
     * Elimina un valor de la sesión.
     */
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    /**
     * Destruye la sesión completamente.
     */
    public static function destroy(): void
    {
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        session_destroy();
    }
}