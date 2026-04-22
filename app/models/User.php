<?php
class User extends Model
{
    protected $table = 'users';

    public function authenticate($email, $password)
    {
        $user = $this->findBy('email', $email);

        if ($user && $user['active'] == 1) {
            // Verificar la contraseña hasheada
            if (password_verify($password, $user['password'])) {
                return $user;
            }
        }

        return false;
    }

    public function create($data)
    {
        // Hashear la contraseña antes de guardar
        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        }

        // Asegurarse de que el rol esté correcto
        if (!isset($data['role'])) {
            $data['role'] = 'client';
        }

        return parent::create($data);
    }

    public function updateLastLogin($userId)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET last_login = NOW() WHERE id = ?");
        return $stmt->execute([$userId]);
    }



    public function getAdmins()
    {
        $stmt = $this->db->prepare("
            SELECT * FROM {$this->table} 
            WHERE role = 'admin' AND active = 1 
            ORDER BY created_at DESC
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public function updateProfile($userId, $data)
    {
        // Si hay nueva contraseña, hashearla
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        } elseif (isset($data['password'])) {
            // Si está vacía, eliminar del array para no actualizar
            unset($data['password']);
        }

        return $this->update($userId, $data);
    }

    public function getClients($limit = 100, $offset = 0)
    {
        $stmt = $this->db->prepare("
        SELECT * FROM {$this->table} 
        WHERE role = 'client'
        ORDER BY created_at DESC 
        LIMIT ? OFFSET ?
    ");
        $stmt->execute([$limit, $offset]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }


    public function getUsers($role = null)
    {
        $sql = "SELECT * FROM {$this->table} WHERE 1=1";

        if ($role) {
            $sql .= " AND role = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$role]);
        } else {
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        }

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // ← ARRAY
    }

    /**
     * Guarda un token de restablecimiento para un usuario
     */
    public function setResetToken($email, $token, $expires)
    {
        $stmt = $this->db->prepare("UPDATE {$this->table} SET reset_token = ?, reset_expires = ? WHERE email = ?");
        return $stmt->execute([$token, $expires, $email]);
    }

    /**
     * Valida si un token existe y no ha expirado
     */
    public function validateResetToken($token)
    {
        $currentDate = date('Y-m-d H:i:s');
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE reset_token = ? AND reset_expires > ? AND active = 1");
        $stmt->execute([$token, $currentDate]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Actualiza la contraseña usando un token y lo limpia
     */
    public function updatePasswordByToken($token, $newPassword)
    {
        $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE {$this->table} SET password = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?");
        return $stmt->execute([$hashedPassword, $token]);
    }
}
