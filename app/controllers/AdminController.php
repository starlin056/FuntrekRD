<?php
// app/controllers/AdminController.php

class AdminController
{
    protected $userModel;
    protected $packageModel;
    protected $excursionModel;
    protected $transferModel;
    protected $bookingModel;
    protected $customRequestModel;

    protected $settingModel;
    protected $quotationModel;

    public function __construct()
    {
        $this->checkAdminAuth();

        $this->userModel          = new User();
        $this->packageModel       = new Package();
        $this->excursionModel     = new Excursion();
        $this->transferModel      = new Transfer();
        $this->bookingModel       = new Booking();
        $this->customRequestModel = new CustomRequest();
        $this->settingModel       = new Setting();
        $this->quotationModel     = new Quotation();
    }

    /* =========================
       SETTINGS (AGENCY INFO)
    ========================== */

    public function settings()
    {
        $settings = $this->settingModel->getAll();
        $this->view('admin/settings', [
            'title'    => 'Configuración de la Agencia',
            'settings' => $settings
        ]);
    }

    public function settings_update()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $logo = $this->handleImageUpload('company_logo', 'agency');
            
            $data = [
                'company_name'    => trim($_POST['company_name'] ?? ''),
                'company_address' => trim($_POST['company_address'] ?? ''),
                'company_phone'   => trim($_POST['company_phone'] ?? ''),
                'company_email'   => trim($_POST['company_email'] ?? ''),
                'default_tax_rate' => (float)($_POST['default_tax_rate'] ?? 18.00)
            ];

            if ($logo) {
                $data['company_logo'] = $logo;
            }

