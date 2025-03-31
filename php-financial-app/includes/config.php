<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'financial_management');
define('DB_USER', 'root');
define('DB_PASS', '');

// Application settings
define('APP_NAME', 'Financial Management System');
define('BASE_URL', 'http://localhost/php-financial-app');

// Start session
session_start();

// Database connection
try {
    $db = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Include common functions
require_once 'functions.php';
?>