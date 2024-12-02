<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../../app/notactive/home.php');
    exit;
}

// Include database connection
require_once __DIR__ . '../../../config/database.php';

// Check if user_id is passed
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete the user from the database
    $delete_query = "DELETE FROM users WHERE user_id = ?";
    $delete_stmt = $mysqli->prepare($delete_query);
    $delete_stmt->bind_param("i", $user_id);
    $delete_stmt->execute();

    // Redirect to users.php after deletion
    header('Location: ../../users.php');
    exit;
} else {
    header('Location: ../../users.php'); // Redirect if no user ID is provided
    exit;
}
?>