            if ($this->settingModel->updateSettings($data)) {
                $_SESSION['success'] = 'Configuración actualizada correctamente.';
            } else {
                $_SESSION['error'] = 'Error al actualizar la configuración.';
            }
        }
        $this->redirect('/admin/settings');
    }

    /* =========================
       QUOTATIONS (COTIZACIONES)
    ========================== */

    public function quotations()
    {
        $quotations = $this->quotationModel->findAll([], 'created_at DESC');
        $this->view('admin/quotations/index', [
            'title'      => 'Gestión de Cotizaciones',
            'quotations' => $quotations
        ]);
    }

    public function quotations_create()
    {
        $packages = $this->packageModel->findAll(['active' => 1]);
        $excursions = $this->excursionModel->findAll(['active' => 1]);
        $transfers = $this->transferModel->findAll(['active' => 1]);
        $settings = $this->settingModel->getAll();
        $clients = $this->userModel->getClients(500); // Fetch up to 500 clients

        $this->view('admin/quotations/create', [
            'title'      => 'Nueva Cotización',
            'packages'   => $packages,
            'excursions' => $excursions,
            'transfers'  => $transfers,
            'settings'   => $settings,
            'clients'    => $clients,
            'nextQuoteNumber' => $this->quotationModel->generateQuoteNumber()
        ]);
    }

    public function quotations_store()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'quote_number'   => $_POST['quote_number'],
                'customer_name'  => trim($_POST['customer_name']),
                'customer_email' => trim($_POST['customer_email']),
                'customer_phone' => trim($_POST['customer_phone']),
                'agent_name'     => trim($_POST['agent_name'] ?? ''),
                'travel_date'    => $_POST['travel_date'],
                'is_tax_enabled' => isset($_POST['is_tax_enabled']) ? 1 : 0,
                'subtotal'       => (float)$_POST['subtotal'],
                'tax_amount'     => (float)$_POST['tax_amount'],
                'total_price'    => (float)$_POST['total_price'],
                'notes'          => trim($_POST['notes']),
                'status'         => 'draft'
            ];

            $items = [];
            if (!empty($_POST['items'])) {
                foreach ($_POST['items'] as $item) {
                    $items[] = [
                        'item_type'   => $item['type'],
                        'item_id'     => !empty($item['id']) ? $item['id'] : null,
                        'description' => $item['description'],
                        'quantity'    => (int)$item['quantity'],
                        'unit_price'  => (float)$item['unit_price'],
                        'total'       => (float)$item['total']
                    ];
                }
            }

            if ($this->quotationModel->createFull($data, $items)) {
                $_SESSION['success'] = 'Cotización creada correctamente.';
                $this->redirect('/admin/quotations');
            } else {
                $_SESSION['error'] = 'Error al crear la cotización.';
                $this->redirect('/admin/quotations_create');
            }
        }
    }

    public function quotations_delete($id)
    {
        if ($this->quotationModel->deleteFull($id)) {
            $_SESSION['success'] = 'Cotización eliminada correctamente.';
        } else {
            $_SESSION['error'] = 'Error al eliminar la cotización.';
        }
        $this->redirect('/admin/quotations');
    }

    public function quotations_print($id)
    {
        $quotation = $this->quotationModel->getWithItems($id);
        $settings = $this->settingModel->getAll();

        if (!$quotation) $this->redirect('/admin/quotations');

        $this->view('admin/quotations/print', [
            'title'     => 'Cotización ' . $quotation['quote_number'],
            'quotation' => $quotation,
            'settings'  => $settings
        ]);
    }

    public function airport_sign($id = null)
    {
        $quotation = $id ? $this->quotationModel->findById($id) : null;
        $settings = $this->settingModel->getAll();

        if (!$quotation) {
            // Default data for standalone tool
            $quotation = [
                'customer_name' => 'NOMBRE DEL CLIENTE'
            ];
        }

        $this->view('admin/quotations/airport_sign', [
            'title'     => 'Letrero de Aeropuerto',
            'quotation' => $quotation,
            'settings'  => $settings
        ]);
    }

    /* =========================
       DASHBOARD
    ========================== */

    public function index()
    {
        $this->redirect('/admin/packages');
    }

    public function dashboard()
    {
        $this->view('admin/dashboard', [
            'title'                   => 'Panel de Administración',
            'stats'                   => $this->getStats(),
            'recentBookings'          => $this->bookingModel->getRecentBookings(10),
            'totalPackages'           => $this->packageModel->count(['active' => 1]),
            'totalExcursions'         => $this->excursionModel->count(['active' => 1]),
            'totalTransfers'          => $this->transferModel->count(['active' => 1]),
            'totalClients'            => $this->userModel->count(['role' => 'client', 'active' => 1]),

            // ✅ Solicitudes personalizadas - usando modelo correcto
            'pendingCustomRequests'   => $this->customRequestModel->countByStatus('pending'),
            'recentCustomRequests'    => $this->customRequestModel->getAllWithFilters(limit: 5),
        ]);
    }

    /* =========================
       CLIENTS
    ========================== */

    public function clients()
    {
        $clients = $this->userModel->getClients();
        $this->view('admin/clients/index', [
            'title'   => 'Gestión de Clientes',
            'clients' => $clients
        ]);
    }

    public function client_view($id)
    {
        $client = $this->userModel->findById($id);
        if (!$client || ($client['role'] !== 'client' && $client['role'] !== 'agent')) {
            $this->redirect('/admin/clients');
        }
        $this->view('admin/clients/view', [
            'title'  => 'Perfil de Cliente',
            'client' => $client
        ]);
    }

    public function client_deactivate($id)
    {
        $this->userModel->update($id, ['active' => 0]);
        $_SESSION['success'] = 'Cliente desactivado.';
        $this->redirect('/admin/clients');
    }

    public function client_activate($id)
    {
        $this->userModel->update($id, ['active' => 1]);
        $_SESSION['success'] = 'Cliente reactivado.';
        $this->redirect('/admin/clients');
    }

    /* =========================
       PACKAGES
    ========================== */

    public function packages()
    {
        $packages = $this->packageModel->findAll([], 'created_at DESC');
        $this->view('admin/packages/index', [
            'title'    => 'Gestión de Paquetes',
            'packages' => $packages
        ]);
    }

    private function handleMultiImageUpload($inputName, $folder)
    {
        if (empty($_FILES[$inputName]['name'][0])) return null;

        $uploadDir    = APP_ROOT . '/public/assets/uploads/' . $folder . '/';
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $maxSize      = 5 * 1024 * 1024;
        $uploaded     = [];

        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

        $files = $_FILES[$inputName];
        $count = is_array($files['name']) ? count($files['name']) : 1;

        for ($i = 0; $i < $count; $i++) {
            $error    = is_array($files['error']) ? $files['error'][$i] : $files['error'];
            $tmpName  = is_array($files['tmp_name']) ? $files['tmp_name'][$i] : $files['tmp_name'];
            $type     = is_array($files['type']) ? $files['type'][$i] : $files['type'];
            $size     = is_array($files['size']) ? $files['size'][$i] : $files['size'];
            $origName = is_array($files['name']) ? $files['name'][$i] : $files['name'];

            if ($error !== UPLOAD_ERR_OK || empty($origName)) continue;
            if (!in_array($type, $allowedTypes)) continue;
            if ($size > $maxSize) continue;

            $ext      = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
            $filename = uniqid() . '.' . $ext;
            $target   = $uploadDir . $filename;

            if (move_uploaded_file($tmpName, $target)) {
                $uploaded[] = $filename;
            }
        }

        return !empty($uploaded) ? json_encode($uploaded) : null;
    }

    public function packages_create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image   = $this->handleImageUpload('image', 'packages');
            $gallery = $this->handleMultiImageUpload('gallery_files', 'packages');

            if ($image === false && !empty($_FILES['image']['name'])) {
                $error = 'Error al subir la imagen principal.';
            } elseif ($gallery === false) {
                $error = 'Error al procesar la galería de imágenes.';
            } else {
                $data = $this->preparePackageData($_POST, $image, $gallery);
                if ($this->packageModel->create($data)) {
                    $_SESSION['success'] = 'Paquete creado exitosamente.';
                    $this->redirect('/admin/packages');
                } else {
                    $error = 'Error al guardar el paquete.';
                }
            }
        }

        $this->view('admin/packages/create', [
            'title' => 'Crear Nuevo Paquete',
            'error' => $error ?? null
        ]);
    }

    public function packages_edit($id)
    {
        $package = $this->packageModel->findById($id);
        if (!$package) $this->redirect('/admin/packages');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image      = $this->handleImageUpload('image', 'packages');
            $newGallery = $this->handleMultiImageUpload('gallery_files', 'packages');

            if ($image === false && !empty($_FILES['image']['name'])) {
                $error = 'Error al subir la imagen principal.';
            } elseif ($newGallery === false) {
                $error = 'Error al procesar la galería de imágenes.';
            } else {
                if ($image === null) $image = $package['image'];
                $existingGallery = json_decode($package['gallery'] ?? '[]', true) ?: [];
                if (!empty($newGallery)) {
                    $newGalleryArr = json_decode($newGallery, true) ?: [];
                    $mergedGallery = json_encode(array_values(array_unique(array_merge($existingGallery, $newGalleryArr))));
                } else {
                    $mergedGallery = $package['gallery'];
                }

                // Procesar eliminaciones de la galería
                if (!empty($_POST['delete_gallery'])) {
                    $toDelete = (array)$_POST['delete_gallery'];
                    $currentGallery = json_decode($mergedGallery ?? '[]', true) ?: [];
                    $remaining = array_values(array_filter($currentGallery, fn($f) => !in_array($f, $toDelete)));
                    foreach ($toDelete as $f) {
                        $fp = APP_ROOT . '/public/assets/uploads/packages/' . basename($f);
                        if (file_exists($fp)) @unlink($fp);
                    }
                    $mergedGallery = json_encode($remaining);
                }

                $data = $this->preparePackageData($_POST, $image, $mergedGallery);
                if ($this->packageModel->update($id, $data)) {
                    $_SESSION['success'] = 'Paquete actualizado exitosamente.';
                    $this->redirect('/admin/packages');
                } else {
                    $error = 'Error al actualizar el paquete.';
                }
            }
        }

        $rawIncludes = $package['includes'] ?? '';
        $decodedInc = json_decode($rawIncludes, true);
        $package['included'] = is_array($decodedInc) ? $decodedInc : array_filter(array_map('trim', explode(',', $rawIncludes)));

        $package['gallery']  = json_decode($package['gallery'] ?? '[]', true) ?: [];

        $this->view('admin/packages/edit', [
            'title'   => 'Editar Paquete',
            'package' => $package,
            'error'   => $error ?? null
        ]);
    }

    public function packages_delete($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            $_SESSION['error'] = 'ID inválido.';
            $this->redirect('/admin/packages');
        }
        if ($this->packageModel->update($id, ['active' => 0])) {
            $_SESSION['success'] = 'Paquete desactivado exitosamente.';
        } else {
            $_SESSION['error'] = 'Error al desactivar el paquete.';
        }
        $this->redirect('/admin/packages');
    }

    public function packages_restore($id)
    {
        if ($this->packageModel->restore($id)) {
            $_SESSION['success'] = 'Paquete reactivado exitosamente.';
        } else {
            $_SESSION['error'] = 'Error al reactivar el paquete.';
        }
        $this->redirect('/admin/packages');
    }

    private function preparePackageData($postData, $image = null, $gallery = null)
    {
        return [
            'name'              => trim($postData['name'] ?? ''),
            'slug'              => $this->createSlug($postData['name'] ?? ''),
            'description'       => trim($postData['description'] ?? ''),
            'short_description' => substr(trim($postData['description'] ?? ''), 0, 500),
            'price'             => (float)($postData['price'] ?? 0),
            'price_type'        => in_array($postData['price_type'] ?? '', ['persona', 'paquete']) ? $postData['price_type'] : 'persona',
            'discount_price'    => !empty($postData['discount_price']) ? (float)$postData['discount_price'] : null,
            'days'              => (int)($postData['days'] ?? 1),
            'nights'            => (int)($postData['nights'] ?? 0),
            'category'          => in_array($postData['category'] ?? 'playa', ['playa', 'aventura', 'romantico', 'familiar', 'luxury', 'cultural', 'gastronomico', 'naturaleza', 'deporte', 'relax']) ? $postData['category'] : 'playa',
            'location'          => trim($postData['location'] ?? ''),
            'hotel_category'    => trim($postData['hotel_category'] ?? ''),
            'max_people'        => (int)($postData['max_people'] ?? 0),
            'image'             => $image,
            'gallery'           => $gallery,
            'includes'          => ($incArr = array_filter(array_map('trim', explode(',', $postData['included'] ?? $postData['includes'] ?? '')))) ? json_encode($incArr) : null,
            'featured'          => isset($postData['featured']) ? 1 : 0,
            'active'            => 1
        ];
    }

    /* =========================
       SOLICITUDES PERSONALIZADAS - CORREGIDO
    ========================== */



    /*
 * MÉTODOS NUEVOS para AdminController.php
 * Añadir dentro de la clase AdminController
 * (Reemplaza / complementa la sección SOLICITUDES PERSONALIZADAS)
 */
 
