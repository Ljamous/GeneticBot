<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once 'mycon.php';

$st = $con->prepare("update po set csfile=? where pono=?");
$st->bind_param("ss", $_POST["csfile"], $_POST["pono"]);
$st->execute();

