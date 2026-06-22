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

$st = $con->prepare("update po set pofile=? where pono=?");
$st->bind_param("ss", $_POST["pofile"], $_POST["pono"]);
$st->execute();

