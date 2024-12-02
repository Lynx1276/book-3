<?php
class DatabaseConfig {
    public static function get() {
        return [
            'app' => [
        'app_name' => 'Book',
        'debug' => true,
            ],
            'database' => [
                'host' => 'localhost', // your host
                'user' => 'root',      // your database username
                'password' => '',      // your database password
                'database' => 'books', // your database name
                'port' => 3306,        // default MySQL port
            ]
        ];
    }
}
?>
