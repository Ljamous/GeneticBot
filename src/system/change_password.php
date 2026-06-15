<?php include './header.php'?>

     <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            
          </div>
          <div class="col-sm-6">

          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
    <div class="card card-info mx-auto" style="width:70%;">
              <div class="card-header" style="background-color: #2D4B69;">
                <h3 class="card-title">Change Password</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" method="post" action="change_password.php">
                <div class="card-body">
                <div class="form-group row">
                    <label for="oldpw" class="col-sm-4 col-form-label">Old Password</label>
                    <div class="col-sm-8">
                      <input type="password" class="form-control" id="oldpw" name="oldpw" placeholder="Old Password" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="newpw" class="col-sm-4 col-form-label">New Password</label>
                    <div class="col-sm-8">
                      <input type="password" class="form-control" id="newpw" name="newpw" placeholder="New Password" required>
                    </div>
                  </div>
                  <div class="form-group row">
                    <label for="conpw" class="col-sm-4 col-form-label">Confirm Password</label>
                    <div class="col-sm-8">
                      <input type="password" class="form-control" id="conpw" name="conpw" placeholder="Confirm Password" required>
                    </div>
                  </div>

                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="submit" class="btn btn-info" style="background-color: #2D4B69;" name="subbtn">Change</button>
                  <a href="./home.php" class="btn btn-default float-right">Cancel</a>
                </div>
                <!-- /.card-footer -->
              </form>
            </div>
            <!-- /.card -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <?php
      if(isset($_POST["subbtn"])){
        if($_POST["newpw"] == $_POST["conpw"]){
          if(changePassword($_SESSION["userid"], $_POST["oldpw"], $_POST["newpw"])==True)
            echo '<script>alert("Password has been changed");</script>';
          else
            echo '<script>alert("Your old password is incorrect");</script>';
        }
        
      }
    ?>

<?php include './footer.php'?>


