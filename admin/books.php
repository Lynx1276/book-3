<?php
session_start();

// Check if the admin is logged in
$admin = isset($_SESSION['admin_id']);

// Include database connection
require_once __DIR__ . '../../config/database.php';

// Initialize variables for form data
$error_message = $success_message = "";


// Initialize variables for form fields
$title = $published_date = $isbn = $stock_quantity = $publisher_id = $authors_id = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $_POST['title'];
    $published_date = $_POST['date'];
    $isbn = $_POST['isbn'];
    $stock_quantity = $_POST['quantity'];
    $publisher_id = $_POST['publisher_id'];
    $authors_id = $_POST['authors_id'];
    $category_id = $_POST['category_id'];

    // Validate IDs
    $publisher_check = $mysqli->query("SELECT publisher_id FROM publishers WHERE publisher_id = $publisher_id");
    $author_check = $mysqli->query("SELECT authors_id FROM authors WHERE authors_id = $authors_id");
    $category_check = $mysqli->query("SELECT category_id FROM categories WHERE category_id = $category_id");

    if (!$publisher_check->num_rows) {
        die("Publisher ID $publisher_id does not exist.");
    }
    if (!$author_check->num_rows) {
        die("Author ID $authors_id does not exist.");
    }
    if (!$category_check->num_rows) {
        die("Category ID $category_id does not exist.");
    }

    $query = "INSERT INTO books (title, published_date, isbn, stock_quantity, publisher_id, authors_id, category_id, created_at, update_at) 
          VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())";

if ($stmt = $mysqli->prepare($query)) {
    $stmt->bind_param("sssiiii", $title, $published_date, $isbn, $stock_quantity, $publisher_id, $authors_id, $category_id);
    
    // Execute the query
    if ($stmt->execute()) {
        $success_message = "Book added successfully!";
    } else {
        $error_message = "Error: " . mysqli_error($mysqli);
    }
    $stmt->close();
} else {
    die("Preparation Error: " . $mysqli->error);
}

    
}


// Fetch books
$sql = "SELECT * FROM books ORDER BY book_id DESC";
$books_result = $mysqli->query($sql);

if (!$books_result) {
    die("Error fetching books: " . $mysqli->error);
}

$books = [];
while ($row = $books_result->fetch_assoc()) {
    $books[] = $row;
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
        <header class="mb-8">
            <h2 class="text-2xl font-bold">Welcome to the Admin Dashboard</h2>
            <p class="mt-2 text-gray-600">Books section.</p>
        </header>

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

        <!-- Books Section -->  
        <section class="mb-8 bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">Upload Books</h3>
                <form class="grid grid-cols-1 md:grid-cols-2 gap-4" method="POST">
                    <div>
                        <label for="title" class="block text-gray-700 font-medium mb-1">Title</label>
                        <input type="text" name="title" id="title" class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="date" class="block text-gray-700 font-medium mb-1">Published Date</label>
                        <input type="date" name="date" id="date" class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="isbn" class="block text-gray-700 font-medium mb-1">ISBN</label>
                        <input type="text" name="isbn" id="isbn" class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="quantity" class="block text-gray-700 font-medium mb-1">Quantity</label>
                        <input type="number" name="quantity" id="quantity" class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="publisher_id" class="block text-gray-700 font-medium mb-1">Publisher</label>
                        <input type="number" name="publisher_id" id="publisher_id" class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="authors_id" class="block text-gray-700 font-medium mb-1">Author</label>
                        <input type="number" name="authors_id" id="authors_id" class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div>
                        <label for="category_id" class="block text-gray-700 font-medium mb-1">Genres</label>
                        <input type="number" name="category_id" id="category_id" class="w-full border rounded px-4 py-2 focus:ring-2 focus:ring-blue-500" required>
                    </div>
                    <div class="flex items-end">
                        <button type="submit" class="bg-blue-500 text-white px-6 py-2 rounded hover:bg-blue-600">
                            Upload
                        </button>
                    </div>
                </form>

        </section>

        <!-- Books Table -->
        <section class="bg-white p-6 rounded-lg shadow">
            <h3 class="text-xl font-bold mb-4">Books List</h3>
            <table class="w-full border-collapse border border-gray-300">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="border border-gray-300 px-4 py-2">Title</th>
                        <th class="border border-gray-300 px-4 py-2">Author</th>
                        <th class="border border-gray-300 px-4 py-2">Published Date</th>
                        <th class="border border-gray-300 px-4 py-2">ISBN</th>
                        <th class="border border-gray-300 px-4 py-2">Quantity</th>
                        
                        <th class="border border-gray-300 px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($books as $book): ?>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($book['title']); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($book['authors_id']); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($book['published_date']); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($book['isbn']); ?></td>
                            <td class="border border-gray-300 px-4 py-2"><?php echo htmlspecialchars($book['stock_quantity']); ?></td>
                            
                            <td class="border border-gray-300 px-4 py-2 text-blue-500 hover:underline cursor-pointer">
                                Edit | Delete
                            </td>
                            
    
                        </td>

                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </main>

</body>
</html>
