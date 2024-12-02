<?php
session_start();
require_once __DIR__ . '../../../config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $supplier_id = $_POST['supplier_id'];
    $total_amount = $_POST['total_amount'];

    $query = "INSERT INTO restocks (supplier_id, admin_id, total_amount, restock_date, updateDate)
              VALUES (?, ?, ?, NOW(), NOW())";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('iid', $supplier_id, $_SESSION['admin_id'], $total_amount);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Restock added successfully.';
    } else {
        $_SESSION['error'] = 'Failed to add restock. Please try again.';
    }

    $stmt->close();
    header('Location: ../../stocks.php');
    exit;
}
