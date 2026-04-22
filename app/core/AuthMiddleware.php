<?php

class AuthMiddleware
{
    /**
     * Maneja la autenticación y autorización de las rutas.
     * 
     * @param string $controllerName El nombre del controlador que se intenta acceder.
     * @return bool True si tiene acceso, False si no.
     */
    public function handle(string $controllerName): bool
    {
        // 1. Verificar si la ruta está protegida en config.php
        $protectedRoutes = defined('PROTECTED_ROUTES') ? PROTECTED_ROUTES : [];
        
        // Si no está en la lista de rutas protegidas, permitimos el acceso
        if (!array_key_exists($controllerName, $protectedRoutes)) {
            return true;
        }

        // 2. Obtener los roles permitidos para este controlador
        $allowedRoles = $protectedRoutes[$controllerName];

        // 3. Verificar si el usuario está autenticado
        $isLoggedIn = isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
        $userRole = $_SESSION['user_role'] ?? 'guest';

        // Caso especial: Si el controlador es 'AuthController' (para login/registro), 
        // permitimos acceso a 'guest' (no autenticados)
        if ($controllerName === 'AuthController' || $controllerName === 'Auth') {
            if (in_array('guest', $allowedRoles) || in_array($userRole, $allowedRoles)) {
                return true;
            }
        }

        // 4. Si no está autenticado, redirigir al login
        if (!$isLoggedIn) {
            if (in_array('guest', $allowedRoles)) {
                return true;
            }
            
            $this->redirectLogin();
            return false;
        }

        // 5. Si está autenticado, verificar si su rol está permitido
        if (!in_array($userRole, $allowedRoles)) {
            $this->redirectUnauthorized();
            return false;
        }

        return true;
    }

    /**
     * Redirige al login si el usuario no está autenticado.
     */
    private function redirectLogin(): void
    {
        header('Location: ' . APP_URL . '/auth/login');
        exit;
    }

    /**
     * Redirige al dashboard o página de error si el usuario no tiene permisos.
     */
    private function redirectUnauthorized(): void
    {
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
            header('Location: ' . APP_URL . '/admin/dashboard');
        } else {
            header('Location: ' . APP_URL . '/dashboard');
        }
        exit;
    }

    /**
     * Redirige al usuario al login si no está autenticado.
     */
    public static function authenticateOrRedirect(): void
    {
        if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
            header('Location: ' . APP_URL . '/auth/login');
            exit;
        }
    }

    /**
     * Refuerza la seguridad de la sesión regenerando el ID.
     * Utiliza la clase Session para mantener la consistencia.
     */
    public static function regenerateSession(): void
    {
        if (class_exists('Session')) {
            Session::regenerate();
        } else {
            session_regenerate_id(true);
        }
    }
}