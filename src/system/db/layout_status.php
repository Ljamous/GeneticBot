<?php

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
