<?php 
include './header.php';
require_once './db/offer.php';

$_SESSION["ver"] = $_GET["ver"];
?>

<div style="padding:8px" id="offerDiv">
<?php 

    if($_SESSION["roleid"] == 1)
    $groupid = 0;
    else
    $groupid = getGroupId($_SESSION["userid"]); 

    $headerRs = showOfferHeader($_GET["pckid"]);
    $headerow = $headerRs->fetch_assoc();
    echo '<h5 id="offer_no">'.$headerow["offerno"] . '</h5>';
    echo '<i>'.$headerow["offer_date"] . '</i><br/>';
    echo '<h4>'.$headerow["name"] . '</h4>';
    echo '<img src="logos/' . $headerow["logo"] . '" style="height:64px; width:64px;" /><br/><br/>';
?>
<table class="table table-bordered table-striped">
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
        <?php 
            $grandTotal = 0;
            $rs = showOffer($_SESSION["pckid"], $_SESSION["ver"]);
            while($row = $rs->fetch_assoc())
            {
                echo '<tr>';
                echo '<td>'.$row["ItemId"] . '</td>';
                echo '<td>'.$row["ItemDescription"] . '</td>';
                echo '<td>'.$row["ExternalItemId"] . '</td>';
                echo '<td>'.$row["CountryId"] . '</td>';
                echo '<td>'.$row["PurchaseUnitId"] . '</td>';
                echo '<td>'.$row["BrandId"] . '</td>';
                echo '<td>'.$row["qty"] . '</td>';
                echo '<td> JD'.$row["unit_price"] . '</td>';
                echo '<td>'.$row["discount"] . '</td>';
                echo '<td>JD'.$row["Price after discount"] . '</td>';
                echo '<td>'.$row["conv_ratio"] . '</td>';
                echo '<td>$'.round($row["Net price"],2) . '</td>';
                echo '<td>$'.round($row["Total"],2) . '</td>';
                echo '</tr>';
                $grandTotal = $grandTotal + $row["Total"];
            }
        ?>
</table>

<h4 style="text-align: right;">Grand Total: $<?php echo round($grandTotal, 2); ?></h4>

</div>

<center><button type="button" class="btn btn-primary" onclick="printDiv()">Print</button></center>

<?php 
                    if($groupid == 3){
                  ?>
                <div style="padding:8px">
                  <h3>Offer Acceptance</h3>
                  <hr/>
                  <form class="form-horizontal">
                  <div class="row">
                    <label class="control-label col-sm-3" for="email">Select status and click on Submit</label>
                    <select class="form-control col-sm-3" id="status">
                      <option value="Accept Offer">Accept Offer</option>
                      <option value="Reject Offer">Reject Offer</option>
                    </select>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="btn btn-primary col-sm-1" type="button" value="Submit" id="sub" />
                  </div>
                  </form>
                </div>
                <?php } else{ 
                    
                    if(getDocStatus($_GET["ver"]) == "Accept Offer"){
                        echo '<br/><br/><center><button class="btn btn-info" onclick="showPO()">Generate Purchase Orders</button></center>';
                    }
                }
               ?>

<script>

        function showPO(){
            window.location = "show_po.php?offerno=" + offer_no.innerText;
        }

        function printDiv() {
            var divContents = document.getElementById("offerDiv").outerHTML;
            var printWindow = window.open('', '', 'height=500,width=500');
            printWindow.document.write('<html><head><title>Print</title>');
            printWindow.document.write('<style> table{border-collapse: collapse;width: 100%;font-family: Arial, sans-serif;} th, td{border: 1px solid #ddd;padding: 8px;text-align: left;} th{background-color: #f2f2f2;} tr:nth-child(even) {background-color: #f9f9f9;} </style>');
            printWindow.document.write('</head><body>');
            printWindow.document.write(divContents);
            printWindow.document.write('</body></html>');
            printWindow.document.close();
            printWindow.print();
        }

        $("#sub").click(function(){
            var st = $("#status").val();
            var ver = <?php echo $_GET["ver"]; ?>;
            
            $.ajax({
        url: "db/layout_status.php",
        type: "POST",
        data: {
            status: st,
            version: ver
        },
        success: function(response) {
            window.location = "tracking_view.php";
        },
        error: function(xhr, status, error) {
            alert(error);
        }
        });
    });

    </script>

<?php include './footer.php'?>

