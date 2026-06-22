<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */


$un = "admin";
$pw = sha1("admin");
$nm = "Admin";
$con = new mysqli("localhost", "root", "", "ptsdb");
$st = $con->prepare("insert into users(username, password,name) values(?,?,?)");
$st->bind_param("sss", $un, $pw, $nm);
$st->execute();
