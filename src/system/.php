<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */
 include './header.php'; ?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- Optionally add header title or breadcrumbs here -->
        </div>
        <div class="col-sm-6">
          <!-- Right side section -->
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="mx-auto" style="width:70%; background-color: #2D4B69; padding: 3px; border-radius: 10px;">
      <h3 class="text-center" style="color:#fff">Genetic Testing Journey - DAS</h3>
    </div>
    <br/>
    <div class="mx-auto" style="width:70%">
      <h5>Please read the following text or watch the video, then click <b>Next</b> button</h5>
    </div>
    <br/>
    <div class="card card-info mx-auto" style="width:70%;">
      <div class="card-header p-0 pt-1 border-bottom-0" style="background-color: #2D4B69;">
        <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
          <li class="nav-item">
            <a class="nav-link active" id="custom-tabs-three-text-tab" data-toggle="pill" href="#custom-tabs-three-text" role="tab" aria-controls="custom-tabs-three-text" aria-selected="true">Text</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-tabs-three-video-tab" data-toggle="pill" href="#custom-tabs-three-video" role="tab" aria-controls="custom-tabs-three-video" aria-selected="false">Video</a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content" id="custom-tabs-three-tabContent">
          <!-- Text Tab Content -->
<div class="tab-pane fade show active" id="custom-tabs-three-text" role="tabpanel" aria-labelledby="custom-tabs-three-text-tab">
    <p>Welcome to our comprehensive genetic testing journey. Our Decision Aid System is designed to educate and empower you throughout the counseling process.</p>

    <p>We begin by gathering essential personal information, including your ID, MRN, name, date of birth, gender, and marital status. We will also explore your clinical history, focusing on the type of cancer and age of diagnosis. For breast cancer patients, additional information, such as the histology report, will be requested.</p>

    <p>Your family history will also be examined, covering multiple generations to ensure accuracy. If you meet the criteria for testing, you will move on to an educational session that provides a basic understanding of genetics, along with the pros and cons of undergoing genetic testing.</p>

    <p>The consent process will give you the opportunity to agree, schedule future steps, or decide to delay testing until you are ready. Our GeneticBot is available to answer frequently asked questions, ensuring you have the necessary information. If GeneticBot cannot provide answers, your queries will be directed to healthcare providers, and the information will help improve GeneticBot's knowledge base.</p>

    <p>This seamless process is designed to guide you every step of the way, helping you make informed and confident decisions regarding your genetic testing journey.</p>
    <br/><br/>
</div>


          <!-- Video Tab Content -->
          <div class="tab-pane fade" id="custom-tabs-three-video" role="tabpanel" aria-labelledby="custom-tabs-three-video-tab">
            <video width="100%" height="100%" controls>
              <source src="videos/Genetic Testing Journey_ Decision Aid System.mp4" type="video/mp4">
              Your browser does not support the video tag.
            </video>
          </div>
        </div>

        <!-- Instruction Before Buttons -->
        <div class="text-center mt-3">
          <b>Please Click 'Next' once you're done reading the text or watching the video.</b>
        </div>
        
        <!-- Button Section -->
        <div class="card-footer">
          <!-- Next Button -->
          <button type="button" class="btn btn-info float-right" style="background-color: #2D4B69;" id="btn_next_v2">Next</button>
          <!-- Back Button -->
          <button type="button" class="btn btn-secondary float-left" style="background-color: #6c757d;"  onclick="window.location.href='Genetic_counseling_and_testing.php'">Back</button>
        </div>
      </div>
      <!-- /.card -->
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
  $(document).ready(function() {
    $('#btn_next_v2').click(function() {
      // Save the change locally
      localStorage.setItem('link3Changed', 'true');
      
      // Move to the next page
      window.location = "personal_info.php";
    });
  });
</script>

<?php include './footer.php'; ?>
