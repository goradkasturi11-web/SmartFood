<?php
/**
 * Notification Model
 * Handles notification-related database operations
 */

class Notification {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Create a new notification
     */
    public function create($userId, $type, $message, $donationId = null, $requestId = null) {
        try {
            $sql = "INSERT INTO notifications (user_id, type, message, related_donation_id, related_request_id) 
                    VALUES (:user_id, :type, :message, :related_donation_id, :related_request_id)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':type', $type);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':related_donation_id', $donationId);
            $stmt->bindParam(':related_request_id', $requestId);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Create notification error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get notifications for a user
     */
    public function getUserNotifications($userId, $unreadOnly = false) {
        try {
            $sql = "SELECT * FROM notifications WHERE user_id = :user_id";
            
            if ($unreadOnly) {
                $sql .= " AND is_read = FALSE";
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT 50";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Get user notifications error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mark notification as read
     */
    public function markAsRead($notificationId) {
        try {
            $sql = "UPDATE notifications SET is_read = TRUE WHERE notification_id = :notification_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':notification_id', $notificationId);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Mark notification read error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Mark all notifications as read for a user
     */
    public function markAllAsRead($userId) {
        try {
            $sql = "UPDATE notifications SET is_read = TRUE WHERE user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Mark all notifications read error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get unread count for a user
     */
    public function getUnreadCount($userId) {
        try {
            $sql = "SELECT COUNT(*) as count FROM notifications WHERE user_id = :user_id AND is_read = FALSE";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            
            $result = $stmt->fetch();
            return $result['count'];
        } catch(PDOException $e) {
            error_log("Get unread count error: " . $e->getMessage());
            return 0;
        }
    }
    
    /**
     * Notify nearby NGOs about new donation
     */
    public function notifyNearbyNGOs($donationId, $donorLat, $donorLng) {
        try {
            // Get verified NGOs
            $sql = "SELECT u.user_id, u.name, u.latitude, u.longitude 
                    FROM users u 
                    JOIN ngos n ON u.user_id = n.ngo_id 
                    WHERE n.verification_status = 'verified'";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            $ngos = $stmt->fetchAll();
            
            foreach ($ngos as $ngo) {
                // Simple distance check (within 50km)
                if ($this->calculateDistance($donorLat, $donorLng, $ngo['latitude'], $ngo['longitude']) <= 50) {
                    $message = "New food donation available near you!";
                    $this->create($ngo['user_id'], 'new_donation', $message, $donationId);
                }
            }
            
            return true;
        } catch(PDOException $e) {
            error_log("Notify nearby NGOs error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Calculate distance between two coordinates (Haversine formula)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2) {
        // Clean coordinate values - remove degree symbols, direction letters, and whitespace
        $lat1 = $this->cleanCoordinate($lat1);
        $lon1 = $this->cleanCoordinate($lon1);
        $lat2 = $this->cleanCoordinate($lat2);
        $lon2 = $this->cleanCoordinate($lon2);
        
        if (!$lat1 || !$lon1 || !$lat2 || !$lon2) {
            return 999; // Return large distance if coordinates missing
        }
        
        $earthRadius = 6371; // km
        
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        
        $a = sin($dLat/2) * sin($dLat/2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon/2) * sin($dLon/2);
        
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        
        return $earthRadius * $c;
    }
    
    /**
     * Clean coordinate string to extract numeric value
     */
    private function cleanCoordinate($coord) {
        if (!is_numeric($coord)) {
            // Remove degree symbols, direction letters (N, S, E, W), and whitespace
            $coord = preg_replace('/[^0-9.\-]/', '', $coord);
        }
        return floatval($coord);
    }
}
?>
