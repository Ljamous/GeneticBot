<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */
 include './header.php'?>

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
                <h3 class="card-title">Welcome</h3>
              </div>
              <!-- /.card-header -->
              <!-- form start -->
                <div class="card-body">
                <img src="images/start.jpg" style="height: 350px; width:100%" />
                <p>
                  Welcome to our pre-genetic counseling decision aid support and GeneticBot for breast cancer patients. We understand that navigating this journey can be challenging, and we are committed to providing you with the essential information and support you require.
                  <br/>
                  In the following sections, we offer a script and a video, each containing the same information. This allows you to choose the format that best suits your comfort—whether you prefer reading at your own pace or watching a video, we have options to accommodate your needs.
                  <br/>
                  This resource is designed to help you understand the genetic testing that has been recommended as part of your treatment journey. Please do not hesitate to contact us with any questions or concerns. Ensuring your comfort and understanding is our utmost priority. Let's take this journey together.
                </p>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                  <button type="button" class="btn btn-info float-right" style="background-color: #2D4B69;" id="btn_start">Start</button>
                  
                </div>
                <!-- /.card-footer -->
            </div>
            <!-- /.card -->
    
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  <script>
    $(btn_start).click(function(){
      
      // Save the change locally
      localStorage.setItem('link1Changed', 'true');
      
      // Move to the next page
      window.location = "Genetic_counseling_and_testing.php";
    });
  </script>

<?php include './footer.php'?>
