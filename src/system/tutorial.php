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
      <h3 class="text-center" style="color:#fff">Tutorial - Genetic Testing Journey</h3>
    </div>
    <br/>
    <div class="mx-auto" style="width:95%">
      <h5>Please watch the tutorial videos below, then click the <b>Next</b> button</h5>
    </div>
    <br/>
    <div class="card card-info mx-auto" style="width:95%;">
      <div class="card-header p-0 pt-1 border-bottom-0" style="background-color: #2D4B69;">
  <ul class="nav nav-tabs" id="custom-tabs-three-tab" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" id="video1-tab" data-toggle="tab" href="#video1" role="tab" aria-controls="video1" aria-selected="true">
        Tutorial 1
      </a>
    </li>
    <li class="nav-item">
      <a class="nav-link" id="video2-tab" data-toggle="tab" href="#video2" role="tab" aria-controls="video2" aria-selected="false">
        Tutorial 2
      </a>
    </li>
  </ul>
</div>
<div class="card-body">
  <div class="tab-content" id="custom-tabs-three-tabContent">
    <!-- Video1 -->
    <div class="tab-pane fade show active" id="video1" role="tabpanel" aria-labelledby="video1-tab">
      <div style="max-width: 900px; margin: 0 auto; padding: 10px 0;">
        <div style="position: relative; width: 100%; aspect-ratio: 16/9; background: #000; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
          <video controls preload="metadata" playsinline style="width: 100%; height: 100%; object-fit: contain; display: block;">
            <source src="videos/tutorial.mp4" type="video/mp4">
            Your browser does not support the video tag.
          </video>
        </div>
      </div>
    </div>

    <!-- Video2 -->
    <div class="tab-pane fade" id="video2" role="tabpanel" aria-labelledby="video2-tab">
      <div style="max-width: 900px; margin: 0 auto; padding: 10px 0;">
        <div style="position: relative; width: 100%; aspect-ratio: 16/9; background: #000; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.2);">
          <video controls preload="metadata" playsinline style="width: 100%; height: 100%; object-fit: contain; display: block;">
            <source src="videos/tutorial2.mp4" type="video/mp4">
            Your browser does not support the video tag.
          </video>
        </div>
      </div>
    </div>
  </div>

  <!-- Instruction Before Buttons -->
  <div class="text-center mt-3">
    <b>Please Click 'Next' once you've completed watching the tutorial videos.</b>
  </div>
</div>

<!-- Button Section -->
<div class="card-footer">
  <button type="button" class="btn btn-info float-right" style="background-color: #2D4B69;" id="btn_next_v2">Next</button>
</div>
       
      <!-- /.card -->
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<script>
  $(document).ready(function() {
    $('a[data-toggle="tab"]').on('hide.bs.tab', function () {
      $('video').each(function () { this.pause(); });
    });

    $('#btn_next_v2').click(function() {
      // Save the change locally
      localStorage.setItem('tutorialWatched', 'true');
      
      // Move to the next page
      window.location = "family_pedigree.php";
    });
  });
</script>

<?php include './footer.php'; ?>