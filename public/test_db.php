<?php
/**
 * Database Connection Test
 */

// Load configuration
require_once __DIR__ . '/../config/config.php';

echo "<h2>Database Connection Test</h2>";

try {
    $db = Database::getInstance()->getConnection();
    echo "<p style='color: green;'>✓ Database connection successful!</p>";
    
    // Test if tables exist
    $tables = ['users', 'ngos', 'donations', 'requests', 'feedback', 'notifications'];
    
    echo "<h3>Checking Tables:</h3>";
    foreach ($tables as $table) {
        try {
            $result = $db->query("SELECT 1 FROM $table LIMIT 1");
            echo "<p style='color: green;'>✓ Table '$table' exists</p>";
        } catch (PDOException $e) {
            echo "<p style='color: red;'>✗ Table '$table' does not exist</p>";
        }
    }
    
    echo "<h3>Actions:</h3>";
    echo "<p><a href='setup.php'>Run Database Setup</a></p>";
    echo "<p><a href='index.php?route=home'>Go to Home Page</a></p>";
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
    echo "<p>Please check your database configuration in config/database.php</p>";
}
?>
