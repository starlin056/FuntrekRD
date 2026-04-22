<?php

class Package extends Model
{
    protected $table = 'packages';
    protected $allowedColumns = [
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'price_type',
        'discount_price',
        'days',
        'nights',
        'category',
        'location',
        'hotel_category',
        'max_people',
        'image',
        'gallery',
        'includes',
        'featured',
        'active'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /* =====================================================
       FRONTEND – CONSULTAS ACTIVAS
    ===================================================== */
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

    public function getFeatured($limit = 6)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM {$this->table}
        WHERE active = 1 AND featured = 1
        ORDER BY created_at DESC 
        LIMIT ?
    ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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

    public function search($filters)
    {
        $sql = "SELECT * FROM {$this->table} WHERE active = 1";
        $params = [];

        if (!empty($filters['q'])) {
            $sql .= " AND (name LIKE :keyword OR location LIKE :keyword OR category LIKE :keyword)";
            $params[':keyword'] = '%' . $filters['q'] . '%';
        }

        if (!empty($filters['category'])) {
            $sql .= " AND LOWER(category) = LOWER(:category)";
            $params[':category'] = $filters['category'];
        }

        if (!empty($filters['min_price'])) {
            $sql .= " AND price >= :min_price";
            $params[':min_price'] = (float)$filters['min_price'];
        }

        if (!empty($filters['max_price'])) {
            $sql .= " AND price <= :max_price";
            $params[':max_price'] = (float)$filters['max_price'];
        }

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
                case 'newest':
                    $sql .= " ORDER BY created_at DESC";
                    break;
                default:
                    $sql .= " ORDER BY featured DESC, created_at DESC";
            }
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPriceRange()
    {
        $stmt = $this->db->prepare("SELECT MIN(price) as min_price, MAX(price) as max_price FROM {$this->table} WHERE active = 1");
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /* =====================================================
       COMPLEMENTOS DEL MODELO VIEJO
    ===================================================== */
    public function getRelated($category, $excludeId, $limit = 3)
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table}
            WHERE category = ?
              AND id != ?
              AND active = 1
            LIMIT ?
        ");
        $stmt->execute([$category, $excludeId, $limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDestinations()
    {
        $stmt = $this->db->prepare("
            SELECT location, COUNT(*) AS count, MIN(image) AS image
            FROM {$this->table}
            WHERE active = 1
            GROUP BY location
            ORDER BY location ASC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findAll($conditions = [], $orderBy = 'id DESC')
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

    /* =====================================================
       ADMIN – CRUD
    ===================================================== */
    public function create($data)
    {
        $allowed = array_intersect_key($data, array_flip($this->allowedColumns));
        $fields = implode(', ', array_keys($allowed));
        $placeholders = implode(', ', array_fill(0, count($allowed), '?'));

        $sql = "INSERT INTO {$this->table} ($fields) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute(array_values($allowed));
    }

    public function update($id, $data)
    {
        $allowed = array_intersect_key($data, array_flip($this->allowedColumns));
        $set = implode(', ', array_map(fn($f) => "$f = ?", array_keys($allowed)));

        $sql = "UPDATE {$this->table} SET $set WHERE id = ?";
        $params = array_values($allowed);
        $params[] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($params);
    }

    // Borrado lógico: en lugar de eliminar el registro, lo marcamos como inactivo
    public function restore($id)
    {
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET active = 1 WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }
}
