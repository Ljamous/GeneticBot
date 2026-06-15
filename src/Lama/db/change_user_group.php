<?php
require_once 'mycon.php';

$st = $con->prepare("select id from users_groups where parentid=? and groupid=?");
$st->bind_param("ii", $_POST["parentid"], $_POST["groupid"]);
$st->execute();
$rs = $st->get_result();
if($rs->num_rows > 0){
    $st2 = $con->prepare("update users_groups set userid=? where parentid=? and groupid=?");
    $st2->bind_param("iii",$_POST["userid"], $_POST["parentid"], $_POST["groupid"]);
    $st2->execute();
}
else{
    $st3 = $con->prepare("insert into users_groups(userid, parentid, groupid) values(?,?,?)");
    $st3->bind_param("iii",$_POST["userid"], $_POST["parentid"], $_POST["groupid"]);
    $st3->execute();
}



