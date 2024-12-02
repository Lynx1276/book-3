<?php
session_start();
require_once __DIR__ . '../../../config/database.php';

// Check if the ID is provided
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $supplier_id = intval($_GET['id']);

    // Prepare the delete query
    $query = "DELETE FROM suppliers WHERE supplier_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $supplier_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Supplier deleted successfully.';
    } else {
        $_SESSION['error'] = 'Failed to delete supplier. Please try again.';
    }

    $stmt->close();
} else {
    $_SESSION['error'] = 'Invalid supplier ID.';
}

// Redirect back to the suppliers page
header('Location: suppliers.php');
exit;
