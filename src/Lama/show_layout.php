<?php include './header.php';
      require_once './db/packages.php';
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
                <table id="example1" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>Document ID</th>
                    <th>Document Version</th>
                    <th>File Name</th>
                    <th>File Type</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>User</th>
                    <th>Document</th>
                    <th>Offer</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php
                    if($_SESSION["roleid"] == 1)
                      $groupid = 0;
                    else
                      $groupid = getGroupId($_SESSION["userid"]); 
                    
                    $rs = showLayouts($_SESSION["pckid"]);
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
                      .'" download>Download</a></td><td>';
                      if($row["status"]=='Accept'){
                        if($groupid == 2)
                        echo '<a class="btn btn-primary" href="add_offer.php?pckid='. $_SESSION["pckid"] 
                        .'&ver='. $row["version"].'">Add Offer</a>';
                      }
                      else if($row["status"]=='Offer Submitted' || $row["status"]=='Accept Offer' || $row["status"]=='Reject Offer'){
                        
                        echo '<a class="btn btn-primary" href="show_offer.php?pckid='. $_SESSION["pckid"] 
                        .'&ver='. $row["version"].'">Show Offer</a>';
                        
                      }
                    
                      else{
                         echo 'Offer is not available'; 
                      }
                      echo '</td></tr>';
                    }
                  ?>
                </tbody>
                </table>
                <br/><br/>
                <?php 
                    if($groupid == 3){
                  ?>
                <div>
                  <h3>Package Acceptance</h3>
                  <hr/>
                  <form class="form-horizontal">
                  <div class="row">
                    <label class="control-label col-sm-3" for="email">Select status and click on Submit</label>
                    <select class="form-control col-sm-3" id="status">
                      <option value="Accept">Accept</option>
                      <option value="Reject">Reject</option>
                    </select>
                    &nbsp;&nbsp;&nbsp;&nbsp;
                    <input class="btn btn-primary col-sm-1" type="button" value="Submit" id="sub" />
                  </div>
                  </form>
                </div>
                <?php } else{ ?>

                  <button class="btn btn-primary" id="btnadd">Add Another Layout</button>
                
                <?php } ?>
              </div>
              <!-- /.card-body -->
            </div>
    </div>
    <!-- end table -->
    <!-- /.content -->
<script>

  $("#btnadd").click(function(){
    var ver = $("#example1 tr:eq(1) td:eq(1)").text();
    window.location ="add_layout.php?ver="+ver + "&pckid=<?php echo $_SESSION["pckid"]; ?>";
  });

  $("#sub").click(function(){
    var st = $("#status").val();
    var ver = $("#example1 tr:eq(1) td:eq(1)").text();
    
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