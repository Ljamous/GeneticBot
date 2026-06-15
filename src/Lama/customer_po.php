<?php 
include './header.php';
require_once './db/purchase_orders.php';
require_once './db/offer.php';

$_SESSION["offerno"] = $_GET["offerno"];
?>

<?php 

    $vendors = getPOSuppliers($_SESSION["offerno"]);
    if($vendors->num_rows == 0)
        echo '<br/><center><h2>The purchase orders will be shown soon</h2></center>';
    else{
        while($vendor = $vendors->fetch_assoc()){
            echo '
            <table class="table" style="table-layout: fixed;" >
            <tr>   
            ';
            echo '<td>Vendor ID<br/><b>' . $vendor["SupplierID"] . '</b></td>';
            echo '<td>Vendor Name<br/><b>' . $vendor["SupplierName"] . '</b></td>';
            echo '<td>Currency<br/><b>' . $vendor["Currency"] . '</b></td>';

            echo '<td>PO Number<br/><b>' . $vendor["pono"] . '</b></td>';
            if(empty($vendor["pofile"]))
                    echo '<td>Details will be shown soon</td>';
            else if(empty($vendor["csfile"])){
                echo '<td><a href="imgpo/'.$vendor["pofile"].'" class="btn btn-info" download>Download PO</a> </td>';
                echo '<td><button id="btnUpload" class="btn btn-primary" data-pono="'.$vendor["pono"].'" >Sign and Upload</button></td>';
            }
            else{
                echo '<td><a href="imgpo/'.$vendor["pofile"].'" class="btn btn-info" download>Download PO</a> </td>';
                echo '<td>Signed PO has been sent</td>';
            }
            echo '</tr></table>';
        }
}

?>

<script>
    $(document).ready(function(){
        $(document).on("click", "#btnUpload", function(){
            var btn = $(this);
            var pono = btn.attr("data-pono");

            window.location = "upload_cspo.php?pono=" + pono + "&offerno=<?php echo $_SESSION["offerno"]; ?>";
        });
    });
</script>

<?php include './footer.php'?>