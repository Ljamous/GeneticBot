<?php 
include './header.php';
require_once './db/warehouse.php';
?>

<div style="padding:8px" id="offerDiv">
<?php 

    if($_SESSION["roleid"] == 1)
    $groupid = 0;
    else
    $groupid = getGroupId($_SESSION["userid"]); 

?>

<table class="table table-bordered table-striped">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Brand</th>
            <th>Qty</th>
            <th>Reject</th>
        </tr>
        <?php 
            $rs = show_inputs();
            while($row = $rs->fetch_assoc())
            {
                echo '<tr>';
                echo '<td>'.$row["ExternalItemId"] . '</td>';
                echo '<td>'.$row["ItemName"] . '</td>';
                echo '<td>'.$row["BrandId"] . '</td>';
                echo '<td>'.$row["qty"] . '</td>';
                echo '<td><a href="warehouse_reject.php?id='.$row["id"].'" class="btn btn-danger">Reject</a></td>';
                echo '</tr>';
            }
        ?>
</table>


</div>

<center>
    <?php if($rs->num_rows > 0){ ?>
    <form action="warehouse_inputs.php" method="post">
        <input type="submit" value="Approve Items" class="btn btn-primary" 
        name="sub" style="width:20%" />
    </form>
    <?php } ?>
</center>

<?php
    if(isset($_POST["sub"])){
        approve_inputs();
        echo '<script>window.location="warehouse_items.php";</script>';
    }
?>


<?php include './footer.php'?>

