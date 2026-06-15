<?php
require_once 'mycon.php';
function insertPatientAssessment1($q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $q10, $q11, $q12, $q13, $q14, $q15, $userId) {

// function insertPatientAssessment($q1, $q2, $q3, $q4,$q5,$q6,$q7,$q8,$q9,$q10,$q11,$q12,$q13,$q14,$q15, $userId){
    global $con;

    // Prepare the SQL statement
    $st = $con->prepare("insert into patient_assessments(q1,q2,q3,q4,q5,q6,q7,q8,q9,q10,q11,q12,q13,q14,q15,userId) values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)");

    if ($st === false) {
        error_log("Error preparing statement: " . $con->error, 3, "/opt/lampp/htdocs/lama/db_connection.log");
        return false;
    }

    // Bind parameters
    // $st->bind_param("sssssssssssssssi", $q1, $q2, $q3, $q4,$q5,$q6,$q7,$q8,$q9,$q10,$q11,$q12,$q13,$q14,$q15, $userId);
$st->bind_param("sssssssssssssssi", 
    $q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $q10, 
    $q11, $q12, $q13, $q14, $q15, $userId
);
    // Execute the statement
    if (!$st->execute()) {
        error_log("Error executing statement: " . $st->error, 3, "/opt/lampp/htdocs/lama/db_connection.log");
        return false;
    }

    // Log successful signup
    error_log("assessment inserted", 3, "/opt/lampp/htdocs/lama/db_connection.log");


    return true;
}

function insertPatientAssessment(
    $q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $q10, 
    $q11, $q12, $q13, $q14, $q15, $userId
) {
    global $con;

    // Check if user already has a record
    $stmt = $con->prepare("SELECT id FROM patient_assessments WHERE userId = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Update existing record
        $stmt = $con->prepare("UPDATE patient_assessments SET q1=?, q2=?, q3=?, q4=?, q5=?, q6=?, q7=?, q8=?, q9=?, q10=?, q11=?, q12=?, q13=?, q14=?, q15=? WHERE userId=?");
        // $stmt->bind_param("ssssssssssssssi", $q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $q10, $q11, $q12, $q13, $q14, $q15, $userId);
        $stmt->bind_param("sssssssssssssssi", 
    $q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $q10, 
    $q11, $q12, $q13, $q14, $q15, $userId
);
    } else {
        // Insert new record
        $stmt = $con->prepare("INSERT INTO patient_assessments (q1, q2, q3, q4, q5, q6, q7, q8, q9, q10, q11, q12, q13, q14, q15, userId) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        // $stmt->bind_param("ssssssssssssssi", $q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $q10, $q11, $q12, $q13, $q14, $q15, $userId);
                $stmt->bind_param("sssssssssssssssi", 
    $q1, $q2, $q3, $q4, $q5, $q6, $q7, $q8, $q9, $q10, 
    $q11, $q12, $q13, $q14, $q15, $userId
);
    }

    return $stmt->execute();
}

function getPatientAssessment($userId) {
    global $con; // Assuming $con is your database connection
    
    $stmt = $con->prepare("SELECT q1, q2, q3, q4, q5, q6, q7, q8, q9, q10, q11, q12, q13, q14, q15 FROM patient_assessments WHERE userId = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    
    return $result->fetch_assoc(); // Returns array of answers or false if none found
}

function getUserAssessmentAttemptCount1($userId) {
    global $con;
    $stmt = $con->prepare("SELECT attempt_count FROM assessment_attempts WHERE user_id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    return $row ? $row['attempt_count'] : 0;
}

function getUserAssessmentAttemptCount($userId) {
    global $con; // This must be a valid MySQLi connection

    $stmt = $con->prepare("SELECT attempt_count FROM assessment_attempts WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($attempt_count);

    if ($stmt->fetch()) {
        $stmt->close();
        return (int)$attempt_count;
    } else {
        $stmt->close();
        return 0;
    }
}


function hasUserCompletedAssessment1($userId) {
    global $con;
    $stmt = $con->prepare("SELECT completed FROM assessment_attempts WHERE user_id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch();
    return $row ? $row['completed'] : false;
}

function hasUserCompletedAssessment($userId) {
    global $con; // $con must be a MySQLi connection

    $stmt = $con->prepare("SELECT completed FROM assessment_attempts WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($completed);

    if ($stmt->fetch()) {
        $stmt->close();
        return (bool)$completed;
    } else {
        $stmt->close();
        return false;
    }
}


function updateUserAssessmentAttempt($userId) {
    global $con;
    $stmt = $con->prepare("INSERT INTO assessment_attempts (user_id, attempt_count)
                           VALUES (?, 1)
                           ON DUPLICATE KEY UPDATE attempt_count = attempt_count + 1, last_accessed = NOW()");
    $stmt->execute([$userId]);
}

function markAssessmentAsCompleted($userId) {
    global $con;
    $stmt = $con->prepare("UPDATE assessment_attempts SET completed = TRUE WHERE user_id = ?");
    $stmt->execute([$userId]);
}