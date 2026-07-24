<?php
/**

 * Smart Food Redistribution Platform
 */

// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Base URL - Auto-detect if not set
if (!defined('BASE_URL')) {
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'];
    $path = dirname($_SERVER['SCRIPT_NAME']);
    define('BASE_URL', $protocol . '://' . $host . $path);
}

// Upload URL - points to the uploaded files directory
if (!defined('UPLOAD_URL')) {
    define('UPLOAD_URL', rtrim(dirname(BASE_URL), '/') . '/uploads');
}

// Site name
define('SITE_NAME', 'Smart Food Redistribution Platform');

// File upload settings
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('MAX_FILE_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_EXTENSIONS', ['jpg', 'jpeg', 'png', 'gif']);

// Timezone
date_default_timezone_set('UTC');

// Error reporting (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Autoload Database class from config folder
spl_autoload_register(function($class_name) {
    if ($class_name === 'Database') {
        $file = __DIR__ . '/database.php';
        if (file_exists($file)) {
            require_once $file;
        }
    }
});

// Autoload models
spl_autoload_register(function($class_name) {
    $file = __DIR__ . '/../models/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// Autoload controllers
spl_autoload_register(function($class_name) {
    $file = __DIR__ . '/../controllers/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});
?>
