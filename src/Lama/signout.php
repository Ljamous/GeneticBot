<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

$_SESSION["userid"] = null;
$_SESSION["roleid"] = null;

echo '<script>window.location="index.php";</script>';
