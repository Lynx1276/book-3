<?php
class AppConfig {
    public static function get() {
        return [
            'app_name' => 'Book',
            'debug' => true,
            'base_url' => 'http://localhost',
            'secret_key' => '',
        ];
    }
}
?>
