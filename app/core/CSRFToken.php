<?php

class CSRFToken
{
    /**
     * Genera un token CSRF y lo guarda en la sesión.
     * 
     * @return string El token generado.
     */
    public static function generate(): string
    {
        if (session_status() === PHP_SESSION_NONE) {
            Session::start();
        }

        $token = bin2hex(random_bytes(32));
        Session::set('csrf_token', $token);
        return $token;
    }

    /**
     * Verifica si el token proporcionado coincide con el token en la sesión.
     * 
     * @param string|null $token El token a verificar.
     * @return bool True si es válido, False si no.
     */
    public static function validate(?string $token): bool
    {
        if (empty($token)) {
            return false;
        }

        $storedToken = Session::get('csrf_token');

        if (!$storedToken) {
            return false;
        }

        return hash_equals($storedToken, $token);
    }

    /**
     * Elimina el token de la sesión.
     * 
     * @return void
     */
    public static function reset(): void
    {
        Session::remove('csrf_token');
    }
}