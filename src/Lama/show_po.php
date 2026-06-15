<?php 
include './header.php';
require_once './db/purchase_orders.php';
require_once './db/offer.php';

if(isset($_GET["offerno"]))
    $_SESSION["offerno"] = $_GET["offerno"];
else{
    $_SESSION["offerno"] = getOfferNo($_GET["pckid"]);

if($_SESSION["roleid"] == 1)
    $groupid = 0;
else{
     $groupid = getGroupId($_SESSION["userid"]);
     if($groupid == 3)
        echo '<script>window.location="customer_po.php?offerno='.$_SESSION["offerno"].'";</script>';
   }
}

?>

<?php 

    $vendors = getPOSuppliers($_SESSION["offerno"]);
    $no_of_pos = $vendors->num_rows;
    $no_signed_pos = 0;
    
    while($vendor = $vendors->fetch_assoc()){
        echo '
    <table class="table" style="table-layout: fixed;" >
     <tr>   
    ';
        echo '<td>Vendor ID<br/><b>' . $vendor["SupplierID"] . '</b></td>';
        echo '<td>Vendor Name<br/><b>' . $vendor["SupplierName"] . '</b></td>';
        echo '<td>Currency<br/><b>' . $vendor["Currency"] . '</b></td>';
        if(is_null($vendor["pono"]))
            echo '<td><button id="btnCreate" class="btn btn-primary" data-supid="'.$vendor["SupplierID"].'" data-cur="'.$vendor["Currency"].'">Create</button></td>';
        else{
            echo '<td>PO Number<br/><b>' . $vendor["pono"] . '</b></td>';
            if(empty($vendor["pofile"]))
                echo '<td><button id="btnUpload" class="btn btn-primary" data-pono="'.$vendor["pono"].'" >Upload PO</button></td>';
            else
                echo '<td><a href="imgpo/'.$vendor["pofile"].'" class="btn btn-info" download>Download IMG PO</a> </td>';
            
                if(empty($vendor["csfile"]))
                echo '<td>Waiting for customer signed PO</td>';
            else{
                echo '<td><a href="cspo/'.$vendor["csfile"].'" class="btn btn-info" download>Download Signed PO</a> </td>';
                $no_signed_pos++;
            }
        }
           
        
        echo '</tr></table>';

        echo '
        <table class="table table-bordered table-striped" id="'.$vendor["SupplierID"].'potbl">
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Part No</th>
            <th>Country</th>
            <th>Unit</th>
            <th>Brand</th>
            <th>Qty</th>
            <th>Price</th>
            <th>Discount</th>
            <th>Price after discount</th>
            <th>Conversion</th>
            <th>Net</th>
            <th>Total</th>
        </tr>
        ';

        $pos = genPO($_SESSION["offerno"], $vendor["SupplierID"]);
        while($po = $pos->fetch_assoc()){
                echo '<tr>';
                echo '<td>'.$po["ItemId"] . '</td>';
                echo '<td>'.$po["ItemDescription"] . '</td>';
                echo '<td>'.$po["ExternalItemId"] . '</td>';
                echo '<td>'.$po["CountryId"] . '</td>';
                echo '<td>'.$po["PurchaseUnitId"] . '</td>';
                echo '<td>'.$po["BrandId"] . '</td>';
                echo '<td>'.$po["qty"] . '</td>';
                echo '<td> JD'.$po["unit_price"] . '</td>';
                echo '<td>'.$po["discount"] . '</td>';
                echo '<td>JD'.$po["Price after discount"] . '</td>';
                echo '<td>'.$po["conv_ratio"] . '</td>';
                echo '<td>$'.round($po["Net price"],2) . '</td>';
                echo '<td>$'.round($po["Total"],2) . '</td>';
                echo '</tr>';    
        }
        echo '</table><hr/>';
    }

    if($no_signed_pos == $no_of_pos)
        echo '<center><form action="db/approve_po.php" method="post">
                <input type="hidden" value="'.$_GET["pckid"].'" name="pckid" />
                <input type="submit" class="btn btn-primary" value="Approve purchase orders" />
              </form></center>
        ';

?>

<script>

$(document).ready(function(){
    $(document).on("click", "#btnCreate", function(){
        var btn = $(this);
        var supid = btn.attr("data-supid");
        var supcur = btn.attr("data-cur");

        itemids = [];
        units = [];
        qtys = [];
        prices = [];

        $("#"+supid+"potbl tr").each(function(){
            var itemId = $(this).find("td:eq(0)").text();
            var unit = $(this).find("td:eq(4)").text();
            var qty = parseInt($(this).find("td:eq(6)").text());
            var price = parseFloat($(this).find("td:eq(9)").text().replace("JD", ""));
            
            if(itemId.length > 0)
                itemids.push(itemId);

            if(unit.length > 0)
                units.push(unit);
            
            if(!isNaN(qty))
                qtys.push(qty);
            
            if(!isNaN(price))
                prices.push(price);
        });

        var p1="", p2="", p3="", p4="";
        for(i=0;i<itemids.length;i++){
            p1 = p1 + "itemIds=" + itemids[i] + "&";
            p2 = p2 + "qty=" + qtys[i] + "&";
            p3 = p3 + "prices=" + prices[i] + "&";
            p4 = p4 + "orderUnits=" + units[i] + "&";
        }

        var wsurl = "http://localhost:44308/Home/Index?" + p1 + p2 + p3 + p4 + "vendorno=" + supid +"&cur=" + supcur + "&refId=Int0001&offerno=<?php echo $_SESSION["offerno"]; ?>";
        alert(wsurl);
        window.location = wsurl;
        
        //http://localhost:44308/Home/Index?itemIds=PLLC001138&itemIds=PLLC000127&qty=10&qty=5&prices=634.11&prices=625&orderUnits=Each&orderUnits=Each&vendorno=V01519&cur=JOD&refId=Int0001

    });

    $(document).on("click", "#btnUpload", function(){
        var btn = $(this);
        var pono = btn.attr("data-pono");

        window.location = "upload_po.php?pono=" + pono + "&offerno=<?php echo $_SESSION["offerno"]; ?>";
    });
});

</script>

<?php include './footer.php'?>