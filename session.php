<?php
session_start();

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function admin() {
    return isset($_SESSION['id']);
}

function require_login() {
    // Check if the user is not logged in
    if (!is_logged_in()) {
        header("Location: /app/notactive/index.php");
        exit;
    }
    // Check if the user is not an admin
    if (!admin()) {
        header("Location: /app/notactive/index.php");
        exit;
    }
}
?>
