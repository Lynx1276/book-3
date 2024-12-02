<?php
session_start();
require_once __DIR__ . '../../../config/database.php';

if (isset($_GET['admin_id']) && is_numeric($_GET['admin_id'])) {
    $restock_id = intval($_GET['admin_id']);

    $query = "DELETE FROM restocks WHERE restock_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $restock_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Restock deleted successfully.';
    } else {
        $_SESSION['error'] = 'Failed to delete restock.';
    }

    $stmt->close();
}

header('Location: ../../stocks.php');
exit;
