<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

// Include database connection
require_once __DIR__ . '../../config/database.php';

// Fetch restocks data
$restocksQuery = "SELECT r.restock_id, s.name AS supplier_name, r.restock_date, r.total_amount 
                  FROM restocks r 
                  JOIN suppliers s ON r.supplier_id = s.supplier_id
                  ORDER BY r.restock_date DESC";
$restocksResult = $mysqli->query($restocksQuery);
if (!$restocksResult) {
    die("Error fetching restocks: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Stocks Management</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex">

    <!-- Sidebar -->
    <aside class="w-1/4 bg-gray-800 text-white h-screen fixed">
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-2xl font-bold">Admin Dashboard</h1>
            <p class="text-sm text-gray-400">Manage everything in one place</p>
        </div>
        <nav class="mt-6">
            <ul>
                <li class="mb-2">
                    <a href="dashboard.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Dashboard</a>
                </li>
                <li class="mb-2">
                    <a href="books.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Books</a>
                </li>
                <li class="mb-2">
                    <a href="users.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Users</a>
                </li>
                <li class="mb-2">
                    <a href="authors.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Authors</a>
                </li>
                <li class="mb-2">
                    <a href="publishers.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Publishers</a>
                </li>
                <li class="mb-2">
                    <a href="suppliers.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Suppliers</a>
                </li>
                <li class="mb-2">
                    <a href="stocks.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Stocks</a>
                </li>
                <li class="mb-2">
                    <a href="sales.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Sales</a>
                </li>
                <li class="mt-6">
                    <a href="../logout.php" class="bg-red-500 px-4 py-2 mx-6 rounded text-center block hover:bg-red-600">
                        Logout
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="w-3/4 ml-auto p-6 h-screen overflow-y-auto">
        <h2 class="text-2xl font-bold mb-6">Stocks Management</h2>

        <!-- Add Restock Form -->
        <div class="bg-white shadow-md rounded p-6 mb-6">
            <h3 class="text-lg font-semibold mb-4">Add New Restock</h3>
            <form action="./restocks/add_restock.php" method="POST" class="space-y-4">
                <div>
                    <label for="supplier" class="block font-medium">Supplier</label>
                    <select name="supplier_id" id="supplier" required class="w-full border-gray-300 rounded">
                        <option value="">Select Supplier</option>
                        <?php
                        $suppliers = $mysqli->query("SELECT supplier_id, name FROM suppliers");
                        if ($suppliers) {
                            while ($supplier = $suppliers->fetch_assoc()) {
                                echo "<option value='" . htmlspecialchars($supplier['supplier_id']) . "'>" . htmlspecialchars($supplier['name']) . "</option>";
                            }
                        } else {
                            echo "<option value=''>No suppliers available</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <label for="total_amount" class="block font-medium">Total Amount</label>
                    <input type="number" name="total_amount" id="total_amount" required step="0.01" class="w-full border-gray-300 rounded">
                </div>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Add Restock</button>
                <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600"><a href="./publishers/stock_details.php"> Restock</a></button>
            </form>
        </div>

        <!-- Restocks Table -->
        <div class="bg-white shadow-md rounded p-6">
            <h3 class="text-lg font-semibold mb-4">Restocks</h3>
            <table class="w-full border-collapse border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-4 py-2">Restock ID</th>
                        <th class="border border-gray-300 px-4 py-2">Supplier</th>
                        <th class="border border-gray-300 px-4 py-2">Date</th>
                        <th class="border border-gray-300 px-4 py-2">Total Amount</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($restock = $restocksResult->fetch_assoc()): ?>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2 text-center"><?= htmlspecialchars($restock['restock_id']) ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?= htmlspecialchars($restock['supplier_name']) ?></td>
                            <td class="border border-gray-300 px-4 py-2 text-center"><?= htmlspecialchars($restock['restock_date']) ?></td>
                            <td class="border border-gray-300 px-4 py-2 text-right">$<?= number_format($restock['total_amount'], 2) ?></td>
                            <td class="border border-gray-300 px-4 py-2 text-center">
                                <a href="view_restock.php?id=<?= htmlspecialchars($restock['restock_id']) ?>" class="text-blue-500 hover:underline">View</a> |
                                <a href="../restocks/delete_restock.php?id=<?= htmlspecialchars($restock['restock_id']) ?>" class="text-red-500 hover:underline">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </main>

</body>
</html>
