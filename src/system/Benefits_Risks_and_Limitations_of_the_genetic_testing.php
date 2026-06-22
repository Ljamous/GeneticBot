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
            <div class="mx-auto" style="width:95%;background-color: #2D4B69;padding: 3px; border-radius: 10px;">
            <h3 class="text-center" style="color:#fff">Benefits, Risks, and Limitations of the genetic testing </h3>
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
                  <div class="tab-pane fade show active" id="custom-tabs-three-text" role="tabpanel" aria-labelledby="custom-tabs-three-text-tab">
                  <b>-	Benefits of the genetic testing: </b> Genetic test results help you and your doctor make more informed choices about your health care, such as screening, risk-reducing surgeries, and preventive medication strategies.  Identifying gene mutation(s) in a family enables other blood relatives to determine whether or not they share the same hereditary cancer risks.
                  <br/><br/>
                  If you are positive, you should discuss with your Physician / Counselor how hereditary cancer is inherited and learn about the chance your children and blood relatives may have inherited the same mutation(s) in the gene(s) tested.
                  <br/><br/>
                  Understanding your risk for hereditary conditions empowers you to make informed decisions about preventive measures and personalized healthcare. This newfound awareness provides psychological relief, easing anxieties or presenting early detection opportunities for proactive health management. Genetic testing enhances your understanding of your genetic makeup, offering profound insights into potential health outcomes. It supports healthcare professionals in tailoring treatment plans to your unique genetic profile.
                  <br/><br/>
                  Suppose you test negative for a known mutation in your family. In that case, you cannot pass on that mutation to your children, and you may be considered to have the same genetic risks for cancer as others in the general population.
                  <br/><br/>
                  <b>-	Risks of the genetic testing: </b> Genetic testing requires DNA, most often provided from a sample of blood from a saliva sample or a tumor sample. Side effects of having blood drawn are uncommon but may include dizziness, fainting, soreness, bleeding, bruising, and rarely, infection.
                  <br/>
                  You may experience heightened anxiety or emotional distress, particularly if results indicate an increased risk. Challenges may arise due to the complexity of genetic information, emphasizing the importance of clear communication between healthcare providers and individuals. Ethical considerations around privacy, genetic discrimination, and familial dynamics may come into play.
                  <br/>
                  Genetic testing unveils predispositions that can influence lifestyle choices and preventive measures, contributing to a holistic approach to health and well-being. It's crucial to carefully weigh these facets and engage in genetic counseling to make choices aligned with your values and preferences.
                  <br/><br/>
                  <b>-	Limitations of the genetic testing: </b> This test analyzes only certain important gene(s) associated with specific hereditary cancer risks. Genetic testing clarifies cancer risks for only those cancers related to the genes analyzed. If you are found to be a carrier of a gene that predisposes you to cancer, there may be differing opinions among physicians about the best steps to take. You best determine your medical care in consultation with your Physician / Counselor. Analysis for a specific genetic variant of uncertain significance may be considered investigational and may not provide additional cancer risk information to blood relatives.
                    <br/>
                </div>
                  <div class="tab-pane fade" id="custom-tabs-three-video" role="tabpanel" aria-labelledby="custom-tabs-three-video-tab">
                  <video width="100%" height="100%" controls>
                    <source src="videos/Benefits, Risks, and Limitations of the genetic testing.mp4" type="video/mp4">
                    Your browser does not support the video tag.
                  </video>
                  </div>
                </div>
                <div class="card-footer">
                  <div class="text-center mt-3">
                    <b>Please Click 'Next' once you're done reading the text or watching the video.</b>
                  </div>
                  <!-- Back Button -->
                  <button type="button" class="btn btn-secondary float-left" style="background-color: #6c757d;"
                          onclick="window.location.href='Multigene_panel_and_Outcome_results.php'">Previous
                  </button>

                  <!-- Next Button -->
                  <button type="button" class="btn btn-info float-right" id="btn_next_v1">Next</button>
                </div>

              </div>
              <!-- /.card -->


    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->

  <script>
    $(btn_next_v1).click(function(){

      // Save the change locally
      localStorage.setItem('link9Changed', 'true');

      // Move to the next page
      window.location = "consent_form.php";
    });
  </script>

<?php include './footer.php'?>