/* =========================
   SOLICITUDES PERSONALIZADAS
========================== */

    /**
     * Listado de solicitudes personalizadas
     */
    public function custom_excursion_requests()
    {
        $status = isset($_GET['status']) && $_GET['status'] !== '' ? $_GET['status'] : null;
        $search = isset($_GET['search']) && $_GET['search'] !== '' ? $_GET['search'] : null;

        $requests = $this->customRequestModel->getAllWithFilters(
            status: $status,
            search: $search,
            limit: 100
        );

        $counts = $this->customRequestModel->getCountsByStatus();

        $this->view('admin/custom-requests/index', [
            'title'    => 'Excursiones Personalizadas',
            'requests' => $requests,
            'counts'   => $counts,
            'filters'  => ['status' => $status, 'search' => $search]
        ]);
    }

    /**
     * Vista de detalle de una solicitud (página completa)
     */
    public function custom_request_view($id)
    {
        $id = (int)$id;
        if ($id <= 0) {
            $_SESSION['error'] = 'ID inválido.';
            $this->redirect('/admin/custom_excursion_requests');
        }

        $request = $this->customRequestModel->getWithActivityLog($id);

        if (!$request) {
            $_SESSION['error'] = 'Solicitud no encontrada.';
            $this->redirect('/admin/custom_excursion_requests');
        }

        $this->view('admin/custom-requests/view', [
            'title'   => 'Solicitud #' . $id . ' — ' . htmlspecialchars($request['customer_name'] ?? ''),
            'request' => $request,
        ]);
    }

    /**
     * Actualizar estado de la solicitud
     */
    public function updateCustomRequestStatus($id)
    {
        $id = (int)$id;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $id <= 0) {
            $this->redirect('/admin/custom_excursion_requests');
        }

        $valid  = ['pending', 'reviewing', 'approved', 'rejected'];
        $status = in_array($_POST['status'] ?? '', $valid) ? $_POST['status'] : 'pending';

        $this->customRequestModel->updateStatus(
            $id,
            $status,
            $_SESSION['user_id'] ?? null
        );

        $_SESSION['success'] = 'Estado de la solicitud actualizado.';

        // Si viene de la página de detalle, regresar a ella
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        if (strpos($referer, 'custom_request_view') !== false) {
            $this->redirect('/admin/custom_request_view/' . $id);
        }
        $this->redirect('/admin/custom_excursion_requests');
    }

    /**
     * Agregar nota interna / visible al cliente
     */
    public function custom_request_add_note($id)
    {
        $id = (int)$id;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $id <= 0) {
            $this->redirect('/admin/custom_excursion_requests');
        }

        $content  = trim($_POST['note_content'] ?? '');
        $visible  = !empty($_POST['visible_to_client']);
        $adminId  = (int)($_SESSION['user_id'] ?? 0);

        if (empty($content)) {
            $_SESSION['error'] = 'La nota no puede estar vacía.';
            $this->redirect('/admin/custom_request_view/' . $id);
        }

        $this->customRequestModel->addNote($id, $content, $adminId ?: null, $visible);

        $_SESSION['success'] = 'Nota guardada correctamente.';
        $this->redirect('/admin/custom_request_view/' . $id);
    }

    /**
     *  Guardar requerimientos (checklist) - CORREGIDO
     */
    public function custom_request_save_requirements($id)
    {
        $id = (int)$id;

        // Validar método y ID
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $id <= 0) {
            $_SESSION['error'] = 'Solicitud inválida.';
            $this->redirect('/admin/custom_excursion_requests');
            return;
        }

        // Validar y filtrar requerimientos
        $valid = ['transporte', 'guia', 'comida', 'entradas', 'seguro', 'equipo', 'hospedaje', 'traslados'];

        // Obtener array de requerimientos del POST
        $rawRequirements = $_POST['requirements'] ?? [];

        // Filtrar solo valores válidos y reindexar array
        $requirements = array_values(array_filter(
            is_array($rawRequirements) ? $rawRequirements : [],
            fn($r) => in_array($r, $valid, true)
        ));

        try {
            //  Usar modelo con método corregido
            $this->customRequestModel->updateRequirements($id, $requirements);
            $_SESSION['success'] = 'Requerimientos guardados correctamente.';
        } catch (Exception $e) {
            error_log('[Admin Error] save_requirements: ' . $e->getMessage());
            $_SESSION['error'] = 'Error al guardar requerimientos.';
        }

        // Redirigir a vista de detalle
        $this->redirect('/admin/custom_request_view/' . $id);
    }

    /**
     * Marcar como contactado
     */
    public function custom_request_mark_contacted($id)
    {
        $id = (int)$id;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $id <= 0) {
            $this->redirect('/admin/custom_excursion_requests');
        }

        $adminId = (int)($_SESSION['user_id'] ?? 0);
        $this->customRequestModel->markAsContacted($id, 'admin_panel', $adminId ?: null);

        $_SESSION['success'] = 'Cliente marcado como contactado.';
        $this->redirect('/admin/custom_request_view/' . $id);
    }

    /**
     * Enviar cotización al cliente (precio + archivo adjunto + email)
     */
    public function custom_request_send_quote($id)
    {
        $id = (int)$id;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || $id <= 0) {
            $this->redirect('/admin/custom_excursion_requests');
        }

        $price   = (float)($_POST['quoted_price'] ?? 0);
        $message = trim($_POST['quote_message'] ?? '');
        $adminId = (int)($_SESSION['user_id'] ?? 0);

        if ($price <= 0) {
            $_SESSION['error'] = 'El precio debe ser mayor a cero.';
            $this->redirect('/admin/custom_request_view/' . $id);
        }

        // Subida de archivo adjunto
        $filePath = null;
        if (!empty($_FILES['proposal']['tmp_name'])) {
            $uploadDir = APP_ROOT . '/public/assets/uploads/proposals/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);

            $allowedExts = ['pdf', 'doc', 'docx'];
            $ext = strtolower(pathinfo($_FILES['proposal']['name'], PATHINFO_EXTENSION));

            if (!in_array($ext, $allowedExts)) {
                $_SESSION['error'] = 'Formato de archivo no permitido (solo PDF, DOC, DOCX).';
                $this->redirect('/admin/custom_request_view/' . $id);
            }
            if ($_FILES['proposal']['size'] > 5 * 1024 * 1024) {
                $_SESSION['error'] = 'El archivo supera 5MB.';
                $this->redirect('/admin/custom_request_view/' . $id);
            }

            $filename = 'propuesta_' . $id . '_' . uniqid() . '.' . $ext;
            $target   = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['proposal']['tmp_name'], $target)) {
                $filePath = 'assets/uploads/proposals/' . $filename;
            }
        }

        // Guardar cotización en BD
        $this->customRequestModel->attachProposal($id, $filePath, $price, $adminId ?: null);

        // Enviar email al cliente
        $request = $this->customRequestModel->findById($id);
        if ($request && !empty($request['customer_email'])) {
            require_once APP_ROOT . '/app/core/Email.php';
            $email = new Email();
            $email->sendQuoteToClient($request, $price, $filePath, $message);
        }

        $_SESSION['success'] = 'Cotización enviada al cliente correctamente.';
        $this->redirect('/admin/custom_request_view/' . $id);
    }
 
