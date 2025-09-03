<?php
// Maharati Platform Configuration
// Main configuration file for the platform

// Error reporting for development (disable in production)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'maharati_db'); // Database name
define('DB_USER', 'root'); // Change this in production
define('DB_PASS', ''); // Change this in production
define('DB_CHARSET', 'utf8mb4');

// Gemini AI Configuration
define('GEMINI_API_KEY', 'AIzaSyARwfE40onw7xRsNUOFQhrVRUd0vd7qTBg'); // Replace with actual API key
define('GEMINI_API_URL', 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent');

// Platform Settings
define('SITE_NAME', 'مهاراتي - منصة المهارات الحياتية');
define('SITE_URL', 'http://localhost/ay%20haga'); // Adjust for your setup
define('DEFAULT_TIMEZONE', 'Africa/Cairo');
define('SUBSCRIPTION_BASIC_PRICE', 30.00);
define('SUBSCRIPTION_PREMIUM_PRICE', 60.00);

// Security Settings
define('ENCRYPTION_KEY', 'maharati_secure_key_2024'); // Change this in production
define('SESSION_TIMEOUT', 3600); // 1 hour
define('PASSWORD_MIN_LENGTH', 8);
define('JWT_SECRET', 'maharati_jwt_secret_key'); // Change this in production

// File Upload Settings
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_IMAGE_TYPES', ['jpg', 'jpeg', 'png', 'gif', 'webp']);
define('UPLOAD_PATH', __DIR__ . '/../uploads/');

// Email Configuration (for notifications)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'your_email@gmail.com');
define('SMTP_PASSWORD', 'your_app_password');
define('FROM_EMAIL', 'noreply@maharati.com');
define('FROM_NAME', 'منصة مهاراتي');

// Set timezone
date_default_timezone_set(DEFAULT_TIMEZONE);

// Start session with secure settings
ini_set('session.cookie_httponly', 1);
ini_set('session.cookie_secure', 0); // Set to 1 for HTTPS
ini_set('session.use_strict_mode', 1);
ini_set('session.cookie_samesite', 'Strict');
session_start();

// Auto-load classes
spl_autoload_register(function ($class_name) {
    $directories = [
        __DIR__ . '/../classes/',
        __DIR__ . '/../includes/',
        __DIR__ . '/../api/'
    ];
    
    foreach ($directories as $directory) {
        $file = $directory . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Database Connection Class
class Database {
    private static $instance = null;
    private $pdo;
    
    private function __construct() {
        try {
            $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
            $this->pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ]);
        } catch(PDOException $e) {
            error_log("Database connection failed: " . $e->getMessage());
            die("خطأ في الاتصال بقاعدة البيانات");
        }
    }
    
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    public function getConnection() {
        return $this->pdo;
    }
    
    public function beginTransaction() {
        return $this->pdo->beginTransaction();
    }
    
    public function commit() {
        return $this->pdo->commit();
    }
    
    public function rollback() {
        return $this->pdo->rollback();
    }
}

// Utility Functions
function sanitizeInput($input) {
    return htmlspecialchars(trim($input), ENT_QUOTES, 'UTF-8');
}

function generateToken($length = 32) {
    return bin2hex(random_bytes($length));
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function isLoggedIn() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /maharati_platform/login.php');
        exit;
    }
}

function redirectTo($url) {
    header("Location: $url");
    exit;
}

function jsonResponse($data, $status = 200) {
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

function logError($message, $context = []) {
    $logMessage = date('Y-m-d H:i:s') . " - " . $message;
    if (!empty($context)) {
        $logMessage .= " - Context: " . json_encode($context, JSON_UNESCAPED_UNICODE);
    }
    error_log($logMessage . PHP_EOL, 3, __DIR__ . '/../logs/error.log');
}

// Create necessary directories
$directories = [
    __DIR__ . '/../logs',
    __DIR__ . '/../uploads',
    __DIR__ . '/../uploads/avatars',
    __DIR__ . '/../uploads/skills'
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// Initialize error log file
$errorLogFile = __DIR__ . '/../logs/error.log';
if (!file_exists($errorLogFile)) {
    file_put_contents($errorLogFile, "Maharati Platform Error Log - Started: " . date('Y-m-d H:i:s') . PHP_EOL);
}
?>
