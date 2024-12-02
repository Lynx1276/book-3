<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../../app/notactive/home.php');
    exit;
}

// Include database connection
require_once __DIR__ . '../../../config/database.php';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Sanitize and get the user input
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $active = isset($_POST['active']) ? 1 : 0;

    // Hash the password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Insert the new user into the database
    $query = "INSERT INTO users (username, email, password, active, created_at, updateDate) VALUES (?, ?, ?, ?, NOW(), NOW())";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("sssi", $username, $email, $hashed_password, $active);

    if ($stmt->execute()) {
        // Redirect to the users page after successful insertion
        header('Location: ../../users.php');
        exit;
    } else {
        // Handle error (optional)
        echo "Error: " . $stmt->error;
    }
}

?>