// ── API ENDPOINT (para búsqueda AJAX interna si se necesita) ──

    /**
     * API: devuelve datos JSON de una solicitud
     * Ruta: GET /api/custom-requests/show/{id}
     */
    public function api_custom_request_show($id)
    {
        header('Content-Type: application/json');
        $id = (int)$id;
        if ($id <= 0) {
            http_response_code(400);
            echo json_encode(['error' => 'ID inválido']);
            return;
        }
        $data = $this->customRequestModel->getWithActivityLog($id);
        if (!$data) {
            http_response_code(404);
            echo json_encode(['error' => 'No encontrado']);
            return;
        }
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
    }


    /* =========================
       EXCURSIONS
    ========================== */

    public function excursions()
    {
        $excursions = $this->excursionModel->findAll([], 'created_at DESC');
        $this->view('admin/excursions/index', [
            'title'      => 'Gestión de Excursiones',
            'excursions' => $excursions
        ]);
    }

    public function excursions_create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image   = $this->handleImageUpload('image', 'excursions');
            $gallery = $this->handleMultiImageUpload('gallery_files', 'excursions');

            if ($image === false && !empty($_FILES['image']['name'])) {
                $error = 'Error al subir la imagen principal.';
            } else {
                $data = $this->prepareExcursionData($_POST, $image, $gallery);
                if ($this->excursionModel->create($data)) {
                    $_SESSION['success'] = 'Excursión creada exitosamente.';
                    $this->redirect('/admin/excursions');
                } else {
                    $error = 'Error al guardar la excursión.';
                }
            }
        }

        $this->view('admin/excursions/create', [
            'title' => 'Crear Nueva Excursión',
            'error' => $error ?? null
        ]);
    }

    public function excursions_edit($id)
    {
        $excursion = $this->excursionModel->findById($id);
        if (!$excursion) $this->redirect('/admin/excursions');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image      = $this->handleImageUpload('image', 'excursions');
            $newGallery = $this->handleMultiImageUpload('gallery_files', 'excursions');

            if ($image === false && !empty($_FILES['image']['name'])) {
                $error = 'Error al subir la imagen.';
            } else {
                if ($image === null) $image = $excursion['image'];
                $existingGallery = json_decode($excursion['gallery'] ?? '[]', true) ?: [];
                if (!empty($newGallery)) {
                    $newGalleryArr = json_decode($newGallery, true) ?: [];
                    $mergedGallery = json_encode(array_values(array_unique(array_merge($existingGallery, $newGalleryArr))));
                } else {
                    $mergedGallery = $excursion['gallery'];
                }

                // Procesar eliminaciones de la galería
                if (!empty($_POST['delete_gallery'])) {
                    $toDelete = (array)$_POST['delete_gallery'];
                    $currentGallery = json_decode($mergedGallery ?? '[]', true) ?: [];
                    $remaining = array_values(array_filter($currentGallery, fn($f) => !in_array($f, $toDelete)));
                    foreach ($toDelete as $f) {
                        $fp = APP_ROOT . '/public/assets/uploads/excursions/' . basename($f);
                        if (file_exists($fp)) @unlink($fp);
                    }
                    $mergedGallery = json_encode($remaining);
                }

                $data = $this->prepareExcursionData($_POST, $image, $mergedGallery);
                if ($this->excursionModel->update($id, $data)) {
                    $_SESSION['success'] = 'Excursión actualizada exitosamente.';
                    $this->redirect('/admin/excursions');
                } else {
                    $error = 'Error al actualizar la excursión.';
                }
            }
        }

        $rawIncludes = $excursion['includes'] ?? '';
        $decodedInc = json_decode($rawIncludes, true);
        $excursion['includes'] = is_array($decodedInc) ? $decodedInc : array_filter(array_map('trim', explode(',', $rawIncludes)));

        $rawReq = $excursion['requirements'] ?? '';
        $decodedReq = json_decode($rawReq, true);
        $excursion['requirements'] = is_array($decodedReq) ? $decodedReq : array_filter(array_map('trim', explode(',', $rawReq)));

        $excursion['gallery']      = json_decode($excursion['gallery'] ?? '[]', true) ?: [];

        $this->view('admin/excursions/edit', [
            'title'     => 'Editar Excursión',
            'excursion' => $excursion,
            'error'     => $error ?? null
        ]);
    }

    public function excursions_delete($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            $_SESSION['error'] = 'ID inválido.';
            $this->redirect('/admin/excursions');
        }
        if ($this->excursionModel->delete($id)) {
            $_SESSION['success'] = 'Excursión desactivada exitosamente.';
        } else {
            $_SESSION['error'] = 'Error al desactivar la excursión.';
        }
        $this->redirect('/admin/excursions');
    }

    public function excursions_restore($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            $_SESSION['error'] = 'ID inválido.';
            $this->redirect('/admin/excursions');
        }
        if ($this->excursionModel->update($id, ['active' => 1])) {
            $_SESSION['success'] = 'Excursión reactivada exitosamente.';
        } else {
            $_SESSION['error'] = 'Error al reactivar la excursión.';
        }
        $this->redirect('/admin/excursions');
    }

    private function prepareExcursionData($postData, $image = null, $gallery = null)
    {
        return [
            'name'         => trim($postData['name'] ?? ''),
            'location'     => trim($postData['location'] ?? ''),
            'description'  => trim($postData['description'] ?? ''),
            'duration'     => trim($postData['duration'] ?? ''),
            'price'        => (float)($postData['price'] ?? 0),
            'price_type'   => in_array($postData['price_type'] ?? '', ['persona', 'paquete']) ? $postData['price_type'] : 'persona',
            'category'     => trim($postData['category'] ?? ''),
            'includes'     => ($incArr = array_filter(array_map('trim', explode(',', $postData['includes'] ?? '')))) ? json_encode($incArr) : null,
            'requirements' => ($reqArr = array_filter(array_map('trim', explode(',', $postData['requirements'] ?? '')))) ? json_encode($reqArr) : null,
            'image'        => $image,
            'gallery'      => $gallery,
            'featured'     => isset($postData['featured']) ? 1 : 0,
            'max_people'   => (int)($postData['max_people'] ?? 0),
            'active'       => 1,
        ];
    }

    /* =========================
       TRANSFERS
    ========================== */

    public function transfers()
    {
        $this->view('admin/transfers/index', [
            'title'     => 'Gestión de Transfers',
            'transfers' => $this->transferModel->findAll(['active' => 1], 'created_at DESC')
        ]);
    }

    public function transfers_create()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image   = $this->handleImageUpload('image', 'transfers');
            $gallery = $this->handleMultiImageUpload('gallery_files', 'transfers');

            if ($image === false && !empty($_FILES['image']['name'])) {
                $error = 'Error al subir la imagen.';
            } elseif ($gallery === false) {
                $error = 'Error al procesar la galería de imágenes.';
            } else {
                $data = $this->prepareTransferData($_POST, $image, $gallery);
                if ($this->transferModel->create($data)) {
                    $_SESSION['success'] = 'Transfer creado exitosamente.';
                    $this->redirect('/admin/transfers');
                } else {
                    $error = 'Error al guardar el transfer.';
                }
            }
        }
        $this->view('admin/transfers/create', [
            'title' => 'Crear Nuevo Transfer',
            'error' => $error ?? null
        ]);
    }

    public function transfers_edit($id)
    {
        $transfer = $this->transferModel->findById($id);
        if (!$transfer) $this->redirect('/admin/transfers');

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $image      = $this->handleImageUpload('image', 'transfers');
            $newGallery = $this->handleMultiImageUpload('gallery_files', 'transfers');

            if ($image === false && !empty($_FILES['image']['name'])) {
                $error = 'Error al subir la imagen.';
            } elseif ($newGallery === false) {
                $error = 'Error al procesar la galería de imágenes.';
            } else {
                if ($image === null) $image = $transfer['image'];

                // Merge existing gallery with new uploads
                $existingGallery = json_decode($transfer['gallery'] ?? '[]', true) ?: [];
                if (!empty($newGallery)) {
                    $newGalleryArr = json_decode($newGallery, true) ?: [];
                    $mergedGallery = json_encode(array_values(array_unique(array_merge($existingGallery, $newGalleryArr))));
                } else {
                    $mergedGallery = $transfer['gallery'];
                }

                // Handle gallery image deletions
                if (!empty($_POST['delete_gallery'])) {
                    $toDelete = (array)$_POST['delete_gallery'];
                    $remaining = array_values(array_filter(
                        json_decode($mergedGallery ?? '[]', true) ?: [],
                        fn($f) => !in_array($f, $toDelete)
                    ));
                    foreach ($toDelete as $f) {
                        $fp = APP_ROOT . '/public/assets/uploads/transfers/' . basename($f);
                        if (file_exists($fp)) @unlink($fp);
                    }
                    $mergedGallery = json_encode($remaining);
                }

                $data = $this->prepareTransferData($_POST, $image, $mergedGallery);
                if ($this->transferModel->update($id, $data)) {
                    $_SESSION['success'] = 'Transfer actualizado exitosamente.';
                    $this->redirect('/admin/transfers');
                } else {
                    $error = 'Error al actualizar el transfer.';
                }
            }
        }

        $transfer['gallery'] = json_decode($transfer['gallery'] ?? '[]', true) ?: [];

        $this->view('admin/transfers/edit', [
            'title'    => 'Editar Transfer',
            'transfer' => $transfer,
            'error'    => $error ?? null
        ]);
    }

    public function transfers_delete($id)
    {
        if (!is_numeric($id) || $id <= 0) {
            $_SESSION['error'] = 'ID inválido.';
            $this->redirect('/admin/transfers');
        }
        if ($this->transferModel->delete($id)) {
            $_SESSION['success'] = 'Transfer eliminado exitosamente.';
        } else {
            $_SESSION['error'] = 'Error al eliminar el transfer.';
        }
        $this->redirect('/admin/transfers');
    }

    private function prepareTransferData($postData, $image = null, $gallery = null)
    {
        return [
            'name'           => trim($postData['name'] ?? ''),
            'from_location'  => trim($postData['from_location'] ?? ''),
            'to_location'    => trim($postData['to_location'] ?? ''),
            'vehicle_type'   => trim($postData['vehicle_type'] ?? ''),
            'max_passengers' => (int)($postData['max_passengers'] ?? 0),
            'price'          => (float)($postData['price'] ?? 0),
            'price_type'     => in_array($postData['price_type'] ?? '', ['persona', 'paquete']) ? $postData['price_type'] : 'paquete',
            'description'    => trim($postData['description'] ?? ''),
            'image'          => $image,
            'gallery'        => $gallery,
            'active'         => isset($postData['active']) ? 1 : 1
        ];
    }

    /* =========================
       BOOKINGS
    ========================== */

    public function bookings()
    {
        $bookings = $this->bookingModel->getAllBookings(100);
        $this->view('admin/bookings/index', [
            'title' => 'Gestión de Reservas',
            'bookings' => $bookings
        ]);
    }

    public function updateBookingStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validStatuses = ['pending', 'confirmed', 'cancelled', 'completed'];
            $status = in_array($_POST['status'], $validStatuses) ? $_POST['status'] : 'pending';
            $this->bookingModel->update($id, ['status' => $status]);
            $_SESSION['success'] = 'Estado de reserva actualizado.';

            if (defined('SEND_EMAILS') && SEND_EMAILS) {
                $booking = $this->bookingModel->getBookingWithServiceDetails($id);
                if ($booking) {
                    require_once APP_ROOT . '/app/core/Email.php';
                    $email = new Email();
                    if ($status === 'confirmed') {
                        $email->sendBookingConfirmation($booking);
                    } elseif ($status === 'cancelled') {
                        $email->sendBookingCancellation($booking);
                    }
                }
            }
        }
        $this->redirect('/admin/bookings');
    }

    public function updatePaymentStatus($id)
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $validStatuses = ['pending', 'paid', 'refunded'];
            $paymentStatus = in_array($_POST['payment_status'], $validStatuses) ? $_POST['payment_status'] : 'pending';
            $this->bookingModel->update($id, ['payment_status' => $paymentStatus]);
            $_SESSION['success'] = 'Estado de pago actualizado.';

            if (defined('SEND_EMAILS') && SEND_EMAILS) {
                $booking = $this->bookingModel->getBookingWithServiceDetails($id);
                if ($booking) {
                    require_once APP_ROOT . '/app/core/Email.php';
                    $email = new Email();
                    if ($paymentStatus === 'paid') {
                        $email->sendPaymentConfirmation($booking);
                    } elseif ($paymentStatus === 'refunded') {
                        $email->sendRefundNotification($booking);
                    }
                }
            }
        }
        $this->redirect('/admin/bookings');
    }

    public function bookingDetail($id)
    {
        $booking = $this->bookingModel->getBookingWithServiceDetails($id);
        if (!$booking) {
            $_SESSION['error'] = 'Reserva no encontrada.';
            $this->redirect('/admin/bookings');
        }
        $this->view('admin/bookings/detail', [
            'title' => 'Detalle de Reserva #' . htmlspecialchars($booking['booking_reference']),
            'booking' => $booking
        ]);
    }

    /* =========================
       HELPERS
    ========================== */

    private function handleImageUpload($inputName, $folder)
    {
        if (!isset($_FILES[$inputName]) || $_FILES[$inputName]['error'] === UPLOAD_ERR_NO_FILE) return null;
        $uploadDir = APP_ROOT . '/public/assets/uploads/' . $folder . '/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
        $file = $_FILES[$inputName];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp'];
        $maxSize = 5 * 1024 * 1024;
        if (!in_array($file['type'], $allowedTypes)) return false;
        if ($file['size'] > $maxSize) return false;
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . strtolower($extension);
        $targetPath = $uploadDir . $filename;
        if (move_uploaded_file($file['tmp_name'], $targetPath)) return $filename;
        return false;
    }

    private function createSlug($text)
    {
        return trim(preg_replace('/[^a-z0-9]+/', '-', strtolower($text)), '-');
    }

    private function getStats()
    {
        return [
            'todayBookings'   => $this->bookingModel->countTodayBookings(),
            'monthBookings'   => $this->bookingModel->countMonthBookings(),
            'pendingBookings' => $this->bookingModel->countPendingBookings(),
            'totalRevenue'    => $this->bookingModel->getTotalRevenue()
        ];
    }

    private function timeAgo($datetime)
    {
        $time = time() - strtotime($datetime);
        if ($time < 60) return 'Hace unos segundos';
        if ($time < 3600) return 'Hace ' . floor($time / 60) . ' min';
        if ($time < 86400) return 'Hace ' . floor($time / 3600) . ' h';
        if ($time < 2592000) return 'Hace ' . floor($time / 86400) . ' días';
        if ($time < 31536000) return 'Hace ' . floor($time / 2592000) . ' meses';
        return 'Hace ' . floor($time / 31536000) . ' años';
    }

    private function checkAdminAuth()
    {
        if (empty($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
            $this->redirect('/auth/login');
        }
    }

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
