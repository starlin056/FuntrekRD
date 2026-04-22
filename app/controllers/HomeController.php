<?php

class HomeController extends Controller
{
    public function index()
    {
        $packageModel = new Package();

        // Paquetes destacados
        $featuredPackages = $packageModel->getFeatured(6);

        // Destinos
        $destinations = $this->getDestinations();

        // Enviar datos a la vista
        $this->view('home/index', [
            'featuredPackages' => $featuredPackages,
            'destinations'     => $destinations
        ]);
    }

    /* =====================================================
       PREPARAR DESTINOS PARA LA VISTA
    ===================================================== */
    private function getDestinations()
    {
        $packageModel = new Package();
        $rows = $packageModel->getDestinations();

        $destinations = [];

        foreach ($rows as $row) {
            $image = !empty($row['image'])
                ? APP_URL . '/assets/uploads/packages/' . $row['image']
                : 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?auto=format&fit=crop&w=600&q=80';

            $destinations[] = [
                'name'  => $row['location'],
                'image' => $image,
                'count' => $row['count']
            ];
        }

        return $destinations;
    }

    protected function translate($key, $default = '')
    {
        return Translator::getInstance()->get($key, $default);
    }
}
