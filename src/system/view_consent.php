<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */
?>
<!-- view_consent.php -->
<?php include './header.php' ?>
<?php

require_once './models/ConsentForm.php';
require_once './db/db_functions.php';
require_once './db/consent_form.php';

if (!isset($_SESSION['userid'])) {
    header("Location: index.php");
    exit();
}

$userId = $_SESSION['userid'];
$consentData = getConsentForm($userId);

if (!$consentData || empty($consentData['consent_agreed'])) {
    header("Location: consent_form.php");
    exit();
}
?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">

                </div>
                <div class="col-sm-6">

                </div>
            </div>
        </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
        <div class="card card-info mx-auto" style="width:95%;">
            <div class="card-header" style="background-color: #2D4B69;">
                <h3 class="card-title">Informed Consent for Genetic Testing</h3>
            </div>
            <!-- /.card-header -->
            <div class="container mt-5" style="text-align: center; font-size: 1.5rem;">
                <p><strong>Patient Name:</strong> <?= htmlspecialchars($consentData['patient_name']) ?></p>
                <p><strong>Date of Birth:</strong> <?= htmlspecialchars($consentData['dob']) ?></p>
                <p><strong>MRN:</strong> <?= htmlspecialchars($consentData['mrn']) ?></p>
                <p><strong>Gender:</strong> <?= htmlspecialchars($consentData['gender']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($consentData['email']) ?></p>
                <!-- Show other fields as needed -->
                <p><em>This form has already been submitted and cannot be edited.</em></p>
            </div>
            <div class="card-footer">
                <button type="button" class="btn btn-secondary float-left"
                    onclick="window.location.href='Benefits_Risks_and_Limitations_of_the_genetic_testing.php'">Back</button>
                <button type="button" class="btn btn-info float-right" id="btnNext"
                    onclick="window.location.href='assessment.php'">Next</button>
            </div>
        </div>

        <!-- /.card -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->


<?php include './footer.php' ?>