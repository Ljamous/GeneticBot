<?php

$un = "admin";
$pw = sha1("admin");
$nm = "Admin";
$con = new mysqli("localhost", "root", "", "ptsdb");
$st = $con->prepare("insert into users(username, password,name) values(?,?,?)");
$st->bind_param("sss", $un, $pw, $nm);
$st->execute();
