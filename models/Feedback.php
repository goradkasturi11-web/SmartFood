<?php
/**
 * Feedback Model
 * Handles feedback-related database operations
 */

class Feedback {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Submit feedback
     */
    public function submit($userId, $donationId, $rating, $comments = null) {
        try {
            $sql = "INSERT INTO feedback (user_id, donation_id, rating, comments) 
                    VALUES (:user_id, :donation_id, :rating, :comments)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':donation_id', $donationId);
            $stmt->bindParam(':rating', $rating);
            $stmt->bindParam(':comments', $comments);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Submit feedback error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get feedback for a donation
     */
    public function getFeedbackByDonation($donationId) {
        try {
            $sql = "SELECT f.*, u.name as user_name 
                    FROM feedback f 
                    JOIN users u ON f.user_id = u.user_id 
                    WHERE f.donation_id = :donation_id 
                    ORDER BY f.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':donation_id', $donationId);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Get donation feedback error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get average rating for a donor
     */
    public function getDonorAverageRating($donorId) {
        try {
            $sql = "SELECT AVG(f.rating) as avg_rating, COUNT(f.feedback_id) as total_feedback
                    FROM feedback f 
                    JOIN donations d ON f.donation_id = d.donation_id 
                    WHERE d.donor_id = :donor_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':donor_id', $donorId);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log("Get donor rating error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if user has already submitted feedback for a donation
     */
    public function hasUserSubmittedFeedback($userId, $donationId) {
        try {
            $sql = "SELECT feedback_id FROM feedback 
                    WHERE user_id = :user_id AND donation_id = :donation_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->bindParam(':donation_id', $donationId);
            $stmt->execute();
            
            return $stmt->fetch() !== false;
        } catch(PDOException $e) {
            error_log("Check feedback error: " . $e->getMessage());
            return false;
        }
    }
}
?>
