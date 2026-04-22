<?php
// app/controllers/CustomRequestsApiController.php

class CustomRequestsApiController extends Controller
{
    protected $model;

    public function __construct()
    {
        // ✅ Verificar autenticación de admin
        if (empty($_SESSION['logged_in']) || $_SESSION['user_role'] !== 'admin') {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'No autorizado']);
            exit;
        }
        $this->model = new CustomRequest();
    }

    /**
     * GET /api/custom-requests/show/{id}
     */
    public function show($id = null)
    {
        header('Content-Type: application/json');

        if (!$id || !is_numeric($id)) {
            http_response_code(400);
            echo json_encode(['error' => 'ID inválido']);
            return;
        }

        try {
            $data = $this->model->getWithActivityLog((int)$id);
            if (!$data) {
                http_response_code(404);
                echo json_encode(['error' => 'Solicitud no encontrada']);
                return;
            }
            echo json_encode($data, JSON_UNESCAPED_UNICODE);
        } catch (Exception $e) {
            error_log('[API Error] show: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error interno']);
        }
    }

    /**
     * POST /api/custom-requests/{id}/addNote
     */
    public function addNote($id = null)
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        $input = json_decode(file_get_contents('php://input'), true);
        if (empty($input['content'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Contenido requerido']);
            return;
        }
        try {
            $noteId = $this->model->addNote(
                (int)$id,
                trim($input['content']),
                $_SESSION['user_id'] ?? null,
                !empty($input['visible_to_client'])
            );
            echo json_encode(['success' => true, 'note_id' => $noteId]);
        } catch (Exception $e) {
            error_log('[API Error] addNote: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error al guardar']);
        }
    }

    /**
     * POST /api/custom-requests/{id}/updateRequirements
     */
    public function updateRequirements($id = null)
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        $input = json_decode(file_get_contents('php://input'), true);
        try {
            $result = $this->model->updateRequirements((int)$id, $input['requirements'] ?? []);
            echo json_encode(['success' => (bool)$result]);
        } catch (Exception $e) {
            error_log('[API Error] updateRequirements: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error al actualizar']);
        }
    }

    /**
     * POST /api/custom-requests/{id}/markContacted
     */
    public function markContacted($id = null)
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        try {
            $this->model->markAsContacted((int)$id, 'admin_panel', $_SESSION['user_id'] ?? null);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            error_log('[API Error] markContacted: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error']);
        }
    }

    /**
     * POST /api/custom-requests/{id}/quote
     */
    public function quote($id = null)
    {
        header('Content-Type: application/json');
        if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !$id) {
            http_response_code(405);
            echo json_encode(['error' => 'Método no permitido']);
            return;
        }
        $price = $_POST['quoted_price'] ?? null;
        $filePath = null;

        if (!empty($_FILES['proposal']['tmp_name'])) {
            $uploadDir = APP_ROOT . '/public/assets/uploads/proposals/';
            if (!is_dir($uploadDir)) mkdir($uploadDir, 0755, true);
            $ext = strtolower(pathinfo($_FILES['proposal']['name'], PATHINFO_EXTENSION));
            if (!in_array($ext, ['pdf', 'doc', 'docx'])) {
                http_response_code(400);
                echo json_encode(['error' => 'Formato no permitido']);
                return;
            }
            $filename = 'proposal_' . uniqid() . '.' . $ext;
            $target = $uploadDir . $filename;
            if (move_uploaded_file($_FILES['proposal']['tmp_name'], $target)) {
                $filePath = 'assets/uploads/proposals/' . $filename;
            }
        }

        if (!$price || !is_numeric($price)) {
            http_response_code(400);
            echo json_encode(['error' => 'Precio requerido']);
            return;
        }

        try {
            $this->model->attachProposal((int)$id, $filePath, (float)$price, $_SESSION['user_id'] ?? null);
            echo json_encode(['success' => true, 'file' => $filePath]);
        } catch (Exception $e) {
            error_log('[API Error] quote: ' . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Error al enviar']);
        }
    }
}
