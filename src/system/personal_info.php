<?php
// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Ensure the user is logged in
if (!isset($_SESSION["userid"])) {
  // Redirect to the login page if not logged in
  header("Location: index.php");
  exit();  // Ensure no further execution after the redirect
}

$userId = $_SESSION["userid"];  // Retrieve the user ID from the session

// Include the necessary file for database functions
require_once './db/personal_information.php'; // Corrected path

// Initialize variables to hold data from the database
$pid = '';
$mrn = '';
$name = '';
$dob = '';
$gender = '';
$ms = '';
$ancestry = '';

// Fetch existing data from the database
$personalInfo = getPersonalInfo($userId);

if ($personalInfo) {
  $pid = $personalInfo['pid'];
  $mrn = $personalInfo['mrn'];
  $name = $personalInfo['name'];
  $dob = $personalInfo['dob'];
  $gender = $personalInfo['gender'];
  $ms = $personalInfo['ms'];
  $ancestry = $personalInfo['ancestry'];

  // Store the values into the session too. This makes it persistent.
  $_SESSION['pid'] = $pid;
  $_SESSION['mrn'] = $mrn;
  $_SESSION['name'] = $name;
  $_SESSION['dob'] = $dob;
  $_SESSION['gender'] = $gender;
  $_SESSION['ms'] = $ms;
  $_SESSION['ancestry'] = $ancestry;
} else {
  // Initialize session variables if not set and if no database record exists
  if (!isset($_SESSION['pid'])) {
    $_SESSION['pid'] = '';
  }
  if (!isset($_SESSION['mrn'])) {
    $_SESSION['mrn'] = '';
  }

  //Set the name by default to session name
  if (!isset($_SESSION['name'])) {
    $_SESSION['name'] = isset($_SESSION['name']) ? $_SESSION['name'] : '';  // Default to the session name if it exists
    $name = $_SESSION['name'];
  } else {
    $name = $_SESSION['name'];
  }

  if (!isset($_SESSION['dob'])) {
    $_SESSION['dob'] = '';
  }
  if (!isset($_SESSION['gender'])) {
    $_SESSION['gender'] = '';
  }
  if (!isset($_SESSION['ms'])) {
    $_SESSION['ms'] = '';
  }
  if (!isset($_SESSION['ancestry'])) {
    $_SESSION['ancestry'] = '';
  }
}

// Handle form submission and store data in session
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_personal_info"])) {
  // Sanitize and validate form inputs
  $pid = htmlspecialchars(trim($_POST['pid']));
  $mrn = htmlspecialchars(trim($_POST['mrn']));
  $name = htmlspecialchars(trim($_POST['name']));
  $dob = htmlspecialchars(trim($_POST['dob']));
  $gender = htmlspecialchars(trim($_POST['gender']));
  $ms = htmlspecialchars(trim($_POST['ms']));
  $ancestry = htmlspecialchars(trim($_POST['ancestry']));

  // Store the sanitized data in the session
  $_SESSION['pid'] = $pid;
  $_SESSION['mrn'] = $mrn;
  $_SESSION['name'] = $name;
  $_SESSION['dob'] = $dob;
  $_SESSION['gender'] = $gender;
  $_SESSION['ms'] = $ms;
  $_SESSION['ancestry'] = $ancestry;

  // Insert or update the personal information in the database
  $result = insertPersonalInfo($pid, $mrn, $name, $dob, $gender, $ms, $ancestry, $userId);

  if ($result) {
    // Redirect after successful insert or update
    header("Location: clinical_history.php");
    exit();  // Stop further script execution after the redirect
  } else {
    // Handle error if needed
    echo '<div class="alert alert-danger">There was an error saving the data.</div>';
  }
}
?>

<?php
include './header.php'; // Ensure correct path
?>
<!-- daterange picker -->
<link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">

