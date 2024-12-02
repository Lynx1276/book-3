<?php
require_once __DIR__ . '/AppConfig.php';
require_once __DIR__ . '/DatabaseConfig.php';

// Combine all configurations into one array
return [
    'app' => AppConfig::get(),
    'database' => DatabaseConfig::get()['database'], // Access the 'database' part of the configuration
];
