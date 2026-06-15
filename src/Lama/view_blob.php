<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
if (!isset($_SESSION["userid"])) {
  header("Location: login.php");
  exit();
}

$userId = (int)$_SESSION["userid"];

require_once './db/clinical_history.php';

$clinicalHistoryData = getClinicalHistoryByUserId($userId);

if (!$clinicalHistoryData || empty($clinicalHistoryData['histologyReportContent'])) {
  header("HTTP/1.0 404 Not Found");
  echo "File not found.";
  exit;
}

$fileContent = $clinicalHistoryData['histologyReportContent'];
$fileName = $clinicalHistoryData['histologyReportName'];
$mimeType = $clinicalHistoryData['histologyReportType'];

// Set headers
header("Content-type: " . $mimeType);
header('Content-Disposition: inline; filename="' . $fileName . '"');
header('Content-Length: ' . strlen($fileContent));

// Output the file content
echo $fileContent;
exit;
