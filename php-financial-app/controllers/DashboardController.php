<?php
require_once __DIR__.'/../includes/BaseController.php';

class DashboardController extends BaseController {
    public function index() {
        $this->checkAuth();
        
        $data = [
            'pageTitle' => 'Dashboard',
            'userRole' => $_SESSION['role'],
            'businessType' => $_SESSION['business_type']
        ];

        // Get recent transactions (last 7 days)
        $stmt = $this->db->prepare("
            SELECT * FROM transactions 
            WHERE user_id = ? AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
            ORDER BY created_at DESC LIMIT 5
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $data['recentTransactions'] = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Get summary data
        $stmt = $this->db->prepare("
            SELECT 
                SUM(CASE WHEN type = 'income' THEN amount ELSE 0 END) as total_income,
                SUM(CASE WHEN type = 'expense' THEN amount ELSE 0 END) as total_expenses
            FROM transactions 
            WHERE user_id = ? AND is_verified = 1
        ");
        $stmt->execute([$_SESSION['user_id']]);
        $summary = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $data['totalIncome'] = $summary['total_income'] ?? 0;
        $data['totalExpenses'] = $summary['total_expenses'] ?? 0;
        $data['netProfit'] = $data['totalIncome'] - $data['totalExpenses'];

        $this->render('dashboard/index', $data);
    }
}

// Initialize and use the controller
if (isset($_SESSION['user_id'])) {
    $dashboardController = new DashboardController($db);
    $dashboardController->index();
} else {
    header("Location: login.php");
    exit();
}
?>