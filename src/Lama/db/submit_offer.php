<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
require_once 'mycon.php';


$st = $con->prepare("insert into offer(userid,packageid,doc_version) values(?,?,?)");
$st->bind_param("iii", $_SESSION["userid"], $_POST["packageid"],$_POST["doc_version"]);
$st->execute();
$offerno = $con->insert_id;

$item_id = $_POST["item_id"];
$qty = $_POST["qty"];
$unit_price = $_POST["unit_price"];
$discount = $_POST["discount"];
$conv_ratio = $_POST["conv_ratio"];

for($x = 0; $x< count($item_id); $x++)
{
    $st2 = $con->prepare("INSERT INTO offer_details(offerno, item_id, qty, unit_price, discount, conv_ratio) VALUES (?,?,?,?,?,?)");
    $st2->bind_param("iiidid", $offerno, $item_id[$x], $qty[$x], $unit_price[$x], $discount[$x], $conv_ratio[$x]);
    $st2->execute();
}

$st3 = $con->prepare("update docs set status='Offer Submitted' where packageid=? and version=?");
$st3->bind_param("ii", $_POST["packageid"],$_POST["doc_version"]);
$st3->execute();


echo "Offer has been submitted";




