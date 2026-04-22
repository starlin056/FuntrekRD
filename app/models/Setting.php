<?php
// app/models/Setting.php

class Setting extends Model
{
    protected $table = 'settings';

    /**
     * Get all settings as an associative array
     */
    public function getAll()
    {
        $settings = $this->findAll();
        return !empty($settings) ? $settings[0] : null;
    }

    /**
     * Update or create company settings
     */
    public function updateSettings($data)
    {
        $current = $this->getAll();
        if ($current) {
            return $this->update($current['id'], $data);
        } else {
            return $this->create($data);
        }
    }

    /**
     * Get a specific setting value
     */
    public static function get($key, $default = null)
    {
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT * FROM settings LIMIT 1");
        $stmt->execute();
        $settings = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $settings[$key] ?? $default;
    }
}
