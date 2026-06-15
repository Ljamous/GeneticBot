<?php

function saveClinicalHistory(
  $typeOfCancerAndAgeOfDiagnosis,
  $otherTypesOfCancer,
  $singleBreastBilateral,
  $histologyReportContent,
  $histologyReportName,
  $histologyReportType,
  $userId,
  $con
) {
  // Log the start of the save process
  error_log("Saving clinical history for userId: $userId");

  // SQL query for insert or update
  $sql = "INSERT INTO clinical_histories (
              userId, typeOfCancerAndAgeOfDiagnosis, otherTypesOfCancer, 
              singleBreastBilateral, histologyReportContent, 
              histologyReportName, histologyReportType
          ) VALUES (?, ?, ?, ?, ?, ?, ?)
          ON DUPLICATE KEY UPDATE
              typeOfCancerAndAgeOfDiagnosis = VALUES(typeOfCancerAndAgeOfDiagnosis),
              otherTypesOfCancer = VALUES(otherTypesOfCancer),
              singleBreastBilateral = VALUES(singleBreastBilateral),
              histologyReportContent = VALUES(histologyReportContent),
              histologyReportName = VALUES(histologyReportName),
              histologyReportType = VALUES(histologyReportType)";

  // Prepare the statement
  $stmt = $con->prepare($sql);
  if (!$stmt) {
    error_log("Error preparing statement: " . $con->error);
    return false;
  }

  // Log the SQL statement
  error_log("Prepared SQL statement: $sql");

  // Bind parameters
  $stmt->bind_param(
    "issssss",
    $userId,
    $typeOfCancerAndAgeOfDiagnosis,
    $otherTypesOfCancer,
    $singleBreastBilateral,
    $histologyReportContent,
    $histologyReportName,
    $histologyReportType
  );

  // Execute and handle result
  if (!$stmt->execute()) {
    error_log("Error executing statement: " . $stmt->error);
    $stmt->close();
    return false;
  }

  // Success
  error_log("Clinical history saved successfully for userId: $userId");
  $stmt->close();
  return true;
}

function getClinicalHistoryByUserId($userId, $con) {
  error_log("Fetching clinical history for userId: $userId");

  $sql = "SELECT typeOfCancerAndAgeOfDiagnosis, otherTypesOfCancer, 
                 singleBreastBilateral, histologyReportContent, 
                 histologyReportName, histologyReportType
          FROM clinical_histories
          WHERE userId = ?";

  $stmt = $con->prepare($sql);
  if (!$stmt) {
    error_log("Error preparing statement: " . $con->error);
    return false;
  }

  // Log the SQL statement
  error_log("Prepared SQL statement: $sql");

  // Bind and execute
  $stmt->bind_param("i", $userId);
  if (!$stmt->execute()) {
    error_log("Error executing statement: " . $stmt->error);
    $stmt->close();
    return false;
  }

  // Fetch and return data
  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  $stmt->close();

  error_log("Clinical history data fetched successfully for userId: $userId");

  return $data;
}

?>
