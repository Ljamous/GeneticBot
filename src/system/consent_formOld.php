<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

// Start the session if it's not already started
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}

// Ensure the user is logged in
if (!isset($_SESSION["userid"])) {
  // Redirect to the login page if not logged in
  header("Location: index.php");
  exit();
}

$userId = $_SESSION["userid"];

require_once './db/users.php';  // Assuming you have user-related DB functions
require_once './db/consent_form.php'; // Assuming you will save to a consent_form DB table

// Initialize variables
$patient_name = '';
$dob = '';
$mrn = '';
$gender = '';
$email = '';
$signature = '';
$death_contact_name = '';
$death_contact_tel = '';
$death_contact_email = '';
$more_discussion = false; // Use a boolean instead of a checkbox
$further_tests_consent = '';

// Fetch existing data if it exists
$consentData = getConsentForm($userId);  // Assuming this function exists in consent_formOld.php

if ($consentData) {
  $patient_name = $consentData['patient_name'];
  $dob = $consentData['dob'];
  $mrn = $consentData['mrn'];
  $gender = $consentData['gender'];
  $email = $consentData['email'];
  $signature = $consentData['signature'];
  $death_contact_name = $consentData['death_contact_name'];
  $death_contact_tel = $consentData['death_contact_tel'];
  $death_contact_email = $consentData['death_contact_email'];
  $more_discussion = $consentData['more_discussion']; // This needs to be boolean
  $further_tests_consent = $consentData['further_tests_consent'];
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit_consent_form"])) {

  // Sanitize and validate inputs
  $patient_name = filter_var($_POST['patient_name'], FILTER_SANITIZE_STRING);
  $dob = filter_var($_POST['dob'], FILTER_SANITIZE_STRING);
  $mrn = filter_var($_POST['mrn'], FILTER_SANITIZE_STRING);
  $gender = filter_var($_POST['gender'], FILTER_SANITIZE_STRING);
  $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
  $signature = filter_var($_POST['signature'], FILTER_SANITIZE_STRING);  // Consider a different way to capture signatures
  $death_contact_name = filter_var($_POST['death_contact_name'], FILTER_SANITIZE_STRING);
  $death_contact_tel = filter_var($_POST['death_contact_tel'], FILTER_SANITIZE_STRING);
  $death_contact_email = filter_var($_POST['death_contact_email'], FILTER_SANITIZE_EMAIL);
  $more_discussion = isset($_POST['more_discussion']) && $_POST['more_discussion'] == '1' ? true : false;   // Convert it into Boolean
  $further_tests_consent = filter_var($_POST['further_tests_consent'], FILTER_SANITIZE_STRING);  // Consider it YES NO
  $consent_agreed = isset($_POST['consent_agreed']) && $_POST['consent_agreed'] == '1' ? true : false; // Ensure the user agrees to the consent

  $patient_use_agreed = isset($_POST['patient_use_agreed']) ? $_POST['patient_use_agreed'] : []; // Array of checked boxes for patient use

  if (!$consent_agreed || empty($patient_use_agreed)) {
    echo '<div class="alert alert-danger">You must agree to the consent terms and patient use paragraphs before submitting the form.</div>';
  } else {
    // Save to the database
    $result = insertConsentForm(
      $userId,
      $patient_name,
      $dob,
      $mrn,
      $gender,
      $email,
      $signature,
      $death_contact_name,
      $death_contact_tel,
      $death_contact_email,
      $more_discussion,
      $further_tests_consent
    );

    if ($result) {
      // Redirect on success
      header("Location: assessment.php");
      exit();
    } else {
      echo '<div class="alert alert-danger">Error saving consent form.</div>';
    }
  }
}
?>

<?php include './header.php'; ?>

