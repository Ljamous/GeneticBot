<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PTS</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="./plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="#" class="h1"><b>PTS</b><br/>Project Tracking System</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Enter new password</p>

      <form action="change_password_ft.php" method="post">
        <div class="input-group mb-3">
          <input name="newpw" type="password" class="form-control" placeholder="New Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input name="conpw" type="password" class="form-control" placeholder="Confirm Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <p id="loginerror" style="color:red; visibility:hidden"><b>Not Match</b></p>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button name="sub" type="submit" class="btn btn-primary btn-block">Change</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      

      
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<?php 
  require_once './db/users.php';

  if(isset($_POST["sub"])){
    if($_POST["newpw"] == $_POST["conpw"])
    {
      changePasswordFT($_SESSION["userid"], $_POST["newpw"]);
      echo '<script>window.location="home.php";</script>'; 
    }
    else{
      echo '<script>document.getElementById("loginerror").style.visibility = "visible";</script>';
    }
  }
?>

<!-- jQuery -->
<script src="./plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./dist/js/adminlte.min.js"></script>
</body>
</html>
