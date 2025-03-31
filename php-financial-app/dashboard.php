<?php
require_once __DIR__.'/includes/config.php';
require_once __DIR__.'/controllers/DashboardController.php';

$dashboardController = new DashboardController($db);
$dashboardController->index();
?>