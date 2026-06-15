<?php
// assessment.php
// --------------------------------------------------------
// Start output buffering and session/auth in header
include __DIR__ . '/header.php';
require_once __DIR__ . '/db/assessment.php';

// User ID from session (header.php already enforces login)
$userId = (int)$_SESSION['userid'];

// Correct answers map
$correctAnswers = [
    1 => 2, 2 => 2, 3 => 2, 4 => 2, 5 => 2,
    6 => 2, 7 => 3, 8 => 2, 9 => 3, 10 => 2,
   11 => 1, 12 => 2, 13 => 3, 14 => 2, 15 => 2
];

$errorMessage = '';

// Check attempt count and completion
$attemptCount = getUserAssessmentAttemptCount($userId);
$completed    = hasUserCompletedAssessment($userId);

if ($completed) {
    header('Location: assessment_result.php');
    exit();
}

if ($attemptCount >= 3) {
    echo '<script>alert("You have reached the maximum number of attempts.");'
       . 'window.location.href = "assessment_result.php";</script>';
    exit();
}

// Handle POST submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['btn_finish'])) {
    // Validate that all questions answered
    $answers = [];
    for ($i = 1; $i <= 15; $i++) {
        if (empty($_POST["q$i"])) {
            $errorMessage = 'Please answer all 15 questions before submitting.';
            break;
        }
        $answers["q$i"] = (int)$_POST["q$i"];
    }

    if ($errorMessage === '') {
        // Increment attempt
        updateUserAssessmentAttempt($userId);

        // Insert into database
        $params = array_values($answers);
        $params[] = $userId;
        if (insertPatientAssessment(...$params)) {
            // Mark completed and calculate score
            markAssessmentAsCompleted($userId);
            $score = 0;
            foreach ($answers as $qKey => $sel) {
                $num = (int)substr($qKey, 1);
                if ($sel === $correctAnswers[$num]) {
                    $score++;
                }
            }
            $_SESSION['assessment_score'] = $score;
            header('Location: assessment_result.php');
            exit();
        } else {
            $errorMessage = 'There was an error saving your answers. Please try again.';
        }
    }
}

// Fetch existing answers for pre-fill
$existing = getPatientAssessment($userId) ?: [];

// Questions array
$questions = [
    1 => 'What is the primary purpose of genetic testing?',
    2 => 'Identifying gene mutation(s) in a family helps other blood relatives to:',
    3 => 'A negative result in genetic testing means:',
    4 => 'Which of the following is a potential risk of genetic testing?',
    5 => 'Genetic testing can influence:',
    6 => 'The complexity of genetic information highlights the importance of:',
    7 => 'What does a Variant of Uncertain Significance (VUS) indicate?',
    8 => 'Incidental Findings from genetic testing might:',
    9 => 'What is autosomal dominant inheritance?',
   10 => 'How are mutations detected in genetic testing?',
   11 => 'A positive genetic test result means:',
   12 => 'If you test negative for a known mutation in your family, you:',
   13 => 'What does an uncertain result in genetic testing imply?',
   14 => 'Why is clear communication important in the context of genetic testing?',
   15 => 'Engaging in genetic counseling is crucial to:'
];

// Options array
$options = [
    1 => ['To predict future diseases','To make more informed healthcare decisions','To change one’s genetic makeup','To find a cure for all hereditary diseases'],
    2 => ['Ignore their health screenings','Determine if they share the same hereditary cancer risks','Avoid talking to a healthcare professional','Guarantee they will not develop cancer'],
    3 => ['You are immune to cancer','You cannot pass the tested mutation to your children','You no longer need medical check-ups','All of the genes in your body are healthy'],
    4 => ['Increased physical fitness','Emotional distress from the results','Immediate cure of detected conditions','Enhanced memory skills'],
    5 => ['Only your past medical history','Your lifestyle choices and preventive measures','The genetic makeup of your ancestors','Your ability to learn new languages'],
    6 => ['Avoiding all medical advice','Clear communication between healthcare providers and individuals','Keeping the results secret from your family','Relying solely on online research for decisions'],
    7 => ['A definite increase in cancer risk','A clear path to treatment','The genetic change’s impact on cancer risk is unknown','That further genetic testing is unnecessary'],
    8 => ['Have no health implications','Provide additional information not related to the initial test purpose','Decrease the need for further investigation','Only relate to hereditary cancers'],
    9 => ['A condition where both parents must carry a mutated gene','A trait passed only through the maternal line','Needing only one copy of a mutated gene from one parent to inherit a condition','A trait that skips generations'],
   10 => ['By guessing based on family history','Sequencing to read the exact order of DNA','Using standard blood tests only','Through physical examinations'],
   11 => ['A mutation associated with increased risk for hereditary cancer was identified','No mutations were found','The results are inconclusive','You are healthy and have no genetic risks'],
   12 => ['Have a higher genetic risk than the general population','May have the same genetic risks as others in the general population','Will develop the disease later in life','Have a 100% chance of passing the mutation to your children'],
   13 => ['The genetic change is definitively linked to cancer risk','No further testing is required','It is not known if the change is linked to cancer risk','You are free from any hereditary cancer risk'],
   14 => ['It has no real importance','It ensures patients fully understand their risks and options','It is only necessary for doctors to understand','It makes genetic testing faster'],
   15 => ['Ignore the genetic testing results','Make choices aligned with your values and preferences','Avoid discussing your health with professionals','Ensure you never have to deal with health issues']
];
?>

<div class="content-wrapper">
  <section class="content-header"></section>
  <section class="content">
    <div class="card card-info mx-auto" style="width:95%;">
      <div class="card-header" style="background-color:#2D4B69;">
        <h3 class="card-title">Patient Assessment</h3>
      </div>
      <form method="post" action="<?= htmlspecialchars($_SERVER['PHP_SELF']) ?>" class="form-horizontal">
        <div class="card-body">
          <?php if ($errorMessage): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
          <?php endif; ?>
          <p>Please take a moment to complete these 15 multiple-choice questions designed to assess your understanding of genetic testing, including its benefits, risks, limitations, and uncertainties. These questions are based on the information shared during the educational session you attended. Your insights are incredibly important to us and will greatly aid our efforts. Once you have answered all the questions, you will receive a final score out of 15. We appreciate your time and participation in this process. Thank you.</p>
          <hr>
          <?php foreach ($questions as $num => $text): ?>
            <?php $prefill = $existing["q$num"] ?? null; ?>
            <p><strong><?= $num ?>. <?= htmlspecialchars($text) ?></strong></p>
            <?php foreach ($options[$num] as $idx => $lbl): ?>
              <div class="form-check">
                <input class="form-check-input" type="radio"
                       name="q<?= $num ?>" id="q<?= $num ?>o<?= $idx+1 ?>"
                       value="<?= $idx+1 ?>" <?= ($prefill == $idx+1) ? 'checked' : '' ?>>
                <label class="form-check-label" for="q<?= $num ?>o<?= $idx+1 ?>">
                  <?= htmlspecialchars($lbl) ?>
                </label>
              </div>
            <?php endforeach; ?>
            <hr>
          <?php endforeach; ?>
        </div>
        <div class="card-footer">
          <button type="button" class="btn btn-secondary" onclick="window.location.href='consent_form.php'">Back</button>
          <button type="submit" name="btn_finish" id="btn_finish" class="btn btn-info float-right">Next</button>
        </div>
      </form>
    </div>
  </section>
</div>

<?php include __DIR__ . '/footer.php'; ?>
