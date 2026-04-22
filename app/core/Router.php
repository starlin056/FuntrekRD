<?php
// app/core/Router.php
class Router
{
    private $routes = [];
    private $params = [];

    /**
     * Registrar una ruta
     */
    public function add($route, $controller, $action = 'index')
    {
        $this->routes[$route] = [
            'controller' => $controller,
            'action'     => $action
        ];
    }

    /**
     * Despachar la URL
     */
    public function dispatch($url)
    {
        $url = $this->removeQueryStringVariables($url);
        $parts = explode('/', trim($url, '/'));

        $route  = $parts[0] ?? 'home';
        $action = $parts[1] ?? 'index';
        $params = array_slice($parts, 2);

        if (isset($this->routes[$route])) {
            $controller = $this->routes[$route]['controller'];
            $controllerFile = APP_ROOT . '/app/controllers/' . $controller . '.php';

            if (file_exists($controllerFile)) {
                require_once $controllerFile;

                if (class_exists($controller)) {
                    $controllerInstance = new $controller();
                    
                    // Aplicar Middleware de Autenticación
                    if (class_exists('AuthMiddleware')) {
                        $middleware = new AuthMiddleware();
                        if (!$middleware->handle($controller)) {
                            return;
                        }
                    }

                    if (method_exists($controllerInstance, $action)) {
                        call_user_func_array([$controllerInstance, $action], $params);
                        return;
                    } else {
                        error_log("Acción '$action' no encontrada en $controller");
                    }
                } else {
                    error_log("Clase '$controller' no definida en $controllerFile");
                }
            } else {
                error_log("Archivo de controlador no encontrado: $controllerFile");
            }
        }

        $this->notFound();
    }

    /**
     * Página no encontrada
     */
    private function notFound()
    {
        http_response_code(404);
        echo "<h1>Error 404 - Página no encontrada</h1>";
        echo "<p>La ruta solicitada no existe.</p>";
        echo "<p><a href='/dominican_travel/'>Volver al inicio</a></p>";
        exit;
    }

    /**
     * Eliminar variables de query string
     */
    private function removeQueryStringVariables($url)
    {
        if ($url != '') {
            $parts = explode('?', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }
}
