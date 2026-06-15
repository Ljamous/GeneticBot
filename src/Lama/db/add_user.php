<?php
require_once 'mycon.php';

$name="Bushra Atassi";
$email="bushra@myaia.com";
$password=sha1("Admin2024");
$roleid=1;
global $con;
$st = $con->prepare("insert into users(name, email, password, roleid) values(?,?,?,?)");
$st->bind_param("sssi", $name, $email, $password, $roleid);
$st->execute();