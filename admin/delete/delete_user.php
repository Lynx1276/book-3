<?php
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php'); // Redirect if not logged in
    exit;
}

// Include database connection
require_once __DIR__ . '../../config/database.php';

// Get user ID from query string
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Delete the user
    $query = "DELETE FROM users WHERE user_id = ?";
    if ($stmt = $mysqli->prepare($query)) {
        $stmt->bind_param("i", $user_id);
        if ($stmt->execute()) {
            header('Location: users.php');
        } else {
            echo "Error deleting user: " . $stmt->error;
        }
    }
}
?>

<?php
// Close the connection
$mysqli->close();
?>
