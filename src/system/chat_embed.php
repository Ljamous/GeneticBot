<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */
 include './header.php' ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <!-- Optional header content -->
                </div>
                <div class="col-sm-6">
                    <!-- Optional right section content -->
                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <!-- Card with seamless design and modern look -->
        <div class="card mx-auto"
            style="width:95%; max-width:1600px; border-radius:20px; overflow:hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);">
            <div class="card-header text-center"
                style="background-color: #2D4B69; color: white; border-radius:20px 20px 0 0;">
                <h3 class="card-title">GeneticBot</h3>
            </div>
            <!-- /.card-header -->

            <div class="card-body p-0" style="height: 100vh;">
                <!-- Embed the chat application in an iframe -->
                <div class="iframe-container" style="position:relative; width: 100%; height: 100%;">
                    <iframe src="http://localhost:8501/" title="Chat Application" width="100%" height="100%"
                        style="position:absolute; top:0; left:0; border:none; border-radius: 15px; overflow:hidden;"
                        scrolling="no" onload="this.style.transform = 'scale(1)';"></iframe>
                </div>
            </div>
            <!-- /.card-body -->

            <!-- Card footer with a sleek button -->
            <div class="card-footer" style="background-color: #fff; border-radius: 0 0 20px 20px; padding: 20px 0;">
                <!-- <button type="button" class="btn btn-info float-right" style="background-color: #2D4B69; border-radius: 5px; padding: 10px 20px; font-weight: bold;" id="btn_start">
          Start Counseling
        </button> -->
            </div>
            <!-- /.card-footer -->
        </div>
        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<?php include './footer.php' ?>

<script>
    $(document).ready(function() {
        // Button click functionality to store localStorage and redirect
        $('#btn_start').click(function() {
            localStorage.setItem('link1Changed', 'true');
            window.location = "Genetic_counseling_and_testing.php";
        });
    });
</script>


<style>
    .st-emotion-cache-h4xjwg.ezrtsby2 {
        display: none !important;
    }
</style>