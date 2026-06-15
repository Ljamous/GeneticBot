<?php
// clinical_history.php
include './header.php'; // Or any header you need for styling and layout

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Check if user is logged in
if (!isset($_SESSION["userid"])) {
  header("Location: login.php"); // Redirect to login page if not logged in
  exit();
}

$userId = (int)$_SESSION["userid"]; // Get user ID from session and cast to integer

// Database connection details
require_once './db/mycon.php'; // Database connection file - **ENSURE `$con` is available after including this**

// Check connection
if ($con->connect_error) {
  die("Connection failed: " . $con->connect_error);
}

// Initialize variables (to hold form data and display existing data)
$typeOfCancerAndAgeOfDiagnosis = "";
$otherTypesOfCancer = "";
$singleBreastBilateral = "";
$histologyReport = "";  // Store only file name
$histologyReportContent = null; // BLOB data (binary)
$histologyReportName = "";
$histologyReportType = "";
$successMessage = "";  // To display a success message
$errorMessage = "";    // To display an error message

// Function to fetch existing clinical history data for a user
function getClinicalHistory($con, $userId) {
  $sql = "SELECT typeOfCancerAndAgeOfDiagnosis, otherTypesOfCancer, singleBreastBilateral, histologyReport, histologyReportContent, histologyReportName, histologyReportType
            FROM clinical_histories WHERE userId = ?";
  $stmt = $con->prepare($sql);
  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    return $result->fetch_assoc();
  } else {
    return null;
  }
}

// Function to handle file uploads and save data (modified)
function saveClinicalData($con, $userId, $typeOfCancerAndAgeOfDiagnosis, $otherTypesOfCancer, $singleBreastBilateral, $histologyReport, $histologyReportContent, $histologyReportName, $histologyReportType) {
  $sql = "INSERT INTO clinical_histories (userId, typeOfCancerAndAgeOfDiagnosis, otherTypesOfCancer, singleBreastBilateral, histologyReport, histologyReportContent, histologyReportName, histologyReportType)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
            ON DUPLICATE KEY UPDATE
            typeOfCancerAndAgeOfDiagnosis = VALUES(typeOfCancerAndAgeOfDiagnosis),
            otherTypesOfCancer = VALUES(otherTypesOfCancer),
            singleBreastBilateral = VALUES(singleBreastBilateral),
            histologyReport = VALUES(histologyReport),
            histologyReportContent = VALUES(histologyReportContent),
            histologyReportName = VALUES(histologyReportName),
            histologyReportType = VALUES(histologyReportType)";

  $stmt = $con->prepare($sql);
  $stmt->bind_param("isssssss", $userId, $typeOfCancerAndAgeOfDiagnosis, $otherTypesOfCancer, $singleBreastBilateral,
    $histologyReport, $histologyReportContent, $histologyReportName, $histologyReportType); // All strings

  if ($stmt->execute()) {
    return true;
  } else {
    return false;
  }
}


// Process form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $typeOfCancerAndAgeOfDiagnosis = $_POST["typeOfCancerAndAgeOfDiagnosis"];
  $otherTypesOfCancer = $_POST["otherTypesOfCancer"];
  $singleBreastBilateral = $_POST["singleBreastBilateral"];

  // File upload handling
  if (isset($_FILES["histologyReport"]) && $_FILES["histologyReport"]["error"] == UPLOAD_ERR_OK) {
    $file = $_FILES["histologyReport"];
    $histologyReportName = basename($file["name"]); // Just the name
    $histologyReportType = strtolower(pathinfo($histologyReportName, PATHINFO_EXTENSION));

    $allowedTypes = array("doc", "docx", "pdf");
    if (in_array($histologyReportType, $allowedTypes)) {
      // Store the BLOB data (carefully)
      $histologyReportContent = file_get_contents($file["tmp_name"]); //Binary Data
      $histologyReport = $histologyReportName;

      if (saveClinicalData($con, $userId, $typeOfCancerAndAgeOfDiagnosis, $otherTypesOfCancer, $singleBreastBilateral,  $histologyReport, $histologyReportContent, $histologyReportName, $histologyReportType)) {
        $successMessage = "Clinical history saved successfully!";
      } else {
        $errorMessage = "Clinical history is not saved";
      }
    } else {
      $errorMessage = "Invalid file type.  Only DOC, DOCX, and PDF files are allowed.";
    }
  } else {
    $errorMessage = "Please upload a histology report.";
  }
} else {
  // Load existing data if available (outside of POST)
  $existingData = getClinicalHistory($con, $userId);
  if ($existingData) {
    $typeOfCancerAndAgeOfDiagnosis = htmlspecialchars($existingData['typeOfCancerAndAgeOfDiagnosis'], ENT_QUOTES, 'UTF-8');
    $otherTypesOfCancer = htmlspecialchars($existingData['otherTypesOfCancer'], ENT_QUOTES, 'UTF-8');
    $singleBreastBilateral = htmlspecialchars($existingData['singleBreastBilateral'], ENT_QUOTES, 'UTF-8');
    $histologyReport = htmlspecialchars($existingData['histologyReport'], ENT_QUOTES, 'UTF-8'); //filename
    $histologyReportContent = $existingData['histologyReportContent']; // BLOB Data
    $histologyReportName = htmlspecialchars($existingData['histologyReportName'], ENT_QUOTES, 'UTF-8');
    $histologyReportType = htmlspecialchars($existingData['histologyReportType'], ENT_QUOTES, 'UTF-8');
  }
}
?>

