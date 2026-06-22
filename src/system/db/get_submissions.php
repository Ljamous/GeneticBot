<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

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
