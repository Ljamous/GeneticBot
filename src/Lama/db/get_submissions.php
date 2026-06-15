<?php
require_once 'mycon.php';

$st = $con->prepare("select id, abbrv from submissions  where projectid=?");
$st->bind_param("i", $_GET["prjid"]);
$st->execute();
$rs = $st->get_result();
while($row = $rs->fetch_assoc())
{
    $result[] = array(
      'id' => $row['id'],
      'abbrv' => $row['abbrv'],
    );
  }
  echo json_encode($result);
