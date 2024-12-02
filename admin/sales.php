<?php
require_once __DIR__ . '../../config/database.php';

// Fetch sales records
$query = "SELECT s.sales_id, u.username, s.purchase_date, s.total_amount, s.stock_quantity, b.title 
          FROM sales s 
          JOIN users u ON s.user_id = u.user_id 
          JOIN books b ON s.book_id = b.book_id";

$result = mysqli_query($mysqli, $query);

if (!$result) {
    die("Error fetching sales records: " . mysqli_error($mysqli));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sales Records - Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100 text-gray-800">

    <!-- Sidebar -->
    <aside class="w-1/4 bg-gray-800 text-white min-h-screen">
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-2xl font-bold">Admin Dashboard</h1>
            <p class="text-sm text-gray-400">Manage everything at one place</p>
        </div>
        <nav class="mt-6">
            <ul>
                <li><a href="dashboard.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Dashboard</a></li>
                <li><a href="books.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Books</a></li>
                <li><a href="users.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Users</a></li>
                <li><a href="authors.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Authors</a></li>
                <li><a href="publishers.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Publishers</a></li>
                <li><a href="suppliers.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Suppliers</a></li>
                <li><a href="stocks.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Stocks</a></li>
                <li><a href="sales.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Sales</a></li>
                <li class="mt-6">
                    <a href="../logout.php" class="bg-red-500 px-4 py-2 mx-6 rounded text-center block hover:bg-red-600">Logout</a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="w-3/4 p-8">
        <header class="mb-6">
            <h2 class="text-3xl font-bold">Sales Records</h2>
            <p class="text-gray-600">Detailed overview of all sales transactions</p>
        </header>

        <!-- Sales Table -->
        <div class="bg-white rounded-lg shadow p-6">
            <table class="table-auto w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2 text-left">Sale ID</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Username</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Book Title</th>
                        <th class="border border-gray-300 px-4 py-2 text-left">Purchase Date</th>
                        <th class="border border-gray-300 px-4 py-2 text-right">Total Amount</th>
                        <th class="border border-gray-300 px-4 py-2 text-right">Quantity</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr class="hover:bg-gray-50">
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['sales_id']; ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['username']); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($row['title']); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo $row['purchase_date']; ?></td>
                            <td class="border border-gray-300 px-4 py-2 text-right"><?php echo number_format($row['total_amount'], 2); ?></td>
                            <td class="border border-gray-300 px-4 py-2 text-right"><?php echo $row['stock_quantity']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
