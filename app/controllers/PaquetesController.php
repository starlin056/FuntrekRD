<?php

class PaquetesController
{
    private $packageModel;

    public function __construct()
    {
        $this->packageModel = new Package();
    }

    public function index()
    {
        // Obtener paquetes activos y destacados
        $packages = $this->packageModel->findAll(['active' => 1], 'featured DESC, created_at DESC');

        // Decodificar campos JSON para la vista
        foreach ($packages as &$package) {
            $package['includes'] = json_decode($package['includes'] ?? '[]', true) ?: [];
            $package['gallery']  = json_decode($package['gallery']  ?? '[]', true) ?: [];
        }

        $data = [
            'title'    => 'Paquetes de Viaje - República Dominicana',
            'packages' => $packages
        ];

        $this->view('packages/index', $data);
    }

    public function ver($id)
    {
        // Buscar paquete por ID
        $package = $this->packageModel->findById((int)$id);

        if (!$package) {
            die("Paquete no encontrado");
        }

        // Decodificar campos JSON
        $package['includes'] = json_decode($package['includes'] ?? '[]', true) ?: [];
        $package['gallery']  = json_decode($package['gallery'] ?? '[]', true) ?: [];

        $data = [
            'title'   => $package['name'] ?? 'Detalle del paquete',
            'package' => $package
        ];

        $this->view('packages/ver', $data);
    }

    protected function view($view, $data = [])
    {
        extract($data);
        $viewPath = APP_ROOT . '/app/views/' . $view . '.php';

        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("Vista no encontrada: " . $viewPath);
        }
    }


    protected function translate($key, $default = '')
    {
        return Translator::getInstance()->get($key, $default);
    }
}
