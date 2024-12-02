<?php
session_start();

// Check if the admin is logged in
$admin = isset($_SESSION['admin_id']);

// Include database connection
require_once __DIR__ . '../../../config/database.php';

// Check if the author ID is passed
if (isset($_GET['id'])) {
    $author_id = $_GET['id'];

    // Delete the author from the database
    $query = "DELETE FROM authors WHERE authors_id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $author_id);
        if ($stmt->execute()) {
            echo "Author deleted successfully!";
            header("Location: ./../authors.php");  // Redirect to authors list page after success
        } else {
            echo "Error deleting author: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error preparing statement: " . $mysqli->error;
    }
} else {
    echo "No author ID provided.";
}

// Close the connection
$mysqli->close();
?>
