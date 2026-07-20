<?php
/**
 * Admin Controller
 * Handles admin dashboard, NGO verification, user management, and reports
 */

class AdminController {
    private $userModel;
    private $donationModel;
    private $requestModel;
    
    public function __construct() {
        $this->userModel = new User();
        $this->donationModel = new Donation();
        $this->requestModel = new Request();
        
        // Check if user is logged in and is an admin
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            header('Location: ' . BASE_URL . '/index.php?route=login');
            exit;
        }
    }
    
    /**
     * Display admin dashboard
     */
    public function dashboard() {
        // Get pending NGOs
        $pendingNGOs = $this->userModel->getPendingNGOs();
        
        // Get statistics
        $totalDonors = count($this->userModel->getAllUsers('donor'));
        $totalNGOs = count($this->userModel->getAllUsers('ngo'));
        $totalDonations = count($this->donationModel->getAllDonations());
        $totalRequests = count($this->requestModel->getAllRequests());
        
        require_once __DIR__ . '/../views/admin/dashboard.php';
    }
    
    /**
     * Display NGO verification queue
     */
    public function ngoVerification() {
        $pendingNGOs = $this->userModel->getPendingNGOs();
        
        require_once __DIR__ . '/../views/admin/ngo_verification.php';
    }
    
    /**
     * Verify NGO
     */
    public function verifyNGO($ngoId) {
        $adminId = $_SESSION['user_id'];
        
        if ($this->userModel->verifyNGO($ngoId, $adminId, 'verified')) {
            $_SESSION['admin_success'] = 'NGO verified successfully!';
        } else {
            $_SESSION['admin_error'] = 'Failed to verify NGO';
        }
        
        header('Location: ' . BASE_URL . '/index.php?route=admin-ngo-verification');
        exit;
    }
    
    /**
     * Reject NGO
     */
    public function rejectNGO($ngoId) {
        $adminId = $_SESSION['user_id'];
        
        if ($this->userModel->verifyNGO($ngoId, $adminId, 'rejected')) {
            $_SESSION['admin_success'] = 'NGO rejected successfully!';
        } else {
            $_SESSION['admin_error'] = 'Failed to reject NGO';
        }
        
        header('Location: ' . BASE_URL . '/index.php?route=admin-ngo-verification');
        exit;
    }
    
    /**
     * Display user management
     */
    public function userManagement() {
        $role = $_GET['role'] ?? '';
        
        if ($role && in_array($role, ['donor', 'ngo'])) {
            $users = $this->userModel->getAllUsers($role);
        } else {
            $users = $this->userModel->getAllUsers();
        }
        
        require_once __DIR__ . '/../views/admin/user_management.php';
    }
    
    /**
     * Suspend user
     */
    public function suspendUser($userId) {
        if ($this->userModel->suspendUser($userId)) {
            $_SESSION['admin_success'] = 'User suspended successfully!';
        } else {
            $_SESSION['admin_error'] = 'Failed to suspend user';
        }
        
        header('Location: ' . BASE_URL . '/index.php?route=admin-user-management');
        exit;
    }
    
    /**
     * Display reports
     */
    public function reports() {
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        
        $donations = $this->donationModel->getAllDonations($startDate, $endDate);
        $requests = $this->requestModel->getAllRequests($startDate, $endDate);
        
        // Calculate statistics
        $totalDonations = count($donations);
        $completedDonations = count(array_filter($donations, function($d) { return $d['status'] === 'completed'; }));
        $totalRequests = count($requests);
        $approvedRequests = count(array_filter($requests, function($r) { return $r['status'] === 'approved'; }));
        
        require_once __DIR__ . '/../views/admin/reports.php';
    }
    
    /**
     * Display donation history
     */
    public function donationHistory() {
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        
        $donations = $this->donationModel->getAllDonations($startDate, $endDate);
        
        require_once __DIR__ . '/../views/admin/donation_history.php';
    }
    
    /**
     * Display request history
     */
    public function requestHistory() {
        $startDate = $_GET['start_date'] ?? null;
        $endDate = $_GET['end_date'] ?? null;
        
        $requests = $this->requestModel->getAllRequests($startDate, $endDate);
        
        require_once __DIR__ . '/../views/admin/request_history.php';
    }
}
?>
