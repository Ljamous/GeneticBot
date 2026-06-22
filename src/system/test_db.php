<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

require_once './db/config.php'; // Adjust the path as needed

$con = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);

if ($con->connect_error) {
    die("Connect Error: " . $con->connect_error);
}
echo "Connected successfully!";
$con->close();
?>