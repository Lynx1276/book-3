<?php
session_start();

// Check if the admin is logged in
$admin = isset($_SESSION['admin_id']);

// Include database connection
require_once __DIR__ . '../../../config/database.php';

// Get the author ID from the query string
if (isset($_GET['id'])) {
    $author_id = $_GET['id'];

    // Fetch the author details from the database
    $query = "SELECT * FROM authors WHERE authors_id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $author_id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            $author = $result->fetch_assoc();
            $first_name = $author['first_name'];
            $last_name = $author['last_name'];
            $nationality = $author['nationality'];
        } else {
            die("Author not found.");
        }

        $stmt->close();
    }
}

// Update author details
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_author'])) {
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $nationality = $_POST['nationality'];

    // Update the author's details in the database
    $update_query = "UPDATE authors SET first_name = ?, last_name = ?, nationality = ?, updated_at = NOW() WHERE authors_id = ?";
    if ($stmt = $mysqli->prepare($update_query)) {
        $stmt->bind_param("sssi", $first_name, $last_name, $nationality, $author_id);
        if ($stmt->execute()) {
            echo "Author updated successfully!";
            header("Location: ./../authors.php");  // Redirect to authors list page after success
        } else {
            echo "Error updating author: " . $stmt->error;
        }
        $stmt->close();
    }
}

// Close the connection
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Author</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex">

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
                    <a href="stocks.php" class="block px-6 py-3 hover:bg-gray-700 rounded">Sales</a>
                </li>
                <li class="mt-6">
                    <a href="../../logout.php" class="bg-red-500 px-4 py-2 mx-6 rounded text-center block hover:bg-red-600">
                        Logout
                    </a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- Main Content -->
    <main class="w-3/4 p-6">
        <h2 class="text-2xl font-bold">Edit Author</h2>

        <!-- Edit Author Form -->
        <section class="bg-white p-6 rounded-lg shadow mt-6">
            <h3 class="text-xl font-bold mb-4">Edit Author Details</h3>
            <form method="POST">
                <div class="mb-4">
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="w-full px-4 py-2 border rounded" value="<?php echo htmlspecialchars($first_name); ?>" required>
                </div>
                <div class="mb-4">
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="w-full px-4 py-2 border rounded" value="<?php echo htmlspecialchars($last_name); ?>" required>
                </div>
                <div class="mb-4">
                    <label for="nationality" class="block text-sm font-medium text-gray-700">Nationality</label>
                    <input type="text" name="nationality" id="nationality" class="w-full px-4 py-2 border rounded" value="<?php echo htmlspecialchars($nationality); ?>">
                </div>
                <div>
                    <button type="submit" name="update_author" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">Update Author</button>
                </div>
            </form>
        </section>
    </main>

</body>
</html>
