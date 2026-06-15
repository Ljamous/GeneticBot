<?php
require_once './db/items.php';

if(isset($_GET["id"]))
    echo searchItemById($_GET['id']);
