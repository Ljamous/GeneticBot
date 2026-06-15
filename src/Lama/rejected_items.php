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

<br/>
<h2>Rejected Items</h2>
<br/>

<center>
    <form action="rejected_items.php" method="post">
<table class="table table-bordered table-striped">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Brand</th>
            <th>Qty</th>
            <th>Type</th>
            <th>Approved</th>
            <th>Date</th>
            <th>Notes</th>
        </tr>
        <?php 
            $rs = show_rejected();
            while($row = $rs->fetch_assoc())
            {
                echo '<tr>';
                echo '<td>'.$row["ExternalItemId"] . '</td>';
                echo '<td>'.$row["ItemName"] . '</td>';
                echo '<td>'.$row["BrandId"] . '</td>';
                echo '<td><input name="qty" value="'.$row["qty"] . '" style="width:50%" /></td>';
                echo '<td>'.$row["type"] . '</td>';
                echo '<td>'.$row["approved"] . '</td>';
                echo '<td>'.$row["trans_date"] . '</td>';
                echo '<td>'.$row["notes"] . '</td>';
                echo '</tr>';
            }
        ?>
</table>
        <br/>
        <?php if($rs->num_rows > 0){ ?>
            <input type="submit" value="Approve" class="btn btn-primary " 
            name="sub" style="width:20%" />
        <?php } ?>

    </form>
</center>

</div>

<?php
    if(isset($_POST["sub"])){
        approve_inputs_byadmin($_POST["qty"]);
        echo '<script>window.location="warehouse_transactions.php";</script>';
    }
?>

<?php include './footer.php'?>

