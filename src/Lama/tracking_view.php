<?php include './header.php';
      require_once './db/packages.php'; ?>

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
                    <th>Project</th>
                    <th>Submission</th>
                    <th>Name</th>
                    <th>Abbreviation</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Task</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                    $rs = showPackages($_SESSION["roleid"]);
                    $groupid = getGroupId($_SESSION["userid"]);
                    while($row = $rs->fetch_assoc()){
                      echo '<tr>';
                      echo '<td>'. $row["proabv"] .'</td>';
                      echo '<td>'. $row["subabv"] .'</td>';
                      echo '<td>'. $row["name"] .'</td>';
                      echo '<td>'. $row["abbrv"] .'</td>';
                      echo '<td>'. $row["type"] .'</td>';
                      echo '<td>'. $row["descr"] .'</td>';
                      echo '<td>'. $row["subdate"] .'</td>';
                      echo '<td>'. $row["status"] .'</td>';
                      if($row["status"] == "Created")
                        echo '<td><button class="btn btn-primary btn-sm" id="taskbtn" data-pckid="'.$row["id"].'">
                                Add Layout</button></td>';
                      else if($row["status"] == "Closed")
                        echo '<td>Closed</td>';
                      else if($row["status"] == "PO Created")
                      echo '<td><button class="btn btn-primary btn-sm" id="pobtn" data-pckid="'.$row["id"].'">
                                Show PO</button></td>';
                      else if($row["status"] == "PO Approved"){
                        if($groupid == 3)
                          echo '<td>Items will be sent soon</td>';
                          else
                          echo '<td><a href="submit_items.php?pckid='.$row["id"].'" class="btn btn-primary btn-sm">
                                      Submit Items</a></td>';
                      }
                      else if($row["status"] == "Items Submitted"){
                        if($groupid == 3)
                          echo '<td>Items has been sent <br/> <a href="receive_items.php?pckid='.$row["id"].'">Click for Details</a></td>';
                          else
                          echo '<td><a href="submit_items.php?pckid='.$row["id"].'" class="btn btn-primary btn-sm">
                                      Submit Items</a></td>';
                      } 
                      else if($row["status"] == "Items Received"){
                          echo '<td>Items has been received </td>';
                      }    
                      else
                        echo '<td><button class="btn btn-primary btn-sm" id="showbtn" data-pckid="'.$row["id"].'">
                                Show Layout</button></td>';
                      echo '</tr>';
                    }
                  ?>
                </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
    </div>
    <!-- end table -->
    <!-- /.content -->
    

    <script>
      $(document).ready(function(){

        $(document).on("click", "#taskbtn", function(){
          window.location = "add_layout.php?pckid=" + $(this).attr("data-pckid");
        });
        
        $(document).on("click", "#showbtn", function(){
          window.location = "show_layout.php?pckid=" + $(this).attr("data-pckid");
        });

        $(document).on("click", "#pobtn", function(){
          window.location = "show_po.php?pckid=" + $(this).attr("data-pckid");
        });
       
      });
    </script>

<?php include './footer.php'?>