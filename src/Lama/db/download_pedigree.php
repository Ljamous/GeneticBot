<?php
// download_pedigree.php

session_start();

// Ensure the user is logged in
if (!isset($_SESSION['userid'])) {
    echo 'Access denied.';
    exit();
}

$userId = (int)$_SESSION['userid']; // Get user ID directly from session

// Include DB connection
require_once './db/mycon.php';

// Query the family_pedigree table for the logged-in user
$stmt = $con->prepare("SELECT img, filename, filetype, filesize FROM family_pedigree WHERE userId = ?");
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows === 1) {
    $stmt->bind_result($imgData, $fileName, $fileType, $fileSize);
    $stmt->fetch();

    if (!empty($imgData)) {
        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Pragma: public');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Content-Type: ' . $fileType);
        header('Content-Disposition: attachment; filename="' . $fileName . '"');
        header('Content-Length: ' . $fileSize);

        echo $imgData;
        exit();
    } else {
        echo 'No file found for this user.';
    }
} else {
    echo 'No data found.';
}

$stmt->close();
$con->close();
?>