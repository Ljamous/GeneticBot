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
    <div class="mx-auto" style="width:95%; background-color: #2D4B69; padding: 3px; border-radius: 10px;">
      <h3 class="text-center" style="color:#fff">Genetic Counseling and Testing</h3>
    </div>
    <br/>
    <div class="mx-auto" style="width:95%">
      <h5>Please read the following text or watch the video, then click <b>Next</b> button</h5>
    </div>
    <br/>
    <div class="card card-info mx-auto" style="width:95%;">
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
            <p>Welcome to our genetic counseling and testing journey, a transformative experience designed to help you uncover the mysteries of your genetic makeup. Genetic counseling serves as a beacon of guidance, providing expert support for individuals and families navigating the complex landscape of genetic information. This journey is an empowering exploration into understanding genetic risks, making informed decisions, and arming individuals with the knowledge needed to take charge of their health.</p>

            <p>Genetic testing is a key part of this journey, diving deep into the very essence of our DNA to identify changes that hold the answers to our unique genetic codes. Together with genetic counseling, it empowers individuals to make choices based on their genetic profiles—whether that means informed health decisions, preventive measures, or personalized treatments that cater to specific needs.</p>

            <p>The genetic counseling journey unfolds in three main steps:</p>
            <ol>
              <li><strong>Initial Consultation:</strong> Individuals explore their medical and family history, gain an understanding of genetics, learn about eligibility criteria, and assess their risk for genetic conditions.</li>
              <li><strong>Genetic Testing:</strong> Those who proceed with testing are briefed on the procedures, potential outcomes, and the consent process involved.</li>
              <li><strong>Post-Test Analysis:</strong> After the test, individuals discuss the results with experts, decode their meaning, and devise personalized plans based on the insights gained, with the option for follow-up sessions as needed.</li>
            </ol>

            <p>These steps are supported by a decision-aid system, ensuring that individuals have all the necessary information to make informed decisions about genetic testing. The process is expertly guided by healthcare professionals, ensuring a seamless and supportive experience.</p>

            <p>Join us on this enlightening journey, where genetic counseling and testing come together to illuminate the path toward better health and well-being. Let’s embark on this voyage of discovery, where knowledge empowers you, and your genetic makeup unlocks the potential for healthier, more informed choices.</p>
          </div>

          <!-- Video Tab Content -->
          <div class="tab-pane fade" id="custom-tabs-three-video" role="tabpanel" aria-labelledby="custom-tabs-three-video-tab">
            <video width="100%" height="100%" controls>
              <source src="videos/Genetic_Counseling_and_Testing.mp4" type="video/mp4">
              Your browser does not support the video tag.
            </video>
          </div>
        </div>


        <div class="text-center mt-3">
          <b>Please Click 'Next' once you're done reading the text or watching the video.</b>
        </div>
        

        <div class="card-footer">
          <button type="button" class="btn btn-info float-right" style="background-color: #2D4B69;" id="btn_next_v1">Next</button>
        </div>
      </div>
      <!-- /.card -->
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
  // Ensure jQuery is loaded before using it
  $(document).ready(function() {
    $('#btn_next_v1').click(function() {
      // Save the change locally to indicate that the user has completed this step
      localStorage.setItem('link2Changed', 'true');
      
      // Move to the next page (you can change the URL accordingly)
      window.location = "Genetic_Testing_Journey.php";
    });
  });
</script>

<?php include './footer.php'; ?>
