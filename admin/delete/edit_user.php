<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect if not logged in
    exit;
}

// Initialize default values
$username = '';
$email = '';
$active = 0;

// Include database connection
require_once __DIR__ . '../../../config/database.php';

// Get user ID from query string
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Fetch user details
    $query = "SELECT * FROM users WHERE user_id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            $username = $user['username'];
            $email = $user['email'];
            $active = $user['active'];
        } else {
            die("User not found.");
        }
    }
}

// Update user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $active = isset($_POST['active']) ? 1 : 0;

    $update_query = "UPDATE users SET username = ?, email = ?, active = ?, updateDate = NOW() WHERE user_id = ?";
    if ($stmt = $mysqli->prepare($update_query)) {
        $stmt->bind_param("ssii", $username, $email, $active, $user_id);
        if ($stmt->execute()) {
            header("Location: users.php");
        } else {
            echo "Error updating user: " . $stmt->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex">

    <!-- Sidebar -->
    <aside class="w-1/4 bg-gray-800 text-white min-h-screen">
        <!-- Sidebar content -->
        <div class="p-6 border-b border-gray-700">
            <h1 class="text-2xl font-bold">Admin Dashboard</h1>
            <p class="text-sm text-gray-400">Manage everything at one place</p>
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
    <main class="w-3/4 p-6">
        <h2 class="text-2xl font-bold">Edit User</h2>
        
        <section class="bg-white p-6 rounded-lg shadow mt-6">
            <form method="POST">
                <div class="mb-4">
                    <label for="username" class="block text-sm font-medium text-gray-700">Username</label>
                    <input type="text" name="username" id="username" class="w-full px-4 py-2 border rounded" value="<?php echo htmlspecialchars($username); ?>" required>
                </div>
                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="w-full px-4 py-2 border rounded" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="mb-4">
                    <label for="active" class="block text-sm font-medium text-gray-700">Active</label>
                    <input type="checkbox" name="active" id="active" class="h-4 w-4" <?php echo $active ? 'checked' : ''; ?>>
                </div>
                <div>
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Update User</button>
                </div>
            </form>
        </section>
    </main>

</body>
</html>

<?php
// Close the connection
$mysqli->close();
?>
