<?php

session_start();
$mysqli = require __DIR__ . '/config/database.php';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];

    // Update the active status to 0 (offline)
    $stmt = $mysqli->prepare("UPDATE users SET active = 0 WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->close();

    // Destroy the session
    session_unset();
    session_destroy();
}

// Redirect to the login page
header("Location: /app/notactive/home.php");
exit();
?>


