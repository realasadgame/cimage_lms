<?php
// Database Configuration for CIMAGE LMS
define('DB_HOST', 'localhost');
define('DB_NAME', 'cimage_lms');
define('DB_USER', 'root');
define('DB_PASS', '');

// Create database connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set charset to utf8
$conn->set_charset("utf8mb4");

// Security settings
define('SITE_URL', 'http://localhost/cimage_lms/');
define('ADMIN_URL', SITE_URL . 'admin/');

// Session security
ini_set('session.cookie_httponly', 1);
ini_set('session.use_only_cookies', 1);
ini_set('session.cookie_secure', 0); // Set to 1 if using HTTPS

// Start secure session
session_start();

// Helper functions
function clean_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function hash_password($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verify_password($password, $hash) {
    return password_verify($password, $hash);
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function is_logged_in() {
    return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
}

function is_admin() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'admin';
}

function is_student() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'student';
}

function is_faculty() {
    return isset($_SESSION['user_type']) && $_SESSION['user_type'] === 'faculty';
}

function get_user_data() {
    if (is_logged_in()) {
        return $_SESSION['user_data'];
    }
    return null;
}

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1); // Set to 0 in production
?>
