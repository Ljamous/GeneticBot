<?php include './header.php';
require_once 'db/agents.php';?>

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

    <section class="content">
        <div class="text-center">
            <img src="dist/img/robot.png" class="img-circle" style="width:20%"/>
            <br/><br/>
            <h3 style="color: #2A2F77;"> You agent has been added successfully</h3>
           
        </div>
    </section>

</div>
<?php
    if(isset($_GET["vp"])){
        echo '
            <script>
                setTimeout(function() {
                    window.location = "home.php"; 
                }, 2000);
            </script>
        ';
    }
    else{
        $agentid = get_last_id($_SESSION["userid"]); 
        echo '
            <script>
                setTimeout(function() {
                    window.location = "aia_agent_training.php?agentid='.$agentid.'"; 
                }, 2000);
            </script>
        ';
    }
?>


<?php include './footer.php'?>