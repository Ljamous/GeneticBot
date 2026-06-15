<?php

// deb/mycon.php

require_once __DIR__ . '/config.php'; // Adjust path as needed

try {
    $con = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $con->set_charset("utf8");

    if ($con->connect_errno) {
        throw new Exception("Database connection failed: " . $con->connect_error);
    }

} catch (Exception $e) {
    error_log("Database connection failed: " . $e->getMessage());
    die("Database connection failed. Please try again later."); // User-friendly message
}
?>