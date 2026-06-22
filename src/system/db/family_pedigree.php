<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */


function saveFamilyPedigree(
  $userId,
  $filename,
  $filetype,
  $filesize,
  $imgContent,
  $csvData,
  $con
) {
  error_log("Saving family pedigree for userId: $userId");

  $sql = "INSERT INTO family_pedigree (
              userId, filename, filetype, filesize, img, csv
          ) VALUES (?, ?, ?, ?, ?, ?)
          ON DUPLICATE KEY UPDATE
              filename = VALUES(filename),
              filetype = VALUES(filetype),
              filesize = VALUES(filesize),
              img = VALUES(img),
              csv = VALUES(csv)";

  $stmt = $con->prepare($sql);
  if (!$stmt) {
    error_log("Error preparing statement: " . $con->error);
    return false;
  }

  $stmt->bind_param(
    "ississ",
    $userId,
    $filename,
    $filetype,
    $filesize,
    $imgContent,
    $csvData
  );

  if (!$stmt->execute()) {
    error_log("Error executing pedigree statement: " . $stmt->error);
    $stmt->close();
    return false;
  }

  $stmt->close();
  error_log("Family pedigree saved successfully for userId: $userId");
  return true;
}

function getFamilyPedigreeByUserId($userId, $con) {
  error_log("Fetching family pedigree for userId: $userId");

  $sql = "SELECT filename, filetype, filesize, img, csv
          FROM family_pedigree
          WHERE userId = ?";

  $stmt = $con->prepare($sql);
  if (!$stmt) {
    error_log("Error preparing fetch statement: " . $con->error);
    return false;
  }

  $stmt->bind_param("i", $userId);
  if (!$stmt->execute()) {
    error_log("Error executing fetch statement: " . $stmt->error);
    $stmt->close();
    return false;
  }

  $result = $stmt->get_result();
  $data = $result->fetch_assoc();
  if (!$data || $data['img'] === null) {
    error_log("No pedigree image found for userId: $userId");
    return false;
  }

  $stmt->close();

  error_log("Family pedigree fetched successfully for userId: $userId");
  return $data;
}

?>