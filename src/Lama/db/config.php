<?php
// config.php
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USERNAME', getenv('DB_USER') ?: 'Lama');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'Lama@2025');
define('DB_NAME', getenv('DB_NAME') ?: 'lamadb');
?>