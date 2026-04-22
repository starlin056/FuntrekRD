<?php
class Controller
{
    public function __construct()
    {
        // Iniciar sesión y verificar CSRF para peticiones POST
        Session::start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST['csrf_token'] ?? '';
            if (!CSRFToken::validate($token)) {
                die("Error: Token CSRF no válido. La petición ha sido rechazada por motivos de seguridad.");
            }
        }
    }

    protected function view($view, $data = [])
    {
        // Extraer datos para que estén disponibles en la vista
        extract($data);

        // Construir ruta a la vista
        $viewPath = APP_ROOT . '/app/views/' . $view . '.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("Error: Vista no encontrada - $viewPath");
        }
    }

    protected function json($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }

    protected function redirect($url)
    {
        header("Location: " . APP_URL . $url);
        exit;
    }

    protected function loadModel($modelName)
    {
        $modelPath = APP_ROOT . '/app/models/' . $modelName . '.php';
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $modelName();
        }
        return null;
    }

    protected function translate($key, $default = '')
    {
        return Translator::getInstance()->get($key, $default);
    }

    /**
     * Escapa contenido para prevenir ataques XSS.
     * Soporta strings y arreglos.
     */
    protected function escape($data)
    {
        if (is_array($data)) {
            return array_map([$this, 'escape'], $data);
        }
        return htmlspecialchars((string)$data, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Genera un token CSRF y lo guarda en la sesión.
     */
    protected function generateCsrfToken()
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * Verifica si el token CSRF proporcionado es válido.
     */
    protected function verifyCsrfToken($token)
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }
}
