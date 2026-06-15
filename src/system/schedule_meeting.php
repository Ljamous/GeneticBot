<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

ob_start();
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include './header.php';
require_once __DIR__ . '/db/mycon.php';

if (!isset($_SESSION['userid'])) {
    die("User not authenticated.");
}

$userId = $_SESSION['userid'];

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$showError = false;
$errorMessage = '';

$formData = [
    'name' => '',
    'phone' => '',
    'email' => ''
];
$existingTimeSlots = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("Security error: Invalid CSRF token");
    }

    $formData['name'] = trim(filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $formData['phone'] = trim(preg_replace('/\D/', '', $_POST['phone']));
    $rawEmail = trim($_POST['email']);

    if (!$formData['name'] || !$formData['phone'] || strlen($formData['phone']) != 10 || !filter_var($rawEmail, FILTER_VALIDATE_EMAIL)) {
        $showError = true;
        $errorMessage = "Valid name, 10-digit phone number, and email are required.";
    } else {
        $formData['email'] = $rawEmail;
    }

    $timeSlots = [];
    $now = time();

    for ($i = 1; $i <= 3; $i++) {
        $start = $_POST["slot_{$i}_start"] ?? '';
        $end = $_POST["slot_{$i}_end"] ?? '';

        $existingTimeSlots[] = ['start_time' => $start, 'end_time' => $end];

        if ($start && $end) {
            $startTime = strtotime($start);
            $endTime = strtotime($end);

            if ($startTime === false || $endTime === false || $startTime < $now || $endTime < $now) {
                $showError = true;
                $errorMessage = "All time slots must be in the future.";
                break;
            }

            if ($endTime <= $startTime) {
                $showError = true;
                $errorMessage = "End time must be after start time in each slot.";
                break;
            }

            if (($endTime - $startTime) < 3600) {
                $showError = true;
                $errorMessage = "Each time slot must be at least 1 hour long.";
                break;
            }

            $timeSlots[] = ['start' => date('Y-m-d H:i:s', $startTime), 'end' => date('Y-m-d H:i:s', $endTime)];
        }
    }

    if (!$showError && count($timeSlots) < 1) {
        $showError = true;
        $errorMessage = "Please provide at least one valid time slot.";
    }

    if (!$showError) {
        try {
            $stmt = $con->prepare("SELECT id FROM meeting_requests WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $stmt->store_result();

            $con->begin_transaction();
            if ($stmt->num_rows > 0) {
                $stmt->bind_result($meetingRequestId);
                $stmt->fetch();
                $stmt->close();

                $stmtDel = $con->prepare("DELETE FROM time_slots WHERE meeting_request_id = ?");
                $stmtDel->bind_param("i", $meetingRequestId);
                $stmtDel->execute();

                $stmtUpdate = $con->prepare("UPDATE meeting_requests SET name = ?, phone = ?, email = ? WHERE id = ?");
                $stmtUpdate->bind_param("sssi", $formData['name'], $formData['phone'], $formData['email'], $meetingRequestId);
                $stmtUpdate->execute();
            } else {
                $stmt->close();
                $stmtInsertReq = $con->prepare("INSERT INTO meeting_requests (user_id, name, phone, email) VALUES (?, ?, ?, ?)");
                $stmtInsertReq->bind_param("isss", $userId, $formData['name'], $formData['phone'], $formData['email']);
                $stmtInsertReq->execute();
                $meetingRequestId = $stmtInsertReq->insert_id;
            }

            foreach ($timeSlots as $slot) {
                $stmtInsertSlot = $con->prepare("INSERT INTO time_slots (meeting_request_id, start_time, end_time) VALUES (?, ?, ?)");
                $stmtInsertSlot->bind_param("iss", $meetingRequestId, $slot['start'], $slot['end']);
                $stmtInsertSlot->execute();
            }

            $con->commit();
            $_SESSION['meeting_scheduled'] = true;
            header("Location: meeting_confirmation.php");
            exit();
        } catch (Exception $e) {
            $con->rollback();
            $showError = true;
            $errorMessage = "Error creating meeting request: " . $e->getMessage();
            error_log("Insert error: " . $e->getMessage());
        }
    }
}
?>


<div class="content-wrapper">
    <section class="content">
        <div class="card mx-auto"
            style="width:95%; max-width:1600px; border-radius:20px; overflow:hidden; box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);">
            <div class="card-header text-center"
                style="background-color: #2D4B69; color: white; border-radius:20px 20px 0 0;">
                <h3 class="card-title">Schedule Your Discussion Meeting</h3>
            </div>

            <div class="card-body p-4">
                <div class="alert alert-info mb-4">
                    <strong>Instructions:</strong> Provide your full name, phone, and email. Choose 1 to 3 time slots
                    (min. 1 hour each).
                </div>

                <?php if ($showError): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
                <?php endif; ?>

                <form method="post" action="schedule_meeting.php">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="name">Full Name</label>
                            <input type="text" id="name" name="name" class="form-control" required
                                value="<?= htmlspecialchars($formData['name']) ?>">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label fw-bold" for="phone">Phone Number</label>
                            <input type="tel" id="phone" name="phone" class="form-control" pattern="[0-9]{10}" required
                                value="<?= htmlspecialchars($formData['phone']) ?>">
                        </div>

                        <div class="col-md-6 mt-2">
                            <label class="form-label fw-bold" for="email">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control" required
                                value="<?= htmlspecialchars($formData['email']) ?>">
                        </div>
                    </div>

                    <h5 class="mt-4 mb-3">Select Available Time Slots</h5>
                    <p class="text-muted">Choose between 1 and 3 options. Each slot must be at least 1 hour long and in
                        the future.</p>

                    <?php for ($i = 1; $i <= 3; $i++):
                        $startRaw = $existingTimeSlots[$i - 1]['start_time'] ?? '';
                        $endRaw = $existingTimeSlots[$i - 1]['end_time'] ?? '';
                        $startTimeValue = $startRaw ? date('Y-m-d\TH:i', strtotime($startRaw)) : '';
                        $endTimeValue = $endRaw ? date('Y-m-d\TH:i', strtotime($endRaw)) : '';
                    ?>
                    <div class="row mb-2 align-items-center">
                        <div class="col-md-5">
                            <label class="form-label">Slot <?= $i ?> Start</label>
                            <input type="datetime-local" name="slot_<?= $i ?>_start" class="form-control"
                                value="<?= htmlspecialchars($startTimeValue) ?>">
                        </div>
                        <div class="col-md-5">
                            <label class="form-label">End</label>
                            <input type="datetime-local" name="slot_<?= $i ?>_end" class="form-control"
                                value="<?= htmlspecialchars($endTimeValue) ?>">
                        </div>
                    </div>
                    <?php endfor; ?>

                    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>">
                    <button type="submit" class="btn btn-primary mt-4 float-end"
                        style="background-color: #2D4B69;">Submit Preferences</button>
                </form>
            </div>
            <div class="card-footer" style="background-color: #fff; border-radius: 0 0 20px 20px; padding: 20px 0;">
            </div>
        </div>
    </section>
</div>

<?php include './footer.php'; ?>