<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['userid'])) {
    echo json_encode(['status' => 'error', 'message' => 'User not logged in.']);
    exit();
}

if (!isset($_POST['eligible'])) {
    echo json_encode(['status' => 'error', 'message' => 'Eligibility flag not provided.']);
    exit();
}

$eligibleFlag = filter_var($_POST['eligible'], FILTER_VALIDATE_BOOLEAN);
$_SESSION['isEligible'] = $eligibleFlag;

$userId = $_SESSION['userid'];

require_once './db/mycon.php'; // assumes $con = new mysqli(...)

try {
    $stmt = $con->prepare("UPDATE users SET is_eligible = ? WHERE id = ?");
    $stmt->bind_param("ii", $eligibleFlag, $userId);
    $stmt->execute();

    if ($stmt->affected_rows >= 0) {
        echo json_encode(['status' => 'success', 'eligible' => $eligibleFlag]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Database update failed.']);
    }

    $stmt->close();
    $con->close();
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
}