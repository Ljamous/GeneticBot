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
            <!-- Horizontal Form -->
            <div class="card card-info">
              <div class="card-header">
                <h3 class="card-title">New Package</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" method="post" action="packages_view.php">
                <div class="card-body">
                <input type="hidden" name="pckid" id="pckid" value="0" />
                <div class="form-group row">
                   
                      <label for="project" class="col-sm-2 col-form-label">Project</label>
                      <div class="col-sm-4">
                        <select type="text" class="form-control" id="project" name="project" required>
                          <option value="0">Select project</option>
                          <?php 
                            $rs = getProjAbrv($_SESSION["roleid"]);
                            while($row = $rs->fetch_assoc())
                            {
                              echo '<option value="'.$row["id"].'">' .$row["abbrv"]. '</option>';
                            }
                          ?>
                        </select>
                      </div>

                      <label for="submission" class="col-sm-2 col-form-label">Submission</label>
                      <div class="col-sm-4">
                        <select class="form-control" id="submission" name="submission" required>
                            
                        </select>
                      </div>
                  </div>
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                    </div>
                    <label for="abbrv" class="col-sm-2 col-form-label">Abbreviation</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="abbrv" name="abbrv" placeholder="Abbreviation" required>
                    </div>
                  </div>

                  <div class="form-group row">
                    <label for="type" class="col-sm-2 col-form-label">Scope</label>
                    <div class="col-sm-4">
                    <select class="form-control" id="type" name="type" required>
                    <option value="Indoor">Indoor</option>
                            <option value="Outdoor">Outdoor</option>
                            <option value="Facade">Facade</option>
                            <option value="Pool">Pool</option>
                        </select>
                    </div>
                    <label for="descr" class="col-sm-2 col-form-label">Description</label>
                    <div class="col-sm-4">
                      <textarea class="form-control" id="descr" name="descr" required>

                      </textarea>
                    </div>
                  </div>
                 
                  
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info" name="subbtn">Submit</button>
                  <a href="./home.php" class="btn btn-default float-right">Cancel</a>
                </div>
                <!-- /.card-footer -->
              </form>
            </div>
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
                    <th>Package</th>
                    <th>Abbreviation</th>
                    <th>Scope</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Status</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                    $rs = showPackages($_SESSION["roleid"]);
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
    <?php
      if(isset($_POST["subbtn"])){

        if($_POST["project"] == 0)
        {
          echo '<script>
                    alert("Please select a project");
                </script>';
        }
        else{ 
            addPackage($_POST["name"], $_POST["abbrv"], $_POST["type"], 
            $_POST["descr"], $_POST["submission"]); 
    
            echo '<script>
                      alert("Package has been added");
                      window.location = "packages_view.php";
                  </script>';
        }
        
      }
    ?>

    <script>
      $(document).ready(function(){
        
        $('#project').on('change', function() {
            var prjid = (this.value);
            $.ajax({
            url:"db/get_submissions.php?prjid=" + prjid,    
            type: "get",
            dataType:"json",
            success:function(data){
                var subSelect = $("#submission");
                subSelect.empty();
                for (var i=0; i<data.length; i++) {
                  subSelect.append('<option value="' + data[i].id + '">' + data[i].abbrv + '</option>');
                }
            },
            error:function(xhr, err){
                alert(xhr.responseText);
            }
          });
        });
      });
    </script>

<?php include './footer.php'?>