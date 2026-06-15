<?php include './header.php';
      require_once './db/companies.php'; ?>

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
                <h3 class="card-title">New Company</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" method="post" action="companies_view.php" enctype="multipart/form-data">
                <div class="card-body">
                <input type="hidden" name="comid" id="comid" value="0" />
                <div class="form-group row">
                    <label for="name" class="col-sm-2 col-form-label">Name</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="name" name="name" placeholder="Name" required>
                    </div>
                    <label for="webapiurl" class="col-sm-2 col-form-label">API URL</label>
                    <div class="col-sm-4">
                      <input type="text" class="form-control" id="webapiurl" name="webapiurl" placeholder="API URL" required>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="logo" class="col-sm-2 col-form-label">Logo</label>
                    <div class="col-sm-4">
                      <input type="file" class="form-control" id="logo" name="logo" >
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
                    <th>API URL</th>
                    <th>Logo</th>
                    <th>Admin</th>
                    <th>Edit</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                    $rs = showCompanies();
                    while($row = $rs->fetch_assoc()){
                      echo '<tr>';
                      echo '<td>'. $row["id"] .'</td>';
                      echo '<td>'. $row["name"] .'</td>';
                      echo '<td>'. $row["webapiurl"] .'</td>';
                      echo '<td>'. $row["logo"] .'<img src="logos/'. $row["logo"] .'" style="width:64px; height:64px" /></td>';
                      $result = checkCompanyAdmin($row["id"]);
                      if($result->num_rows == 0){
                        echo '<td><button class="btn btn-primary btn-sm" data-comid="'.$row["id"].'" id="btnadd">Add Admin</button></td>';
                      }
                      else{
                        $adminrow = $result->fetch_assoc();
                        echo '<td><button class="btn btn-primary btn-sm" data-comid="'.$row["id"].'" id="btnadd">'.$adminrow["name"].'</button></td>';
                      }
                      echo '<td><button class="btn btn-info btn-sm" id="edtbtn" >
                                <i class="fas fa-pencil-alt"></i>
                                Edit</button></td>';
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
        <form class="form-horizontal" method="post" action="companies_view.php">
                <input type="hidden" id="companyid" name="companyid" value="0" />
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
                    <th>Activation</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php 
                    $rs = getAdmins();
                    while($row = $rs->fetch_assoc()){
                      echo '<tr>';
                      echo '<td>'. $row["id"] .'</td>';
                      echo '<td>'. $row["username"] .'</td>';
                      echo '<td>'. $row["name"] .'</td>';
                      echo '<td><button class="btn btn-primary btn-sm" data-userid="'.$row["id"].'" id="selbtn" >
                                Select</button></td>';
                      if($row["active"] == 1)
                      echo '<td><button class="btn btn-danger btn-sm" data-useridact="'.$row["id"].'" id="actbtn" >
                                Disable</button></td>';
                      else
                      echo '<td><button class="btn btn-info btn-sm" data-useridact="'.$row["id"].'" id="actbtn" >
                                Enable</button></td>';
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
        if($_POST["comid"] == 0){  

          $comid = addCompany($_POST["name"],$_POST["webapiurl"], $_FILES["logo"]["name"]);
          move_uploaded_file($_FILES["logo"]["tmp_name"], "logos/" . $_FILES["logo"]["name"]);
  
          echo '<script>
                    alert("Company has been added");
                    window.location = "companies_view.php";
                </script>';
        }
        else{
          if(!file_exists($_FILES['logo']['tmp_name']) || !is_uploaded_file($_FILES['logo']['tmp_name'])) {
            editCompanyName($_POST["comid"], $_POST["name"],$_POST["webapiurl"]);
        }
        else{
          editCompany($_POST["comid"], $_POST["name"], $_POST["webapiurl"],$_FILES["logo"]["name"]);
          move_uploaded_file($_FILES["logo"]["tmp_name"], "logos/" . $_FILES["logo"]["name"]);
        }
          echo '<script>
                    alert("Company data have been updated");
                    window.location = "companies_view.php";
                </script>';
        }
      }
      if(isset($_POST["subadduser"])){
        $result = checkCompanyAdmin($_POST["companyid"]);
        if($result->num_rows == 0){
          $roleid = 2;
          $encpw = sha1($_POST["password"]);
          $userid = addUser($_POST["username"], $encpw, $_POST["name"], $roleid);
          $groupid = 2;
          addUserToGroup($userid, $_POST["companyid"], $groupid);
        }
        else{
          $roleid = 2;
          $encpw = sha1($_POST["password"]);
          $userid = addUser($_POST["username"], $encpw, $_POST["name"], $roleid);
          $groupid = 2;
          editUserToGroup($userid, $_POST["companyid"], $groupid);
        }
        echo '<script>window.location="companies_view.php";</script>';
        
      }
    ?>

    <script>
      $(document).ready(function(){
        var comid;
        $(document).on("click", "#edtbtn", function(){
          var $row = $(this).closest("tr");

          var id = $row.find("td:nth-child(1)").text();
          $("#comid").val(id);

          var nm = $row.find("td:nth-child(2)").text();
          $("#name").val(nm);

          var apiurl = $row.find("td:nth-child(3)").text();
          $("#webapiurl").val(apiurl);

          $("#name").focus();
          
        });

        $(document).on("click", "#btnadd", function(){
          comid = $(this).attr("data-comid");
          $("#companyid").val(comid);
          $("#myModal").modal("show");
        });

        $(document).on("click", "#selbtn", function(){
          var userid = $(this).attr("data-userid");
          var groupid = 2;

          $.ajax({
            url:"db/change_user_group.php",    
            type: "post",
            data: {userid: userid, parentid: comid, groupid: groupid},
            success:function(data){
                window.location = "companies_view.php";
            },
            error:function(xhr, err){
                alert(xhr.responseText);
            }
          });

        });

        $(document).on("click", "#actbtn", function(){
          var userid = $(this).attr("data-useridact");

          $.ajax({
            url:"db/activate_user.php",    
            type: "post",
            data: {userid: userid},
            success:function(data){
                alert(data);
                window.location = "companies_view.php";
            },
            error:function(xhr, err){
                alert(xhr.responseText);
            }
          });

        });


        $('#myModal').on('shown.bs.modal', function () {
            $("#mb").html(comid);
        });

        $("#genbtn").click(function(){
          $("#password").val(generateUUID());
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