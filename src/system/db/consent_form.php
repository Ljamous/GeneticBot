<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

// /db/consent_form.php

require_once 'mycon.php'; // Ensure this path is correct

function insertConsentForm(
  $userId,
  $patient_name,
  $dob,
  $mrn,
  $gender,
  $email,
  $signature,
  $contact_before,           // 'Yes' or 'No'
  $consent_agreed,           // 1 or 0
  $patient_use_agreed,       // optional text
  $consent_decision          // 'Complete' or 'NeedDiscussion'
) {
  global $con;

  // Check if a consent form already exists for the user
  $existingForm = getConsentForm($userId);

  if ($existingForm) {
    // Update existing record
    $st = $con->prepare("
      UPDATE users_consent
      SET
        patient_name = ?,
        dob = ?,
        mrn = ?,
        gender = ?,
        email = ?,
        signature = ?,
        contact_before = ?,
        consent_agreed = ?,
        patient_use_agreed = ?,
        consent_decision = ?,
        created_at = CURRENT_TIMESTAMP
      WHERE user_id = ?
    ");

    if ($st === false) {
      error_log("Error preparing UPDATE statement: " . $con->error, 3, "/opt/lampp/htdocs/lama/db_connection.log");
      return false;
    }

    $st->bind_param(
      "sssssssis si",
      $patient_name,
      $dob,
      $mrn,
      $gender,
      $email,
      $signature,
      $contact_before,
      $consent_agreed,
      $patient_use_agreed,
      $consent_decision,
      $userId
    );

  } else {
    // Insert new record
    $st = $con->prepare("
      INSERT INTO users_consent (
        user_id,
        patient_name,
        dob,
        mrn,
        gender,
        email,
        signature,
        contact_before,
        consent_agreed,
        patient_use_agreed,
        consent_decision
      ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if ($st === false) {
      error_log("Error preparing INSERT statement: " . $con->error, 3, "/opt/lampp/htdocs/lama/db_connection.log");
      return false;
    }

    $st->bind_param(
      "issssssisss",
      $userId,
      $patient_name,
      $dob,
      $mrn,
      $gender,
      $email,
      $signature,
      $contact_before,
      $consent_agreed,
      $patient_use_agreed,
      $consent_decision
    );
  }

  if (!$st->execute()) {
    error_log("Error executing statement: " . $st->error, 3, "/opt/lampp/htdocs/lama/db_connection.log");
    return false;
  }

  error_log("Consent form inserted/updated for user ID: " . $userId, 3, "/opt/lampp/htdocs/lama/db_connection.log");

  return true;
}
function saveConsentForm($userId, $form) {
    global $con;

    // Check if a record already exists for the user
    $existingForm = getConsentForm($userId);

    if ($existingForm) {
        // Update existing record
        $stmt = $con->prepare("
            UPDATE users_consent
            SET
                patient_name = ?, dob = ?, mrn = ?, gender = ?, email = ?, signature = ?,
                contact_before = ?, patient_use_agreed = ?, consent_decision = ?, consent_agreed = ?, updated_at = CURRENT_TIMESTAMP
            WHERE user_id = ?
        ");

        if ($stmt === false) {
            error_log("Error preparing UPDATE statement: " . $con->error, 3, "/opt/lampp/htdocs/lama/db_connection.log");
            return false;
        }

        $stmt->bind_param(
            "ssssssssiii",
            $form->patient_name,
            $form->dob,
            $form->mrn,
            $form->gender,
            $form->email,
            $form->signature,
            $form->contact_before,
            $form->patient_use_agreed,
            $form->consent_decision,
            $form->consent_agreed,
            $userId
        );

    } else {
        // Insert new record
        $stmt = $con->prepare("
            INSERT INTO users_consent (
                user_id, patient_name, dob, mrn, gender, email, signature,
                contact_before, patient_use_agreed, consent_decision, consent_agreed
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        if ($stmt === false) {
            error_log("Error preparing INSERT statement: " . $con->error, 3, "/opt/lampp/htdocs/lama/db_connection.log");
            return false;
        }

        $stmt->bind_param(
            "isssssssssi",
            $userId,
            $form->patient_name,
            $form->dob,
            $form->mrn,
            $form->gender,
            $form->email,
            $form->signature,
            $form->contact_before,
            $form->patient_use_agreed,
            $form->consent_decision,
            $form->consent_agreed
        );
    }

    if (!$stmt->execute()) {
        error_log("Error executing statement: " . $stmt->error, 3, "/opt/lampp/htdocs/lama/db_connection.log");
        return false;
    }

    error_log("Consent form inserted/updated for user ID: " . $userId, 3, "/opt/lampp/htdocs/lama/db_connection.log");
    return true;
}

function getConsentForm($userId) {
    global $con;

    $stmt = $con->prepare("SELECT * FROM users_consent WHERE user_id = ?");
    if ($stmt === false) {
        error_log("Error preparing SELECT statement: " . $con->error, 3, "/opt/lampp/htdocs/lama/db_connection.log");
        return false;
    }

    $stmt->bind_param("i", $userId);

    if (!$stmt->execute()) {
        error_log("Error executing SELECT statement: " . $stmt->error, 3, "/opt/lampp/htdocs/lama/db_connection.log");
        return false;
    }

    $result = $stmt->get_result();
    return $result->num_rows > 0 ? $result->fetch_assoc() : null;
}
?>