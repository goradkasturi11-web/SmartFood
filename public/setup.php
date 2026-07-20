<?php
/**
 * Database Setup Script
 * Run this file to initialize the database
 */

// Load configuration
require_once __DIR__ . '/../config/config.php';

try {
    $db = Database::getInstance()->getConnection();
    
    // Read the SQL file
    $sqlFile = __DIR__ . '/../database/init.sql';
    if (!file_exists($sqlFile)) {
        die("Error: SQL file not found at $sqlFile");
    }
    
    $sql = file_get_contents($sqlFile);
    
    // Split SQL into individual statements
    $statements = explode(';', $sql);
    
    $successCount = 0;
    $errorCount = 0;
    
    foreach ($statements as $statement) {
        $statement = trim($statement);
        if (empty($statement)) {
            continue;
        }
        
        try {
            $db->exec($statement);
            $successCount++;
        } catch (PDOException $e) {
            // Ignore errors for existing tables/databases
            if (strpos($e->getMessage(), 'already exists') === false) {
                $errorCount++;
                echo "Warning: " . $e->getMessage() . "<br>";
            }
        }
    }
    
    echo "<h2>Database Setup Complete</h2>";
    echo "<p>Successfully executed $successCount statements.</p>";
    if ($errorCount > 0) {
        echo "<p>Encountered $errorCount errors (some may be expected).</p>";
    }
    echo "<p><a href='index.php?route=home'>Go to Home Page</a></p>";
    
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
