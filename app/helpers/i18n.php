<?php

if (!function_exists('__')) {
    function __($key)
    {
        return Translator::getInstance()->get($key);
    }
}

/**
 * Returns a hidden input field containing the CSRF token.
 * Use this in all HTML forms that use POST.
 * 
 * @return string
 */
function csrf_token_field()
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $token = $_SESSION['csrf_token'] ?? '';
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}
