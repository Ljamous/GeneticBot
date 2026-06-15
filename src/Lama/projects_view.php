<?php include './header.php';
      require_once './db/projects.php'; ?>

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
                <h3 class="card-title">New Project</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" method="post" action="projects_view.php">
                <div class="card-body">
                
                <input type="hidden" name="prjid" id="prjid" value="0" />
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
                    <label for="scope" class="col-sm-2 col-form-label">Scope</label>
                      <div class="col-sm-4">
                        <select class="form-control" id="scope" name="scope" required>
                            <option value="Lighting">Lighting</option>
                            <option value="Control System">Control System</option>
                        </select>
                      </div>
                      <label for="location" class="col-sm-2 col-form-label">Location</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="location" name="location" placeholder="Location" required>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label for="first_con_name" class="col-sm-2 col-form-label">Contact Name 1</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="first_con_name" name="first_con_name" placeholder="Contact Name 1" required>
                      </div>
                      <label for="first_con_number" class="col-sm-2 col-form-label">Contact Number 1</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="first_con_number" name="first_con_number" placeholder="Contact Number 1" required>
                      </div>
                  </div>
                  <div class="form-group row">
                    <label for="second_con_name" class="col-sm-2 col-form-label">Contact Name 2</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="second_con_name" name="second_con_name" placeholder="Contact Name 2" required>
                      </div>
                      <label for="second_con_number" class="col-sm-2 col-form-label">Contact Number 2</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="second_con_number" name="second_con_number" placeholder="Contact Number 2" required>
                      </div>
                  </div>

                  <div class="form-group row">
                  <label for="project" class="col-sm-2 col-form-label">Customer</label>
                      <div class="col-sm-4">
                        <select type="text" class="form-control" id="customer" name="customer" required>
                          <?php 
                            $rs = showCustomers();
                            while($row = $rs->fetch_assoc())
                            {
                              echo '<option value="'.$row["id"].'">' .$row["name"]. '</option>';
                            }
                          ?>
                        </select>
                      </div>
                      <label for="second_con_number" class="col-sm-2 col-form-label">Type</label>
                      <div class="col-sm-4">
                        <input type="text" class="form-control" id="prjtype" name="prjtype" placeholder="Project Type" required>
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
                <table id="example1" class="table table-bordered table-striped" style="cursor:pointer">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Abbreviation</th>
                    <th>Type</th>
                    <th>Scope</th>
                    <th>Location</th>
                    <th>Contact Name 1</th>
                    <th>Contact Number 1</th>
                    <th>Contact Name 2</th>
                    <th>Contact Number 2</th>
                    <th>Customer</th>
                    <th>Designer</th>
                    <th>Finance</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                    $rs = showProjects($_SESSION["roleid"]);
                    while($row = $rs->fetch_assoc()){
                      echo '<tr>';
                      echo '<td>'. $row["id"] .'</td>';
                      echo '<td>'. $row["name"] .'</td>';
                      echo '<td>'. $row["abbrv"] .'</td>';
                      echo '<td>'. $row["prjtype"] .'</td>';
                      echo '<td>'. $row["scope"] .'</td>';
                      echo '<td>'. $row["location"] .'</td>';
                      echo '<td>'. $row["first_con_name"] .'</td>';
                      echo '<td>'. $row["first_con_number"] .'</td>';
                      echo '<td>'. $row["second_con_name"] .'</td>';
                      echo '<td>'. $row["second_con_number"] .'</td>';
                      echo '<td>'. $row["cid"] . ' - ' . $row["cname"] .'</td>';
                      echo '<td id="designer" data-projid="'.$row["id"].'">' . getName($row["designerid"]) .'</td>';
                      echo '<td id="accountant" data-projid="'.$row["id"].'">' . getName($row["accountantid"]) .'</td>';
                      echo '</tr>';
                    }
                  ?>
                </tbody>
                </table>

                 <!-- The Modal -->
   <div class="modal" id="myModal">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
      
        <!-- Modal Header -->
        <div class="modal-header">
          <h4 class="modal-title">Select Admin</h4>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        
        <!-- Modal body -->
        <div class="modal-body">
          <form class="form-horizontal" method="post" action="projects_view.php">
                <input type="hidden" value="0" id="projectid" name="projectid" />
                <input type="hidden" name="doa" id="doa" value="0" />
                <div class="card-body">
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                    </div>
                    <label for="username" class="col-sm-2 col-form-label">User Name</label>
                    <div class="col-sm-4">
                    <input type="text" class="form-control" id="username" name="username" placeholder="User Name" required>
                    </div>
                  </div>
                  <div class="form-group row">
                      <label for="password" class="col-sm-2 col-form-label">Password</label>
                      <div class="col-sm-6">
                        <input type="text" class="form-control" id="password" name="password" placeholder="Password" required>
                        <br/>
                        <h6><i>The password will be generated randomly</i></h6>
                      </div>
                      <div class="col-sm-3">
                        <button type="button" class="btn btn-primary btn-sm" id="genbtn">Generate Password</button>
                      </div>
                  </div>
                  
                  <div class="col-sm-12">
                    <hr/>
                  </div>
                  
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info" name="subadduser">Add User</button>
                </div>
                <!-- /.card-footer -->
              </form>
        <table id="example2" class="table table-bordered table-striped">
                  <thead>
                  <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Name</th>
                    <th>Select</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                    $rs = getUsers();
                    while($row = $rs->fetch_assoc()){
                      echo '<tr>';
                      echo '<td>'. $row["id"] .'</td>';
                      echo '<td>'. $row["username"] .'</td>';
                      echo '<td>'. $row["name"] .'</td>';
                      echo '<td><button class="btn btn-primary btn-sm" data-userid="'.$row["id"].'" id="selbtn" >
                                Select</button></td>';
                      echo '</tr>';
                    }
                  ?>
                </tbody>
                </table>
        </div>
        
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
        </div>
        
      </div>
    </div>
  </div>
  <!-- Edn Modal -->
              </div>
              <!-- /.card-body -->
            </div>
    </div>
    <!-- end table -->
    <!-- /.content -->
    <?php
      if(isset($_POST["subbtn"])){
        if($_POST["prjid"] == 0){
          
          $companyid = getParentId($_SESSION["userid"]);  


          addProject($_POST["name"], $_POST["abbrv"], $_POST["prjtype"], $_POST["scope"], 
          $_POST["location"],$_POST["first_con_name"], $_POST["first_con_number"], 
          $_POST["second_con_name"], $_POST["second_con_number"], $_POST["customer"], $companyid); 
  
          echo '<script>
                    alert("Project has been added");
                    window.location = "projects_view.php";
                </script>';
        }
        else{
          editProject($_POST["prjid"], $_POST["name"], $_POST["abbrv"], $_POST["prjtype"], $_POST["scope"], 
          $_POST["location"],$_POST["first_con_name"], $_POST["first_con_number"], 
          $_POST["second_con_name"], $_POST["second_con_number"]);

          echo '<script>
                    alert("Project data have been updated");
                    window.location = "projects_view.php";
                </script>';
        }
      }

      if(isset($_POST["subadduser"])){

        if($_POST["doa"] == 0)
          $roleid = 3;
        else
          $roleid = 4;

          $encpw = sha1($_POST["password"]);
          $userid = addUser($_POST["username"], $encpw, $_POST["name"], $roleid);
          addMember($_POST["projectid"], $userid, $_POST["doa"]);

          

        echo '<script>window.location="projects_view.php";</script>';
        
      }
    ?>

    <script>
      $(document).ready(function(){
       
          var projid = 0;

          $('#example1 tbody').on('click', 'tr', function () {
          var $row = $(this).closest("tr");

          var id = $row.find("td:nth-child(1)").text();
          $("#prjid").val(id);

          var nm = $row.find("td:nth-child(2)").text();
          $("#name").val(nm);

          var abr = $row.find("td:nth-child(3)").text();
          $("#abbrv").val(abr);

          var prjtype = $row.find("td:nth-child(4)").text();
          $("#prjtype").val(prjtype);

          var scp = $row.find("td:nth-child(5)").text();
          $("#scope").val(scp);

          var loc = $row.find("td:nth-child(6)").text();
          $("#location").val(loc);

          var fcnm = $row.find("td:nth-child(7)").text();
          $("#first_con_name").val(fcnm);

          var fcnb = $row.find("td:nth-child(8)").text();
          $("#first_con_number").val(fcnb);

          var scnm = $row.find("td:nth-child(9)").text();
          $("#second_con_name").val(scnm);

          var scnb = $row.find("td:nth-child(10)").text();
          $("#second_con_number").val(scnb);

          var csr = $row.find("td:nth-child(11)").text();
          var csrid = csr.substring(0, csr.indexOf(" "));
          $("#customer").val(csrid);

          $("#name").focus();
          
        });

        $(document).on("click", "#designer", function(){
          projid = $(this).attr("data-projid");
          showUsersModal(0);
        });

        $(document).on("click", "#accountant", function(){
          projid = $(this).attr("data-projid");
          showUsersModal(1);
        });

        function showUsersModal(doa_value){
          
          $("#doa").val(doa_value);
          $("#projectid").val(projid);
          $("#myModal").modal("show");
        }

        $("#genbtn").click(function(){
          $("#password").val(generateUUID());
        });

        $(document).on("click", "#selbtn", function(){
          var userid = $(this).attr("data-userid");

          $.ajax({
            url:"db/add_project_member.php",    
            type: "post",
            data: {member: userid, type: $("#doa").val(), id: projid},
            success:function(data){
                window.location = "projects_view.php";
            },
            error:function(xhr, err){
                alert(xhr.responseText);
            }
          });

        });
      });


      function generateUUID() { 
    var d = new Date().getTime();
    var d2 = ((typeof performance !== 'undefined') && performance.now && (performance.now()*1000)) || 0;//Time in microseconds since page-load or 0 if unsupported
    return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace(/[xy]/g, function(c) {
        var r = Math.random() * 16;//random number between 0 and 16
        if(d > 0){//Use timestamp until depleted
            r = (d + r)%16 | 0;
            d = Math.floor(d/16);
        } else {//Use microseconds since page-load if supported
            r = (d2 + r)%16 | 0;
            d2 = Math.floor(d2/16);
        }
        return (c === 'x' ? r : (r & 0x3 | 0x8)).toString(16);
    });
}
    </script>

<?php include './footer.php'?>