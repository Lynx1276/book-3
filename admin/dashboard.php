<?php
session_start();

// Check if the admin is logged in
$admin = isset($_SESSION['admin_id']);

// Include database connection
require_once __DIR__ . '../../config/database.php';

// Initialize counts
$totalUsers = 0;
$activeUsers = 0;
$totalSales = 0;
$totalBooks = 0;
$totalAuthors = 0;
$activeAuthors = 0;
$totalPublishers = 0;
$activePublishers = 0;
$totalSuppliers = 0;
$activeSuppliers = 0;

try {
    // Query for total and active users
    $userQuery = "SELECT COUNT(*) as count FROM users";
    $userResult = mysqli_query($mysqli, $userQuery);
    $totalUsers = mysqli_fetch_assoc($userResult)['count'];

    $activeUserQuery = "SELECT COUNT(*) as count FROM users WHERE active = 1"; // Assuming 'is_active' column exists
    $activeUserResult = mysqli_query($mysqli, $activeUserQuery);
    $activeUsers = mysqli_fetch_assoc($activeUserResult)['count'];

    // Query for total sales
    $salesQuery = "SELECT COUNT(*) as count FROM sales";
    $salesResult = mysqli_query($mysqli, $salesQuery);
    $totalSales = mysqli_fetch_assoc($salesResult)['count'];

    // Query for total books
    $booksQuery = "SELECT COUNT(*) as count FROM books";
    $booksResult = mysqli_query($mysqli, $booksQuery);
    $totalBooks = mysqli_fetch_assoc($booksResult)['count'];

    // Query for total authors and active authors
    $authorsQuery = "SELECT COUNT(*) as count FROM authors";
    $authorsResult = mysqli_query($mysqli, $authorsQuery);
    $totalAuthors = mysqli_fetch_assoc($authorsResult)['count'];

    $activeAuthorsQuery = "SELECT COUNT(*) as count FROM authors WHERE active = 1"; // Assuming 'is_active' column exists
    $activeAuthorsResult = mysqli_query($mysqli, $activeAuthorsQuery);
    $activeAuthors = mysqli_fetch_assoc($activeAuthorsResult)['count'];

    // Query for total publishers and active publishers
    $publishersQuery = "SELECT COUNT(*) as count FROM publishers";
    $publishersResult = mysqli_query($mysqli, $publishersQuery);
    $totalPublishers = mysqli_fetch_assoc($publishersResult)['count'];

    $activePublishersQuery = "SELECT COUNT(*) as count FROM publishers WHERE active = 1"; // Assuming 'is_active' column exists
    $activePublishersResult = mysqli_query($mysqli, $activePublishersQuery);
    $activePublishers = mysqli_fetch_assoc($activePublishersResult)['count'];

    // Query for total suppliers and active suppliers
    $suppliersQuery = "SELECT COUNT(*) as count FROM suppliers";
    $suppliersResult = mysqli_query($mysqli, $suppliersQuery);
    $totalSuppliers = mysqli_fetch_assoc($suppliersResult)['count'];

    $activeSuppliersQuery = "SELECT COUNT(*) as count FROM suppliers WHERE active = 1"; // Assuming 'is_active' column exists
    $activeSuppliersResult = mysqli_query($mysqli, $activeSuppliersQuery);
    $activeSuppliers = mysqli_fetch_assoc($activeSuppliersResult)['count'];

} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
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
    <main class="w-3/4 p-8">
        <header class="mb-8">
            <h2 class="text-3xl font-bold mb-2">Welcome, Admin</h2>
            <p class="text-gray-600">Dashboard section</p>
        </header>

        <!-- Dashboard Sections -->
        <section class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Users Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold mb-2">Total Users</h3>
                <p class="text-gray-600">Total: <?php echo $totalUsers; ?> | Active: <?php echo $activeUsers; ?></p>
                <a href="users.php" class="text-blue-500 hover:underline mt-4 block">Go to Users</a>
            </div>

            <!-- Sales Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold mb-2">Total Sales</h3>
                <p class="text-gray-600">Count: <?php echo $totalSales; ?></p>
                <a href="#" class="text-blue-500 hover:underline mt-4 block">View Sales</a>
            </div>

            <!-- Books Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold mb-2">Total Books</h3>
                <p class="text-gray-600">Count: <?php echo $totalBooks; ?></p>
                <a href="books.php" class="text-blue-500 hover:underline mt-4 block">Manage Books</a>
            </div>

            <!-- Authors Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold mb-2">Total Authors</h3>
                <p class="text-gray-600">Total: <?php echo $totalAuthors; ?> | Active: <?php echo $activeAuthors; ?></p>
                <a href="authors.php" class="text-blue-500 hover:underline mt-4 block">Manage Authors</a>
            </div>

            <!-- Publishers Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold mb-2">Total Publishers</h3>
                <p class="text-gray-600">Total: <?php echo $totalPublishers; ?> | Active: <?php echo $activePublishers; ?></p>
                <a href="publishers.php" class="text-blue-500 hover:underline mt-4 block">Manage Publishers</a>
            </div>

            <!-- Suppliers Section -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold mb-2">Total Suppliers</h3>
                <p class="text-gray-600">Total: <?php echo $totalSuppliers; ?> | Active: <?php echo $activeSuppliers; ?></p>
                <a href="suppliers.php" class="text-blue-500 hover:underline mt-4 block">Manage Suppliers</a>
            </div>
        </section>
    </main>
</body>
</html>
