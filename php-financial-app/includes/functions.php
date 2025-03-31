<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function isDirector() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'director';
}

function isAccountant() {
    return isset($_SESSION['role']) && $_SESSION['role'] === 'accountant';
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sanitize($data) {
    return htmlspecialchars(strip_tags(trim($data)));
}

function passwordHash($password) {
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function checkPermission($requiredRole) {
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== $requiredRole) {
        $_SESSION['error'] = "Unauthorized access";
        redirect('index.php');
    }
}

function displayError() {
    if (isset($_SESSION['error'])) {
        echo '<div class="alert alert-danger">'.$_SESSION['error'].'</div>';
        unset($_SESSION['error']);
    }
}

function displaySuccess() {
    if (isset($_SESSION['success'])) {
        echo '<div class="alert alert-success">'.$_SESSION['success'].'</div>';
        unset($_SESSION['success']);
    }
}
?>