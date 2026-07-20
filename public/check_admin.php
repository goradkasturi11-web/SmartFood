<?php
/**
 * Check and Create Admin User
 */

require_once __DIR__ . '/../config/config.php';

echo "<h2>Admin User Check</h2>";

try {
    $db = Database::getInstance()->getConnection();
    
    // Check if admin user exists
    $stmt = $db->prepare("SELECT * FROM users WHERE email = 'admin@smartfood.com'");
    $stmt->execute();
    $admin = $stmt->fetch();
    
    if ($admin) {
        echo "<p style='color: green;'>✓ Admin user exists</p>";
        echo "<p>Email: " . htmlspecialchars($admin['email']) . "</p>";
        echo "<p>Name: " . htmlspecialchars($admin['name']) . "</p>";
        echo "<p>Role: " . htmlspecialchars($admin['role']) . "</p>";
        
        // Test password verification
        $testPassword = 'admin123';
        if (password_verify($testPassword, $admin['password'])) {
            echo "<p style='color: green;'>✓ Password verification works</p>";
        } else {
            echo "<p style='color: red;'>✗ Password verification failed</p>";
            echo "<p>Recreating admin user with correct password...</p>";
            
            // Delete and recreate
            $stmt = $db->prepare("DELETE FROM users WHERE email = 'admin@smartfood.com'");
            $stmt->execute();
            
            $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
            $stmt = $db->prepare("INSERT INTO users (name, email, phone, password, role, address) VALUES ('Admin', 'admin@smartfood.com', '1234567890', ?, 'admin', 'Admin Office')");
            $stmt->execute([$hashedPassword]);
            
            echo "<p style='color: green;'>✓ Admin user recreated</p>";
        }
    } else {
        echo "<p style='color: red;'>✗ Admin user does not exist</p>";
        echo "<p>Creating admin user...</p>";
        
        $hashedPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $stmt = $db->prepare("INSERT INTO users (name, email, phone, password, role, address) VALUES ('Admin', 'admin@smartfood.com', '1234567890', ?, 'admin', 'Admin Office')");
        $stmt->execute([$hashedPassword]);
        
        echo "<p style='color: green;'>✓ Admin user created</p>";
    }
    
    echo "<h3>Login Credentials:</h3>";
    echo "<p><strong>Email:</strong> admin@smartfood.com</p>";
    echo "<p><strong>Password:</strong> admin123</p>";
    
    echo "<h3>Actions:</h3>";
    echo "<p><a href='index.php?route=login'>Go to Login</a></p>";
    echo "<p><a href='debug.php'>Back to Debug</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>Database error: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p>Please run setup.php first: <a href='setup.php'>Setup Database</a></p>";
}
?>
