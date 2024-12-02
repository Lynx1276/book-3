<?php
session_start();
require_once __DIR__ . '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $restock_id = $_POST['restock_id'];
    $price_per_unit = $_POST['price_per_unit'];
    $publisher_id = $_POST['publisher_id'];
    $book_id = $_POST['book_id'];

    // Validate input data
    if (empty($restock_id) || empty($price_per_unit) || empty($publisher_id) || empty($book_id)) {
        die("All fields are required.");
    }

    // Prepare and execute the insert query
    $stmt = $mysqli->prepare("INSERT INTO restock_details (restock_id, price_per_unit, publisher_id, book_id, created_at, update_at) VALUES (?, ?, ?, ?, NOW(), NOW())");
    $stmt->bind_param('idsi', $restock_id, $price_per_unit, $publisher_id, $book_id);

    if ($stmt->execute()) {
        echo "Restock detail added successfully.";
        header("Location: restock_details.php"); // Redirect back to the list
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
    $stmt->close();
    header('Location: ../../publishers/add_stocksDetails.php');
    exit;
}
?>
