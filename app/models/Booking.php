<?php

class Booking extends Model
{
    protected $table = 'bookings';

    /**
     * Sobrescribe el método create para generar booking_reference automáticamente
     */
    public function create($data)
    {
        // Generar referencia única antes de insertar
        $data['booking_reference'] = 'FK-' . strtoupper(uniqid());
        return parent::create($data);
    }

    /**
     * Obtiene las reservas de un usuario (devuelve arrays)
     */
    public function getUserBookings($userId)
    {
        $stmt = $this->db->prepare("
            SELECT b.*, 
                   p.name as package_name,
                   e.name as excursion_name,
                   t.name as transfer_name
            FROM bookings b
            LEFT JOIN packages p ON b.item_type = 'package' AND b.item_id = p.id
            LEFT JOIN excursions e ON b.item_type = 'excursion' AND b.item_id = e.id
            LEFT JOIN transfers t ON b.item_type = 'transfer' AND b.item_id = t.id
            WHERE b.customer_email = (SELECT email FROM users WHERE id = ?)
            ORDER BY b.created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // ← ARRAY, no objeto
    }

    /**
     * Obtiene el detalle de una reserva específica
     */
    public function getUserBookingDetail($userId, $bookingId)
    {
        $stmt = $this->db->prepare("
            SELECT b.*, 
                   p.name as package_name, p.description as package_description,
                   e.name as excursion_name, e.description as excursion_description,
                   t.name as transfer_name, t.description as transfer_description
            FROM bookings b
            LEFT JOIN packages p ON b.item_type = 'package' AND b.item_id = p.id
            LEFT JOIN excursions e ON b.item_type = 'excursion' AND b.item_id = e.id
            LEFT JOIN transfers t ON b.item_type = 'transfer' AND b.item_id = t.id
            WHERE b.id = ? AND b.customer_email = (SELECT email FROM users WHERE id = ?)
        ");
        $stmt->execute([$bookingId, $userId]);
        return $stmt->fetch(PDO::FETCH_ASSOC); // ← ARRAY
    }

    /**
     * Cuenta reservas activas de un usuario
     */
    public function countUserActiveBookings($userId)
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total 
            FROM bookings b
            WHERE b.customer_email = (SELECT email FROM users WHERE id = ?) 
            AND b.status IN ('pending', 'confirmed')
        ");
        $stmt->execute([$userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }

    /**
     * Obtiene todas las reservas para el admin
     */
    public function getAllBookings($limit = 50)
    {
        $stmt = $this->db->prepare("
            SELECT b.*, 
                   u.full_name as customer_full_name,
                   p.name as package_name,
                   e.name as excursion_name,
                   t.name as transfer_name
            FROM bookings b
            LEFT JOIN users u ON b.customer_email = u.email
            LEFT JOIN packages p ON b.item_type = 'package' AND b.item_id = p.id
            LEFT JOIN excursions e ON b.item_type = 'excursion' AND b.item_id = e.id
            LEFT JOIN transfers t ON b.item_type = 'transfer' AND b.item_id = t.id
            ORDER BY b.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // ← ARRAY
    }

    /**
     * Obtiene reservas recientes para el dashboard
     */
    public function getRecentBookings($limit = 10)
    {
        $stmt = $this->db->prepare("
            SELECT b.*, u.full_name as customer_name
            FROM bookings b
            LEFT JOIN users u ON b.customer_email = u.email
            ORDER BY b.created_at DESC
            LIMIT ?
        ");
        $stmt->execute([$limit]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // ← ARRAY
    }

    /**
     * Obtiene ingresos totales
     */
    public function getTotalRevenue()
    {
        $stmt = $this->db->prepare("
            SELECT SUM(total_price) as total 
            FROM bookings 
            WHERE status IN ('confirmed', 'completed') 
            AND payment_status = 'paid'
        ");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float) ($result['total'] ?? 0);
    }

    /* =========================
       MÉTODOS PARA ESTADÍSTICAS DEL DASHBOARD
    ========================== */

    /**
     * Cuenta reservas de hoy
     */
    public function countTodayBookings()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE DATE(created_at) = CURDATE()");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }

    /**
     * Cuenta reservas del mes actual
     */
    public function countMonthBookings()
    {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as total 
            FROM {$this->table} 
            WHERE YEAR(created_at) = YEAR(CURDATE()) 
            AND MONTH(created_at) = MONTH(CURDATE())
        ");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }

    /**
     * Cuenta reservas pendientes
     */
    public function countPendingBookings()
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM {$this->table} WHERE status = 'pending'");
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int) ($result['total'] ?? 0);
    }


    /**
     * Obtiene una reserva con detalles completos del servicio para admin/emails
     * Incluye JOINs con users, packages, excursions y transfers
     */

    public function getBookingWithServiceDetails($bookingId)
    {
        $stmt = $this->db->prepare("
        SELECT 
            b.*,
            u.full_name as customer_name,
            u.email as customer_email,
            -- Packages: usa days/nights en lugar de duration
            p.name as package_name, 
            p.description as package_description, 
            p.days as package_days,
            p.nights as package_nights,
            p.category as package_category,
            p.location as package_location,
            -- Excursions: sí tiene duration
            e.name as excursion_name, 
            e.description as excursion_description, 
            e.duration as excursion_duration,
            e.location as excursion_location,
            e.category as excursion_category,
            -- Transfers: sin duration, usa vehicle_type
            t.name as transfer_name, 
            t.description as transfer_description, 
            t.vehicle_type as transfer_vehicle,
            t.from_location as transfer_from,
            t.to_location as transfer_to
        FROM bookings b
        LEFT JOIN users u ON b.customer_email = u.email
        LEFT JOIN packages p ON b.item_type = 'package' AND b.item_id = p.id
        LEFT JOIN excursions e ON b.item_type = 'excursion' AND b.item_id = e.id
        LEFT JOIN transfers t ON b.item_type = 'transfer' AND b.item_id = t.id
        WHERE b.id = ?
    ");
        $stmt->execute([$bookingId]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
