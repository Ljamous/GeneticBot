<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */


header("Access-Control-Allow-Origin: *"); // You may want to specify a domain for security
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
  http_response_code(200);
  exit();
}

session_start(); // Ensure no output before this line

require_once 'mycon.php';

function insertFamilyPedigree($csv, $img, $userId) {
  global $con;

  // Prepare the SQL statement
  $st = $con->prepare("INSERT INTO family_pedigree(csv, img, userId) VALUES (?, ?, ?)");

  if ($st === false) {
    error_log("Error preparing statement: " . $con->error, 3, "/opt/lampp/htdocs/lama/db_connection.log");
    return false;
  }

  // Bind parameters and check for errors
  if (!$st->bind_param("sss", $csv, $img, $userId)) {
    error_log("Error binding parameters: " . $st->error, 3, "/opt/lampp/htdocs/lama/db_connection.log");
    return false;
  }

  // Execute the statement
  if (!$st->execute()) {
    error_log("Error executing statement: " . $st->error, 3, "/opt/lampp/htdocs/lama/db_connection.log");
    return false;
  }

  // Log success
  error_log("Personal info inserted", 3, "/opt/lampp/htdocs/lama/db_connection.log");

  return true;
}

// Handle the POST request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  // Get the JSON data from the request body
  $data = json_decode(file_get_contents('php://input'), true);

  // Extract the values from the request
  $csv = $data['csv'] ?? null;
  $img = $data['img'] ?? null;
  $userId = $_SESSION['userid'] ?? null; // Check session for userId

  // Validate the input (you can add more validation if needed)
  if (!$csv || !$img || !$userId) {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
    exit();
  }

  // Call the insert function with the retrieved values
  $result = insertFamilyPedigree($csv, $img, $userId);

  // Return the result as JSON
  echo json_encode(['success' => $result]);
}
?>
