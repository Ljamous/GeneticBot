<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

// insert_family.php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit();
}

// Always log incoming data for debugging
$logFile = __DIR__ . '/insert_family.log';
$requestData = file_get_contents('php://input');  // Read raw request body
$data = json_decode($requestData, true);

error_log("Request Data: " . $requestData . PHP_EOL, 3, $logFile);


require_once 'mycon.php';

// Function to insert family pedigree data into the database
function insertFamilyPedigree($csvData, $imgData, $userId, $con)
{ //Take note, add it as a parameter
  global $con, $logFile;

  // 1. Check for existing record for the user
  $stmtCheck = $con->prepare("SELECT id FROM family_pedigree WHERE userId = ?");
  $stmtCheck->bind_param("i", $userId);
  $stmtCheck->execute();
  $stmtCheck->store_result(); // Important for num_rows to work correctly

  $csvContent = $csvData['content']; // Directly get the content
  $imgContent = $imgData['content']; // Directly get the content

  if ($stmtCheck->num_rows > 0) {
    $stmtUpdate = $con->prepare("UPDATE family_pedigree SET csv = ?, img = ? WHERE userId = ?");
    $stmtUpdate->bind_param("ssi", $csvContent, $imgContent, $userId);

    if ($stmtUpdate->execute()) {
      error_log("Pedigree updated for userId: $userId" . PHP_EOL, 3, $logFile);
      return true;
    } else {
      error_log("Update error: " . $stmtUpdate->error . PHP_EOL, 3, $logFile);
      return false;
    }
  } else {
    $stmtInsert = $con->prepare("INSERT INTO family_pedigree (csv, img, userId) VALUES (?, ?, ?)");
    $stmtInsert->bind_param("ssi", $csvContent, $imgContent, $userId);

    if ($stmtInsert->execute()) {
      error_log("Pedigree inserted for userId: $userId" . PHP_EOL, 3, $logFile);
      return true;
    } else {
      error_log("Insert error: " . $stmtInsert->error . PHP_EOL, 3, $logFile);
      return false;
    }
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $requestData = file_get_contents('php://input');
  $data = json_decode($requestData, true);

  if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    error_log("JSON Error: " . json_last_error_msg() . PHP_EOL, 3, $logFile);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
    exit();
  }


  $userId = $data['userId'] ?? null;
  if (!$userId) {
    error_log("Missing userId" . PHP_EOL, 3, $logFile);
    echo json_encode(['success' => false, 'message' => 'Missing userId']);
    exit();
  }

  $csvData = $data['csv'] ?? null;
  $imgData = $data['img'] ?? null;


  if (!$csvData || !$imgData) {  // Check for null values as well
    error_log("Missing data for userId: $userId" . PHP_EOL, 3, $logFile); // More specific error logging
    echo json_encode(['success' => false, 'message' => 'Missing CSV or image data']);
    exit();
  }


  $result = insertFamilyPedigree($csvData, $imgData, $userId, $con);
  if ($result) {
    echo json_encode(['success' => true, 'message' => 'Upload successful']);
  } else {
    echo json_encode(['success' => false, 'message' => 'Upload failed']); // More informative error message
  }
} else {
  echo json_encode(['success' => false, 'message' => 'Invalid request method']);

}


?>