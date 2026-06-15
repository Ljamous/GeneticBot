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
<h2>Warehouse Items</h2>
<br/>
<table class="table table-bordered table-striped">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Brand</th>
            <th>Qty</th>
            <th>Withdraw</th>
        </tr>
        <?php 
            $rs = show_warehouse_items();
            while($row = $rs->fetch_assoc())
            {
                echo '<tr>';
                echo '<td>'.$row["ExternalItemId"] . '</td>';
                echo '<td>'.$row["ItemName"] . '</td>';
                echo '<td>'.$row["BrandId"] . '</td>';
                echo '<td>'.$row["qty"] . '</td>';
                echo '<td><a href="warehouse_outputs.php?itemid='.$row["itemid"].'" class="btn btn-info">Withdraw</a></td>';
                echo '</tr>';
            }
        ?>
</table>


</div>



<?php include './footer.php'?>

