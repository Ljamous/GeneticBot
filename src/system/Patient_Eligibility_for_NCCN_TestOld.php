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
                <h3 class="card-title">Evaluating Patient Eligibility for NCCN Testing Criteria</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
              <form class="form-horizontal" method="post" action="personal_info.php">
                <div class="card-body">
                 <p>Click the "Satrt" button below to evaluate the patient eligibility for NCCN test</p>
                 <p>The process will take around 45 seconds</p>
                </div>    
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="button" class="btn btn-info" style="background-color: #2D4B69;" id="btn_start_eval" name="btn_start_eval">Start</button>
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
  <script>
    $(btn_start_eval).click(function(){
      
      // Save the change locally
      localStorage.setItem('link6Changed', 'true');
      
      // Move to the next page
      window.location = "Concept_of_genetics_and_mode_of_inheritance.php";
    });
  </script>

<?php include './footer.php'?>
