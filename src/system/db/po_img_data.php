<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once 'mycon.php';

$st = $con->prepare("update po set pofile=? where pono=?");
$st->bind_param("ss", $_POST["pofile"], $_POST["pono"]);
$st->execute();

