<?php include './header.php';
      require_once './db/submissions.php'; ?>

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
                <h3 class="card-title">New Submission</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" method="post" action="submissions_view.php">
                <div class="card-body">
                <input type="hidden" name="sbmid" id="sbmid" value="0" />
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
                      <label for="project" class="col-sm-2 col-form-label">Project</label>
                      <div class="col-sm-4">
                        <select type="text" class="form-control" id="project" name="project" required>
                          <?php 
                            $rs = getProjAbrv($_SESSION["roleid"]);
                            while($row = $rs->fetch_assoc())
                            {
                              echo '<option value="'.$row["id"].'">' .$row["abbrv"]. '</option>';
                            }
                          ?>
                        </select>
                      </div>
                  </div>
                 
                  
                  <div class="col-sm-12">
                    <hr/>
                  </div>
                  
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info" name="subbtn">Save</button>
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
                    <th>ID</th>
                    <th>Name</th>
                    <th>Abbreviation</th>
                    <th>Scope</th>
                    <th>Project</th>
                    <th>Edit</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                    $rs = showSubmissions($_SESSION["roleid"]);
                    while($row = $rs->fetch_assoc()){
                      echo '<tr>';
                      echo '<td>'. $row["id"] .'</td>';
                      echo '<td>'. $row["name"] .'</td>';
                      echo '<td>'. $row["abbrv"] .'</td>';
                      echo '<td>'. $row["type"] .'</td>';
                      echo '<td>'. $row["projectid"] . ' - ' . $row["abv"] .'</td>';
                      echo '<td><button class="btn btn-info btn-sm" id="edtbtn" >
                                <i class="fas fa-pencil-alt"></i>
                                Edit</button></td>';
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
        if($_POST["sbmid"] == 0){  

          addSubmission($_POST["name"], $_POST["abbrv"], $_POST["type"], $_POST["project"]); 
  
          echo '<script>
                    alert("Submission has been added");
                    window.location = "submissions_view.php";
                </script>';
        }
        else{
          editSubmission($_POST["sbmid"], $_POST["name"], $_POST["abbrv"], $_POST["type"], 
          $_POST["project"]);

          echo '<script>
                    alert("Submission data have been updated");
                    window.location = "submissions_view.php";
                </script>';
        }
      }
    ?>

    <script>
      $(document).ready(function(){
        $(document).on("click", "#edtbtn", function(){
          var $row = $(this).closest("tr");

          var id = $row.find("td:nth-child(1)").text();
          $("#sbmid").val(id);

          var nm = $row.find("td:nth-child(2)").text();
          $("#name").val(nm);

          var abr = $row.find("td:nth-child(3)").text();
          $("#abbrv").val(abr);

          var typ = $row.find("td:nth-child(4)").text();
          $("#type").val(typ);

          var prj = $row.find("td:nth-child(5)").text();
          var prjid = prj.substring(0, prj.indexOf(" "));
          $("#project").val(prjid);

          

          $("#name").focus();
          
        });
      });
    </script>

<?php include './footer.php'?>