<div class="content-wrapper">
  <section class="content">
    <div class="card card-primary">
      <div class="card-header">
        <h3 class="card-title">Informed Consent for Genetic Testing</h3>
      </div>
      <form method="post" action="consent_form.php">
        <div class="card-body">

          <!-- Consent Text -->

          <p><strong>Patient Name:</strong> <input type="text" name="patient_name" value="<?= htmlspecialchars($patient_name) ?>" required></p>
          <p><strong>DOB:</strong> <input type="date" name="dob" value="<?= htmlspecialchars($dob) ?>" required></p>
          <p><strong>MRN:</strong> <input type="text" name="mrn" value="<?= htmlspecialchars($mrn) ?>" required></p>

          <p><strong>Gender:</strong>
            <label><input type="radio" name="gender" value="Female" <?= ($gender == 'Female') ? 'checked' : '' ?> required> Female</label>
            <label><input type="radio" name="gender" value="Male" <?= ($gender == 'Male') ? 'checked' : '' ?>> Male</label>
          </p>

          <p><strong>Requested Genetic Test:</strong> A multigene panel test that includes BRCA1, BRCA2, CDH1, PALB2, PTEN, and TP53 for breast cancer susceptibility.</p>

          <h5>For patient use only:</h5>

          <!-- Patient Use Checkboxes -->
          <p>
            <label><input type="checkbox" name="patient_use_agreed[]" value="agree_1" required> I agree to have genetic (DNA) testing for a multigene panel.</label>
          </p>
          <p>
            <label><input type="checkbox" name="patient_use_agreed[]" value="agree_2" required> I understand the purpose of this genetic test.</label>
          </p>
          <p>
            <label><input type="checkbox" name="patient_use_agreed[]" value="agree_3" required> I have been informed about the benefits, risks, and limitations of genetic testing.</label>
          </p>
          <p>
            <label><input type="checkbox" name="patient_use_agreed[]" value="agree_4" required> I understand that the possible results are positive, negative, uncertain, or incidental findings.</label>
          </p>
          <p>
            <label><input type="checkbox" name="patient_use_agreed[]" value="agree_5" required> I agree that all information I gave about my family history and their clinical diagnosis are correct.</label>
          </p>
          <p>
            <label><input type="checkbox" name="patient_use_agreed[]" value="agree_6" required> I understand that errors may occur during testing.</label>
          </p>
          <p>
            <label><input type="checkbox" name="patient_use_agreed[]" value="agree_7" required> I agree to storage of my sample after analysis.</label>
          </p>
          <p>
            <label><input type="checkbox" name="patient_use_agreed[]" value="agree_8" required> I understand that my sample may be used anonymously for research or quality assurance.</label>
          </p>
          <p>
            <label><input type="checkbox" name="patient_use_agreed[]" value="agree_9" required> I understand that my information may be used for clinical audit.</label>
          </p>
          <p>
            <label><input type="checkbox" name="patient_use_agreed[]" value="agree_10" required> I understand that my results may be discussed in a multidisciplinary committee meeting.</label>
          </p>
          <p>
            <label><input type="checkbox" name="patient_use_agreed[]" value="agree_11" required> If new tests become available, I understand I will be contacted for further testing if required.</label>
          </p>

          <h5>Patient Consent Statement:</h5>

          <p>By signing below, I acknowledge the following:</p>

          <ul>
            <li>I've been given the chance to explore the decision aid system and inquire through the chatbot.</li>
            <li>I consent to being tested for hereditary cancer predisposition and will discuss the results with my physician/counselor.</li>
            <li>I am the owner of my medical history and test results, and I allow my physician to discuss my results with third parties as necessary.</li>
          </ul>

          <p><strong>Name of patient (Signature):</strong> <input type="text" name="signature" value="<?= htmlspecialchars($signature) ?>" required placeholder="Please type your full name" /></p>
          <p><strong>Email:</strong> <input type="email" name="email" value="<?= htmlspecialchars($email) ?>" required placeholder="Please enter a valid email" /></p>

          <div class="form-group">
            <label for="consent_agreed">
              <input type="checkbox" name="consent_agreed" value="1" required> I agree to the informed consent terms.
            </label>
          </div>

        </div>
        <div class="card-footer">
          <button type="submit" class="btn btn-primary" name="submit_consent_form">Submit</button>
        </div>
      </form>
    </div>
  </section>
</div>

<?php include './footer.php'; ?>
