<?php
// app/models/Excursion.php

class Excursion extends Model
{
    protected $table = 'excursions';

    protected $allowedColumns = [
        'name',
        'location',
        'description',
        'duration',
        'price',
        'price_type',
        'category',
        'includes',
        'requirements',
        'image',
        'gallery',
        'rating',
        'reviews_count',
        'featured',
        'max_people',
        'active'
    ];

    /* =========================
       FRONTEND – CONSULTAS ACTIVAS
    ========================== */

    /**
     * Obtiene todas las excursiones activas
     */
    public function getActive()
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE active = 1
            ORDER BY featured DESC, created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene excursiones destacadas
     */
    public function getFeatured($limit = 6)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE active = 1 AND featured = 1
            ORDER BY rating DESC, created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene excursiones agrupadas por categoría
     */
    public function getGroupedByCategory()
    {
        $excursions = $this->getActive();
        $grouped = [];

        foreach ($excursions as $exc) {
            $cat = $exc['category'] ?? 'General';
            if (!isset($grouped[$cat])) {
                $grouped[$cat] = [];
            }
            $grouped[$cat][] = $exc;
        }

        return $grouped;
    }

    /**
     * Obtiene las categorías con conteo
     */
    public function getCategories()
    {
        $stmt = $this->db->prepare("
            SELECT category, COUNT(*) as count
            FROM {$this->table}
            WHERE active = 1 AND category IS NOT NULL
            GROUP BY category
            ORDER BY count DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Búsqueda con filtros
     */
    public function search($filters)
    {
        $sql = "SELECT * FROM {$this->table} WHERE active = 1";
        $params = [];

        // 🔍 BÚSQUEDA (input name="q")
        if (!empty($filters['q'])) {
            $sql .= " AND (
            name LIKE :keyword 
            OR location LIKE :keyword 
            OR category LIKE :keyword
        )";
            $params[':keyword'] = '%' . $filters['q'] . '%';
        }

        //  CATEGORÍA
        if (!empty($filters['category'])) {
            $sql .= " AND LOWER(category) = LOWER(:category)";
            $params[':category'] = $filters['category'];
        }

        //  PRECIO MÍNIMO
        if (!empty($filters['min_price'])) {
            $sql .= " AND price >= :min_price";
            $params[':min_price'] = (float)$filters['min_price'];
        }

        //  PRECIO MÁXIMO
        if (!empty($filters['max_price'])) {
            $sql .= " AND price <= :max_price";
            $params[':max_price'] = (float)$filters['max_price'];
        }

        //  ORDEN
        if (empty($filters['sort'])) {
            $sql .= " ORDER BY featured DESC, created_at DESC";
        } else {
            switch ($filters['sort']) {
                case 'price_asc':
                    $sql .= " ORDER BY price ASC";
                    break;
                case 'price_desc':
                    $sql .= " ORDER BY price DESC";
                    break;
                case 'rating':
                    $sql .= " ORDER BY rating DESC";
                    break;
                case 'newest':
                    $sql .= " ORDER BY created_at DESC";
                    break;
                default:
                    $sql .= " ORDER BY featured DESC, created_at DESC";
                    break;
            }
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Buscar por ID — devuelve array
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Obtiene sugerencias para autocompletado
     */
    public function getSearchSuggestions()
    {
        $stmt = $this->db->prepare("
            SELECT DISTINCT name, location, category
            FROM {$this->table}
            WHERE active = 1
            ORDER BY name ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // En Excursion.php
    public function searchForAdmin($filters)
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND LOWER(category) = LOWER(:category)";
            $params[':category'] = $filters['category'];
        }
        if (!empty($filters['location'])) {
            $sql .= " AND location LIKE :location";
            $params[':location'] = '%' . $filters['location'] . '%';
        }
        if (!empty($filters['keyword'])) {
            $sql .= " AND (name LIKE :keyword OR location LIKE :keyword OR description LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['keyword'] . '%';
        }
        if (!empty($filters['min_price'])) {
            $sql .= " AND price >= :min_price";
            $params[':min_price'] = (float)$filters['min_price'];
        }
        if (!empty($filters['max_price'])) {
            $sql .= " AND price <= :max_price";
            $params[':max_price'] = (float)$filters['max_price'];
        }

        $sql .= " ORDER BY " . ($filters['sort'] == 'price_asc' ? 'price ASC' : ($filters['sort'] == 'price_desc' ? 'price DESC' : 'created_at DESC'));

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Rango de precios disponible
     */
    public function getPriceRange()
    {
        $stmt = $this->db->prepare("
            SELECT MIN(price) as min_price, MAX(price) as max_price
            FROM {$this->table}
            WHERE active = 1
        ");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =========================
       SOLICITUDES PERSONALIZADAS
    ========================== */

    /**
     * Crear solicitud de excursión personalizada
     */
    public function createCustomRequest($data)
    {
        $sql = "INSERT INTO custom_excursion_requests
            (customer_name, customer_email, customer_phone, destinations, activities,
             travel_date, people_count, budget, additional_notes)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([
            $data['customer_name'],
            $data['customer_email'],
            $data['customer_phone'] ?? null,
            $data['destinations'],
            $data['activities'] ?? null,
            !empty($data['travel_date']) ? $data['travel_date'] : null,
            (int)($data['people_count'] ?? 1),
            $data['budget'] ?? null,
            $data['additional_notes'] ?? null
        ]);

        return $result ? $this->db->lastInsertId() : false;
    }

    /**
     * Obtiene todas las solicitudes personalizadas (admin)
     */
    public function getAllCustomRequests($limit = 50)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM custom_excursion_requests
            ORDER BY created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Contar solicitudes pendientes
     */
    public function countPendingCustomRequests()
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total FROM custom_excursion_requests WHERE status = 'pending'
        ");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['total'] ?? 0);
    }

    /* =========================
       ADMIN
    ========================== */

    public function findAll($conditions = [], $orderBy = 'created_at DESC')
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];

        foreach ($conditions as $field => $value) {
            $sql .= " AND {$field} = ?";
            $params[] = $value;
        }

        $sql .= " ORDER BY {$orderBy}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    // Actualizar estado de una solicitud personalizada
    public function updateCustomRequestStatus($id, $status)
    {
        $stmt = $this->db->prepare(
            "UPDATE custom_excursion_requests 
         SET status = ?, updated_at = NOW() 
         WHERE id = ?"
        );

        return $stmt->execute([$status, (int)$id]);
    }
}
