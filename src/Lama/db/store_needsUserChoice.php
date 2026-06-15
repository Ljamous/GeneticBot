<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the value from POST (should be 'true' or 'false' as string)
    $value = filter_input(INPUT_POST, 'needsUserChoice', FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    
    if ($value !== null) {
        $_SESSION['needsUserChoice'] = $value;
        echo json_encode(['status' => 'success', 'needsUserChoice' => $value]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid value']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
