<?php include './header.php' ?>

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
      <h3 class="text-center" style="color:#fff">Concept of genetics and mode of inheritance </h3>
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
            <a class="nav-link active" id="custom-tabs-three-text-tab" data-toggle="pill" href="#custom-tabs-three-text"
               role="tab" aria-controls="custom-tabs-three-text" aria-selected="true">Text</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" id="custom-tabs-three-video-tab" data-toggle="pill" href="#custom-tabs-three-video"
               role="tab" aria-controls="custom-tabs-three-video" aria-selected="false">Video</a>
          </li>
        </ul>
      </div>
      <div class="card-body">
        <div class="tab-content" id="custom-tabs-three-tabContent">
          <div class="tab-pane fade show active" id="custom-tabs-three-text" role="tabpanel"
               aria-labelledby="custom-tabs-three-text-tab">
            Welcome to our educational journey where we unlock the secrets of DNA and how traits are passed down through
            generations. Our genetic makeup is like a blueprint for building and maintaining our body. It's composed of
            DNA, a molecule that contains our genes. Genes are segments of DNA that carry instructions for our
            development, functioning, growth, and reproduction. These genes are packed into structures called
            chromosomes, of which humans have 23 pairs, totaling 46.
            <br/><br/>
            In autosomal dominant inheritance, only one copy of a mutated gene from one parent is needed to inherit a
            condition or trait. This means if a parent has the trait, there's a 50% chance it will be passed to each
            child, regardless of the child's sex. Autosomal recessive inheritance requires two copies of a mutated gene
            for the individual to exhibit the trait or condition— one from each parent. Parents who each carry one copy
            of the mutated gene are referred to as carriers. They typically do not show symptoms of the condition.
            However, there's a 25% chance their child will inherit both copies of the mutated gene and express the
            condition.
            <br/><br/>
            How can we detect mutations: In simple terms, sequencing is one of the cutting-edge techniques used to read the
            exact order of the DNA in our genes. Think of it as decoding the letters in a very long word, where each
            letter represents a small part of our genetic code. By reading these letters carefully, scientists can
            identify tiny differences or errors in the genetic code, known as mutations, which can sometimes lead to
            diseases or affect how we respond to certain medications.
            <br/>
            Genetics is a key to unlocking many mysteries of our health and traits. By understanding how traits and
            conditions are passed down, we can better manage our health, make informed decisions, and support those
            affected by genetic conditions.
          </div>
          <div class="tab-pane fade" id="custom-tabs-three-video" role="tabpanel"
               aria-labelledby="custom-tabs-three-video-tab">
            <video width="100%" height="100%" controls>
              <source src="videos/concept of genetics and mode of inheritance.mp4" type="video/mp4">
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
  $(btn_next_v1).click(function () {

    // Save the change locally
    localStorage.setItem('link7Changed', 'true');

    // Move to the next page
    window.location = "Multigene_panel_and_Outcome_results.php";
  });
</script>

<?php include './footer.php' ?>
