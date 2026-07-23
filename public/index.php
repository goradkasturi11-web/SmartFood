<?php
/**
 * Main Entry Point
 * Smart Food Redistribution Platform
 */

// Load configuration
require_once __DIR__ . '/../config/config.php';

// Security Headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: SAMEORIGIN');
header('X-XSS-Protection: 1; mode=block');
header('Referrer-Policy: strict-origin-when-cross-origin');

// Maintenance Mode Check
$maintenanceFile = __DIR__ . '/../.maintenance';
if (file_exists($maintenanceFile)) {
    // Allow access only if user is logged in as admin
    if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
        http_response_code(503);
        require_once __DIR__ . '/../views/maintenance.php';
        exit;
    }
}

// Check if database is set up
try {
    $db = Database::getInstance()->getConnection();
    $db->query("SELECT 1 FROM users LIMIT 1");
} catch (PDOException $e) {
    // Database not set up, redirect to setup
    if (strpos($e->getMessage(), "Table") !== false || strpos($e->getMessage(), "doesn't exist") !== false) {
        echo "<h2>Database Not Set Up</h2>";
        echo "<p>Please run the setup script first: <a href='setup.php'>Click here to setup database</a></p>";
        exit;
    }
}

// Basic Rate Limiting (prevent abuse)
$rateLimitKey = 'rate_limit_' . ($_SERVER['REMOTE_ADDR'] ?? 'unknown');
if (!isset($_SESSION[$rateLimitKey])) {
    $_SESSION[$rateLimitKey] = ['count' => 0, 'time' => time()];
}
$_SESSION[$rateLimitKey]['count']++;
// Reset if more than 60 seconds have passed
if (time() - $_SESSION[$rateLimitKey]['time'] > 60) {
    $_SESSION[$rateLimitKey] = ['count' => 1, 'time' => time()];
}
// Block if more than 100 requests per minute
if ($_SESSION[$rateLimitKey]['count'] > 100) {
    http_response_code(429);
    echo "<h2>Too Many Requests</h2>";
    echo "<p>Please wait a moment before trying again.</p>";
    exit;
}

// Get route from query parameter
$route = $_GET['route'] ?? 'home';

// Validate route to prevent directory traversal
if (preg_match('/\.\./', $route) || preg_match('/[\/\\\\]/', $route)) {
    http_response_code(400);
    echo "<h2>Invalid Route</h2>";
    exit;
}

// Log route access for debugging (optional - can be disabled in production)
if (defined('ENABLE_ROUTE_LOGGING') && ENABLE_ROUTE_LOGGING) {
    $logMessage = date('Y-m-d H:i:s') . " - Route: $route - IP: " . ($_SERVER['REMOTE_ADDR'] ?? 'unknown') . "\n";
    file_put_contents(__DIR__ . '/../logs/route.log', $logMessage, FILE_APPEND);
}

