<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

function handleFileUpload($file, $allowedTypes) {
  $response = [
    "success" => false,
    "error" => "",
    "fileContent" => null,
    "fileName" => "",
    "fileType" => ""
  ];

  // Check if the file was uploaded
  if ($file['error'] !== UPLOAD_ERR_OK) {
    $response['error'] = 'File upload error: ' . $file['error'];
    return $response;
  }

  // Validate the file type
  if (!in_array($file['type'], $allowedTypes)) {
    $response['error'] = 'Invalid file type. Allowed types are: ' . implode(", ", $allowedTypes);
    return $response;
  }

  // Optional: Check the file size (e.g., max 10MB)
  if ($file['size'] > 10485760) { // 10MB
    $response['error'] = 'File is too large. Maximum size allowed is 10MB.';
    return $response;
  }

  // Sanitize the file name
  $sanitizedFileName = preg_replace("/[^a-zA-Z0-9\-_\.]/", "", basename($file['name']));

  // Define the directory to store files (Ensure the directory is writable)
  $uploadDirectory = 'uploads/';
  if (!file_exists($uploadDirectory)) {
    mkdir($uploadDirectory, 0777, true); // Ensure the directory exists
  }

  $uploadPath = $uploadDirectory . $sanitizedFileName;

  // Move the uploaded file to the desired directory
  if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
    $response['success'] = true;
    $response['fileName'] = $sanitizedFileName;
    $response['fileType'] = $file['type'];

    // Optionally, read the content of the file for database storage (if required)
    $response['fileContent'] = file_get_contents($uploadPath); // Use this if you want to store the content
  } else {
    $response['error'] = 'Failed to move uploaded file.';
  }

  return $response;
}
?>