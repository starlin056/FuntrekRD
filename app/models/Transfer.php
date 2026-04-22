<?php
// app/models/Transfer.php

class Transfer extends Model
{
    protected $table = 'transfers';
    protected $allowedColumns = [
        'name',
        'from_location',
        'to_location',
        'vehicle_type',
        'max_passengers',
        'price',
        'price_type',
        'description',
        'image',
        'gallery',
        'active'
    ];

    /* =========================
       CRUD BÁSICO
    ========================== */


    public function findAll($conditions = [], $orderBy = 'id DESC')
    {
        $sql = "SELECT * FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $field => $value) {
                $where[] = "{$field} = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $sql .= " ORDER BY {$orderBy}";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

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



    public function delete($id)
    {
        // Borrado lógico
        $stmt = $this->db->prepare(
            "UPDATE {$this->table} SET active = 0 WHERE id = ?"
        );
        return $stmt->execute([$id]);
    }

    public function count($conditions = [])
    {
        $sql = "SELECT COUNT(*) FROM {$this->table}";
        $params = [];

        if (!empty($conditions)) {
            $where = [];
            foreach ($conditions as $field => $value) {
                $where[] = "{$field} = ?";
                $params[] = $value;
            }
            $sql .= " WHERE " . implode(' AND ', $where);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return (int)$stmt->fetchColumn();
    }

    /* =========================
       MÉTODOS ESPECÍFICOS
    ========================== */

    public function getByRoute($from, $to)
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM {$this->table}
             WHERE from_location = ?
               AND to_location = ?
               AND active = 1
             ORDER BY price ASC"
        );
        $stmt->execute([$from, $to]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllActive()
    {
        $stmt = $this->db->prepare(
            "SELECT *
             FROM {$this->table}
             WHERE active = 1
             ORDER BY name ASC"
        );
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
{
    $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ? LIMIT 1");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC); // ← ARRAY
}
}
