<?php
require_once 'mycon.php';

$st = $con->prepare("insert into po(pono,offerno,vendorno) values(?,?,?)");
$st->bind_param("sis", $_GET["pono"], $_GET["offerno"], $_GET["vno"]);
$st->execute();

$st2 = $con->prepare("update offer set status='PO Created' where offerno=?");
$st2->bind_param("i",$_GET["offerno"]);
$st2->execute();

$st3 = $con->prepare("update docs set status='PO Created' where version=(select doc_version from offer where offerno=?)");
$st3->bind_param("i",$_GET["offerno"]);
$st3->execute();

$st4 = $con->prepare("update packages set status='PO Created' where id=(select packageid from offer where offerno=?)");
$st4->bind_param("i",$_GET["offerno"]);
$st4->execute();

echo '<script>window.location="../show_po.php?offerno='.$_GET["offerno"].'";</script>';