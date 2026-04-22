<?php
abstract class Model {
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function findAll($conditions = [], $orderBy = 'id DESC') {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";
        $params = [];
        
        foreach ($conditions as $field => $value) {
            $sql .= " AND {$field} = ?";
            $params[] = $value;
        }
        
        $sql .= " ORDER BY {$orderBy}";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(); 
    }
    
    // En app/core/Model.php
public function findById($id)
{
    $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$this->primaryKey} = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC); // ← ARRAY
}
    
    public function findBy($field, $value) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE {$field} = ?");
        $stmt->execute([$value]);
        return $stmt->fetch();
    }
    
    public function create($data) {
        $columns = implode(', ', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $stmt = $this->db->prepare($sql);
        
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        return $stmt->execute() ? $this->db->lastInsertId() : false;
    }
    
    public function update($id, $data) {
        $setClause = [];
        foreach ($data as $key => $value) {
            $setClause[] = "{$key} = :{$key}";
        }
        $setClause = implode(', ', $setClause);
        
        $sql = "UPDATE {$this->table} SET {$setClause} WHERE {$this->primaryKey} = :id";
        $stmt = $this->db->prepare($sql);
        
        $stmt->bindValue(':id', $id);
        foreach ($data as $key => $value) {
            $stmt->bindValue(':' . $key, $value);
        }
        
        return $stmt->execute();
    }
    
    public function delete($id) {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET active = 0 WHERE {$this->primaryKey} = ?");
        return $stmt->execute([$id]);
    }
    
    public function count($conditions = []) {
        $sql = "SELECT COUNT(*) as total FROM {$this->table} WHERE 1=1";
        $params = [];
        
        foreach ($conditions as $field => $value) {
            $sql .= " AND {$field} = ?";
            $params[] = $value;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $result = $stmt->fetch();
        return $result['total'];
    }



    
}