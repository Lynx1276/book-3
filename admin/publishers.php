<?php
session_start();

// Check if the admin is logged in
$admin = isset($_SESSION['admin_id']);

// Include database connection
require_once __DIR__ . '../../config/database.php';

// Initialize variables for form fields
$name = $address = $contact = $stablish_year = "";

// Initialize variables for form data
$publisher_name = $publisher_address = "";
$error_message = $success_message = "";


// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $address = $_POST['address'];
    $contact = $_POST['contact'];
    $stablish_year = $_POST['stablish_year'];

    // Prepare SQL query to insert publisher data
    $query = "INSERT INTO publishers (name, address, contact, stablish_year, created_at, update_at, active) 
              VALUES (?, ?, ?, ?, NOW(), NOW(), 1)";  // Assuming active publisher by default

    // Prepare the statement
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("ssss", $name, $address, $contact, $stablish_year);

        // Execute the query
        if ($stmt->execute()) {
            $success_message = "Publisher added successfully!";
        } else {
            $error_message = "Error: " . mysqli_error($mysqli);
        }

        // Close statement
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $mysqli->error;
    }
}

// Fetch publishers from the database
$sql = "SELECT * FROM publishers ORDER BY publisher_id DESC";
$stmt = $mysqli->query($sql);
$publishers = [];
while ($row = $stmt->fetch_assoc()) {
    $publishers[] = $row;
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
<body class="flex">

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
        <p class="mt-4">Publishers section.</p>
        
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

        <!-- Publisher Form -->
        <section class="mb-8 bg-white p-6 rounded-lg shadow">
        <h3 class="text-xl font-bold mb-4">Add Publisher</h3>
            <form method="POST">
                <div>
                    <label for="name" class="block text-gray-700 font-medium mb-1">Publisher Name</label>
                    <input type="text" name="name" id="name" class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div>
                    <label for="address" class="block text-gray-700 font-medium mb-1">Address</label>
                    <textarea name="address" id="address" class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500"></textarea>
                </div>
                <div>
                    <label for="contact" class="block text-gray-700 font-medium mb-1">Contact</label>
                    <input type="text" name="contact" id="contact" class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label for="stablish_year" class="block text-gray-700 font-medium mb-1">Establishment Year</label>
                    <input type="number" name="stablish_year" id="stablish_year" class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500" required>
                </div>
                <div class="flex items-end mt-5">
                    <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                        Add Publisher
                    </button>
                </div>
            </form>
        </section>

        <section class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">Publishers List</h3>
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Name</th>
                        <th class="border border-gray-300 px-4 py-2">Address</th>
                        <th class="border border-gray-300 px-4 py-2">Contact</th>
                        <th class="border border-gray-300 px-4 py-2">Establishment Year</th>
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($publishers as $publisher): ?>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($publisher['name']); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($publisher['address']); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($publisher['contact']); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($publisher['stablish_year']); ?></td>
                            <td class="border border-gray-300 px-4 py-2 text-blue-500 hover:underline cursor-pointer">
                                Edit | Delete
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>

    </main>

</body>
</html>
