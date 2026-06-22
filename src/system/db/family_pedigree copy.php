<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

require_once 'mycon.php';

function insertFamilyPedigree($csv, $img, $userId){
    global $con;

    // Prepare the SQL statement
    $st = $con->prepare("insert into personal_infos(csv, img,userId) values(?,?,?)");

    if ($st === false) {
        error_log("Error preparing statement: " . $con->error, 3, "/opt/lampp/htdocs/lama/db_connection.log");
        return false;
    }

    // Bind parameters
    $st->bind_param("sss",$csv, $img, $userId);

    // Execute the statement
    if (!$st->execute()) {
        error_log("Error executing statement: " . $st->error, 3, "/opt/lampp/htdocs/lama/db_connection.log");
        return false;
    }

    // Log successful signup
    error_log("Personal info inserted", 3, "/opt/lampp/htdocs/lama/db_connection.log");


    return true;
}