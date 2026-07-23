<?php
/**
 * Donor Controller
 * Handles donor dashboard and donation management
 */

class DonorController {
    private $donationModel;
    private $requestModel;
    private $notificationModel;
    
    public function __construct() {
        $this->donationModel = new Donation();
        $this->requestModel = new Request();
        $this->notificationModel = new Notification();
        
        // Check if user is logged in and is a donor
        if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'donor') {
            header('Location: ' . BASE_URL . '/index.php?route=login');
            exit;
        }
    }
    
    /**
     * Display donor dashboard
     */
    public function dashboard() {
        $donorId = $_SESSION['user_id'];
        
        // Get donor's donations
        $donations = $this->donationModel->getDonationsByDonor($donorId);
        
        // Get unread notification count
        $unreadCount = $this->notificationModel->getUnreadCount($donorId);
        
        require_once __DIR__ . '/../views/donor/dashboard.php';
    }
    
    /**
     * Display add donation form
     */
    public function showAddDonation() {
        require_once __DIR__ . '/../views/donor/add_donation.php';
    }
    
    /**
     * Handle add donation submission
     */
    public function addDonation() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?route=donor-add-donation');
            exit;
        }
        
        // Prevent duplicate form submission
        if (isset($_SESSION['form_token']) && isset($_POST['form_token'])) {
            if ($_SESSION['form_token'] !== $_POST['form_token']) {
                $_SESSION['donation_errors'] = ['Invalid form submission. Please try again.'];
                header('Location: ' . BASE_URL . '/index.php?route=donor-add-donation');
                exit;
            }
        }
        
        // Clear the form token after validation
        unset($_SESSION['form_token']);
        
        $donorId = $_SESSION['user_id'];
        
        $foodName = trim($_POST['food_name'] ?? '');
        $quantityValue = $_POST['quantity_value'] ?? '';
        $quantityUnit = $_POST['quantity_unit'] ?? '';
        $foodType = $_POST['food_type'] ?? '';
        $preparationDate = $_POST['preparation_date'] ?? '';
        $expiryTime = $_POST['expiry_time'] ?? '';
        $pickupLocation = trim($_POST['pickup_location'] ?? '');
        $latitude = $_POST['latitude'] ?? null;
        $longitude = $_POST['longitude'] ?? null;
        
        $errors = [];
        
        if (empty($foodName)) {
            $errors[] = 'Food name is required';
        }
        
        if (strlen($foodName) > 100) {
    $errors[] = 'Food name is too long.';
}
        if (empty($quantityValue) || !is_numeric($quantityValue)) {
            $errors[] = 'Valid quantity is required';
        }
        
        if (empty($quantityUnit)) {
            $errors[] = 'Quantity unit is required';
        }
        
        if (empty($foodType)) {
            $errors[] = 'Food type is required';
        }
        
        if (empty($preparationDate)) {
            $errors[] = 'Preparation date is required';
        }
        
        if (empty($expiryTime)) {
            $errors[] = 'Expiry time is required';
        }
        
        if (empty($pickupLocation)) {
            $errors[] = 'Pickup location is required';
        }
        
        // Handle image upload
        $imagePath = null;
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->handleImageUpload($_FILES['image']);
            if ($uploadResult['success']) {
                $imagePath = $uploadResult['path'];
            } else {
                $errors[] = $uploadResult['error'];
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['donation_errors'] = $errors;
            $_SESSION['donation_data'] = $_POST;
            header('Location: ' . BASE_URL . '/index.php?route=donor-add-donation');
            exit;
        }
        
        $donationData = [
            'donor_id' => $donorId,
            'food_name' => $foodName,
            'quantity_value' => $quantityValue,
            'quantity_unit' => $quantityUnit,
            'food_type' => $foodType,
            'preparation_date' => $preparationDate,
            'expiry_time' => $expiryTime,
            'pickup_location' => $pickupLocation,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'image_path' => $imagePath
        ];
        
        $donationId = $this->donationModel->create($donationData);
        
        if ($donationId) {
            // Notify nearby NGOs
            $this->notificationModel->notifyNearbyNGOs($donationId, $latitude, $longitude);
            
            $_SESSION['donation_success'] = 'Donation added successfully!';
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        } else {
            $_SESSION['donation_errors'] = ['Failed to add donation. Please try again.'];
            $_SESSION['donation_data'] = $_POST;
            header('Location: ' . BASE_URL . '/index.php?route=donor-add-donation');
            exit;
        }
    }
    
    /**
     * Display edit donation form
     */
    public function showEditDonation($donationId) {
        $donation = $this->donationModel->getDonationById($donationId);
        
        if (!$donation || $donation['donor_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Donation not found or access denied';
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        }
        
        require_once __DIR__ . '/../views/donor/edit_donation.php';
    }
    
    /**
     * Handle edit donation submission
     */
    public function editDonation($donationId) {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        }
        
        $donation = $this->donationModel->getDonationById($donationId);
        
        if (!$donation || $donation['donor_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Donation not found or access denied';
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        }
        
        // Only allow editing if donation is still available
        if ($donation['status'] !== 'available') {
            $_SESSION['error'] = 'Cannot edit donation that is not available';
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        }
        
        $foodName = trim($_POST['food_name'] ?? '');
        $quantityValue = $_POST['quantity_value'] ?? '';
        $quantityUnit = $_POST['quantity_unit'] ?? '';
        $foodType = $_POST['food_type'] ?? '';
        $preparationDate = $_POST['preparation_date'] ?? '';
        $expiryTime = $_POST['expiry_time'] ?? '';
        $pickupLocation = trim($_POST['pickup_location'] ?? '');
        $latitude = $_POST['latitude'] ?? null;
        $longitude = $_POST['longitude'] ?? null;
        
        $errors = [];
        
        if (empty($foodName)) {
            $errors[] = 'Food name is required';
        }
        
        if (empty($quantityValue) || !is_numeric($quantityValue)) {
            $errors[] = 'Valid quantity is required';
        }
        
        if (empty($quantityUnit)) {
            $errors[] = 'Quantity unit is required';
        }
        
        if (empty($foodType)) {
            $errors[] = 'Food type is required';
        }
        
        if (empty($preparationDate)) {
            $errors[] = 'Preparation date is required';
        }
        
        if (empty($expiryTime)) {
            $errors[] = 'Expiry time is required';
        }
        
        if (empty($pickupLocation)) {
            $errors[] = 'Pickup location is required';
        }
        
        // Handle image upload
        $imagePath = $donation['image_path'];
        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $uploadResult = $this->handleImageUpload($_FILES['image']);
            if ($uploadResult['success']) {
                // Delete old image if exists
                if ($imagePath && file_exists(UPLOAD_DIR . $imagePath)) {
                    unlink(UPLOAD_DIR . $imagePath);
                }
                $imagePath = $uploadResult['path'];
            } else {
                $errors[] = $uploadResult['error'];
            }
        }
        
        if (!empty($errors)) {
            $_SESSION['donation_errors'] = $errors;
            header('Location: ' . BASE_URL . '/index.php?route=donor-edit-donation&id=' . $donationId);
            exit;
        }
        
        $donationData = [
            'food_name' => $foodName,
            'quantity_value' => $quantityValue,
            'quantity_unit' => $quantityUnit,
            'food_type' => $foodType,
            'preparation_date' => $preparationDate,
            'expiry_time' => $expiryTime,
            'pickup_location' => $pickupLocation,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'image_path' => $imagePath
        ];
        
        if ($this->donationModel->update($donationId, $donationData)) {
            $_SESSION['donation_success'] = 'Donation updated successfully!';
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        } else {
            $_SESSION['donation_errors'] = ['Failed to update donation. Please try again.'];
            header('Location: ' . BASE_URL . '/index.php?route=donor-edit-donation&id=' . $donationId);
            exit;
        }
    }
    
    /**
     * Delete donation
     */
    public function deleteDonation($donationId) {
        $donation = $this->donationModel->getDonationById($donationId);
        
        if (!$donation || $donation['donor_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Donation not found or access denied';
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        }
        
        // Only allow deletion if donation is still available
        if ($donation['status'] !== 'available') {
            $_SESSION['error'] = 'Cannot delete donation that is not available';
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        }
        
        // Delete image if exists
        if ($donation['image_path'] && file_exists(UPLOAD_DIR . $donation['image_path'])) {
            unlink(UPLOAD_DIR . $donation['image_path']);
        }
        
        if ($this->donationModel->delete($donationId)) {
            $_SESSION['donation_success'] = 'Donation deleted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to delete donation';
        }
        
        header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
        exit;
    }
    
    /**
     * View donation requests
     */
    public function viewRequests($donationId) {
        $donation = $this->donationModel->getDonationById($donationId);
        
        if (!$donation || $donation['donor_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Donation not found or access denied';
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        }
        
        $requests = $this->requestModel->getRequestsByDonation($donationId);
        
        require_once __DIR__ . '/../views/donor/view_requests.php';
    }
    
    /**
     * Accept request
     */
    public function acceptRequest($requestId) {
        $request = $this->requestModel->getRequestById($requestId);
        
        if (!$request) {
            $_SESSION['error'] = 'Request not found';
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        }
        
        $donation = $this->donationModel->getDonationById($request['donation_id']);
        
        if (!$donation || $donation['donor_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Access denied';
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        }
        
        // Update request status
        if ($this->requestModel->updateStatus($requestId, 'approved')) {
            // Update donation status
            $this->donationModel->updateStatus($donation['donation_id'], 'requested');
            
            // Notify NGO
            $this->notificationModel->create(
                $request['ngo_id'],
                'request_approved',
                'Your request for ' . $donation['food_name'] . ' has been approved!',
                $donation['donation_id'],
                $requestId
            );
            
            // Reject other pending requests for this donation
            $allRequests = $this->requestModel->getRequestsByDonation($donation['donation_id']);
            foreach ($allRequests as $req) {
                if ($req['request_id'] != $requestId && $req['status'] == 'pending') {
                    $this->requestModel->updateStatus($req['request_id'], 'rejected');
                    $this->notificationModel->create(
                        $req['ngo_id'],
                        'request_rejected',
                        'Your request for ' . $donation['food_name'] . ' has been rejected.',
                        $donation['donation_id'],
                        $req['request_id']
                    );
                }
            }
            
            $_SESSION['donation_success'] = 'Request accepted successfully!';
        } else {
            $_SESSION['error'] = 'Failed to accept request';
        }
        
        header('Location: ' . BASE_URL . '/index.php?route=donor-view-requests&id=' . $donation['donation_id']);
        exit;
    }
    
    /**
     * Reject request
     */
    public function rejectRequest($requestId) {
        $request = $this->requestModel->getRequestById($requestId);
        
        if (!$request) {
            $_SESSION['error'] = 'Request not found';
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        }
        
        $donation = $this->donationModel->getDonationById($request['donation_id']);
        
        if (!$donation || $donation['donor_id'] != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Access denied';
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        }
        
        if ($this->requestModel->updateStatus($requestId, 'rejected')) {
            // Notify NGO
            $this->notificationModel->create(
                $request['ngo_id'],
                'request_rejected',
                'Your request for ' . $donation['food_name'] . ' has been rejected.',
                $donation['donation_id'],
                $requestId
            );
            
            $_SESSION['donation_success'] = 'Request rejected successfully!';
        } else {
            $_SESSION['error'] = 'Failed to reject request';
        }
        
        header('Location: ' . BASE_URL . '/index.php?route=donor-view-requests&id=' . $donation['donation_id']);
        exit;
    }
    
    /**
     * Handle image upload
     */
    private function handleImageUpload($file) {
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        if (!in_array($extension, ALLOWED_EXTENSIONS)) {
            return ['success' => false, 'error' => 'Invalid file type. Only JPG, PNG, and GIF allowed.'];
        }
        
        if ($file['size'] > MAX_FILE_SIZE) {
            return ['success' => false, 'error' => 'File size exceeds limit (5MB).'];
        }
        
        $filename = uniqid() . '.' . $extension;
        $filepath = UPLOAD_DIR . $filename;
        
        if (!move_uploaded_file($file['tmp_name'], $filepath)) {
            return ['success' => false, 'error' => 'Failed to upload file.'];
        }
        
        return ['success' => true, 'path' => $filename];
    }
}
?>
