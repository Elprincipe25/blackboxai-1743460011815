<?php
class BaseController {
    protected $db;
    protected $viewPath = __DIR__.'/../views/';

    public function __construct($db) {
        $this->db = $db;
    }

    protected function render($view, $data = []) {
        extract($data);
        require $this->viewPath . $view . '.php';
    }

    protected function redirect($url) {
        header("Location: $url");
        exit();
    }

    protected function sanitize($input) {
        if (is_array($input)) {
            return array_map([$this, 'sanitize'], $input);
        }
        return htmlspecialchars(strip_tags(trim($input)));
    }

    protected function checkAuth() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error'] = 'Please login first';
            $this->redirect('login.php');
        }
    }

    protected function checkRole($requiredRole) {
        $this->checkAuth();
        if ($_SESSION['role'] !== $requiredRole) {
            $_SESSION['error'] = 'Unauthorized access';
            $this->redirect('dashboard.php');
        }
    }
}
?>