<?php
/**
 * User Model
 * Handles user-related database operations
 */

class User {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    /**
     * Register a new user
     */
    public function register($data) {
        try {
            $sql = "INSERT INTO users (name, email, phone, password, role, address, latitude, longitude) 
                    VALUES (:name, :email, :phone, :password, :role, :address, :latitude, :longitude)";
            
            $stmt = $this->db->prepare($sql);
            
            // Hash password
            $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
            
            $stmt->bindParam(':name', $data['name']);
            $stmt->bindParam(':email', $data['email']);
            $stmt->bindParam(':phone', $data['phone']);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':role', $data['role']);
            $stmt->bindParam(':address', $data['address']);
            $stmt->bindParam(':latitude', $data['latitude']);
            $stmt->bindParam(':longitude', $data['longitude']);
            
            if ($stmt->execute()) {
                return $this->db->lastInsertId();
            }
            return false;
        } catch(PDOException $e) {
            error_log("Registration error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Register NGO details
     */
    public function registerNGO($ngoData) {
        try {
            $sql = "INSERT INTO ngos (ngo_id, organization_name, registration_number) 
                    VALUES (:ngo_id, :organization_name, :registration_number)";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ngo_id', $ngoData['ngo_id']);
            $stmt->bindParam(':organization_name', $ngoData['organization_name']);
            $stmt->bindParam(':registration_number', $ngoData['registration_number']);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("NGO registration error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Login user
     */
    public function login($email, $password) {
        $email = trim($email);
        try {
            $sql = "SELECT * FROM users WHERE email = :email LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password'])) {
                // Remove password from session data
                unset($user['password']);
                return $user;
            }
            return false;
        } catch(PDOException $e) {
            error_log("User Login Failed: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get user by ID
     */
    public function getUserById($userId) {
        try {
            $sql = "SELECT user_id, name, email, phone, role, address, latitude, longitude, created_at 
                    FROM users WHERE user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log("Get user error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get NGO details
     */
    public function getNGODetails($ngoId) {
        try {
            $sql = "SELECT n.*, u.name, u.email, u.phone, u.address 
                    FROM ngos n 
                    JOIN users u ON n.ngo_id = u.user_id 
                    WHERE n.ngo_id = :ngo_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':ngo_id', $ngoId);
            $stmt->execute();
            
            return $stmt->fetch();
        } catch(PDOException $e) {
            error_log("Get NGO error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Check if email exists
     */
    public function emailExists($email) {
        try {
            $sql = "SELECT user_id FROM users WHERE email = :email";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            return $stmt->fetch() !== false;
        } catch(PDOException $e) {
            error_log("Email check error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all users (for admin)
     */
    public function getAllUsers($role = null) {
        try {
            $sql = "SELECT user_id, name, email, phone, role, address, created_at 
                    FROM users";
            
            if ($role) {
                $sql .= " WHERE role = :role";
            }
            
            $sql .= " ORDER BY created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            
            if ($role) {
                $stmt->bindParam(':role', $role);
            }
            
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Get all users error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Suspend user account
     * updated some code to handle user suspension
     */
    public function suspendUser($userId) {
        try {
            $sql = "UPDATE users SET status = 'suspended' WHERE user_id = :user_id";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':user_id', $userId);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Suspend user error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get pending NGOs for verification
     */
    public function getPendingNGOs() {
        try {
            $sql = "SELECT n.*, u.name, u.email, u.phone, u.address 
                    FROM ngos n 
                    JOIN users u ON n.ngo_id = u.user_id 
                    WHERE n.verification_status = 'pending' 
                    ORDER BY u.created_at DESC";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            return $stmt->fetchAll();
        } catch(PDOException $e) {
            error_log("Get pending NGOs error: " . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Verify NGO
     */
    public function verifyNGO($ngoId, $adminId, $status) {
        try {
            $sql = "UPDATE ngos 
                    SET verification_status = :status, 
                        verified_by = :admin_id, 
                        verified_at = NOW() 
                    WHERE ngo_id = :ngo_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':status', $status);
            $stmt->bindParam(':admin_id', $adminId);
            $stmt->bindParam(':ngo_id', $ngoId);
            
            return $stmt->execute();
        } catch(PDOException $e) {
            error_log("Verify NGO error: " . $e->getMessage());
            return false;
        }
    }
}
?>
