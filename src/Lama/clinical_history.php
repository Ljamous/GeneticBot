<?php
// clinical_history.php
include './header.php';

// Start the session
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Check if user is logged in
if (!isset($_SESSION["userid"])) {
  header("Location: login.php");
  exit();
}

$userId = (int)$_SESSION["userid"];

// Include database connection and functions
require_once './db/mycon.php'; // Database connection
require_once './db/clinical_history.php'; // Database functions
require_once './db/helpers.php';  // Helper functions

// Initialize variables
$typeOfCancerAndAgeOfDiagnosis = '';
$otherTypesOfCancer = '';
$singleBreastBilateral = '';
$histologyReportContent = null;
$histologyReportName = '';
$histologyReportType = '';
$successMessage = '';
$errorMessage = '';

// Define dropdown options
$cancerTypes = ["Breast Cancer", "Ovarian Cancer", "Pancreatic Cancer", "Other", "None"];
$breastTypes = ["Single breast", "Bilateral"];

// Fetch existing data
$clinicalHistoryData = getClinicalHistoryByUserId($userId, $con);

if ($clinicalHistoryData) {
  $typeOfCancerAndAgeOfDiagnosis = htmlspecialchars($clinicalHistoryData['typeOfCancerAndAgeOfDiagnosis'], ENT_QUOTES, 'UTF-8');
  $otherTypesOfCancer = htmlspecialchars($clinicalHistoryData['otherTypesOfCancer'], ENT_QUOTES, 'UTF-8');
  $singleBreastBilateral = htmlspecialchars($clinicalHistoryData['singleBreastBilateral'], ENT_QUOTES, 'UTF-8');
  $histologyReportContent = $clinicalHistoryData['histologyReportContent'];
  $histologyReportName = htmlspecialchars($clinicalHistoryData['histologyReportName'], ENT_QUOTES, 'UTF-8');
  $histologyReportType = htmlspecialchars($clinicalHistoryData['histologyReportType'], ENT_QUOTES, 'UTF-8');
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  // Sanitize and get data
  $typeOfCancerAndAgeOfDiagnosis = mysqli_real_escape_string($con, $_POST["typeOfCancerAndAgeOfDiagnosis"] ?? '');
  $otherTypesOfCancer = mysqli_real_escape_string($con, $_POST["otherTypesOfCancer"] ?? '');
  $singleBreastBilateral = mysqli_real_escape_string($con, $_POST["singleBreastBilateral"] ?? '');

  //Check if existing filename is exists, save it if null
  $existingHistologyReportName = $histologyReportName;
  $existingHistologyReportType = $histologyReportType;
  $existingHistologyReportContent = $histologyReportContent;


  // File upload handling
  $allowedTypes = [
    'application/msword', // .doc
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document', // .docx
    'application/pdf', // .pdf
    'image/jpeg', // .jpg, .jpeg
   'image/png' // .png
];  //Case Number 1: user submit data and uploaded new data
  if(isset($_FILES["histologyReport"]) && $_FILES["histologyReport"]["error"] == UPLOAD_ERR_OK) {
    $fileUploadResult = handleFileUpload($_FILES["histologyReport"], $allowedTypes);

    if ($fileUploadResult["success"]) {
      $histologyReportContent = $fileUploadResult['fileContent'];
      $histologyReportName = $fileUploadResult['fileName'];
      $histologyReportType = $fileUploadResult['fileType'];
    }else{
      $errorMessage = $fileUploadResult["error"];
    }
  }else{
    //Case Number 2: User submit data without new uploaded data, then take back old data
    $histologyReportName     = $existingHistologyReportName;
    $histologyReportType     = $existingHistologyReportType;
    $histologyReportContent = $existingHistologyReportContent;
    error_log("no new file, pass on the previous file");
    $fileUploadResult["success"] = true; //pass on the process, the function has its protection
  }

  if ($fileUploadResult["success"]) {

    // Save clinical history data
    $saveSuccess = saveClinicalHistory(
      $typeOfCancerAndAgeOfDiagnosis,
      $otherTypesOfCancer,
      $singleBreastBilateral,
      $histologyReportContent,
      $histologyReportName,
      $histologyReportType,
      $userId,
      $con
    );

    if ($saveSuccess) {
      $successMessage = "Clinical history saved successfully!";
      echo '<script>window.location="family_pedigree.php";</script>'; // **REDIRECT ON SUCCESS**
      exit(); // Ensure script stops execution after redirect
    } else {
      $errorMessage = "There was an error saving your data. Please try again.";
    }
  } else {
    $errorMessage = $fileUploadResult["error"];
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
        <div class="card card-info mx-auto" style="width:95%; border-radius: 10px;">
            <div class="card-header" style="background-color: #2D4B69;">
                <h3 class="card-title">Clinical History</h3>
            </div>
            <!-- /.card-header -->
            <form class="form-horizontal" method="post" action="clinical_history.php" enctype="multipart/form-data"
                id="clinicalHistoryForm">
                <div class="card-body">
                    <input type="hidden" name="user_id" value="<?php echo $userId; ?>">

                    <?php if (!empty($successMessage)): ?>
                    <div class="alert alert-success"><?php echo htmlspecialchars($successMessage); ?></div>
                    <?php endif; ?>

                    <?php if (!empty($errorMessage)): ?>
                    <div class="alert alert-danger"><?php echo htmlspecialchars($errorMessage); ?></div>
                    <?php endif; ?>

                    <!-- Type of cancer and age of diagnosis -->
                    <div class="form-group row">
                        <label for="typeOfCancerAndAgeOfDiagnosis" class="col-sm-4 col-form-label">Type of cancer and
                            age of diagnosis <span style="color:red;">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control" id="typeOfCancerAndAgeOfDiagnosis"
                                name="typeOfCancerAndAgeOfDiagnosis" required>
                                <option value="">Select Type of Cancer</option>
                                <?php foreach ($cancerTypes as $type): ?>
                                <option value="<?php echo htmlspecialchars($type); ?>"
                                    <?php echo ($typeOfCancerAndAgeOfDiagnosis == $type) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($type); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Other types of cancer -->
                    <div class="form-group row">
                        <label for="otherTypesOfCancer" class="col-sm-4 col-form-label">Other types of cancer <span
                                style="color:red;">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control" id="otherTypesOfCancer" name="otherTypesOfCancer" required>
                                <option value="">Select Other Cancer Type</option>
                                <?php foreach ($cancerTypes as $type): ?>
                                <option value="<?php echo htmlspecialchars($type); ?>"
                                    <?php echo ($otherTypesOfCancer == $type) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($type); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Single breast/Bilateral -->
                    <div class="form-group row">
                        <label for="singleBreastBilateral" class="col-sm-4 col-form-label">Single breast/Bilateral <span
                                style="color:red;">*</span></label>
                        <div class="col-sm-8">
                            <select class="form-control" id="singleBreastBilateral" name="singleBreastBilateral"
                                required>
                                <option value="">Select Breast Type</option>
                                <?php foreach ($breastTypes as $type): ?>
                                <option value="<?php echo htmlspecialchars($type); ?>"
                                    <?php echo ($singleBreastBilateral == $type) ? 'selected' : ''; ?>>
                                    <?php echo htmlspecialchars($type); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <!-- Histology report -->
                    <!-- Histology report -->
                    <div class="form-group row">
                        <label for="histologyReport" class="col-sm-4 col-form-label">Histology report <span
                                style="color:red;">*</span></label>
                        <div class="col-sm-8">
                            <input type="file" class="form-control" id="histologyReport" name="histologyReport"
                                accept=".doc,.docx,.pdf,.jpg,.jpeg,.png"
                                <?php echo empty($histologyReportContent) ? 'required' : ''; ?>>

                            <?php if (!empty($histologyReportContent)): ?>
                            <div class="mt-2">
                                <label>Current File:</label>
                                <p class="filename">
                                    <a href="download_clinical_history_file.php?userId=<?php echo $userId; ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        <?php echo htmlspecialchars($histologyReportName, ENT_QUOTES, 'UTF-8'); ?>
                                    </a>
                                </p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>


                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                    <div class="text-center mt-3">
                        <b>Please fill out all the required fields before proceeding.</b>
                    </div>
                    <button type="submit" class="btn btn-info float-right" style="background-color: #2D4B69;"
                        name="submit_personal_info" id="btn_save_ch">Next</button>

                    <!-- Back Button -->
                    <button type="button" class="btn btn-secondary float-left" style="background-color: #6c757d;"
                        onclick="window.location.href='personal_info.php'">Back</button>
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('clinicalHistoryForm');
    const nextButton = document.getElementById('btn_save_ch');
    const histologyReportInput = document.getElementById('histologyReport');

    const typeOfCancer = document.getElementById('typeOfCancerAndAgeOfDiagnosis');
    const otherTypesOfCancer = document.getElementById('otherTypesOfCancer');
    const singleBreastBilateral = document.getElementById('singleBreastBilateral');

    // Function to check if all required fields are filled
    function checkFields() {
        let allFilled = true;

        // Check if Type of cancer and age of diagnosis is selected
        if (!typeOfCancer.value) {
            allFilled = false;
        }

        // Check if Other types of cancer is selected
        if (!otherTypesOfCancer.value) {
            allFilled = false;
        }

        // Check if Single breast/Bilateral is selected
        if (!singleBreastBilateral.value) {
            allFilled = false;
        }

        // The Key Change: If $histologyReportName exists, OR a new file has been selected, allow Next

        // Enable/Disable Next button based on the validation status
        nextButton.disabled = !allFilled;

        console.log("Fields are filled: " + allFilled);
    }

    // Attach change and input event listeners to all required fields
    typeOfCancer.addEventListener('change', checkFields);
    otherTypesOfCancer.addEventListener('change', checkFields);
    singleBreastBilateral.addEventListener('change', checkFields);
    //histologyReportInput.addEventListener('change', checkFields);

    // Initial check on page load
    checkFields(); // Initial validation check when the page loads

    // Disable the submit button after form submission
    form.addEventListener('submit', function() {
        nextButton.disabled = true;
    });
});
</script>