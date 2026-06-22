<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

require_once 'mycon.php';

$st = $con->prepare("update packages set status='PO Approved' where id=?");
$st->bind_param("i", $_POST["pckid"]);
$st->execute();

echo '<script>window.location="../tracking_view.php";</script>';