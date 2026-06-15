<?php
require_once 'mycon.php';

$st = $con->prepare("select active from users where id=?");
$st->bind_param("i", $_POST["userid"]);
$st->execute();
$rs = $st->get_result();
$row = $rs->fetch_assoc();

if($row["active"] == 1){
    $st2 = $con->prepare("update users set active=-1 where id=?");
    $st2->bind_param("i", $_POST["userid"]);
    $st2->execute();
    echo "User has been disabled";
}
else{
    $st3 = $con->prepare("update users set active=1 where id=?");
    $st3->bind_param("i", $_POST["userid"]);
    $st3->execute();
    echo "User has been enabled";
}