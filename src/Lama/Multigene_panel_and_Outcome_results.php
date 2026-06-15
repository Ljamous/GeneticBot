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
            <div class="mx-auto" style="width:95%;background-color: #2D4B69;padding: 3px; border-radius: 10px;">
            <h3 class="text-center" style="color:#fff">Multigene panel and Outcome results</h3>
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
                  As you embark on your genetic testing journey, there are various options available, including targeted testing for a single gene, a selection of genes related to breast cancer risk, or a more extensive panel that screens for a variety of cancer risks.  In this particular stage, your testing will encompass a multigene panel, focusing on genes with a high likelihood of increasing breast cancer susceptibility. The specific genes included in this testing are BRCA1, BRCA2, CDH1, PALB2, PTEN, and TP53.
                    <br/>
                    Potential outcomes of the genetic testing can include positive, negative, uncertain, and incidental findings:
                    <br/>
                    -	Positive – The test has identified a mutation correlated with a heightened risk of hereditary cancer. This knowledge could enable you and your healthcare provider to make informed decisions regarding your medical care, including enhanced screening protocols, risk-lowering surgeries, and preventive pharmacological measures.
                    <br/>
                    -	Negative – No mutations have been detected in the genes analyzed during your test. This signifies that:
                    <ul>
                        <li>As the initial family member is undergoing testing, your baseline risk for cancer parallels that of the average person. However, there remains a possibility of an undetected hereditary cancer risk, which this test may not capture, whether within the tested gene(s) or another gene associated with hereditary cancer.</li>
                        <li>If your results are negative for a familial mutation, your genetic risk may be considered equivalent to the general population.</li>
                    </ul>
                    -	Uncertain – A genetic variation was found; however, its association with cancer risk is currently unknown. Your cancer risk remains at least on par with the general populace, and there could be an above-average risk due to this genetic variation or another undetected predisposition related to the tested gene(s) or a different gene involved in hereditary cancer.
                    <br/>
                    -	Incidental Findings: Additional findings not specifically related to the assessed cancer risk may emerge during the analysis. These incidental findings could have health implications and require further investigation or medical follow-up.
                    <br/>
                </div>
                  <div class="tab-pane fade" id="custom-tabs-three-video" role="tabpanel" aria-labelledby="custom-tabs-three-video-tab">
                  <video width="100%" height="100%" controls>
                    <source src="videos/Multigene panel and  Outcome results.mp4" type="video/mp4">
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
                          onclick="window.history.back()">Previous
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
      localStorage.setItem('link8Changed', 'true');

      // Move to the next page
      window.location = "Benefits_Risks_and_Limitations_of_the_genetic_testing.php";
    });
  </script>

<?php include './footer.php'?>
