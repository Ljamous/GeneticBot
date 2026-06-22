<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

ob_start();
include './header.php';
include './db/mycon.php';
require_once './db/helpers.php';
require_once './db/family_pedigree.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["userid"])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION["userid"];

// Fetch existing pedigree info
$pedigreeData = getFamilyPedigreeByUserId($userId, $con);


$filename = $pedigreeData['filename'] ?? '';
$filetype = $pedigreeData['filetype'] ?? '';
$filesize = $pedigreeData['filesize'] ?? 0;
$imgData = $pedigreeData['img'] ?? null;
$hasImage = !empty($pedigreeData['img']) && str_starts_with($pedigreeData['filetype'], 'image/');

$hasUploadedFile = !empty($filenameDB);
$hasManualData = !empty($csvDataDB);
$needsUserChoice = ($hasUploadedFile && $hasManualData);
$hasExistingFile = (!empty($filename) && $filesize > 0) || $hasImage;

$_SESSION['needsUserChoice'] = $needsUserChoice;

$allowedTypes = [
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
    'application/pdf',
    'image/jpeg',
    'image/png'
];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['pedigreeOption'])) {
    $pedigreeOption = $_POST['pedigreeOption'] ?? '';

    if (!in_array($pedigreeOption, ['upload', 'manual'], true)) {
        $errorMessage = "Invalid pedigree option.";
    } elseif ($pedigreeOption === 'upload') {
        $fileUploadResult = ["success" => false];

        if (isset($_FILES['pedigreeFile']) && $_FILES['pedigreeFile']['error'] === UPLOAD_ERR_OK) {
            $fileUploadResult = handleFileUpload($_FILES['pedigreeFile'], $allowedTypes);

            if ($fileUploadResult["success"]) {
                $fileContent = $fileUploadResult['fileContent'];
                $fileName = $fileUploadResult['fileName'];
                $fileType = $fileUploadResult['fileType'];
                $fileSize = strlen($fileContent);
            } else {
                $errorMessage = $fileUploadResult["error"];
            }
        } else {
            // Use previously saved file if no new file uploaded
            $fileContent = $imgData;
            $fileName = $filenameDB;
            $fileType = $filetype;
            $fileSize = strlen($imgData);
            $fileUploadResult["success"] = true;
        }

        if ($fileUploadResult["success"]) {
            if (saveFamilyPedigree($userId, $fileName, $fileType, $fileSize, $fileContent, null, $con)) {
                $_SESSION['selected_pedigree'] = [
                    'type' => 'upload',
                    'filename' => $fileName,
                    'filetype' => $fileType,
                    'content_base64' => base64_encode($fileContent)
                ];
                header("Location: Patient_Eligibility_for_NCCN_Test.php");
                exit();
            } else {
                $errorMessage = "Database error while saving pedigree file.";
            }
        }

    } elseif ($pedigreeOption === 'manual') {
        $manualPedigreeData = $_POST['manualPedigreeData'] ?? '';
        if (!empty($manualPedigreeData)) {
            if (saveFamilyPedigree($userId, null, null, 0, null, $manualPedigreeData, $con)) {
                $_SESSION['selected_pedigree'] = [
                    'type' => 'manual',
                    'csv_data' => $manualPedigreeData
                ];
                header("Location: Patient_Eligibility_for_NCCN_Test.php");
                exit();
            } else {
                $errorMessage = "Database error while saving manual pedigree.";
            }
        } else {
            $errorMessage = "Manual pedigree data is empty.";
        }
    }
}
?>