<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2">
        <div class="col-sm-6"></div>
        <div class="col-sm-6"></div>
      </div>
    </div>
  </section>

  <section class="content">
    <div class="card card-info mx-auto" style="width:95%; border-radius: 10px;">
      <div class="card-header" style="background-color: #2D4B69; color: white; border-radius: 10px;">
        <h3 class="card-title">Personal Information</h3>
      </div>

      <!-- Form Start -->
      <form class="form-horizontal" method="post" action="personal_info.php" id="personalInfoForm">
        <div class="card-body">

          <!-- Hidden input field to send the userId -->
          <input type="hidden" name="user_id" value="<?php echo $userId; ?>">

          <!-- ID Input -->
          <div class="form-group row">
            <label for="pid" class="col-sm-4 col-form-label">ID <span style="color:red;">*</span></label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="pid" name="pid" placeholder="ID" value="<?= htmlspecialchars($pid); ?>" required>
            </div>
          </div>

          <!-- MRN Input -->
          <div class="form-group row">
            <label for="mrn" class="col-sm-4 col-form-label">MRN <span style="color:red;">*</span></label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="mrn" name="mrn" placeholder="MRN" value="<?= htmlspecialchars($mrn); ?>" required>
            </div>
          </div>

          <!-- Name Input -->
          <div class="form-group row">
            <label for="nm" class="col-sm-4 col-form-label">Name <span style="color:red;">*</span></label>
            <div class="col-sm-8">
              <input type="text" class="form-control" id="nm" name="name" placeholder="Name" value="<?= htmlspecialchars($name); ?>" required>
            </div>
          </div>

          <!-- Date of Birth Input -->
          <div class="form-group row">
            <label for="dob" class="col-sm-4 col-form-label">Date of Birth <span style="color:red;">*</span></label>
            <div class="col-sm-8">
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                </div>
                <input type="text" class="form-control" id="dob" name="dob" value="<?= htmlspecialchars($dob); ?>" required>
              </div>
            </div>
          </div>

          <!-- Gender Input -->
          <div class="form-group row">
            <label for="gender" class="col-sm-4 col-form-label">Gender <span style="color:red;">*</span></label>
            <div class="col-sm-8">
              <select class="form-control" id="gender" name="gender" required>
                <option value="">Select Gender</option>
                <option value="m" <?= $gender == 'm' ? 'selected' : ''; ?>>Male</option>
                <option value="f" <?= $gender == 'f' ? 'selected' : ''; ?>>Female</option>
              </select>
            </div>
          </div>

          <!-- Marital Status Input -->
          <div class="form-group row">
            <label for="ms" class="col-sm-4 col-form-label">Marital Status <span style="color:red;">*</span></label>
            <div class="col-sm-8">
              <select class="form-control" id="ms" name="ms" required>
                <option value="">Select Marital Status</option>
                <option value="Single" <?= $ms == 'Single' ? 'selected' : ''; ?>>Single</option>
                <option value="Married" <?= $ms == 'Married' ? 'selected' : ''; ?>>Married</option>
                <option value="Divorced" <?= $ms == 'Divorced' ? 'selected' : ''; ?>>Divorced</option>
                <option value="Widowed" <?= $ms == 'Widowed' ? 'selected' : ''; ?>>Widowed</option>
                <option value="Separated" <?= $ms == 'Separated' ? 'selected' : ''; ?>>Separated</option>
                <option value="Engaged" <?= $ms == 'Engaged' ? 'selected' : ''; ?>>Engaged</option>
              </select>
            </div>
          </div>

          <!-- Ancestry Input -->
          <div class="form-group row">
            <label for="ancestry" class="col-sm-4 col-form-label">Ancestry</label>
            <div class="col-sm-8">
              <textarea class="form-control" id="ancestry" name="ancestry" rows="4"><?= htmlspecialchars($ancestry); ?></textarea>
            </div>
          </div>

        </div>

        <div class="card-footer">
          <div class="text-center mt-3">
            <b>Please fill out all the required fields before proceeding.</b>
          </div>
          <button type="submit" class="btn btn-info float-right" style="background-color: #2D4B69;" name="submit_personal_info" id="nextBtn" disabled>Next</button>

          <!-- Back Button -->
          <button type="button" class="btn btn-secondary float-left" style="background-color: #6c757d;" onclick="window.location.href='Genetic_Testing_Journey.php'">Back</button>
        </div>
      </form>
    </div>
  </section>
</div>

<?php
// Include the footer
include './footer.php';
?>

<!-- InputMask -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/inputmask/jquery.inputmask.min.js"></script>
<!-- date-range-picker -->
<script src="plugins/daterangepicker/daterangepicker.js"></script>

<script>
  $(function() {
    // Initialize the daterangepicker on the #dob input field
    $('#dob').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
      locale: {
        format: 'YYYY-MM-DD'
      }
    });

    // Handle other form input masking (optional)
    $('[data-mask]').inputmask();

    // Check if required fields are filled (excluding "Ancestry")
    function checkRequiredFields() {
      var allFilled = true;

      // Check each required input except the "Ancestry" field
      $('#personalInfoForm').find('input[required], select[required], textarea[required]').each(function() {
        // Skip the "Ancestry" field as it's not required anymore
        if ($(this).attr('id') !== "ancestry" && $(this).val() === "") {
          allFilled = false;
        }
      });

      // Enable/Disable the Next button based on the check
      $('#nextBtn').prop('disabled', !allFilled);
    }

    // Initial check on page load
    checkRequiredFields();

    // Bind change events to fields
    $('#personalInfoForm input, #personalInfoForm select, #personalInfoForm textarea').on('input change', function() {
      checkRequiredFields();
    });
  });

</script>