// Route handling
switch ($route) {
    // Public routes
    case 'home':
        require_once __DIR__ . '/../views/home.php';
        break;
        
    case 'login':
        $auth = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->login();
        } else {
            $auth->showLogin();
        }
        break;
        
    case 'register':
        $auth = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth->register();
        } else {
            $auth->showRegister();
        }
        break;
        
    case 'logout':
        $auth = new AuthController();
        $auth->logout();
        break;
        
    // Donor routes
    case 'donor-dashboard':
        $donor = new DonorController();
        $donor->dashboard();
        break;
        
    case 'donor-add-donation':
        $donor = new DonorController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $donor->addDonation();
        } else {
            $donor->showAddDonation();
        }
        break;
        
    case 'donor-edit-donation':
        $donor = new DonorController();
        $donationId = $_GET['id'] ?? null;
        if (!$donationId) {
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $donor->editDonation($donationId);
        } else {
            $donor->showEditDonation($donationId);
        }
        break;
        
    case 'donor-delete-donation':
        $donor = new DonorController();
        $donationId = $_GET['id'] ?? null;
        if ($donationId) {
            $donor->deleteDonation($donationId);
        } else {
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        }
        break;
        
    case 'donor-view-requests':
        $donor = new DonorController();
        $donationId = $_GET['id'] ?? null;
        if (!$donationId) {
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        }
        $donor->viewRequests($donationId);
        break;
        
    case 'donor-accept-request':
        $donor = new DonorController();
        $requestId = $_GET['id'] ?? null;
        if ($requestId) {
            $donor->acceptRequest($requestId);
        } else {
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        }
        break;
        
    case 'donor-reject-request':
        $donor = new DonorController();
        $requestId = $_GET['id'] ?? null;
        if ($requestId) {
            $donor->rejectRequest($requestId);
        } else {
            header('Location: ' . BASE_URL . '/index.php?route=donor-dashboard');
            exit;
        }
        break;
        
    // NGO routes
    case 'ngo-dashboard':
        $ngo = new NGOController();
        $ngo->dashboard();
        break;
        
    case 'ngo-browse-food':
        $ngo = new NGOController();
        $ngo->browseFood();
        break;
        
    case 'ngo-view-donation':
        $ngo = new NGOController();
        $donationId = $_GET['id'] ?? null;
        if (!$donationId) {
            header('Location: ' . BASE_URL . '/index.php?route=ngo-browse-food');
            exit;
        }
        $ngo->viewDonation($donationId);
        break;
        
    case 'ngo-request-donation':
        $ngo = new NGOController();
        $donationId = $_GET['id'] ?? null;
        if ($donationId) {
            $ngo->requestDonation($donationId);
        } else {
            header('Location: ' . BASE_URL . '/index.php?route=ngo-browse-food');
            exit;
        }
        break;
        
    case 'ngo-cancel-request':
        $ngo = new NGOController();
        $requestId = $_GET['id'] ?? null;
        if ($requestId) {
            $ngo->cancelRequest($requestId);
        } else {
            header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
            exit;
        }
        break;
        
    case 'ngo-mark-collected':
        $ngo = new NGOController();
        $requestId = $_GET['id'] ?? null;
        if ($requestId) {
            $ngo->markCollected($requestId);
        } else {
            header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
            exit;
        }
        break;
        
    case 'ngo-feedback':
        $ngo = new NGOController();
        $requestId = $_GET['id'] ?? null;
        if (!$requestId) {
            header('Location: ' . BASE_URL . '/index.php?route=ngo-dashboard');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ngo->submitFeedback($requestId);
        } else {
            $ngo->showFeedback($requestId);
        }
        break;
        
    case 'ngo-notifications':
        $ngo = new NGOController();
        $ngo->notifications();
        break;
        
    case 'ngo-mark-read':
        $ngo = new NGOController();
        $notificationId = $_GET['id'] ?? null;
        if ($notificationId) {
            $ngo->markNotificationRead($notificationId);
        } else {
            header('Location: ' . BASE_URL . '/index.php?route=ngo-notifications');
            exit;
        }
        break;
        
    case 'ngo-mark-all-read':
        $ngo = new NGOController();
        $ngo->markAllNotificationsRead();
        break;
        
    // Admin routes
    case 'admin-dashboard':
        $admin = new AdminController();
        $admin->dashboard();
        break;
        
    case 'admin-ngo-verification':
        $admin = new AdminController();
        $admin->ngoVerification();
        break;
        
    case 'admin-verify-ngo':
        $admin = new AdminController();
        $ngoId = $_GET['id'] ?? null;
        if ($ngoId) {
            $admin->verifyNGO($ngoId);
        } else {
            header('Location: ' . BASE_URL . '/index.php?route=admin-ngo-verification');
            exit;
        }
        break;
        
    case 'admin-reject-ngo':
        $admin = new AdminController();
        $ngoId = $_GET['id'] ?? null;
        if ($ngoId) {
            $admin->rejectNGO($ngoId);
        } else {
            header('Location: ' . BASE_URL . '/index.php?route=admin-ngo-verification');
            exit;
        }
        break;
        
    case 'admin-user-management':
        $admin = new AdminController();
        $admin->userManagement();
        break;
        
    case 'admin-suspend-user':
        $admin = new AdminController();
        $userId = $_GET['id'] ?? null;
        if ($userId) {
            $admin->suspendUser($userId);
        } else {
            header('Location: ' . BASE_URL . '/index.php?route=admin-user-management');
            exit;
        }
        break;
        
    case 'admin-reports':
        $admin = new AdminController();
        $admin->reports();
        break;
        
    case 'admin-donation-history':
        $admin = new AdminController();
        $admin->donationHistory();
        break;
        
    case 'admin-request-history':
        $admin = new AdminController();
        $admin->requestHistory();
        break;
        
    // Default route
    default:
        require_once __DIR__ . '/../views/home.php';
        break;
}
?>
