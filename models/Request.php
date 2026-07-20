<?php
/**
 * Request Model
 * Handles request-related database operations
 */

class Request {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Create a new request
     */
    public function create($donationId, $ngoId) {
        try {
            $sql = "INSERT INTO requests (donation_id, ngo_id) VALUES (:donation_id, :ngo_id)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':donation_id', $donationId);
            $stmt->bindParam(':ngo_id', $ngoId);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch(PDOException $e) {
            error_log("Create request error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get request by ID
     */
    public function getRequestById($requestId) {
        try {
            $sql = "SELECT r.*, d.food_name, d.quantity_value, d.quantity_unit, d.pickup_location,
                    u_ngo.name as ngo_name, u_ngo.phone as ngo_phone,
                    u_donor.name as donor_name, u_donor.phone as donor_phone
                    FROM requests r 
                    JOIN donations d ON r.donation_id = d.donation_id 
                    JOIN users u_ngo ON r.ngo_id = u_ngo.user_id 
                    JOIN users u_donor ON d.donor_id = u_donor.user_id 
                    WHERE r.request_id = :request_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':request_id', $requestId);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log("Get request error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get requests for a donation
     */
    public function getRequestsByDonation($donationId) {
        try {
            $sql = "SELECT r.*, u.name as ngo_name, u.phone as ngo_phone, u.address as ngo_address
                    FROM requests r 
                    JOIN users u ON r.ngo_id = u.user_id 
                    WHERE r.donation_id = :donation_id 
                    ORDER BY r.request_date DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':donation_id', $donationId);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Get donation requests error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get requests for an NGO
     */
    public function getRequestsByNGO($ngoId) {
        try {
            $sql = "SELECT r.*, d.food_name, d.quantity_value, d.quantity_unit, d.pickup_location, 
                    d.expiry_time, d.status as donation_status,
                    u.name as donor_name, u.phone as donor_phone
                    FROM requests r 
                    JOIN donations d ON r.donation_id = d.donation_id 
                    JOIN users u ON d.donor_id = u.user_id 
                    WHERE r.ngo_id = :ngo_id 
                    ORDER BY r.request_date DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ngo_id', $ngoId);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Get NGO requests error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update request status
     */
    public function updateStatus($requestId, $status) {
        try {
            $sql = "UPDATE requests SET status = :status WHERE request_id = :request_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':request_id', $requestId);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Update request status error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Cancel request
     */
    public function cancelRequest($requestId) {
        return $this->updateStatus($requestId, 'cancelled');
    }
    
    /**
     * Check if NGO has already requested this donation
     */
    public function hasNGORequested($donationId, $ngoId) {
        try {
            $sql = "SELECT request_id FROM requests 
                    WHERE donation_id = :donation_id AND ngo_id = :ngo_id 
                    AND status IN ('pending', 'approved')";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':donation_id', $donationId);
            $stmt->bindParam(':ngo_id', $ngoId);
            $stmt->execute();
            
            return $stmt->fetch() !== false;
        } catch(PDOException $e) {
            error_log("Check NGO request error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all requests (for admin reports)
     */
    public function getAllRequests($startDate = null, $endDate = null) {
        try {
            $sql = "SELECT r.*, d.food_name, u_ngo.name as ngo_name, u_donor.name as donor_name
                    FROM requests r 
                    JOIN donations d ON r.donation_id = d.donation_id 
                    JOIN users u_ngo ON r.ngo_id = u_ngo.user_id 
                    JOIN users u_donor ON d.donor_id = u_donor.user_id";
            
            if ($startDate && $endDate) {
                $sql .= " WHERE r.request_date BETWEEN :start_date AND :end_date";
            }
            
            $sql .= " ORDER BY r.request_date DESC";
            
            $stmt = $this->db->prepare($sql);
            
            if ($startDate && $endDate) {
                $stmt->bindParam(':start_date', $startDate);
                $stmt->bindParam(':end_date', $endDate);
            }
            
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Get all requests error: " . $e->getMessage());
            return false;
        }
    }
}
?>
