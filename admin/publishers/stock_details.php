<?php
session_start();

// Check if the admin is logged in
$admin = isset($_SESSION['admin_id']);

// Include database connection
require_once __DIR__ . '../../../config/database.php';

// Fetch restock details from the database
$query = "SELECT * FROM restock_details";
$result = $mysqli->query($query);

// Handle Add, Edit, and Delete functionality here if needed (can be moved to separate PHP files)
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restock Details - Admin Dashboard</title>
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
                    <a href="../dashboard.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Dashboard</a>
                </li>
                <li class="mb-2">
                    <a href="../books.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Books</a>
                </li>
                <li class="mb-2">
                    <a href="../users.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Users</a>
                </li>
                <li class="mb-2">
                    <a href="../authors.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Authors</a>
                </li>
                <li class="mb-2">
                    <a href="../publishers.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Publishers</a>
                </li>
                <li class="mb-2">
                    <a href="../suppliers.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Suppliers</a>
                </li>
                <li class="mb-2">
                    <a href="../stocks.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Stocks</a>
                </li>
                <li class="mb-2">
                    <a href="../sales.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Sales</a>
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
        <h1 class="text-2xl font-bold mb-6">Restock Details</h1>

        <!-- Add New Restock Details Form -->
        <section class="bg-white p-6 rounded-lg shadow mb-8">
            <h3 class="text-xl font-semibold mb-4">Add New Restock Detail</h3>
            <form action="./add_stocksDetails.php" method="POST" class="space-y-4">
                <div>
                    <label for="restock_id" class="block text-sm font-medium text-gray-700">Restock ID</label>
                    <input type="number" id="restock_id" name="restock_id" required class="mt-2 w-full px-3 py-2 border rounded-md">
                </div>
                
                <div>
                    <label for="price_per_unit" class="block text-sm font-medium text-gray-700">Price Per Unit</label>
                    <input type="text" id="price_per_unit" name="price_per_unit" required class="mt-2 w-full px-3 py-2 border rounded-md">
                </div>

                <div>
                    <label for="publisher_id" class="block text-sm font-medium text-gray-700">Publisher ID</label>
                    <input type="number" id="publisher_id" name="publisher_id" required class="mt-2 w-full px-3 py-2 border rounded-md">
                </div>

                <div>
                    <label for="book_id" class="block text-sm font-medium text-gray-700">Book ID</label>
                    <input type="number" id="book_id" name="book_id" required class="mt-2 w-full px-3 py-2 border rounded-md">
                </div>

                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-md">Add Restock</button>
            </form>
        </section>

        <!-- Restock Details Table -->
        <section class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-semibold mb-4">Restock Details</h3>
            <table class="min-w-full table-auto border-collapse">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">Restock ID</th>
                        <th class="px-4 py-2 border-b">Price Per Unit</th>
                        <th class="px-4 py-2 border-b">Publisher ID</th>
                        <th class="px-4 py-2 border-b">Book ID</th>
                        <th class="px-4 py-2 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="px-4 py-2 border-b"><?= $row['restock_id'] ?></td>
                            <td class="px-4 py-2 border-b"><?= $row['price_per_unit'] ?></td>
                            <td class="px-4 py-2 border-b"><?= $row['publisher_id'] ?></td>
                            <td class="px-4 py-2 border-b"><?= $row['book_id'] ?></td>
                            <td class="px-4 py-2 border-b flex space-x-4">
                                <a href="edit_restock_detail.php?id=<?= $row['restock_detail_id'] ?>" class="text-blue-500 hover:underline">Edit</a>
                                <a href="delete_restock_detail.php?id=<?= $row['restock_detail_id'] ?>" class="text-red-500 hover:underline">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>

</body>
</html>
