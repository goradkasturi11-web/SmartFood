<?php
/**
 * Debug Page
 * Helps diagnose common issues
 */

session_start();

echo "<h2>Smart Food Platform Debug</h2>";

// Check session
echo "<h3>Session Status:</h3>";
if (isset($_SESSION['user_id'])) {
    echo "<p style='color: green;'>✓ Logged in as: " . htmlspecialchars($_SESSION['user_name']) . "</p>";
    echo "<p>Role: " . htmlspecialchars($_SESSION['user_role']) . "</p>";
    echo "<p>User ID: " . htmlspecialchars($_SESSION['user_id']) . "</p>";
} else {
    echo "<p style='color: red;'>✗ Not logged in</p>";
}

// Check database
echo "<h3>Database Status:</h3>";
require_once __DIR__ . '/../config/config.php';
try {
    $db = Database::getInstance()->getConnection();
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Check admin user
    $stmt = $db->prepare("SELECT * FROM users WHERE email = 'admin@smartfood.com'");
    $stmt->execute();
    $admin = $stmt->fetch();
    if ($admin) {
        echo "<p style='color: green;'>✓ Admin user exists</p>";
    } else {
        echo "<p style='color: red;'>✗ Admin user not found - run setup.php</p>";
    }
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

// Check file structure
echo "<h3>File Structure:</h3>";
$files = [
    'config/config.php',
    'config/database.php',
    'controllers/AdminController.php',
    'views/admin/dashboard.php'
];
foreach ($files as $file) {
    $path = __DIR__ . '/../' . $file;
    if (file_exists($path)) {
        echo "<p style='color: green;'>✓ $file</p>";
    } else {
        echo "<p style='color: red;'>✗ $file (missing)</p>";
    }
}

// Actions
echo "<h3>Actions:</h3>";
echo "<p><a href='setup.php'>Run Database Setup</a></p>";
echo "<p><a href='index.php?route=login'>Go to Login</a></p>";
echo "<p><a href='index.php?route=home'>Go to Home</a></p>";
if (isset($_SESSION['user_id']) && $_SESSION['user_role'] === 'admin') {
    echo "<p><a href='index.php?route=admin-dashboard'>Go to Admin Dashboard</a></p>";
}
?>
