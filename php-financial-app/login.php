<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/controllers/AuthController.php';

// Handle login requests
if (isset($_GET['action']) && $_GET['action'] === 'login') {
    $authController = new AuthController($db);
    $authController->login();
} else {
    // Display login form
    if (isLoggedIn()) {
        redirect('dashboard.php');
    }
    require_once __DIR__.'/views/auth/login.php';
}
?>