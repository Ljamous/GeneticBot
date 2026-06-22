<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */


if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once 'mycon.php';

$st = $con->prepare("update docs set status=? where version=?");
$st->bind_param("si", $_POST["status"], $_POST["version"]);
$st->execute();

$st2 = $con->prepare("update packages set status='Response' where id=?");
$st2->bind_param("i", $_SESSION["pckid"]);
$st2->execute();

echo "Done";
