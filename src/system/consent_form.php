<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

// consent_form.php

if (session_status() === PHP_SESSION_NONE) session_start();

require_once './models/ConsentForm.php';
require_once './db/db_functions.php';
require_once './db/consent_form.php';

// Redirect if not logged in
if (!isset($_SESSION["userid"])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION["userid"];
$consentForm = new ConsentForm();
$showError = false;
$errorMessage = '';

// Load existing consent data (if any)
if ($consentData = getConsentForm($userId)) {
    // Check if the form has already been submitted and agreed
    if (!empty($consentData['consent_agreed']) && $consentData['consent_agreed'] == 1) {
        // Redirect to a view-only page or display message
        header("Location: view_consent.php"); // Create this file for viewing
        exit();
    }

    // Load the data into the form for editing

    $consentForm = new ConsentForm(
        $consentData['patient_name'],
        $consentData['dob'],
        $consentData['mrn'],
        $consentData['gender'],
        $consentData['email'],
        $consentData['signature'],
        $consentData['contact_before'],
        $consentData['patient_use_agreed'],
        $consentData['consent_decision'],
        $consentData['consent_agreed'] ?? 0
    );
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_consent_form'])) {

    // CSRF token validation
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Security error: Invalid CSRF token");
    }

    $con = connectToDatabase();
    if (!$con) {
        $showError = true;
        $errorMessage = "Database connection failed.";
    } else {
        $patient_name = mysqli_real_escape_string($con, $_POST['patient_name']);
        $dob = mysqli_real_escape_string($con, $_POST['dob']);
        $mrn = mysqli_real_escape_string($con, $_POST['mrn']);
        $gender = mysqli_real_escape_string($con, $_POST['gender']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $signature = mysqli_real_escape_string($con, $_POST['signature']);
        $contact_before = isset($_POST['contact_before']) ? mysqli_real_escape_string($con, $_POST['contact_before']) : null;
        $patient_use_agreed = isset($_POST['patient_use_agreed']) ? json_encode($_POST['patient_use_agreed']) : null;
        $consent_decision = isset($_POST['consentDecision']) ? mysqli_real_escape_string($con, $_POST['consentDecision']) : null;


         // Determine if consent is agreed (simple rule: if decision is "Complete")
        $consent_agreed = ($consent_decision === 'Complete') ? 1 : 0;

    // // Sanitize and validate form input
    // $patient_name = mysqli_real_escape_string($con, $_POST['patient_name']);
    // $dob = mysqli_real_escape_string($con, $_POST['dob']);
    // $mrn = mysqli_real_escape_string($con, $_POST['mrn']);
    // $gender = mysqli_real_escape_string($con, $_POST['gender']);
    // $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    // $signature = mysqli_real_escape_string($con, $_POST['signature']);
    // $contact_before = in_array($_POST['contact_before'], ['Yes', 'No']) ? $_POST['contact_before'] : null;
    // $consent_decision = in_array($_POST['consentDecision'], ['Complete', 'NeedDiscussion']) ? $_POST['consentDecision'] : null;
    // $patient_use_agreed = isset($_POST['patient_use_agreed']) ? json_encode($_POST['patient_use_agreed']) : null;
    // $consent_agreed = isset($_POST['consent_agreed']) ? 1 : 0;

    // Create ConsentForm object
    $consentForm = new ConsentForm(
            $patient_name,
            $dob,
            $mrn,
            $gender,
            $email,
            $signature,
            $contact_before,
            $patient_use_agreed,
            $consent_decision,
            $consent_agreed
        );
try {
        // Save the form data first
if (saveConsentForm($userId, $consentForm)) {

   // Check if the user selected "NeedDiscussion"
        if ($consentForm->consent_decision === 'NeedDiscussion') {
            $_SESSION['pending_user_id'] = $userId;
            header("Location: schedule_meeting.php");
            exit();
        } else {
            header("Location: assessment.php");
            exit();
        }

} else {
    $showError = true;
    $errorMessage = 'Error saving form. Please check the error log or try again.';
}
} catch (mysqli_sql_exception $e) {
    // Handle duplicate email error (MySQL error code 1062)
    if ($e->getCode() === 1062) {
        $showError = true;
        $errorMessage = 'This email has already been used. Please use a different one or go back to edit your existing form.';
    } else {
        // For any other SQL error, rethrow
        throw $e;
    }
}
        
        $con->close();
    }
}

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

include './header.php';
include './views/consent_form_view.php';
include './footer.php';