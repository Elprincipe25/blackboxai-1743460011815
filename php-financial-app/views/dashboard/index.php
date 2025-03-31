<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle; ?> - Financial Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100">
    <div class="flex h-screen">
        <!-- Sidebar -->
        <div class="bg-blue-800 text-white w-64 p-4">
            <h1 class="text-xl font-bold mb-6">Financial System</h1>
            <div class="mb-4">
                <div class="flex items-center mb-2 p-2 rounded hover:bg-blue-700">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    <span>Dashboard</span>
                </div>
                <div class="flex items-center mb-2 p-2 rounded hover:bg-blue-700">
                    <i class="fas fa-exchange-alt mr-2"></i>
                    <span>Transactions</span>
                </div>
                <?php if ($userRole === 'director'): ?>
                    <div class="flex items-center mb-2 p-2 rounded hover:bg-blue-700">
                        <i class="fas fa-users mr-2"></i>
                        <span>User Management</span>
                    </div>
                <?php endif; ?>
                <div class="flex items-center mb-2 p-2 rounded hover:bg-blue-700">
                    <i class="fas fa-chart-bar mr-2"></i>
                    <span>Reports</span>
                </div>
            </div>
            <div class="mt-auto">
                <a href="login.php?action=logout" class="flex items-center p-2 rounded hover:bg-blue-700">
                    <i class="fas fa-sign-out-alt mr-2"></i>
                    <span>Logout</span>
                </a>
            </div>
        </div>

        <!-- Main Content -->
        <div class="flex-1 p-8">
            <h2 class="text-2xl font-bold mb-6">Welcome, <?php echo $_SESSION['username']; ?></h2>
            
            <!-- Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 mb-2">Total Income</h3>
                    <p class="text-3xl font-bold text-green-500">$<?php echo number_format($totalIncome, 2); ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 mb-2">Total Expenses</h3>
                    <p class="text-3xl font-bold text-red-500">$<?php echo number_format($totalExpenses, 2); ?></p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow">
                    <h3 class="text-gray-500 mb-2">Net Profit</h3>
                    <p class="text-3xl font-bold <?php echo $netProfit >= 0 ? 'text-green-500' : 'text-red-500'; ?>">
                        $<?php echo number_format($netProfit, 2); ?>
                    </p>
                </div>
            </div>

            <!-- Recent Transactions -->
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-xl font-bold mb-4">Recent Transactions</h3>
                <?php if (!empty($recentTransactions)): ?>
                    <table class="w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left p-2">Date</th>
                                <th class="text-left p-2">Description</th>
                                <th class="text-left p-2">Type</th>
                                <th class="text-right p-2">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentTransactions as $transaction): ?>
                                <tr class="border-b hover:bg-gray-50">
                                    <td class="p-2"><?php echo date('M d, Y', strtotime($transaction['created_at'])); ?></td>
                                    <td class="p-2"><?php echo $transaction['description']; ?></td>
                                    <td class="p-2">
                                        <span class="px-2 py-1 rounded-full text-xs <?php echo $transaction['type'] === 'income' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'; ?>">
                                            <?php echo ucfirst($transaction['type']); ?>
                                        </span>
                                    </td>
                                    <td class="p-2 text-right">
                                        <?php echo '$' . number_format($transaction['amount'], 2); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-gray-500">No recent transactions found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>