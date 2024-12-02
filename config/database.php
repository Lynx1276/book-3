<?php
// Load database configuration
$config = require_once __DIR__ . '/config.php';
$db_config = $config['database'];

// Create a new mysqli connection using the configuration
$mysqli = new mysqli(
    $db_config['host'],
    $db_config['user'],
    $db_config['password'],
    $db_config['database'],
    $db_config['port']
);

// Check for any connection errors
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}

// Return the connection to be used elsewhere in the application
return $mysqli;
?>
