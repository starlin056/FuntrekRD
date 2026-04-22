<?php

class TransfersController
{
    protected $transferModel;

    public function __construct()
    {
        $this->transferModel = new Transfer();
    }

    /**
     * Vista pública de transfers (cliente)
     * URL: /transfers
     */
    public function index()
    {
        $transfers = $this->transferModel->getAllActive();

        if (!is_array($transfers)) {
            $transfers = [];
        }

        // Normalizar datos para que el buscador JS funcione correctamente
        foreach ($transfers as &$tr) {
            $tr['from_location'] = $tr['from_location'] ?? '';
            $tr['to_location']   = $tr['to_location']   ?? '';
            $tr['vehicle_type']  = $tr['vehicle_type']  ?? 'Privado';
            $tr['max_passengers'] = (int)($tr['max_passengers'] ?? 0);
            $tr['price']         = (float)($tr['price'] ?? 0);
            $tr['active']        = (int)($tr['active'] ?? 1);
        }
        unset($tr);

        $this->view('transfers/index', [
            'transfers' => $transfers,
            'title'     => 'Transfers - Dominican Travel'
        ]);
    }

    /**
     * Vista detalle de un transfer (opcional para reservas)
     * URL: /transfers/show/{id}
     */
    public function show($id)
    {
        $transfer = $this->transferModel->findById((int)$id);

        if (!$transfer || !$transfer['active']) {
            $this->redirect('/');
        }

        $this->view('transfers/show', [
            'transfer' => $transfer
        ]);
    }

    /* =========================
       HELPERS
    ========================== */

    protected function view($view, $data = [])
    {
        extract($data);
        require APP_ROOT . "/app/views/{$view}.php";
    }

    protected function redirect($url)
    {
        header("Location: " . APP_URL . $url);
        exit;
    }

    protected function translate($key, $default = '')
    {
        return Translator::getInstance()->get($key, $default);
    }
}
