<?php
include './header.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION["userid"])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['userid'];
include './db/mycon.php';

// Fetch existing pedigree for display
$existingPedigree = null;
$result = $con->prepare("SELECT img FROM family_pedigree WHERE userId = ?");
$result->bind_param("i", $userId);
$result->execute();
$result->store_result();

if ($result->num_rows > 0) {
    $result->bind_result($imgData);
    $result->fetch();
    $existingPedigree = base64_encode($imgData);
}
$result->close();
?>

<div class="content-wrapper">
    <section class="content">
        <div class="card card-info mx-auto" style="width: 70%; border-radius: 10px;">
            <div class="card-header" style="background-color: #2D4B69;">
                <h3 class="card-title">Pedigree Options</h3>
            </div>

            <form id="pedigreeForm" method="POST" enctype="multipart/form-data">
                <div class="card-body">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label">Choose how to create the pedigree</label>
                        <div class="col-sm-8">
                            <select class="form-control" id="pedigreeOption" name="pedigreeOption" required>
                                <option value="">Select an option</option>
                                <option value="upload">Upload a pedigree</option>
                                <option value="manual">Construct manually</option>
                            </select>
                        </div>
                    </div>

                    <!-- Upload section -->
                    <div id="uploadSection" style="display: none;">
                        <div class="form-group row">
                            <label for="pedigreeFile" class="col-sm-4 col-form-label">Upload Pedigree File</label>
                            <div class="col-sm-8">
                                <input type="file" class="form-control" id="pedigreeFile" name="pedigreeFile" accept="image/*, .pdf">
                            </div>
                        </div>

                        <?php if ($existingPedigree): ?>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Stored Pedigree:</label>
                            <div class="col-sm-8">
                                <img src="data:image/jpeg;base64,<?= $existingPedigree ?>" alt="Pedigree" style="max-width:100%;">
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <!-- Iframe section -->
                    <div id="iframeSection" style="display: none;">
                        <iframe id="myIframe" src="" width="100%" height="800px" frameborder="0"></iframe>
                    </div>
                </div>

                <div class="card-footer">
                    <div class="text-center mt-3">
                        <b>Please watch the tutorial videos section on the bottom left-hand corner before starting the pedigree construction. You may also upload the pedigree image instead.</b>
                    </div>
                    <button type="button" class="btn btn-secondary float-left" onclick="window.history.back()">Previous</button>
                    <button type="submit" class="btn btn-info float-right" id="btnNext" disabled>Next</button>
                </div>
            </form>
        </div>
    </section>
</div>

<?php include './footer.php'; ?>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const pedigreeOption = document.getElementById('pedigreeOption');
    const uploadSection = document.getElementById('uploadSection');
    const iframeSection = document.getElementById('iframeSection');
    const pedigreeFile = document.getElementById('pedigreeFile');
    const btnNext = document.getElementById('btnNext');
    const iframe = document.getElementById('myIframe');
    const form = document.getElementById('pedigreeForm');

    function checkFormValidity() {
        const option = pedigreeOption.value;
        const isUploadValid = option === 'upload' && pedigreeFile.files.length > 0;
        const isManualValid = option === 'manual';
        btnNext.disabled = !(isUploadValid || isManualValid);
    }

    pedigreeOption.addEventListener('change', function () {
        const option = this.value;
        uploadSection.style.display = option === 'upload' ? 'block' : 'none';
        iframeSection.style.display = option === 'manual' ? 'block' : 'none';

        if (option === 'manual') {
            iframe.src = `http://localhost:8001?userId=<?= $userId ?>`;
        }

        checkFormValidity();
    });

    pedigreeFile.addEventListener('change', checkFormValidity);

    form.addEventListener('submit', function (e) {
        const option = pedigreeOption.value;

        if (option === 'manual') {
            e.preventDefault();
            // Send userId to iframe and wait for manual save
            iframe.contentWindow.postMessage({
                type: 'savePedigree',
                userId: <?= json_encode($userId) ?>
            }, "http://localhost:8001");

            // Optional: Wait for a response from iframe before redirecting
            window.addEventListener('message', function listener(event) {
                if (event.origin === "http://localhost:8001" && event.data === 'saved') {
                    window.removeEventListener('message', listener);
                    window.location.href = "manual_pedigree.php";
                }
            });
        }
    });
});
</script>

<?php
// Handle Upload pedigree form submission (via POST)
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["pedigreeOption"]) && $_POST["pedigreeOption"] === "upload") {
    $pedigreeFile = $_FILES["pedigreeFile"] ?? null;

    if ($pedigreeFile && $pedigreeFile['error'] === 0) {
        $fileTmpName = $pedigreeFile["tmp_name"];
        $fileContent = file_get_contents($fileTmpName);

        $stmt = $con->prepare("INSERT INTO family_pedigree (img, userId) VALUES (?, ?) ON DUPLICATE KEY UPDATE img = VALUES(img)");
        $null = NULL;
        $stmt->bind_param("bi", $null, $userId);
        $stmt->send_long_data(0, $fileContent);
        $stmt->execute();
        $stmt->close();

        header("Location: family_pedigree.php");
        exit();
    } else {
        echo '<div class="alert alert-danger">File upload failed. Please try again.</div>';
    }
}
?>
