<?php
session_start();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['user_id']);

// Include database connection
require_once __DIR__ . '../../../config/database.php';

// Results per page
$results_per_page = 5;

// Determine the current page
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;

// Calculate the starting limit
$starting_limit = ($page - 1) * $results_per_page;

// Fetch the total number of books
$total_books_query = "SELECT COUNT(*) AS total FROM books";
$total_books_result = $mysqli->query($total_books_query);
$total_books = $total_books_result->fetch_assoc()['total'];

// Fetch books for the current page
$sql = "SELECT * FROM books, authors LIMIT $starting_limit, $results_per_page";
$result = $mysqli->query($sql);

// Check for any database errors
if (!$result) {
    die("Error fetching books: " . $mysqli->error);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Available Books</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900 flex flex-col min-h-screen">
    <!-- Header -->
    <header class="bg-blue-600 text-white fixed top-0 w-full z-50 shadow-lg">
        <nav class="container mx-auto flex justify-between items-center p-4">
            <div class="text-2xl font-bold">
                <a href="index.php" class="hover:text-blue-300 transition">Library System</a>
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

    <!-- Main Content -->
    <main class="container mx-auto my-28">
        <section class="bg-white p-8 rounded-lg shadow-lg">
            <h1 class="text-4xl font-bold mb-8 text-blue-600">Available Books</h1>

            <!-- Book List -->
            <div class="space-y-6">
                <?php if ($result->num_rows > 0): ?>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <div class="bg-gray-50 p-6 rounded-md shadow-md hover:shadow-lg transition">
                            <h2 class="text-lg font-semibold text-gray-800"><?php echo htmlspecialchars($row['title']); ?></h2>
                            <p class="text-gray-600">Author: <?php echo htmlspecialchars($row['first_name']); ?></p>
                            <p class="text-gray-500 text-sm">Published: <?php echo htmlspecialchars($row['published_date']); ?></p>
                            <p class="text-gray-500 text-sm">Quantity: <?php echo htmlspecialchars($row['stock_quantity']); ?></p>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-gray-700">No books are currently available.</p>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                <?php
                // Calculate total pages
                $total_pages = ceil($total_books / $results_per_page);
                if ($total_pages > 1): ?>
                    <nav class="flex justify-center space-x-2">
                        <!-- Previous Page -->
                        <?php if ($page > 1): ?>
                            <a href="home.php?page=<?php echo $page - 1; ?>" 
                               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                                &laquo; Previous
                            </a>
                        <?php endif; ?>

                        <!-- Page Numbers -->
                        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                            <a href="home.php?page=<?php echo $i; ?>" 
                               class="px-4 py-2 <?php echo ($i == $page) ? 'bg-blue-500 text-white' : 'bg-gray-200 text-gray-700'; ?> rounded-md hover:bg-gray-300 transition">
                                <?php echo $i; ?>
                            </a>
                        <?php endfor; ?>

                        <!-- Next Page -->
                        <?php if ($page < $total_pages): ?>
                            <a href="home.php?page=<?php echo $page + 1; ?>" 
                               class="px-4 py-2 bg-gray-200 text-gray-700 rounded-md hover:bg-gray-300 transition">
                                Next &raquo;
                            </a>
                        <?php endif; ?>
                    </nav>
                <?php endif; ?>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white text-center py-4 fixed bottom-0 w-full shadow-lg">
        <p>&copy; 2024 Library System. All Rights Reserved.</p>
    </footer>
</body>
</html>


<?php
// Close the database connection
$mysqli->close();
?>
