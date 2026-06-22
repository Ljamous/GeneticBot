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
header('Content-Type: application/json'); // Specify that the server returns JSON

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
function insertFamilyPedigree($csvContent, $imgContent, $userId, $con) { //Take note, add it as a parameter
  global $con;


  // Prepare the SQL statement
  $st = $con->prepare("INSERT INTO family_pedigree (csv, img, userId) VALUES (?, ?, ?)");

  if ($st === false) {
    error_log("Error preparing statement: " . $con->error . PHP_EOL, 3, $logFile);
    return false;
  }

  // Bind parameters (CSV as string and Image as binary blob)
  $st->bind_param("ssi", $csvContent, $imgContent, $userId); // All string, CSV image data should be encoded to Base64

  // Execute the statement
  if (!$st->execute()) {
    error_log("Error executing statement: " . $st->error . PHP_EOL, 3, $logFile);
    return false;
  }

  // Log success
  error_log("Family pedigree inserted successfully" . PHP_EOL, 3, $logFile);

  return true;
}

// Handle the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the JSON data from the request body
  $data = json_decode(file_get_contents('php://input'), true);

  // Check if JSON decoding was successful
  if ($data === null && json_last_error() !== JSON_ERROR_NONE) {
    error_log("JSON decode error: " . json_last_error_msg() . PHP_EOL, 3, $logFile);
    echo json_encode(['success' => false, 'message' => 'Invalid JSON data']);
    exit();
  }

  // Extract the userId from the POST data
  $userId = $data['userId'] ?? null;

  // Validate if userId is available
  if (!$userId) {
    error_log("User ID is missing" . PHP_EOL, 3, $logFile);
    echo json_encode(['success' => false, 'message' => 'User ID is required']);
    exit();
  }

  // Extract CSV file content (base64 encoded) and image file content (base64 encoded)
  $csvContent = $data['csv'] ?? null;
  $imgContent = $data['img'] ?? null;

  // Validate if both csvContent and imgContent are available
  if (!$csvContent || !$imgContent) {
    error_log("CSV or Image content is missing" . PHP_EOL, 3, $logFile);
    echo json_encode(['success' => false, 'message' => 'CSV and Image content are required']);
    exit();
  }

  // $result = false;
  // Call the insert function to save the content into the database
  $result = insertFamilyPedigree($csvContent, $imgContent, $userId,$con);

  // Return the result as JSON
  if($result){
    echo json_encode(['success' => true, 'message' => "upload success"]);
  }else{
    echo json_encode(['success' => false, 'message' => "upload unsuccess"]);
  }

}else{
  echo json_encode(['success' => false, 'message' => "not POST"]);
}
?>