<!-- HTML Form and Content -->

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"></div>
        <div class="col-sm-6"></div>
      </div>
    </div><!-- /.container-fluid -->
  </section>

  <!-- Main content -->
  <section class="content">
    <div class="card card-info mx-auto" style="width:70%; border-radius: 10px;">
      <div class="card-header" style="background-color: #2D4B69;">
        <h3 class="card-title">Clinical History</h3>
      </div>
      <!-- /.card-header -->
      <form class="form-horizontal" method="post" action="clinical_history.php" enctype="multipart/form-data" id="clinicalHistoryForm">
        <div class="card-body">
          <input type="hidden" name="user_id" value="<?php echo $userId; ?>">

          <!-- Type of cancer and age of diagnosis -->
          <div class="form-group row">
            <label for="typeOfCancerAndAgeOfDiagnosis" class="col-sm-4 col-form-label">Type of cancer and age of diagnosis <span style="color:red;">*</span></label>
            <div class="col-sm-8">
              <select class="form-control" id="typeOfCancerAndAgeOfDiagnosis" name="typeOfCancerAndAgeOfDiagnosis" required>
                <option value="" <?php echo empty($typeOfCancerAndAgeOfDiagnosis) ? 'selected' : ''; ?>>Select Type of Cancer</option>
                <option value="Breast Cancer" <?php echo ($typeOfCancerAndAgeOfDiagnosis == 'Breast Cancer') ? 'selected' : ''; ?>>Breast Cancer</option>
                <option value="Other" <?php echo ($typeOfCancerAndAgeOfDiagnosis == 'Other') ? 'selected' : ''; ?>>Other</option>
              </select>
            </div>
          </div>

          <!-- Other types of cancer -->
          <div class="form-group row">
            <label for="otherTypesOfCancer" class="col-sm-4 col-form-label">Other types of cancer <span style="color:red;">*</span></label>
            <div class="col-sm-8">
              <select class="form-control" id="otherTypesOfCancer" name="otherTypesOfCancer" required>
                <option value="" <?php echo empty($otherTypesOfCancer) ? 'selected' : ''; ?>>Select Other Cancer Type</option>
                <option value="Breast Cancer" <?php echo ($otherTypesOfCancer == 'Breast Cancer') ? 'selected' : ''; ?>>Breast Cancer</option>
                <option value="Ovarian Cancer" <?php echo ($otherTypesOfCancer == 'Ovarian Cancer') ? 'selected' : ''; ?>>Ovarian Cancer</option>
                <option value="Pancreatic Cancer" <?php echo ($otherTypesOfCancer == 'Pancreatic Cancer') ? 'selected' : ''; ?>>Pancreatic Cancer</option>
              </select>
            </div>
          </div>

          <!-- Single breast/Bilateral -->
          <div class="form-group row">
            <label for="singleBreastBilateral" class="col-sm-4 col-form-label">Single breast/Bilateral <span style="color:red;">*</span></label>
            <div class="col-sm-8">
              <select class="form-control" id="singleBreastBilateral" name="singleBreastBilateral" required>
                <option value="" <?php echo empty($singleBreastBilateral) ? 'selected' : ''; ?>>Select Breast Type</option>
                <option value="single breast" <?php echo ($singleBreastBilateral == 'single breast') ? 'selected' : ''; ?>>Single breast</option>
                <option value="bilateral" <?php echo ($singleBreastBilateral == 'bilateral') ? 'selected' : ''; ?>>Bilateral</option>
              </select>
            </div>
          </div>

          <!-- Histology report -->
          <div class="form-group row">
            <label for="histologyReport" class="col-sm-4 col-form-label">Histology report <span style="color:red;">*</span></label>
            <div class="col-sm-8">
              <!-- Accept only .doc, .docx, .pdf files -->
              <input type="file" class="form-control" id="histologyReport" name="histologyReport" accept=".doc,.docx,.pdf" <?php echo empty($histologyReportContent) ? 'required' : ''; ?>>
              <?php if (!empty($histologyReportContent)): ?>
                <p>Current File:
                  <?php
                  // Check file extension to determine if it's viewable or needs to be downloaded
                  if (in_array($histologyReportType, ['doc', 'docx'])) {
                    echo '<a href="download.php?userId=' . $userId . '" target="_blank">Download File</a>';
                  } elseif ($histologyReportType == 'pdf') {
                    echo '<a href="view_blob.php?userId=' . $userId . '" target="_blank">View File</a>';
                  } else {
                    echo '<a href="download.php?userId=' . $userId . '" target="_blank">Download File</a>';
                  }
                  ?>
                </p>
                <p>File Name: <?php echo htmlspecialchars($histologyReportName, ENT_QUOTES, 'UTF-8'); ?></p>
              <?php endif; ?>
            </div>
          </div>

        </div>
        <!-- /.card-body -->

        <div class="card-footer">
          <div class="text-center mt-3">
            <b>Please fill out all the required fields before proceeding.</b>
          </div>
          <button type="submit" class="btn btn-info float-right" style="background-color: #2D4B69;" name="submit_personal_info" id="btn_save_ch" disabled>Next</button>

          <!-- Back Button -->
          <button type="button" class="btn btn-secondary float-left" style="background-color: #6c757d;" onclick="window.history.back()">Back</button>
        </div>
        <!-- /.card-footer -->
      </form>
    </div>
    <!-- /.card -->
  </section>
  <!-- /.content -->
</div>
<!-- /.content-wrapper -->

<?php include './footer.php'; ?>

<?php
// Close database connection
$con->close();
?>
