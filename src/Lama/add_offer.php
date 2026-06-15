<?php include './header.php';
      require_once './db/items.php';
      $_SESSION["pckid"] = $_GET["pckid"];
?>

<!-- Content Header (Page header) -->
<div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            
          </div><!-- /.col -->
          <!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-lg-1"></div>  
          <div class="col-lg-10" >
            
            <!-- /.card -->
          </div>
          <div class="col-lg-1"></div>  
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
      <!-- table -->
      <div class="card">

              <div class="card-body">

              <table style="width: 100%;">
                <tr>
                  <td style="width: 25%;"><input type="text" id="item" name="item" placeholder="Item ID" class="form-control"></td>
                  <td style="width: 50%;"></td>
                  <td style="width: 15%;"><b>Grand Total</b>  <h2 id="grandTotalHeader">0</h2></td>
                  <td style="width: 10%;"><input type="button" id="btnsub" class="btn btn-primary" value="Submit Offer"></td>
                </tr>
              </table>
              

                <br/>
                <table id="itemsTable" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Item ID</th>
                    <th>Brand</th>
                    <th>Description</th>
                    <th>Country</th>
                    <th>Currency</th>
                    <th>Unit</th>
                    <th>Vendor</th>
                    <th>Add</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                     
                    /*$rs = showLayouts($_SESSION["pckid"]);
                    while($row = $rs->fetch_assoc()){
                      echo '<tr>';
                      echo '<td>'. $row["id"] .'</td>';
                      echo '<td>'. $row["version"] .'</td>';
                      $version = $row["version"];
                      echo '<td>'. $row["file_name"] .'</td>';
                      echo '<td>'. $row["file_type"] .'</td>';
                      echo '<td>'. $row["subdate"] .'</td>';
                      echo '<td>'. $row["status"] .'</td>';
                      echo '<td>'. getName($row["userid"]) .'</td>';
                      echo '<td><a class="btn btn-primary" href="./layouts/'. $row["file_name"] 
                      .'" download>Download</a></td>';
                      if($row["status"]=='Accept'){
                        echo '<td><a class="btn btn-primary" href="add_offer.php?pckid='. $_SESSION["pckid"] 
                        .'&ver='. $row["version"].'">Add Offer</a></td>';
                      }
                      else{
                         echo '<td>Offer is not available</td>'; 
                      }
                      echo '</tr>';
                    }*/
                  ?>
                </tbody>
                </table>
                <br/><br/>
                <h2>Offer</h2><hr/>
                <table id="offerTable" class="table">
                    <th>ID</th>
                    <th>Item ID</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Discount %</th>
                    <th>Price after Discount</th>
                    <th>Conversion Ratio</th>
                    <th>Net Unit Price (USD)</th>
                    <th>Total</th>
                    <th>Delete</th>
                    <tbody></tbody>
                </table>
                <br/><br/>
                
              </div>
              <!-- /.card-body -->
            </div>
    </div>
    <!-- end table -->
    <!-- /.content -->
    <script>
      
        $(document).ready(function() {
          
            $("#item").autocomplete({
                source: "search_item.php",
                minLength: 3, 
                select: function( event, ui ) {
                event.preventDefault();
                    //alert(ui.item.id);
                    //alert(ui.item.value);
                    $.ajax({
                      url: "item_details.php?id=" + ui.item.id,
                      type: "GET",
                      success: function(response) {
                        var jsonObj = $.parseJSON(response);
                        var table = $("#itemsTable");
                        table.find("tr").not(":first").remove();
                        var newRow = $("<tr>");
                        // Create the cells for the new row
                        var idCell = $("<td>").text(jsonObj["id"]); 
                        var itemIdCell = $("<td>").text(jsonObj["externalitemid"]); 
                        var brandCell = $("<td>").text(jsonObj["brandid"]); 
                        var descriptionCell = $("<td>").text(jsonObj["itemDescription"]);
                        var countryCell = $("<td>").text(jsonObj["countryid"]); 
                        var currencyCell = $("<td>").text(jsonObj["currency"]); 
                        var unitCell = $("<td>").text(jsonObj["purchaseunitid"]); 
                        var vendorCell = $("<td>").text(jsonObj["suppliername"]);
                        var addButtonCell = $("<td>").html('<button class="btn btn-primary" id="btnadd">Add</button>'); 

                        // Append the cells to the new row
                        newRow.append(idCell, itemIdCell, brandCell, descriptionCell,  countryCell, currencyCell, unitCell, vendorCell, addButtonCell);

                        // Append the new row to the table body
                        table.append(newRow);

                        $("#btnadd").click(function(){
                          var myid = $("#itemsTable tr:eq(1) td:eq(0)").text();
                          var itemid = $("#itemsTable tr:eq(1) td:eq(1)").text();
                          var table = $("#offerTable");

                          // Create a new row and append it to the table body
                          var newRow = $("<tr>");

                          // Create the cells for the new row
                          var idCell = $("<td class='itemid'>").text(myid);
                          var itemIdCell = $("<td class='itemid2'>").text(itemid); 
                          var qtyCell = $("<td>").text("1"); 
                          var unitPriceCell = $("<td>").text("10"); 
                          var discountCell = $("<td>").text("5"); 
                          var priceAfterDiscountCell = $("<td>").text("9.5"); 
                          var conversionRatioCell = $("<td>").text("1.4"); 
                          var netUnitPriceCell = $("<td>").text("13.3"); 
                          var totalCell = $("<td class='total'>").text("13.3"); 
                          var deleteButtonCell = $("<td>").html('<button id="btndel" class="btn btn-danger">Delete</button>'); 

                          // Append the cells to the new row
                          newRow.append(idCell, itemIdCell, qtyCell, unitPriceCell, discountCell, priceAfterDiscountCell, conversionRatioCell, netUnitPriceCell, totalCell, deleteButtonCell);

                          // Append the new row to the table body
                          table.append(newRow);
                          updateTotal();
                          $("#item").val("");
                          $("#item").focus();
                          
                          $("#offerTable").on("click", "#btndel", function(){
                            var row = $(this).closest("tr");
                            row.remove();
                            updateTotal();
                          });

                          $("#offerTable td").on("click", function() {
                            var cellContent = $(this).text();

                            if(($(this).hasClass("itemid")==false && $(this).hasClass("itemid2")==false) && cellContent != "Delete")
                            {
                            var inputField = $("<input type='text'>").val(cellContent);
                            $(this).html(inputField);
                            inputField.focus();
                            inputField.on("blur", function() {
                              var newValue = $(this).val();
                              $(this).parent().text(newValue);
                              updateTotal();
                            });
                          }
                          else if($(this).hasClass("itemid")){
                            showItem($(this).text());
                          }
                          });
                        
                        });

                      },
                      error: function(xhr, status, error) {
                        alert(error);
                      }
                    });
                }
            });

            $("#btnsub").click(function(){
              var pckId = <?php echo $_SESSION["pckid"]; ?> ;
              var docVer = <?php echo $_GET["ver"]; ?> ;
              
              ids = [];
              qtys = [];
              ups = [];
              discs = [];
              cnvrts = [];

              $("#offerTable tbody tr").each(function(){
                 var id = parseFloat($(this).find("td:eq(0)").text());
                 if(!isNaN(id))
                  ids.push(id);
                
                  var qty = parseFloat($(this).find("td:eq(2)").text());
                 if(!isNaN(qty))
                  qtys.push(qty);

                  var up = parseFloat($(this).find("td:eq(3)").text());
                 if(!isNaN(up))
                  ups.push(up);

                  var disc = parseFloat($(this).find("td:eq(4)").text());
                 if(!isNaN(disc))
                 discs.push(disc);

                 var cnvrt = parseFloat($(this).find("td:eq(6)").text());
                 if(!isNaN(cnvrt))
                 cnvrts.push(cnvrt);

              });

              $.ajax({
                url:"db/submit_offer.php",    
                type: "post",
                data: {packageid: pckId, doc_version: docVer, item_id:ids,
                  qty: qtys, unit_price: ups, discount: discs, conv_ratio: cnvrts},
                success:function(data){
                    alert(data);
                    window.location = "http://localhost:8080/pts/show_layout.php?pckid=" + pckId;
                },
                error:function(xhr, err){
                    alert(xhr.responseText);
                }
            });
              
            });
        });

        function updateTotal() {
          var grandTotal = 0;
    $("#offerTable tbody tr").each(function() {
      var qty = parseFloat($(this).find("td:eq(2)").text());
      var price = parseFloat($(this).find("td:eq(3)").text());
      var discount = parseFloat($(this).find("td:eq(4)").text());
      var priceAfterDiscount =  price - price * discount / 100;
      $(this).find("td:eq(5)").text(priceAfterDiscount.toFixed(2));

      var conversionRatio = parseFloat($(this).find("td:eq(6)").text());
      var netUnitPrice = priceAfterDiscount * conversionRatio;
      $(this).find("td:eq(7)").text(netUnitPrice.toFixed(2));

      var total = netUnitPrice * qty;
      $(this).find("td:eq(8)").text(total.toFixed(2));
      
      if(!isNaN(total))
      grandTotal = grandTotal + total;

      $("#grandTotalHeader").text(grandTotal.toFixed(2));
    });
  }

  function showItem(extid){
    $.ajax({
                      url: "item_details.php?id=" + extid,
                      type: "GET",
                      success: function(response) {
                        var jsonObj = $.parseJSON(response);
                        var table = $("#itemsTable");
                        table.find("tr").not(":first").remove();
                        var newRow = $("<tr>");
                        // Create the cells for the new row
                        var myIdCell = $("<td>").text(jsonObj["id"]); 
                        var itemIdCell = $("<td>").text(jsonObj["externalitemid"]); 
                        var brandCell = $("<td>").text(jsonObj["brandid"]); 
                        var descriptionCell = $("<td>").text(jsonObj["itemDescription"]);
                        var countryCell = $("<td>").text(jsonObj["countryid"]); 
                        var currencyCell = $("<td>").text(jsonObj["currency"]); 
                        var unitCell = $("<td>").text(jsonObj["purchaseunitid"]); 
                        var vendorCell = $("<td>").text(jsonObj["suppliername"]);
                        var addButtonCell = $("<td>").html('<button class="btn btn-primary" id="btnadd">Add</button>'); 
                        // Append the cells to the new row
                        newRow.append(myIdCell, itemIdCell, brandCell, descriptionCell,  countryCell, currencyCell, unitCell, vendorCell, addButtonCell);
                        // Append the new row to the table body
                        table.append(newRow);
                        
                      }, failure: function(ex){
                        alert(ex);
                      }
                      });
                
  }

    </script>

<?php include './footer.php'?>