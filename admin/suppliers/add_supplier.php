<?php
session_start();
require_once __DIR__ . '../../../config/database.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $contact = isset($_POST['contact']) ? trim($_POST['contact']) : null;
    $address = isset($_POST['address']) ? trim($_POST['address']) : null;
    $active = isset($_POST['active']) ? intval($_POST['active']) : 1; // Default to active

    // Validate input
    if (empty($name)) {
        $_SESSION['error'] = 'Supplier name is required.';
        header('Location: ../../suppliers.php');
        exit;
    }

    // Insert into the database
    $query = "INSERT INTO suppliers (name, contact, address, active, created_at, update_at) 
              VALUES (?, ?, ?, ?, NOW(), NOW())";

    $stmt = $mysqli->prepare($query);
    $stmt->bind_param('sssi', $name, $contact, $address, $active);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'Supplier added successfully.';
    } else {
        $_SESSION['error'] = 'Failed to add supplier. Please try again.';
    }

    $stmt->close();
    $mysqli->close();

    // Redirect back to the suppliers page
    header('Location: ../../suppliers.php');
    exit;
}
