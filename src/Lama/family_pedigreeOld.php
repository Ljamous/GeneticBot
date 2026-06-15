<?php
include './header.php';

if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
// Ensure the user is logged in
if (!isset($_SESSION["userid"])) {
  // Redirect to the login page if not logged in
  header("Location: index.php");
  exit();  // Ensure no further execution after the redirect
}

$userId = isset($_SESSION['userid']) ? $_SESSION['userid'] : null; // Handle missing user ID appropriately
include './db/mycon.php';
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6">
          <!-- Optional breadcrumbs or content can be added here -->
        </div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="card card-info mx-auto" style="width: 70%; border-radius: 10px;">
      <div class="card-header" style="background-color: #2D4B69;">
        <h3 class="card-title">Pedigree Options</h3>
      </div>
      <div class="card-body">
        <!-- Option selection -->
        <div class="form-group row">
          <label class="col-sm-4 col-form-label">Choose how to create the pedigree</label>
          <div class="col-sm-8">
            <select class="form-control" id="pedigreeOption" name="pedigreeOption">
              <option value="">Select an option</option>
              <option value="upload">Upload a pedigree</option>
              <option value="manual">Construct manually</option>
            </select>
          </div>
        </div>

        <!-- Upload file input (visible when "upload" is selected) -->
        <div id="uploadSection" style="display: none;">
          <div class="form-group row">
            <label for="pedigreeFile" class="col-sm-4 col-form-label">Upload Pedigree File</label>
            <div class="col-sm-8">
              <input type="file" class="form-control" id="pedigreeFile" name="pedigreeFile" accept="image/*, .pdf">
            </div>
          </div>
        </div>

        <!-- Iframe for manual construction (visible when "manual" is selected) -->
        <div id="iframeSection" style="display: none;">
          <iframe id="myIframe" src="http://localhost:8001?userId=<?php echo $userId; ?>" width="100%" height="800px" frameborder="0"></iframe>

<!--          <iframe id="myIframe" src="http://localhost:8001?userId=--><?php //echo $userId; ?><!--" width="100%" height="800px" frameborder="0"></iframe>-->
        </div>
      </div>
      <!-- Card Footer with buttons -->
      <div class="card-footer">
        <div class="text-center mt-3">
          <b>Please watch the tutorial videos section on the bottom left-hand corner before starting the pedigree construction. You may also upload the pedigree image instead.</b>
        </div>
        <!-- Back Button -->
        <button type="button" class="btn btn-secondary float-left" style="background-color: #6c757d;" onclick="window.history.back()">Previous</button>

        <!-- Next Button -->
        <button type="button" class="btn btn-info float-right" id="btnNext" disabled>Next</button>
      </div>
    </div>
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php
// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Get form data
  $pedigreeOption = $_POST["pedigreeOption"] ?? '';
  $pedigreeFile = $_FILES["pedigreeFile"] ?? null;

  if ($pedigreeOption == 'upload' && $pedigreeFile && $pedigreeFile['error'] == 0) {
    // Process file upload logic
    $fileName = $_FILES["pedigreeFile"]["name"];
    $fileTmpName = $_FILES["pedigreeFile"]["tmp_name"];
    $filePath = 'uploads/' . $fileName;

    // Move the uploaded file to the desired directory
    if (move_uploaded_file($fileTmpName, $filePath)) {
      // Assuming you have a database connection $conn
      $userId = $_SESSION['user_id']; // Assuming user_id is stored in the session
      $stmt = $con->prepare("INSERT INTO pedigrees (user_id, file_path) VALUES (?, ?)");
      $stmt->bind_param("is", $userId, $filePath);
      $stmt->execute();
      $stmt->close();

      header("Location: family_pedigree.php"); // Redirect to the next page
      exit();
    } else {
      echo '<div class="alert alert-danger">File upload failed. Please try again.</div>';
    }
  } elseif ($pedigreeOption == 'manual') {
    // Handle manual pedigree save from iframe
    $manualPedigreeData = $_POST['manualPedigreeData'] ?? '';

    if ($manualPedigreeData) {
      // Store the manual pedigree data in the database
      $userId = $_SESSION['user_id']; // Assuming user_id is stored in the session
      $stmt = $con->prepare("INSERT INTO pedigrees (user_id, pedigree_data) VALUES (?, ?)");
      $stmt->bind_param("is", $userId, $manualPedigreeData);
      $stmt->execute();
      $stmt->close();

      header("Location: manual_pedigree.php"); // Redirect to manual pedigree page
      exit();
    } else {
      echo '<div class="alert alert-danger">No data received. Please try again.</div>';
    }
  } else {
    echo '<div class="alert alert-danger">Please select an option and provide the necessary information.</div>';
  }
}
?>

<?php include './footer.php' ?>

<script>

  // Listen for the message from iframe
  window.addEventListener('message', function(event) {
    // Check that the message is from the iframe (you may want to add additional security here)
    if (event.origin !== "http://localhost:8001") return; // Replace with your iframe's actual origin

    if (event.data.type === 'navigateToPage') {
      // Navigate to the page specified in the message
      window.location.href = event.data.url; // URL passed from iframe
    }
  });

  document.addEventListener('DOMContentLoaded', function () {
    const pedigreeOption = document.getElementById('pedigreeOption');
    const uploadSection = document.getElementById('uploadSection');
    const iframeSection = document.getElementById('iframeSection');
    const btnNext = document.getElementById('btnNext');

    document.addEventListener('DOMContentLoaded', function () {
      const iframe = document.getElementById('myIframe');
      iframe.contentWindow.postMessage({
        type: 'setUserId',
        userId: <?php echo json_encode($userId); ?>
      }, '*');
    });

    window.parent.postMessage({ type: 'iframeReady' }, '*');
  });

  // Toggle visibility based on selected option
  pedigreeOption.addEventListener('change', function () {
    const selectedOption = pedigreeOption.value;
    if (selectedOption === 'upload') {
      uploadSection.style.display = 'block';
      iframeSection.style.display = 'none';
    } else if (selectedOption === 'manual') {
      iframeSection.style.display = 'block';
      uploadSection.style.display = 'none';
    } else {
      uploadSection.style.display = 'none';
      iframeSection.style.display = 'none';
    }
    checkFormValidity();
  });

  // Form validation to enable/disable Next button
  function checkFormValidity() {
    const isPedigreeOptionSelected = pedigreeOption.value !== '';
    const isUploadValid = (pedigreeOption.value === 'upload' && document.getElementById('pedigreeFile').files.length > 0);
    const isManualValid = (pedigreeOption.value === 'manual');

    btnNext.disabled = !(isPedigreeOptionSelected && (isUploadValid || isManualValid));
  }

  // Check the form validity on change or input
  pedigreeOption.addEventListener('change', checkFormValidity);
  document.getElementById('pedigreeFile').addEventListener('change', checkFormValidity);

  // Listen for postMessage from iframe to focus input
  window.addEventListener('message', function(event) {
    if (event.data === 'focusInput') {
      const input = document.querySelector('input');
      if (input) {
        input.focus();
      }
    }
  });
  document.getElementById('myIframe').contentWindow.postMessage({
    type: 'setUserId',
    userId: <?php echo json_encode($userId); ?> // Ensure PHP data is safely encoded
  }, '*');
</script>
