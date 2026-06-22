<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

require_once 'mycon.php';

$name="Bushra Atassi";
$email="bushra@myaia.com";
$password=sha1("Admin2024");
$roleid=1;
global $con;
$st = $con->prepare("insert into users(name, email, password, roleid) values(?,?,?,?)");
$st->bind_param("sssi", $name, $email, $password, $roleid);
$st->execute();