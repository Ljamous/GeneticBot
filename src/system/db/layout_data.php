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

if($_POST["ver"] == 0)
{
    $st = $con->prepare("insert into docs(file_name, file_type, packageid, userid) values(?,?,?,?)");
    $st->bind_param("ssii", $_POST["fn"], $_POST["ft"], $_SESSION["pckid"],$_SESSION["userid"]);
    $st->execute();

    $st2 = $con->prepare("update packages set status='Sent' where id=?");
    $st2->bind_param("i", $_SESSION["pckid"]);
    $st2->execute();
}
else
{
    $ver = $_POST["ver"] + 1;
    $st = $con->prepare("insert into docs(file_name, file_type, packageid, userid, version) values(?,?,?,?,?)");
    $st->bind_param("ssiii", $_POST["fn"], $_POST["ft"], $_SESSION["pckid"],$_SESSION["userid"], $ver);
    $st->execute();
}
