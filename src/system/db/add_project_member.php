<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

require_once 'mycon.php';

$sql = "update projects set designerid=? where id=?";
    if($_POST["type"] == 1)
        $sql = "update projects set accountantid=? where id=?";
    
    $st = $con->prepare($sql);
    $st->bind_param("ii", $_POST["member"], $_POST["id"]);
    $st->execute();

