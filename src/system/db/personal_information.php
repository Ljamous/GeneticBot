<?php
require_once 'mycon.php';

function insertPersonalInfo($pid, $mrn, $name, $dob, $gender, $ms, $ancestry, $userId) {
  global $con;

  // Prepare the query to check if the user already has a record in the personal_infos table
  $check_query = "SELECT id FROM personal_infos WHERE userId = ?";
  $st_check = $con->prepare($check_query);

  if ($st_check === false) {
    error_log("Error preparing check query: " . $con->error);
    return false;
  }

  $st_check->bind_param("i", $userId);
  $st_check->execute();
  $st_check->store_result();

  // If a record exists, update it; otherwise, insert a new record
  if ($st_check->num_rows > 0) {
    // Record exists, update the existing record
    $update_query = "UPDATE personal_infos SET pid = ?, mrn = ?, name = ?, dob = ?, gender = ?, ms = ?, ancestry = ? WHERE userId = ?";
    $st_update = $con->prepare($update_query);

    if ($st_update === false) {
      error_log("Error preparing update statement: " . $con->error);
      return false;
    }

    $st_update->bind_param("sssssssi", $pid, $mrn, $name, $dob, $gender, $ms, $ancestry, $userId);
    if (!$st_update->execute()) {
      error_log("Error executing update statement: " . $st_update->error);
      return false;
    }

    error_log("Personal info updated for user: $userId");
    return true;
  } else {
    // No record exists for this user, so insert a new record
    $insert_query = "INSERT INTO personal_infos (pid, mrn, name, dob, gender, ms, ancestry, userId) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $st_insert = $con->prepare($insert_query);

    if ($st_insert === false) {
      error_log("Error preparing insert statement: " . $con->error);
      return false;
    }

    // Note the "d" for dob, indicating it's a string (varchar)
    $st_insert->bind_param("ssssssss", $pid, $mrn, $name, $dob, $gender, $ms, $ancestry, $userId);
    if (!$st_insert->execute()) {
      error_log("Error executing insert statement: " . $st_insert->error);
      return false;
    }

    error_log("Personal info inserted for user: $userId");
    return true;
  }
}

// Function to retrieve personal information based on userId
function getPersonalInfo($userId) {
  global $con;

  $query = "SELECT pid, mrn, name, dob, gender, ms, ancestry FROM personal_infos WHERE userId = ?";
  $stmt = $con->prepare($query);

  if ($stmt === false) {
    error_log("Error preparing select statement: " . $con->error);
    return false;
  }

  $stmt->bind_param("i", $userId);
  $stmt->execute();
  $result = $stmt->get_result();

  if ($result->num_rows > 0) {
    return $result->fetch_assoc();
  } else {
    return false;
  }
}
?>
