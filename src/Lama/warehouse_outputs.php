<?php include './header.php';
require_once './db/warehouse.php';
    if(isset($_GET["itemid"]))
    $_SESSION["itemid"] = $_GET["itemid"];
?>

<br/><br/>
<center>
    <h2>Item Withdrawing</h2>
    <br/><br/>
    <form action="warehouse_outputs.php" method="post">
        <input type="text" name="qty" placeholder="Qty"
        class="form-control" style="width:20%" />
        <br/><br/>
        <textarea name="notes" placeholder="Description" rows="5"
        class="form-control" style="width:30%" ></textarea>
        <br/><br/>
        <input type="submit" name="btnsub" value="Submit"
        class="btn btn-primary" style="width:25%" />
    </form>
</center>

<?php 

    if(isset($_POST["btnsub"])){
       
        withDraw($_SESSION["itemid"], $_POST["qty"], $_POST["notes"]);
        echo '<script>window.location="warehouse_items.php";</script>';
    }

?>


<?php include './footer.php'; ?>