<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */
 session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Pre-genetic Counseling Agent</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="./plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="./plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="./dist/css/adminlte.min.css">

  <!-- Custom Styles -->
  <style>
    body {
      background: linear-gradient(to right, #2D4B69, #616065);
      font-family: 'Source Sans Pro', sans-serif;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
      margin: 0;
    }
    .login-box {
      width: 400px;
      padding: 30px;
      background: white;
      border-radius: 15px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    .card-header {
      background-color: transparent;
      border-bottom: 0;
    }
    .login-box-msg {
      font-size: 18px;
      margin-bottom: 20px;
      text-align: center;
      color: #2D4B69;
    }
    .input-group .form-control {
      border-radius: 25px;
      padding: 12px 20px;
      font-size: 16px;
      box-shadow: 0 1px 5px rgba(0, 0, 0, 0.1);
      border: 1px solid #ddd;
    }
    .input-group-append .input-group-text {
      border-radius: 25px;
      background-color: #2D4B69;
      color: white;
    }
    .btn-primary {
      width: 100%;
      padding: 12px;
      border-radius: 25px;
      background-color: #2D4B69;
      border: none;
      font-size: 16px;
    }
    .btn-primary:hover {
      background-color: #1a3c54;
    }
    #loginerror {
      font-size: 14px;
      text-align: center;
      margin-top: 10px; /* Reduce margin for better proximity to the button */
      color: red;
      font-weight: bold;
      display: none; /* Initially hidden using display: none; */
    }
    .login-box a {
      color: #2D4B69;
      text-decoration: none;
    }
    .login-box a:hover {
      color: #1a3c54;
    }
    .logo-container img {
      width: 150px;
      height: auto;
    }
  </style>
</head>
<body>

<div class="login-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center logo-container">
      <a href="#">
        <img src="images/logo.png" alt="Logo" />
        <br/>
        <b style="font-size: 24px; color: #2D4B69;">Pre-genetic Counseling<span style="color: #616065;"> Agent</span></b>
      </a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Sign in to start your session</p>

      <form action="index.php" method="post">
        <div class="input-group mb-3">
          <input name="em" type="text" class="form-control" placeholder="E-Mail" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>

        <div class="input-group mb-3">
          <input name="pw" type="password" class="form-control" placeholder="Password" required>
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-8">
            <a href="signup.php" class="login-box-msg" style="font-size: 14px;">New User? <br/> <b>Click here to Sign Up</b></a>
          </div>
          <div class="col-4">
            <button name="sub" type="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
        </div>
        <p id="loginerror"></p>
      </form>
    </div>
  </div>
</div>

<?php
require_once './db/users.php';

if(isset($_POST["sub"])){
  $em = $_POST["em"];
  $pw = sha1($_POST["pw"]);
  $rs = login($em, $pw);

  if ($rs !== false && is_object($rs)) // Check if $rs is a valid result object
  {
    if($rs->num_rows > 0)
    {
      $row = $rs->fetch_assoc();
      $_SESSION["userid"] = $row["id"];
      $_SESSION["name"] = $row["name"];
      echo '<script>window.location="home.php";</script>';
    }
    else {
      echo '<script>
                document.getElementById("loginerror").style.display = "block";
                document.getElementById("loginerror").innerHTML = "<b>Login Failed: Invalid Credentials</b>";
              </script>';
    }
  }
  else { // Handle the case where $rs is false (error occurred)
    echo '<script>
              document.getElementById("loginerror").style.display = "block";
              document.getElementById("loginerror").innerHTML = "<b>Login Failed: An error occurred. Please try again later.</b>";
            </script>';
  }
}
?>

<!-- jQuery -->
<script src="./plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="./plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="./dist/js/adminlte.min.js"></script>
<script>
  $(document).ready(function(){
    localStorage.clear();
  });
</script>
</body>
</html>
