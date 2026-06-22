<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

// download.php
if (isset($_GET['userId']) && !empty($_GET['userId'])) {
  $userId = (int)$_GET['userId'];

  // Include your database connection and fetching logic
  require_once './db/mycon.php';
  require_once './db/clinical_history.php';

  // Fetch file content from the database
  $clinicalHistoryData = getClinicalHistoryByUserId($userId,$con);

  if ($clinicalHistoryData && !empty($clinicalHistoryData['histologyReportContent'])) {
    $fileContent = $clinicalHistoryData['histologyReportContent'];
    $fileName = $clinicalHistoryData['histologyReportName'];
    $fileType = $clinicalHistoryData['histologyReportType'];

    // Set headers for the download
    header('Content-Type: ' . $fileType);
    header('Content-Disposition: attachment; filename="' . $fileName . '"');
    header('Content-Length: ' . strlen($fileContent));

    // Output the file content
    echo $fileContent;
    exit();
  } else {
    echo 'File not found.';
  }
} else {
  echo 'Invalid user ID.';
}
?>
