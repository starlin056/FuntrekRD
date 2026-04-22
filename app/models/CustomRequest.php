<?php
// app/models/CustomRequest.php

class CustomRequest extends Model
{
    protected $table = 'custom_excursion_requests';






    private function safeJsonDecode($value, $default = [])
    {
        // Si ya es array, retornarlo (caso de doble decodificación)
        if (is_array($value)) {
            return $value;
        }

        // Si es null o vacío, retornar default
        if ($value === null || $value === '') {
            return $default;
        }

        // Si es string, intentar decodificar
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            // Verificar que no hubo error en la decodificación
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }

        // Fallback seguro
        return $default;
    }



    private function safeJsonEncode($value)
    {
        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        // Si ya es string, retornar tal cual (evitar doble codificación)
        return is_string($value) ? $value : json_encode($value ?? [], JSON_UNESCAPED_UNICODE);
    }

    /**
     * Helper seguro para decodificar JSON
     */
    private function safeDecode($value, $default = [])
    {
        // Si ya es array, retornarlo tal cual
        if (is_array($value)) {
            return $value;
        }
        // Si es null o vacío, retornar default
        if ($value === null || $value === '') {
            return $default;
        }
        // Si es string, intentar decodificar
        if (is_string($value)) {
            $decoded = json_decode($value, true);
            // Verificar que la decodificación fue exitosa
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return $decoded;
            }
        }
        // Fallback seguro
        return $default;
    }

    private function safeEncode($value)
    {
        if (is_array($value)) {
            return json_encode($value, JSON_UNESCAPED_UNICODE);
        }
        return $value; // Si ya es string, retornar tal cual
    }

    public function getAllWithFilters($status = null, $search = null, $limit = 50)
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if ($status && $status !== 'all') {
            $sql .= " AND status = ?";
            $params[] = $status;
        }

        if ($search) {
            $sql .= " AND (customer_name LIKE ? OR customer_email LIKE ? OR destinations LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm]);
        }

        $sql .= " ORDER BY created_at DESC LIMIT ?";
        $params[] = $limit;

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



    /**
     * ✅ Agrega una nota - CORREGIDO para manejo seguro de JSON
     */
    public function addNote($requestId, $note, $adminId = null, $visibleToClient = false)
    {
        $noteEntry = [
            'id' => uniqid(),
            'content' => trim($note),
            'created_by' => $adminId,
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Obtener notas actuales con safeJsonDecode
        $current = $this->findById($requestId);
        $notes = $this->safeJsonDecode($current['admin_notes'] ?? '', []);

        // Agregar nueva nota
        $notes[] = $noteEntry;

        // Codificar y guardar con safeJsonEncode
        $this->update($requestId, [
            'admin_notes' => $this->safeJsonEncode($notes)
        ]);

        // Registrar en activity_log
        $this->logActivity($requestId, 'note_added', $noteEntry, $adminId, $visibleToClient);

        return $noteEntry['id'];
    }

    /**
     * ✅ Actualiza checklist de requerimientos - CORREGIDO para PHP 8+
     */
    public function updateRequirements($requestId, $requirements)
    {
        // Asegurar que requirements es un array válido
        $requirements = is_array($requirements) ? array_values($requirements) : [];

        // Codificar solo si es array, con safeJsonEncode
        $encoded = $this->safeJsonEncode($requirements);

        return $this->update($requestId, [
            'requirements_checklist' => $encoded
        ]);
    }
    /**
     * ✅ Obtiene solicitud con historial - CORREGIDO
     */
    public function getWithActivityLog($requestId)
    {
        $stmt = $this->db->prepare("
            SELECT r.*, 
                   GROUP_CONCAT(
                       CONCAT_WS('|||', a.action_type, a.action_details, a.created_at, a.visible_to_client)
                       ORDER BY a.created_at DESC SEPARATOR '###') as activity_log
            FROM {$this->table} r
            LEFT JOIN request_activity_log a ON r.id = a.request_id
            WHERE r.id = ?
            GROUP BY r.id
        ");
        $stmt->execute([$requestId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Parsear activity_log
        if (!empty($result['activity_log'])) {
            $activities = [];
            foreach (explode('###', $result['activity_log']) as $entry) {
                $parts = explode('|||', $entry);
                if (count($parts) >= 3) {
                    $activities[] = [
                        'type' => $parts[0],
                        'details' => $this->safeJsonDecode($parts[1] ?? '{}', []),
                        'created_at' => $parts[2],
                        'visible_to_client' => (bool) ($parts[3] ?? 0)
                    ];
                }
            }
            $result['activity_history'] = $activities;
        } else {
            $result['activity_history'] = [];
        }

        // ✅ Decodificar campos JSON con safeJsonDecode (PHP 8+ compatible)
        $result['admin_notes'] = $this->safeJsonDecode($result['admin_notes'] ?? '', []);
        $result['requirements_checklist'] = $this->safeJsonDecode($result['requirements_checklist'] ?? '', []);

        return $result;
    }




    public function logActivity($requestId, $actionType, $details, $adminId = null, $visibleToClient = false)
    {
        $stmt = $this->db->prepare("
            INSERT INTO request_activity_log 
            (request_id, admin_id, action_type, action_details, visible_to_client)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([
            $requestId,
            $adminId,
            $actionType,
            json_encode($details),
            $visibleToClient ? 1 : 0
        ]);
    }

    public function updateStatus($requestId, $newStatus, $adminId = null, $note = null)
    {
        $result = $this->update($requestId, ['status' => $newStatus]);

        if ($result && $adminId) {
            $this->logActivity($requestId, 'status_changed', [
                'from' => $this->findById($requestId)['status'] ?? 'unknown',
                'to' => $newStatus,
                'note' => $note
            ], $adminId, false);
        }

        return $result;
    }

    public function markAsContacted($requestId, $contactMethod = 'email', $adminId = null)
    {
        $this->update($requestId, ['last_contacted_at' => date('Y-m-d H:i:s')]);
        $this->logActivity($requestId, 'contacted', [
            'method' => $contactMethod,
            'timestamp' => date('Y-m-d H:i:s')
        ], $adminId, false);
    }

    public function attachProposal($requestId, $filePath, $quotedPrice, $adminId = null)
    {
        $this->update($requestId, [
            'proposal_attachment' => $filePath,
            'quoted_price' => $quotedPrice
        ]);
        $this->logActivity($requestId, 'quote_sent', [
            'file' => basename($filePath),
            'price' => $quotedPrice
        ], $adminId, true);
    }

    public function getCountsByStatus()
    {
        $stmt = $this->db->query("
            SELECT status, COUNT(*) as count 
            FROM {$this->table} 
            GROUP BY status
        ");
        $counts = ['pending' => 0, 'reviewing' => 0, 'approved' => 0, 'rejected' => 0];
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $counts[$row['status']] = (int) $row['count'];
        }
        return $counts;
    }

    public function countByStatus($status)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total 
            FROM {$this->table} 
            WHERE status = ?
        ");
        $stmt->execute([$status]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }

    public function createBookingFromRequest($requestId, $adminId = null)
    {
        $request = $this->findById($requestId);
        if (!$request || $request['status'] !== 'approved') {
            throw new Exception('Solicitud no aprobada o inexistente');
        }

        $reference = 'FK-CUST-' . strtoupper(uniqid());

        $bookingData = [
            'booking_reference' => $reference,
            'customer_name' => $request['customer_name'],
            'customer_email' => $request['customer_email'],
            'customer_phone' => $request['customer_phone'],
            'travel_date' => $request['travel_date'] ?? date('Y-m-d'),
            'adults' => (int) $request['people_count'],
            'children' => 0,
            'special_requests' => $request['additional_notes'] . "\nDestinos: " . $request['destinations'] . "\nActividades: " . $request['activities'],
            'item_type' => 'custom',
            'item_id' => 0,
            'total_price' => (float) ($request['quoted_price'] ?? 0),
            'status' => 'pending',
            'payment_status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $bookingModel = new Booking();
        $bookingId = $bookingModel->create($bookingData);

        if ($bookingId) {
            $this->logActivity($requestId, 'booking_created', [
                'booking_id' => $bookingId,
                'reference' => $reference
            ], $adminId, true);
        }

        return $bookingId;
    }
}
