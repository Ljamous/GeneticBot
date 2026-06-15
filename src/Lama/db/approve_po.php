<?php
require_once 'mycon.php';

$st = $con->prepare("update packages set status='PO Approved' where id=?");
$st->bind_param("i", $_POST["pckid"]);
$st->execute();

echo '<script>window.location="../tracking_view.php";</script>';