<!-- HTML Layout -->
<div class="content-wrapper">
    <?php if (!empty($errorMessage)): ?>
    <div class="alert alert-danger m-3" id="errorAlert">
        <?= htmlspecialchars($errorMessage) ?>
    </div>
    <?php endif; ?>

    <section class="content">
        <form method="POST" enctype="multipart/form-data">
            <div class="card card-info mx-auto" style="width:95%; border-radius: 10px;">
                <div class="card-header" style="background-color: #2D4B69;">
                    <h3 class="card-title">Pedigree Options</h3>
                </div>

                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Choose how to create the pedigree</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="pedigreeOption" name="pedigreeOption">
                                <option value="">Select an option</option>
                                <option value="upload">Upload a pedigree</option>
                                <option value="manual">Construct manually</option>
                            </select>
                        </div>
                    </div>

                    <div id="uploadSection" style="display: none;">
                        <div class="form-group row">
                            <label for="pedigreeFile" class="col-sm-4 col-form-label">Upload Pedigree File</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" id="pedigreeFile" name="pedigreeFile"
                                    accept=".pdf,image/*">
                            </div>
                        </div>

                        <?php if (!empty($filename)): ?>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Existing Uploaded File</label>
                            <div class="col-sm-8">
                                <a href="download_pedigree.php" target="_blank">
                                    <?= htmlspecialchars($filename) ?> (<?= number_format($filesize / 1024, 2) ?> KB)
                                </a>
                            </div>
                        </div>
                        <?php endif; ?>

                        <?php if ($hasImage): ?>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Image Preview</label>
                            <div class="col-sm-8">
                                <img src="data:<?= htmlspecialchars($pedigreeData['filetype']) ?>;base64,<?= base64_encode($pedigreeData['img']) ?>"
                                    alt="Uploaded Pedigree"
                                    style="max-width: 100%; border: 1px solid #ccc; padding: 5px;" />
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div id="iframeSection" style="display: none;">
                        <div class="alert alert-info" id="manualInstructions" style="display: none;">
                            Please watch the tutorial videos before starting the pedigree construction. Or upload an
                            image instead.
                        </div>
                        <iframe id="myIframe" src="http://localhost:8001?userId=<?= $userId ?>" width="100%"
                            height="800px" frameborder="0"></iframe>
                    </div>

                    <input type="hidden" id="manualPedigreeData" name="manualPedigreeData">
                </div>

                <div class="card-footer">
                    <button type="button" class="btn btn-secondary float-left"
                        onclick="window.location.href='clinical_history.php'">Back</button>
                    <button type="submit" class="btn btn-info float-right" id="btnNext" disabled>Next</button>
                </div>
            </div>
        </form>

        <!-- Modal -->
        <div class="modal fade" id="choiceModal" tabindex="-1" role="dialog" aria-labelledby="choiceModalLabel"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="choiceModalLabel">Select Pedigree Source</h5>
                    </div>
                    <div class="modal-body">
                        You have both an uploaded file and a manually constructed pedigree. Which one would you like to
                        use?
                    </div>
                    <div class="modal-footer">
                        <form method="POST">
                            <input type="hidden" name="useChoice" value="img">
                            <button type="submit" class="btn btn-primary">Use Uploaded File</button>
                        </form>
                        <form method="POST">
                            <input type="hidden" name="useChoice" value="csv">
                            <button type="submit" class="btn btn-secondary">Use Manual Pedigree</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<?php include './footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pedigreeOption = document.getElementById('pedigreeOption');
    const uploadSection = document.getElementById('uploadSection');
    const iframeSection = document.getElementById('iframeSection');
    const manualInstructions = document.getElementById('manualInstructions');
    const fileInput = document.getElementById('pedigreeFile');
    const nextButton = document.getElementById('btnNext');
    const iframe = document.getElementById('myIframe');
    const errorAlert = document.getElementById('errorAlert');
    const form = document.querySelector('form');

    const hasExistingFile = <?= json_encode($hasExistingFile) ?>;
    const needsUserChoice = <?= json_encode($needsUserChoice) ?>;

    function checkFormValidity() {
        const selected = pedigreeOption.value;
        const fileSelected = fileInput.files.length > 0;

        if (selected === 'manual') {
            nextButton.disabled = false;
        } else if (selected === 'upload') {
            nextButton.disabled = !(fileSelected || hasExistingFile);
        } else {
            nextButton.disabled = true;
        }
    }

    if (hasExistingFile) {
    pedigreeOption.value = 'upload';
    uploadSection.style.display = 'block';
    iframeSection.style.display = 'none';
    manualInstructions.style.display = 'none';
    checkFormValidity();
}


    pedigreeOption.addEventListener('change', function() {
        if (this.value === 'upload') {
            uploadSection.style.display = 'block';
            iframeSection.style.display = 'none';
            manualInstructions.style.display = 'none';
        } else if (this.value === 'manual') {
            uploadSection.style.display = 'none';
            iframeSection.style.display = 'block';
            manualInstructions.style.display = 'block';
        } else {
            uploadSection.style.display = 'none';
            iframeSection.style.display = 'none';
            manualInstructions.style.display = 'none';
        }
        checkFormValidity();
    });

    fileInput.addEventListener('change', function() {
        checkFormValidity();
    });

    if (errorAlert) {
        setTimeout(() => {
            errorAlert.style.display = 'none';
        }, 5000);
    }

    nextButton.addEventListener('click', function(e) {
        const selected = pedigreeOption.value;
        if (needsUserChoice) {
            e.preventDefault();
            $('#choiceModal').modal('show');
            return;
        }

        if (selected === 'manual') {
            e.preventDefault();
            iframe.contentWindow.postMessage({
                type: 'submitPedigree',
                userId: <?= json_encode($userId) ?>
            }, '*');
        }
    });

    window.addEventListener('message', function(event) {
        if (event.origin !== "http://localhost:8001") return;
        if (event.data.type === 'pedigreeData') {
            document.getElementById('manualPedigreeData').value = event.data.payload;
            form.submit();
        }
    });

    window.addEventListener('load', () => {
        iframe.contentWindow.postMessage({
            type: 'setUserId',
            userId: <?= json_encode($userId) ?>
        }, '*');
    });
});
</script>