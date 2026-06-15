<?php
// Prevent any output before headers
if (ob_get_length()) ob_end_clean();
header_remove();

session_start();
if (!isset($_SESSION["userid"])) {
    http_response_code(403);
    exit("Unauthorized access.");
}

require_once './db/mycon.php';
require_once __DIR__ . '/vendor/autoload.php';

use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;

$userId = $_SESSION["userid"];

// --- Get report JSON from database ---
$stmt = $con->prepare("SELECT report_text FROM analysis_reports WHERE userId = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    exit("No report found.");
}

$row = $result->fetch_assoc();
$reportJson = $row['report_text'];
$reportData = json_decode($reportJson, true);

if (json_last_error() !== JSON_ERROR_NONE) {
    exit("Invalid report data.");
}

// --- Start Word Document ---
$phpWord = new PhpWord();
$section = $phpWord->addSection();
$section->addTitle("Medical Report", 1);
$section->addText("User ID: $userId", ['bold' => true]);
$section->addTextBreak(1);

// --- Helper to add key-value pairs ---
function addKeyValueList($section, $data, $indent = 0) {
    foreach ($data as $key => $value) {
        $prefix = str_repeat('    ', $indent);
        if (is_array($value)) {
            $section->addText("$prefix$key:", ['bold' => true]);
            addKeyValueList($section, $value, $indent + 1);
        } else {
            $section->addText("$prefix$key: $value");
        }
    }
}

// --- Pathology Report Summary ---
if (!empty($reportData['Pathology Report Summary'])) {
    $section->addTitle("Pathology Report Summary", 2);
    addKeyValueList($section, $reportData['Pathology Report Summary']);
    $section->addTextBreak(1);
}

// --- Pedigree Analysis ---
if (!empty($reportData['Pedigree Analysis']['Generations'])) {
    $section->addTitle("Pedigree Analysis", 2);
    foreach ($reportData['Pedigree Analysis']['Generations'] as $generation => $members) {
        $section->addText("$generation:", ['bold' => true]);
        foreach ($members as $line) {
            $section->addText("  - $line");
        }
    }
    $section->addTextBreak(1);
}

// --- NCCN Testing Criteria Assessment ---
if (!empty($reportData['NCCN Testing Criteria Assessment'])) {
    $section->addTitle("NCCN Testing Criteria Assessment", 2);
    addKeyValueList($section, $reportData['NCCN Testing Criteria Assessment']);
    $section->addTextBreak(1);
}

// --- Conclusion ---
if (!empty($reportData['Conclusion'])) {
    $section->addTitle("Conclusion", 2);
    addKeyValueList($section, $reportData['Conclusion']);
    $section->addTextBreak(1);
}

// --- Eligibility Flag ---
if (isset($reportData['eligible_for_NCCN'])) {
    $section->addText("Eligible for NCCN Testing: " . ($reportData['eligible_for_NCCN'] ? 'Yes' : 'No'), ['bold' => true]);
    $section->addTextBreak(1);
}

// --- Output .docx to browser ---
header("Content-Description: File Transfer");
header("Content-Disposition: attachment; filename=Medical_Report_User_{$userId}.docx");
header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
header("Cache-Control: must-revalidate");
header("Pragma: public");
header("Expires: 0");

$objWriter = IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save("php://output");
exit;
