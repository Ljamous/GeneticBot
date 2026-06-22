<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

session_start();

// Check if user is logged in
if (!isset($_SESSION["userid"])) {
    die("Unauthorized access.");
}

$userId = $_SESSION["userid"];

// Include DB connection
include './db/mycon.php';

// Fetch user personal info
$stmtInfo = $con->prepare("SELECT name, dob FROM personal_infos WHERE userId = ?");
$stmtInfo->bind_param("i", $userId);
$stmtInfo->execute();
$resultInfo = $stmtInfo->get_result();

if ($resultInfo->num_rows === 0) {
    die("User personal info not found.");
}

$infoRow = $resultInfo->fetch_assoc();
$name = $infoRow['name'];
$dob = $infoRow['dob'];

// Calculate age from DOB (assuming format YYYY-MM-DD)
$age = "Unknown";
if ($dob && preg_match('/^\d{4}-\d{2}-\d{2}$/', $dob)) {
    $birthDate = new DateTime($dob);
    $today = new DateTime();
    $ageInterval = $today->diff($birthDate);
    $age = $ageInterval->y;
}

// Fetch report from database
$stmt = $con->prepare("SELECT report_text FROM analysis_reports WHERE userId = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("No report found for this user.");
}

$row = $result->fetch_assoc();
$reportJson = $row['report_text'];
$reportData = json_decode($reportJson, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    die("Failed to decode report JSON.");
}

// Function to add section text recursively with indentation
function addSectionText($data, $level = 0) {
    $lines = [];
    $indent = str_repeat("    ", $level);

    if (is_array($data)) {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $lines[] = "{$indent}{$key}:";
                $lines = array_merge($lines, addSectionText($value, $level + 1));
            } else {
                $lines[] = "{$indent}{$key}: $value";
            }
        }
    } else {
        $lines[] = "{$indent}{$data}";
    }

    return $lines;
}

// Start building the text content
$lines = [];

$lines[] = "===============================";
$lines[] = "       MEDICAL REPORT";
$lines[] = "===============================\n";

$lines[] = "Patient Name: $name";
$lines[] = "Date of Birth: $dob (Age: $age)\n";

$eligibility = isset($reportData['eligible_for_NCCN']) && $reportData['eligible_for_NCCN'] ? 'Eligible for NCCN Genetic Testing' : 'Not Eligible for NCCN Genetic Testing';
$lines[] = "NCCN Eligibility: $eligibility\n";

if (!empty($reportData["Pathology Report Summary"])) {
    $lines[] = "---- Pathology Report Summary ----";
    $lines = array_merge($lines, addSectionText($reportData["Pathology Report Summary"], 1));
    $lines[] = "";
}

if (!empty($reportData["Pedigree Analysis"]["Generations"])) {
    $lines[] = "---- Pedigree Analysis ----";
    $lines = array_merge($lines, addSectionText($reportData["Pedigree Analysis"]["Generations"], 1));
    $lines[] = "";
}

if (!empty($reportData["NCCN Testing Criteria Assessment"])) {
    $lines[] = "---- NCCN Testing Criteria Assessment ----";
    $lines = array_merge($lines, addSectionText($reportData["NCCN Testing Criteria Assessment"], 1));
    $lines[] = "";
}

if (!empty($reportData["Conclusion"])) {
    $lines[] = "---- Conclusion ----";
    $lines = array_merge($lines, addSectionText($reportData["Conclusion"], 1));
    $lines[] = "";
}

// Combine all lines to single string with newlines
$reportText = implode("\n", $lines);

// Sanitize filename to avoid issues
$safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $name);
$filename = "Medical_Report_{$safeName}.txt";

// Send headers and output the file for download
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=\"$filename\"");
header("Content-Type: text/plain; charset=utf-8");
header("Content-Length: " . strlen($reportText));
header("Cache-Control: no-cache, must-revalidate");
header("Expires: 0");

echo $reportText;
exit;
