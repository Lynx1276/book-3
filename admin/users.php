<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect if not logged in
    exit;
}

// Include database connection
require_once __DIR__ . '../../config/database.php';

// Fetch all users
$query = "SELECT * FROM users";
$result = $mysqli->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Users</title>
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
        <h2 class="text-2xl font-bold">Users Management</h2>
        <p class="mt-4">Here you can manage users.</p>

        <!-- Add New User Form -->
        <section class="mt-6 bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">Add New User</h3>
            <form action="../admin/users/add_user.php" method="POST">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" id="username" name="username" required class="mt-2 w-full px-3 py-2 border rounded-md">
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" id="email" name="email" required class="mt-2 w-full px-3 py-2 border rounded-md">
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Password</label>
                    <input type="password" id="password" name="password" required class="mt-2 w-full px-3 py-2 border rounded-md">
                </div>

                <div class="mb-4">
                    <label for="active" class="block text-sm font-medium text-gray-700">Active</label>
                    <input type="checkbox" id="active" name="active" class="mt-2">
                </div>

                <button type="submit" name="submit" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded-md">Add User</button>
            </form>
        </section>


        <!-- Users Table -->
        <section class="mt-6 bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">Users List</h3>
            <table class="w-full border-collapse">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border-b">ID</th>
                        <th class="px-4 py-2 border-b">Username</th>
                        <th class="px-4 py-2 border-b">Email</th>
                        <th class="px-4 py-2 border-b">Active</th>
                        <th class="px-4 py-2 border-b">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="px-4 py-2 border-b"><?php echo htmlspecialchars($user['user_id']); ?></td>
                            <td class="px-4 py-2 border-b"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td class="px-4 py-2 border-b"><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="px-4 py-2 border-b"><?php echo $user['active'] ? 'Active' : 'Inactive'; ?></td>
                            <td class="px-4 py-2 border-b">
                                <a href="./delete/edit_user.php?=id=<?php echo $user['user_id']; ?>" class="text-blue-500">Edit</a> |
                                <a href="./delete/delete_user.php?id=<?php echo $user['user_id']; ?>" class="text-red-500" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </main>

</body>
</html>

<?php
// Close the connection
$mysqli->close();
?>
