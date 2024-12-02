<?php
session_start();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);

// Include database connection
require_once __DIR__ . '../../../config/database.php';

// Initialize variables for messages
$error_message = $success_message = "";

// Fetch available books with their price per unit from the restock_details table
$sql = "
    SELECT 
        books.book_id, 
        books.title, 
        books.stock_quantity, 
        authors.first_name AS first_name,
        categories.genre AS genre,
        -- Subquery to fetch the latest price from restock_details
        (SELECT price_per_unit 
         FROM restock_details 
         WHERE book_id = books.book_id 
         ORDER BY created_at DESC LIMIT 1) AS price_per_unit
    FROM books
    JOIN authors ON books.authors_id = authors.authors_id
    JOIN categories ON books.category_id = categories.category_id
    WHERE books.stock_quantity > 0
    ORDER BY books.book_id ASC";
$books_result = $mysqli->query($sql);

if (!$books_result) {
    die("Error fetching books: " . $mysqli->error);
}

$books = [];
while ($row = $books_result->fetch_assoc()) {
    $books[] = $row;
}

// Handle book purchase
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['book_id'], $_POST['quantity'])) {
    $book_id = intval($_POST['book_id']);
    $purchase_quantity = intval($_POST['quantity']);

    // Fetch the selected book with price from restock_details
    $book_query = $mysqli->prepare("
        SELECT 
            books.*, 
            rd.price_per_unit
        FROM 
            books 
        LEFT JOIN 
            (SELECT 
                book_id, 
                price_per_unit 
             FROM 
                restock_details 
             WHERE 
                created_at = (SELECT MAX(created_at) FROM restock_details AS rd2 WHERE rd2.book_id = restock_details.book_id)
            ) AS rd 
        ON 
            books.book_id = rd.book_id 
        WHERE 
            books.book_id = ?");
    $book_query->bind_param("i", $book_id);
    $book_query->execute();
    $book_result = $book_query->get_result();
    $book_query->close();

    if ($book_result->num_rows == 0) {
        $error_message = "Book not found.";
    } else {
        $book = $book_result->fetch_assoc();

        if (!isset($book['price_per_unit']) || $book['price_per_unit'] <= 0) {
            $error_message = "This book cannot be purchased because its price is not set.";
        } elseif ($purchase_quantity <= 0) {
            $error_message = "Invalid quantity.";
        } elseif ($purchase_quantity > $book['stock_quantity']) {
            $error_message = "Insufficient stock available.";
        } else {
            // Calculate total price
            $total_amount = $purchase_quantity * $book['price_per_unit'];

            // Update the stock quantity
            $new_stock = $book['stock_quantity'] - $purchase_quantity;
            $update_query = $mysqli->prepare("UPDATE books SET stock_quantity = ? WHERE book_id = ?");
            $update_query->bind_param("ii", $new_stock, $book_id);

            if ($update_query->execute()) {
                // Save purchase details in the sales table
                $sales_query = $mysqli->prepare("
                    INSERT INTO sales (user_id, book_id, stock_quantity, total_amount, purchase_date)
                    VALUES (?, ?, ?, ?, NOW())");
                $user_id = $_SESSION['user_id']; // Ensure the user is logged in
                $sales_query->bind_param("iiid", $user_id, $book_id, $purchase_quantity, $total_amount);

                if ($sales_query->execute()) {
                    $success_message = "Book purchased successfully! Total price: $" . number_format($total_amount, 2);
                } else {
                    $error_message = "Failed to save purchase details: " . $mysqli->error;
                }

                $sales_query->close();
            } else {
                $error_message = "Failed to update stock: " . $mysqli->error;
            }
            $update_query->close();
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Book Store</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800">

    <!-- Header -->
    <header class="bg-blue-600 text-white fixed top-0 w-full z-50 shadow-lg">
        <nav class="container mx-auto flex justify-between items-center p-4">
            <div class="text-2xl font-bold">
                <a href="index.php" class="hover:text-blue-300 transition">Modern Bookstore</a>
            </div>
            <ul class="flex space-x-4">
                <li><a href="home.php" class="hover:bg-blue-500 px-4 py-2 rounded transition">Home</a></li>
                <li><a href="book.php" class="hover:bg-blue-500 px-4 py-2 rounded transition">Books</a></li>
                <?php if ($is_logged_in): ?>
                    <li><a href="../../logout.php" class="hover:bg-blue-500 px-4 py-2 rounded transition">Sign Out</a></li>
                <?php else: ?>
                    <li><a href="../../auth/login.php" class="hover:bg-blue-500 px-4 py-2 rounded transition">Sign In</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main class="container mx-auto p-6 pt-20">
        <h1 class="text-3xl font-bold mb-6">Books for Sale</h1>

        <!-- Success & Error Messages -->
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

        <!-- Book List -->
        <table class="table-auto w-full bg-white shadow rounded">
            <thead>
                <tr class="bg-gray-200">
                    <th class="px-4 py-2">Title</th>
                    <th class="px-4 py-2">Author</th>
                    <th class="px-4 py-2">Genre</th>
                    <th class="px-4 py-2">Stock</th>
                    <th class="px-4 py-2">Price</th>
                    <th class="px-4 py-2">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr class="border-t">
                        <td class="px-4 py-2"><?php echo htmlspecialchars($book['title']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($book['first_name']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($book['genre']); ?></td>
                        <td class="px-4 py-2"><?php echo htmlspecialchars($book['stock_quantity']); ?></td>
                        <td class="px-4 py-2"><?php echo $book['price_per_unit'] ? "$" . number_format($book['price_per_unit'], 2) : "N/A"; ?></td>
                        <td class="px-4 py-2">
                            <form method="POST" class="flex items-center space-x-2">
                                <input type="hidden" name="book_id" value="<?php echo $book['book_id']; ?>">
                                <input type="number" name="quantity" min="1" max="<?php echo $book['stock_quantity']; ?>" class="border px-2 py-1 w-16 rounded" required>
                                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">Buy</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </main>
</body>
</html>
