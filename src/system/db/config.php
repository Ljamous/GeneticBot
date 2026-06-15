<?php
// config.php
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USERNAME', getenv('DB_USER') ?: 'db_user');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'db_password');
define('DB_NAME', getenv('DB_NAME') ?: 'app_db');
?>