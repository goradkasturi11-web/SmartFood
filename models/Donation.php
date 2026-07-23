<?php
/**
 * Donation Model
 * Handles donation-related database operations
 */

class Donation {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Create a new donation
     */
    public function create($data) {
        try {
            $sql = "INSERT INTO donations (donor_id, food_name, quantity_value, quantity_unit, food_type, 
                    preparation_date, expiry_time, pickup_location, latitude, longitude, image_path) 
                    VALUES (:donor_id, :food_name, :quantity_value, :quantity_unit, :food_type, 
                    :preparation_date, :expiry_time, :pickup_location, :latitude, :longitude, :image_path)";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':donor_id', $data['donor_id']);
            $stmt->bindParam(':food_name', $data['food_name']);
            $stmt->bindParam(':quantity_value', $data['quantity_value']);
            $stmt->bindParam(':quantity_unit', $data['quantity_unit']);
            $stmt->bindParam(':food_type', $data['food_type']);
            $stmt->bindParam(':preparation_date', $data['preparation_date']);
            $stmt->bindParam(':expiry_time', $data['expiry_time']);
            $stmt->bindParam(':pickup_location', $data['pickup_location']);
            $stmt->bindParam(':latitude', $data['latitude']);
            $stmt->bindParam(':longitude', $data['longitude']);
            $stmt->bindParam(':image_path', $data['image_path']);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch(PDOException $e) {
            error_log("Create donation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get donation by ID
     */
    public function getDonationById($donationId) {
        try {
            $sql = "SELECT d.*, u.name as donor_name, u.phone as donor_phone, u.address as donor_address 
                    FROM donations d 
                    JOIN users u ON d.donor_id = u.user_id 
                    WHERE d.donation_id = :donation_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':donation_id', $donationId);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log("Get donation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get donations by donor
     */
    public function getDonationsByDonor($donorId) {
        try {
            $sql = "SELECT * FROM donations WHERE donor_id = :donor_id ORDER BY created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':donor_id', $donorId);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Get donor donations error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get available donations for NGOs
     */
    public function getAvailableDonations($filters = []) {
        try {
            $sql = "SELECT d.*, u.name as donor_name, u.phone as donor_phone,
                    (SELECT COUNT(*) FROM requests r WHERE r.donation_id = d.donation_id AND r.status = 'pending') as pending_requests
                    FROM donations d
                    JOIN users u ON d.donor_id = u.user_id
                    WHERE d.status = 'available' AND d.expiry_time > NOW()";

            $params = [];

            if (!empty($filters['food_type'])) {
                $sql .= " AND d.food_type = :food_type";
                $params[':food_type'] = $filters['food_type'];
            }

            if (!empty($filters['min_quantity'])) {
                $sql .= " AND d.quantity_value >= :min_quantity";
                $params[':min_quantity'] = $filters['min_quantity'];
            }

            if (!empty($filters['location'])) {
                $sql .= " AND (d.pickup_location LIKE :location OR u.address LIKE :location)";
                $params[':location'] = '%' . $filters['location'] . '%';
            }

            $sql .= " ORDER BY d.created_at DESC";

            error_log("Donation Model - SQL: " . $sql);
            error_log("Donation Model - Params: " . json_encode($params));

            $stmt = $this->db->prepare($sql);

            foreach ($params as $key => $value) {
                $stmt->bindValue($key, $value);
            }

            $stmt->execute();

            $result = $stmt->fetchAll();
            error_log("Donation Model - Result count: " . count($result));

            return $result;
        } catch(PDOException $e) {
            error_log("Get available donations error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update donation
     */
    public function update($donationId, $data) {
        try {
            $sql = "UPDATE donations 
                    SET food_name = :food_name, 
                        quantity_value = :quantity_value, 
                        quantity_unit = :quantity_unit, 
                        food_type = :food_type, 
                        preparation_date = :preparation_date, 
                        expiry_time = :expiry_time, 
                        pickup_location = :pickup_location, 
                        latitude = :latitude, 
                        longitude = :longitude";
            
            if (!empty($data['image_path'])) {
                $sql .= ", image_path = :image_path";
            }
            
            $sql .= " WHERE donation_id = :donation_id";
            
            $stmt = $this->db->prepare($sql);
            
            $stmt->bindParam(':donation_id', $donationId);
            $stmt->bindParam(':food_name', $data['food_name']);
            $stmt->bindParam(':quantity_value', $data['quantity_value']);
            $stmt->bindParam(':quantity_unit', $data['quantity_unit']);
            $stmt->bindParam(':food_type', $data['food_type']);
            $stmt->bindParam(':preparation_date', $data['preparation_date']);
            $stmt->bindParam(':expiry_time', $data['expiry_time']);
            $stmt->bindParam(':pickup_location', $data['pickup_location']);
            $stmt->bindParam(':latitude', $data['latitude']);
            $stmt->bindParam(':longitude', $data['longitude']);
            
            if (!empty($data['image_path'])) {
                $stmt->bindParam(':image_path', $data['image_path']);
            }
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Update donation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete donation
     */
    public function delete($donationId) {
        try {
            $sql = "DELETE FROM donations WHERE donation_id = :donation_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':donation_id', $donationId);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Delete donation error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Update donation status
     */
    public function updateStatus($donationId, $status) {
        try {
            $sql = "UPDATE donations SET status = :status WHERE donation_id = :donation_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':donation_id', $donationId);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Update donation status error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mark expired donations
     */
    public function markExpiredDonations() {
        try {
            $sql = "UPDATE donations SET status = 'expired' 
                    WHERE status = 'available' AND expiry_time < NOW()";
            $stmt = $this->db->prepare($sql);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Mark expired donations error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all donations (for admin reports)
     */
    public function getAllDonations($startDate = null, $endDate = null) {
        try {
            $sql = "SELECT d.*, u.name as donor_name 
                    FROM donations d 
                    JOIN users u ON d.donor_id = u.user_id";
            
            if ($startDate && $endDate) {
                $sql .= " WHERE d.created_at BETWEEN :start_date AND :end_date";
            }
            
            $sql .= " ORDER BY d.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            
            if ($startDate && $endDate) {
                $stmt->bindParam(':start_date', $startDate);
                $stmt->bindParam(':end_date', $endDate);
            }
            
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Get all donations error: " . $e->getMessage());
            return false;
        }
    }
}
?>
