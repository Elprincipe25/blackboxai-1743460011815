<?php
require_once __DIR__.'/../includes/BaseController.php';
require_once __DIR__.'/../includes/config.php';

class AuthController extends BaseController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $this->sanitize($_POST['username']);
            $password = $this->sanitize($_POST['password']);

            $stmt = $this->db->prepare("SELECT * FROM users WHERE username = ? AND is_active = 1");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['role'] = $user['role'];
                $_SESSION['business_type'] = $user['business_type'];
                
                $this->redirect('dashboard.php');
            } else {
                $_SESSION['error'] = 'Invalid username or password';
                $this->redirect('login.php');
            }
        } else {
            $this->render('auth/login');
        }
    }

    public function logout() {
        session_unset();
        session_destroy();
        $this->redirect('login.php');
    }
}

// Initialize and use the controller
$authController = new AuthController($db);

if (isset($_GET['action'])) {
    $action = $_GET['action'];
    if ($action === 'login') {
        $authController->login();
    } elseif ($action === 'logout') {
        $authController->logout();
    }
}
?>