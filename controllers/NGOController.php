<?php
/**
 * NGO Controller
 * Handles NGO dashboard and food search/request management
 */

class NGOController {
    private $donationModel;
    private $requestModel;
    private $notificationModel;
    private $feedbackModel;
    private $userModel;
    
    public function __construct() {
        $this->donationModel = new Donation();
        $this->requestModel = new Request();
        $this->notificationModel = new Notification();
        $this->feedbackModel = new Feedback();
        $this->userModel = new User();
        
        // Check if user is logged in and is an NGO
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'ngo') {
            header('Location: ' . BASE_URL . '/index.php?route=login');
            exit;
        }
        
        // Check if NGO is verified
        $ngoDetails = $this->userModel->getNGODetails($_SESSION['user_id']);
        if ($ngoDetails && $ngoDetails['verification_status'] !== 'verified') {
            session_destroy();
            header('Location: ' . BASE_URL . '/index.php?route=login');
            exit;
        }
    }
    
    /**
     * Display NGO dashboard
     */
    public function dashboard() {
        $ngoId = $_SESSION['user_id'];
        
        // Get NGO's requests
        $requests = $this->requestModel->getRequestsByNGO($ngoId);
        
        // Get unread notification count
        $unreadCount = $this->notificationModel->getUnreadCount($ngoId);
        
        require_once __DIR__ . '/../views/ngo/dashboard.php';
    }
    
    /**
     * Display available food listings
     */
    public function browseFood() {
        $filters = [
            'food_type' => $_GET['food_type'] ?? '',
            'min_quantity' => $_GET['min_quantity'] ?? '',
            'location' => $_GET['location'] ?? ''
        ];
        
        $donations = $this->donationModel->getAvailableDonations($filters);
        
        require_once __DIR__ . '/../views/ngo/browse_food.php';
    }
    
    /**
     * View donation details
     */
    public function viewDonation($donationId) {
        $donation = $this->donationModel->getDonationById($donationId);
        
        if (!$donation || $donation['status'] !== 'available') {
            $_SESSION['error'] = 'Donation not available';
            header('Location: ' . BASE_URL . '/index.php?route=ngo-browse-food');
            exit;
        }
        
        require_once __DIR__ . '/../views/ngo/view_donation.php';
    }
    
    /**
     * Request a donation
     */
    public function requestDonation($donationId) {
        $ngoId = $_SESSION['user_id'];
        
        $donation = $this->donationModel->getDonationById($donationId);
        
        if (!$donation || $donation['status'] !== 'available') {
            $_SESSION['error'] = 'Donation not available';
            header('Location: ' . BASE_URL . '/index.php?route=ngo-browse-food');
            exit;
        }
        
        // Check if already requested
        if ($this->requestModel->hasNGORequested($donationId, $ngoId)) {
            $_SESSION['error'] = 'You have already requested this donation';
            header('Location: ' . BASE_URL . '/index.php?route=ngo-view-donation&id=' . $donationId);
            exit;
        }
        
        $requestId = $this->requestModel->create($donationId, $ngoId);
        
        if ($requestId) {
            // Notify donor
            $this->notificationModel->create(
                $donation['donor_id'],
                'request_submitted',
                'New request received for your donation: ' . $donation['food_name'],
                $donationId,
                $requestId
            );
            
            $_SESSION['request_success'] = 'Request submitted successfully!';
            header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
            exit;
        } else {
            $_SESSION['error'] = 'Failed to submit request';
            header('Location: ' . BASE_URL . '/index.php?route=ngo-view-donation&id=' . $donationId);
            exit;
        }
    }
    
    /**
     * Cancel request
     */
    public function cancelRequest($requestId) {
        $ngoId = $_SESSION['user_id'];
        
        $request = $this->requestModel->getRequestById($requestId);
        
        if (!$request || $request['ngo_id'] != $ngoId) {
            $_SESSION['error'] = 'Request not found or access denied';
            header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
            exit;
        }
        
        // Only allow cancellation if request is still pending
        if ($request['status'] !== 'pending') {
            $_SESSION['error'] = 'Cannot cancel request that is not pending';
            header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
            exit;
        }
        
        if ($this->requestModel->cancelRequest($requestId)) {
            $_SESSION['request_success'] = 'Request cancelled successfully!';
        } else {
            $_SESSION['error'] = 'Failed to cancel request';
        }
        
        header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
        exit;
    }
    
    /**
     * Mark food as collected
     */
    public function markCollected($requestId) {
        $ngoId = $_SESSION['user_id'];
        
        $request = $this->requestModel->getRequestById($requestId);
        
        if (!$request || $request['ngo_id'] != $ngoId) {
            $_SESSION['error'] = 'Request not found or access denied';
            header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
            exit;
        }
        
        // Only allow marking as collected if request is approved
        if ($request['status'] !== 'approved') {
            $_SESSION['error'] = 'Cannot mark as collected for unapproved request';
            header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
            exit;
        }
        
        // Update donation status to completed
        if ($this->donationModel->updateStatus($request['donation_id'], 'completed')) {
            // Notify donor
            $this->notificationModel->create(
                $request['donor_id'],
                'food_collected',
                'Food has been collected by ' . $request['ngo_name'],
                $request['donation_id'],
                $requestId
            );
            
            $_SESSION['request_success'] = 'Food marked as collected!';
        } else {
            $_SESSION['error'] = 'Failed to mark as collected';
        }
        
        header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
        exit;
    }
    
    /**
     * Display feedback form
     */
    public function showFeedback($requestId) {
        $ngoId = $_SESSION['user_id'];
        
        $request = $this->requestModel->getRequestById($requestId);
        
        if (!$request || $request['ngo_id'] != $ngoId) {
            $_SESSION['error'] = 'Request not found or access denied';
            header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
            exit;
        }
        
        // Only allow feedback if request is approved and food is collected
        if ($request['status'] !== 'approved') {
            $_SESSION['error'] = 'Cannot submit feedback for this request';
            header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
            exit;
        }
        
        // Check if already submitted feedback
        if ($this->feedbackModel->hasUserSubmittedFeedback($ngoId, $request['donation_id'])) {
            $_SESSION['error'] = 'You have already submitted feedback for this donation';
            header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
            exit;
        }
        
        require_once __DIR__ . '/../views/ngo/feedback.php';
    }
    
    /**
     * Submit feedback
     */
    public function submitFeedback($requestId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
            exit;
        }
        
        $ngoId = $_SESSION['user_id'];
        
        $request = $this->requestModel->getRequestById($requestId);
        
        if (!$request || $request['ngo_id'] != $ngoId) {
            $_SESSION['error'] = 'Request not found or access denied';
            header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
            exit;
        }
        
        $rating = $_POST['rating'] ?? '';
        $comments = trim($_POST['comments'] ?? '');
        
        $errors = [];
        
        if (empty($rating) || !is_numeric($rating) || $rating < 1 || $rating > 5) {
            $errors[] = 'Valid rating (1-5) is required';
        }
        
        if (!empty($errors)) {
            $_SESSION['feedback_errors'] = $errors;
            header('Location: ' . BASE_URL . '/index.php?route=ngo-feedback&id=' . $requestId);
            exit;
        }
        
        if ($this->feedbackModel->submit($ngoId, $request['donation_id'], $rating, $comments)) {
            $_SESSION['feedback_success'] = 'Feedback submitted successfully!';
            header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
            exit;
        } else {
            $_SESSION['feedback_errors'] = ['Failed to submit feedback'];
            header('Location: ' . BASE_URL . '/index.php?route=ngo-feedback&id=' . $requestId);
            exit;
        }
    }
    
    /**
     * Display notifications
     */
    public function notifications() {
        $ngoId = $_SESSION['user_id'];
        
        $notifications = $this->notificationModel->getUserNotifications($ngoId);
        
        require_once __DIR__ . '/../views/ngo/notifications.php';
    }
    
    /**
     * Mark notification as read
     */
    public function markNotificationRead($notificationId) {
        $ngoId = $_SESSION['user_id'];
        
        $this->notificationModel->markAsRead($notificationId);
        
        header('Location: ' . BASE_URL . '/index.php?route=ngo-notifications');
        exit;
    }
    
    /**
     * Mark all notifications as read
     */
    public function markAllNotificationsRead() {
        $ngoId = $_SESSION['user_id'];
        
        $this->notificationModel->markAllAsRead($ngoId);
        
        header('Location: ' . BASE_URL . '/index.php?route=ngo-notifications');
        exit;
    }
}
?>
