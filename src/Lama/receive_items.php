<?php include './header.php';
require_once './db/warehouse.php';

if(isset($_GET["pckid"]))
    $_SESSION["pckid"] = $_GET["pckid"];
?>

<br/><br/>
<center>

<?php
require_once 'db/mycon.php';

$st = $con->prepare("select exp_delivery from packages where id=?");
$st->bind_param("i",$_SESSION["pckid"]);
$st->execute();
$rs = $st->get_result();
$row = $rs->fetch_assoc();
echo '<h5> Expected Delivery Period </h5>';
echo '<h2>'. $row["exp_delivery"] .'</h2>';

?>

<br/><br/>
    <form action="receive_items.php" method="post">
        <input type="submit" name="btnsub" value="Receive Items"
        class="btn btn-primary" style="width:25%" />
    </form>
</center>

<?php 

    if(isset($_POST["btnsub"])){
        require_once 'db/mycon.php';

        $status="Items Received";
        $st = $con->prepare("update packages set status=? where id=?");
        $st->bind_param("si", $status,  $_SESSION["pckid"]);
        $st->execute();

        save_inputs($_SESSION["pckid"]);


        echo '<script>window.location="tracking_view.php";</script>';
    }

?>

<?php include './footer.php'; ?>