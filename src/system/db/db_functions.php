<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

// db/db_functions.php
require_once 'mycon.php';

function connectToDatabase() {

    $con = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_NAME);
    $con->set_charset("utf8");

    if ($con->connect_error) {
        error_log("Database connection failed: " . $con->connect_error);
        die("Database connection failed: " . $con->connect_error);
    }
    return $con;
}


function saveConsentForm1($userId, ConsentForm $form) {
    global $con;

    $agreements = json_encode($form->patient_use_agreed);
    $consent_agreed = 1; //  Consent agreed is assumed if the form is submitted.

    // Simplified INSERT/UPDATE query (using INSERT ... ON DUPLICATE KEY UPDATE)
    $stmt = $con->prepare("
    INSERT INTO users_consent (user_id, patient_name, dob, mrn, gender, email, signature, contact_before, patient_use_agreed, consent_agreed, consent_decision)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE 
        patient_name=VALUES(patient_name), dob=VALUES(dob), mrn=VALUES(mrn), gender=VALUES(gender), 
        email=VALUES(email), signature=VALUES(signature), contact_before=VALUES(contact_before), 
        patient_use_agreed=VALUES(patient_use_agreed), 
        consent_agreed=VALUES(consent_agreed), consent_decision=VALUES(consent_decision)
");


    if (!$stmt) {
        die('Prepare failed: ' . $con->error); // Important error handling
    }

    $stmt->bind_param(
        "issssssssis", // Updated parameter types
        $userId, $form->patient_name, $form->dob, $form->mrn, $form->gender, $form->email,
        $form->signature, $form->contact_before, $agreements, $consent_agreed, $form->consent_decision
    );

    $result = $stmt->execute();
     if (!$result) {  // Check for errors *after* executing the statement
        error_log("Error saving consent form: " . $stmt->error);  // Log the error
        return false; // Indicate failure
    }

    $stmt->close();
    return true; // Indicate success


}
?>