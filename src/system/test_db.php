<?php
require_once './db/config.php'; // Adjust the path as needed

$con = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($con->connect_error) {
    die("Connect Error: " . $con->connect_error);
}
echo "Connected successfully!";
$con->close();
?>