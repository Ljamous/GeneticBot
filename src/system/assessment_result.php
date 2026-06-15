<?php
session_start();

include './header.php';

// Ensure the user is logged in
if (!isset($_SESSION["userid"])) {
    header("Location: login.php");
    exit();
}

// Fetch user ID from the session
$userId = $_SESSION["userid"];

// Database connection
require_once './db/assessment.php';

// Check if the user has completed the assessment
$completed = hasUserCompletedAssessment($userId);

// If not completed, redirect to the assessment page
if (!$completed) {
    header("Location: assessment.php");
    exit();
}

// Fetch the user's answers and calculate the score
$correctAnswers = [
    1 => 2,
    2 => 2,
    3 => 2,
    4 => 2,
    5 => 2,
    6 => 2,
    7 => 3,
    8 => 2,
    9 => 3,
    10 => 2,
    11 => 1,
    12 => 2,
    13 => 3,
    14 => 2,
    15 => 2
];

// Fetch user's answers
$existingAnswers = getPatientAssessment($userId);

// Calculate score
$score = 0;
foreach ($existingAnswers as $questionNum => $selectedOption) {
    $questionIndex = substr($questionNum, 1); // Remove 'q' prefix
    if ((int)$selectedOption === $correctAnswers[$questionIndex]) {
        $score++;
    }
}

?>

<div class="content-wrapper">
    <section class="content">
        <div class="card card-info mx-auto" style="width:95%;">
            <div class="card-header" style="background-color: #2D4B69;">
                <h3 class="card-title">Assessment Result</h3>
            </div>
            <div class="card-body">
                <h4>Your Score: <?= $score ?> out of 15</h4>
                <br>
                <p>Thank you for completing the assessment. Your score has been recorded.</p>
                <a href="chat_embed.php" class="btn btn-info float-right">GeneticBot</a>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-secondary float-left"
                    onclick="window.location.href='consent_form.php'">Back</button>
                <button type="button" class="btn btn-info float-right" id="btnNext"
                    onclick="window.location.href='home.php'">Home</button>
            </div>
        </div>
    </section>
</div>

<?php include './footer.php'; ?>