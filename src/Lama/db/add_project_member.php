<?php
require_once 'mycon.php';

$sql = "update projects set designerid=? where id=?";
    if($_POST["type"] == 1)
        $sql = "update projects set accountantid=? where id=?";
    
    $st = $con->prepare($sql);
    $st->bind_param("ii", $_POST["member"], $_POST["id"]);
    $st->execute();

