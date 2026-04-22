<?php

class BrokersController
{
    public function index()
    {
        $this->view('brokers/index', [
            'title' => 'Brokers - Dominican Travel'
        ]);
    }

    protected function view($view, $data = [])
    {
        extract($data);
        $viewPath = APP_ROOT . '/app/views/' . $view . '.php';

        require_once APP_ROOT . '/app/views/layouts/header.php';
        require_once APP_ROOT . '/app/views/layouts/navigation.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            echo "<h1>Vista no encontrada: {$view}</h1>";
        }

        require_once APP_ROOT . '/app/views/layouts/footer.php';
    }
}
