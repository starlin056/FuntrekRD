<?php
// app/models/Quotation.php

class Quotation extends Model
{
    protected $table = 'quotations';

    /**
     * Get quotation with all its items
     */
    public function getWithItems($id)
    {
        $quotation = $this->findById($id);
        if (!$quotation) return null;

        $stmt = $this->db->prepare("SELECT * FROM quotation_items WHERE quotation_id = ?");
        $stmt->execute([$id]);
        $quotation['items'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $quotation;
    }

    /**
     * Create a new quotation with its items
     */
    public function createFull($data, $items)
    {
        try {
            $this->db->beginTransaction();

            $quotationId = $this->create($data);
            if (!$quotationId) throw new Exception("Error creating quotation");

            foreach ($items as $item) {
                $item['quotation_id'] = $quotationId;
                $stmt = $this->db->prepare("
                    INSERT INTO quotation_items (quotation_id, item_type, item_id, description, quantity, unit_price, total)
                    VALUES (:quotation_id, :item_type, :item_id, :description, :quantity, :unit_price, :total)
                ");
                $stmt->execute($item);
            }

            $this->db->commit();
            return $quotationId;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Delete a quotation and its items
     */
    public function deleteFull($id)
    {
        try {
            $this->db->beginTransaction();
            
            // Delete items first (due to FK constraint and cleanliness)
            $stmt = $this->db->prepare("DELETE FROM quotation_items WHERE quotation_id = ?");
            $stmt->execute([$id]);

            // Delete quotation
            $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
            $stmt->execute([$id]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Generate next quote number (e.g., QT-2026-0001)
     */
    public function generateQuoteNumber()
    {
        $year = date('Y');
        $stmt = $this->db->prepare("SELECT COUNT(*) as total FROM quotations WHERE YEAR(created_at) = ?");
        $stmt->execute([$year]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $next = ($result['total'] ?? 0) + 1;
        
        return "QT-{$year}-" . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
