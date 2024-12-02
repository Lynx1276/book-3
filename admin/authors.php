<?php
session_start();

// Check if the admin is logged in
$admin = isset($_SESSION['admin_id']);

// Include database connection
require_once __DIR__ . '../../config/database.php';

// Initialize variables for form data
$publisher_name = $publisher_address = "";
$error_message = $success_message = "";

// Variables to hold form data
$first_name = $last_name = $nationality = "";

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_author'])) {
    // Get form data
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $nationality = $_POST['nationality'];

    // Ensure fields are not empty
    if (!empty($first_name) && !empty($last_name)) {
        // Prepare the insert query
        $query = "INSERT INTO authors (first_name, last_name, nationality, created_at, update_at, active) 
                  VALUES (?, ?, ?, NOW(), NOW(), 1)";

        if ($stmt = $mysqli->prepare($query)) {
            // Correct bind_param with type string 'sss' for three string parameters
            $stmt->bind_param('sss', $first_name, $last_name, $nationality);

            // Execute the query
            if ($stmt->execute()) {
                $success_message = "Publisher added successfully!";
            } else {
                $error_message = "Error: " . mysqli_error($mysqli);
            }
        }
    }
}

// Fetch authors from the database
$sql = "SELECT * FROM authors ORDER BY authors_id DESC";
$stmt = $mysqli->query($sql);
$authors = [];
while ($row = $stmt->fetch_assoc()) {
    $authors[] = $row;
}

// Close the connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex h-screen">

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
        <h2 class="text-2xl font-bold">Welcome to the Admin Dashboard</h2>
        <p class="mt-4">Authors Section.</p>

        <!-- Show success or error message -->
        <?php if ($success_message): ?>
            <div class="bg-green-500 text-white p-4 rounded mb-4">
                <?php echo $success_message; ?>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="bg-red-500 text-white p-4 rounded mb-4">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <!-- Add New Author Form -->
        <section class="bg-white p-6 rounded-lg shadow mt-6">
            <h3 class="text-xl font-bold mb-4">Add New Author</h3>
            <form method="POST">
                <div class="mb-4">
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="w-full px-4 py-2 border rounded" required>
                </div>
                <div class="mb-4">
                    <label for="nationality" class="block text-sm font-medium text-gray-700">Nationality</label>
                    <input type="text" name="nationality" id="nationality" class="w-full px-4 py-2 border rounded">
                </div>
                <div>
                    <button type="submit" name="add_author" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Add Author</button>
                </div>
            </form>
        </section>

        <!-- Authors List -->
        <section class="bg-white p-6 rounded-lg shadow mt-6">
            <h3 class="text-xl font-bold mb-4">Authors List</h3>
            <div class="overflow-x-auto">
                <table class="w-full table-auto border-collapse">
                    <thead>
                        <tr>
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">First Name</th>
                            <th class="px-4 py-2 border">Last Name</th>
                            <th class="px-4 py-2 border">Nationality</th>
                            <th class="px-4 py-2 border">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($authors as $author): ?>
                            <tr>
                                <td class="px-4 py-2 border"><?php echo htmlspecialchars($author['authors_id']); ?></td>
                                <td class="px-4 py-2 border"><?php echo htmlspecialchars($author['first_name']); ?></td>
                                <td class="px-4 py-2 border"><?php echo htmlspecialchars($author['last_name']); ?></td>
                                <td class="px-4 py-2 border"><?php echo htmlspecialchars($author['nationality']); ?></td>
                                <td class="px-4 py-2 border">
                                    <a href="edit_author.php?id=<?php echo $author['authors_id']; ?>" class="text-blue-500">Edit</a> |
                                    <a href="./delete/delete_author.php?id=<?php echo $author['authors_id']; ?>" class="text-red-500">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>

</body>
</html>

