<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */
?>
<!-- /views/consent_form_view.php -->

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
        </div>
        <!-- /.container-fluid -->
    </section>

    <section class="content">
        <div class="card card-info mx-auto" style="width:95%;">
            <div class="card-header" style="background-color: #2D4B69;">
                <h3 class="card-title">Informed Consent for Genetic Testing</h3>
            </div>



            <form method="post" action="consent_form.php">
                <div class="card-body">

                    <div class="container py-4">
                        <div class="row">
                            <?php if ($showError): ?>
                            <div class="alert alert-danger"><?= htmlspecialchars($errorMessage) ?></div>
                            <?php endif; ?>
                        </div>
                        <div class="row ">
                            <!-- Patient Name -->

                            <div class="col-md-6">
                                <label for="patient_name" class="form-label fw-bold">Patient Name</label>
                                <input type="text" id="patient_name" name="patient_name" class="form-control"
                                    value="<?php echo isset($consentForm->patient_name) ? htmlspecialchars($consentForm->patient_name, ENT_QUOTES, 'UTF-8') : ''; ?>"
                                    required>
                            </div>

                            <!-- DOB -->
                            <div class="col-md-6">
                                <label for="dob" class="form-label fw-bold">Date of Birth</label>
                                <input type="date" id="dob" name="dob" class="form-control"
                                    value="<?php echo isset($consentForm->dob) ? htmlspecialchars($consentForm->dob, ENT_QUOTES, 'UTF-8') : ''; ?>"
                                    required>
                            </div>

                            <!-- MRN -->
                            <div class="col-md-6">
                                <label for="mrn" class="form-label fw-bold">Medical Record Number (MRN)</label>
                                <input type="text" id="mrn" name="mrn" class="form-control"
                                    value="<?php echo isset($consentForm->mrn) ? htmlspecialchars($consentForm->mrn, ENT_QUOTES, 'UTF-8') : ''; ?>"
                                    required>
                            </div>

                            <!-- Gender -->
                            <div class="col-md-6">
                                <label for="gender" class="form-label fw-bold">Gender</label>
                                <select id="gender" name="gender" class="form-control" required>
                                    <option value="" disabled selected>-- Select Gender --</option>
                                    <option value="Female"
                                        <?php echo(isset($consentForm->gender) && $consentForm->gender == 'Female') ? 'selected' : ''; ?>>
                                        Female</option>
                                    <option value="Male"
                                        <?php echo(isset($consentForm->gender) && $consentForm->gender == 'Male') ? 'selected' : ''; ?>>
                                        Male</option>
                                    <option value="Other"
                                        <?php echo(isset($consentForm->gender) && $consentForm->gender == 'Other') ? 'selected' : ''; ?>>
                                        Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Request Genetics Test -->
                        <div class="col-md-12 mt-4">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="patient_use_agreed[]"
                                    value="agree1" id="geneticsTestRequest" required>
                                <label class="form-check-label" for="geneticsTestRequest">
                                    I request a Genetics test for: A multigene panel test that includes specific genes
                                    known to increase the risk of breast cancer susceptibility, including
                                    <strong>BRCA1, BRCA2, CDH1, PALB2, PTEN, and TP53</strong>.
                                </label>
                            </div>
                        </div>
                    </div>

                    <hr class="my-5">

                    <!-- Patient Use Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <p class="fw-bold">It has been explained to me and I understand the following:</p>

                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="patient_use_agreed[]"
                                    value="agree2" id="agreeCheck1">
                                <label class="form-check-label" for="agreeCheck1">
                                    1. I agree to have genetic (DNA) testing for a multigene panel.
                                </label>
                            </div>

                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="patient_use_agreed[]"
                                    value="agree3" id="agreeCheck2">
                                <label class="form-check-label" for="agreeCheck2">
                                    2. I understand the purpose of this genetic test.
                                </label>
                            </div>

                            <div class="form-check mt-2">
                                <input class="form-check-input" type="checkbox" name="patient_use_agreed[]"
                                    value="agree4" id="agreeCheck3">
                                <label class="form-check-label" for="agreeCheck3">
                                    3. I have been informed about the following regarding the genetic testing:
                                    <ul>
                                        <li>
                                            <strong>Benefits of the genetic testing:</strong>
                                            <ul>
                                                <li>
                                                    Genetic test results help you and your doctor make more informed
                                                    choices about your health care, such as screening, risk-reducing
                                                    surgeries, and preventive medication strategies. Identifying gene
                                                    mutation(s) in a family enables other blood relatives to determine
                                                    whether or not they share the same hereditary cancer risks.
                                                </li>
                                                <li>
                                                    If you are positive, you should discuss with your Physician /
                                                    Counselor how hereditary cancer is inherited and learn about the
                                                    chance your children and blood relatives may have inherited the same
                                                    mutation(s) in the gene(s) tested.
                                                </li>
                                                <li>
                                                    Understanding your risk for hereditary conditions empowers you to
                                                    make informed decisions about preventive measures and personalized
                                                    healthcare. This newfound awareness provides psychological relief,
                                                    easing anxieties or presenting early detection opportunities for
                                                    proactive health management. Genetic testing enhances your
                                                    understanding of your genetic makeup, offering profound insights
                                                    into potential health outcomes. It supports healthcare professionals
                                                    in tailoring treatment plans to your unique genetic profile.
                                                </li>
                                                <li>
                                                    Suppose you test negative for a known mutation in your family. In
                                                    that case, you cannot pass on that mutation to your children, and
                                                    you may be considered to have the same genetic risks for cancer as
                                                    others in the general population.
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <strong>Risks of the genetic testing:</strong>
                                            <ul>
                                                <li>
                                                    Genetic testing requires DNA, most often provided from a sample of
                                                    blood from a saliva sample or a tumor sample. Side effects of having
                                                    blood drawn are uncommon but may include dizziness, fainting,
                                                    soreness, bleeding, bruising, and rarely, infection.
                                                </li>
                                                <li>
                                                    You may experience heightened anxiety or emotional distress,
                                                    particularly if results indicate an increased risk. Challenges may
                                                    arise due to the complexity of genetic information, emphasizing the
                                                    importance of clear communication between healthcare providers and
                                                    individuals. Ethical considerations around privacy, genetic
                                                    discrimination, and familial dynamics may come into play. Genetic
                                                    testing unveils predispositions that can influence lifestyle choices
                                                    and preventive measures, contributing to a holistic approach to
                                                    health and well-being. It's crucial to carefully weigh these facets
                                                    and engage in genetic counseling to make choices aligned with your
                                                    values and preferences.
                                                </li>
                                            </ul>
                                        </li>
                                        <li>
                                            <strong>Limitations of the genetic testing:</strong>
                                            <ul>
                                                <li>This test analyzes only certain important gene(s) associated with
                                                    specific hereditary cancer risks. Genetic testing clarifies cancer
                                                    risks for only those cancers related to the genes analyzed. If you
                                                    are found to be a carrier of a gene that predisposes you to cancer,
                                                    there may be differing opinions among physicians about the best
                                                    steps to take. You best determine your medical care in consultation
                                                    with your Physician / Counselor. Analysis for a specific genetic
                                                    variant of uncertain significance may be considered investigational
                                                    and may not provide additional cancer risk information to blood
                                                    relatives
                                                    .</li>
                                            </ul>
                                        </li>
                                    </ul>
                                </label>
                            </div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="patient_use_agreed[]"
                                    value="agree5" id="outcomes4">
                                <label class="form-check-label" for="outcomes4">
                                    4. I understand that the possible result outcomes include positive, negative,
                                    uncertain, and incidental findings:
                                </label>
                            </div>

                            <div class="form-check mt-2 bl-4 ml-4">
                                <!--                <input class="form-check-input" type="checkbox" value="" id="positive4">-->
                                <label class="form-check-label" for="positive4">
                                    <strong>Positive :</strong> A mutation that is associated with an increased risk for
                                    hereditary cancer was identified. Knowing this information may help you and your
                                    doctor make more informed choices about your health care, such as screening,
                                    risk-reducing surgeries and preventive medication strategies.
                                </label>
                            </div>

                            <div class="form-check mt-2 bl-4 ml-4">
                                <!--                <input class="form-check-input" type="checkbox" value="" id="negative4">-->
                                <label class="form-check-label" for="negative4">
                                    <strong>Negative:</strong> A mutation was not identified in any of the genes
                                    included as part of your testing.
                                    <ul>
                                        <li>If you are the first person tested in your family, you still have at least
                                            the same risk of cancer as does a person in the general population. You may
                                            still be at greater than average risk for hereditary cancer due to a genetic
                                            predisposition that cannot be detected by this test, either in the gene(s)
                                            for which you were tested or in another gene linked to hereditary cancer.
                                        </li>
                                        <li>If you test negative for a mutation known to be in your family, you may be
                                            considered to have the same genetic risks as others in the general
                                            population.
                                        </li>
                                    </ul>
                                </label>
                            </div>

                            <div class="form-check mt-2 bl-4 ml-4">
                                <!--                <input class="form-check-input" type="checkbox" value="" id="uncertain4">-->
                                <label class="form-check-label" for="uncertain4">
                                    <strong>Uncertain:</strong> A genetic change was detected but it is not known if
                                    this change is linked to cancer risk. You still have at least the same risk of
                                    cancer as the general population. In addition, you may still be at greater than
                                    average risk due to this change or a genetic predisposition that cannot be detected
                                    by this test, either in the gene(s) for which you were tested or in another gene
                                    linked to hereditary cancer.
                                </label>
                            </div>

                            <div class="form-check mt-2 bl-4 ml-4">
                                <!--                <input class="form-check-input" type="checkbox" value="" id="incidental4">-->
                                <label class="form-check-label" for="incidental4">
                                    <strong>Incidental Findings:</strong> Additional findings not specifically related
                                    to the assessed cancer risk may emerge during the analysis. These incidental
                                    findings could have health implications and require further investigation or medical
                                    follow-up.
                                </label>
                            </div>

                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="patient_use_agreed[]"
                                    value="agree6" id="familyHistory5">
                                <label class="form-check-label" for="familyHistory5">
                                    5. I agree that all information I gave about my family history and their clinical
                                    diagnosis are correct as far as I know.
                                </label>
                            </div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="patient_use_agreed[]"
                                    value="agree7" id="newTestsAgreement6">
                                <label class="form-check-label" for="newTestsAgreement6">
                                    6. I understand that the genetic testing is often complex and need specialized
                                    material, however, a small chance of errors may occur.
                                </label>
                            </div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="patient_use_agreed[]"
                                    value="agree8" id="storageAgreement7">
                                <label class="form-check-label" for="storageAgreement7">
                                    7. I agree to the storage of my sample after analysis.
                                </label>
                            </div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="patient_use_agreed[]"
                                    value="agree9" id="storageSampleAgreement8">
                                <label class="form-check-label" for="storageSampleAgreement8">
                                    8. I understand that the stored sample may be used anonymously for new tests and
                                    quality assurance.
                                </label>
                            </div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="patient_use_agreed[]"
                                    value="agree10" id="clinicalAuditAgreement9">
                                <label class="form-check-label" for="clinicalAuditAgreement9">
                                    9. I understand that my information may be used for clinical audit.
                                </label>
                            </div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="patient_use_agreed[]"
                                    value="agree11" id="unexpectedResultsAgreement10">
                                <label class="form-check-label" for="unexpectedResultsAgreement10">
                                    10. It has been explained to me that some genetic tests can reveal unexpected
                                    information.
                                </label>
                            </div>

                            <div class="form-check mt-3">
                                <input class="form-check-input" type="checkbox" name="patient_use_agreed[]"
                                    value="agree12" id="MDCDiscussionAgreement11">
                                <label class="form-check-label" for="MDCDiscussionAgreement11">
                                    11. I understand that my results may be discussed in the multi-disciplinary
                                    committee
                                    (MDC).
                                </label>
                            </div>

                            <div class="form-group mt-4">
                                <label for="newTestsAvailable" class="form-label">
                                    12. If new tests become available, I want to be contacted:
                                </label>
                                <div class="d-flex">
                                    <div class="form-check mr-4">
                                        <input type="radio" id="contactBeforeYes12" name="contact_new_tests" value="Yes"
                                            class="form-check-input">
                                        <label class="form-check-label" for="contactBeforeYes12">
                                            Yes
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" id="contactBeforeNo12" name="contact_new_tests" value="No"
                                            class="form-check-input">
                                        <label class="form-check-label" for="contactBeforeNo12">
                                            No
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group mt-4">
                                <label for="furtherTestsWithoutContact" class="form-label">
                                    13. I agree for further tests to be undertaken without contacting me:
                                </label>
                                <div class="d-flex">
                                    <div class="form-check mr-4">
                                        <input type="radio" id="contactBeforeYes13" name="agree_further_tests"
                                            value="Yes" class="form-check-input" required>
                                        <label class="form-check-label" for="contactBeforeYes13">Yes</label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" id="contactBeforeNo13" name="agree_further_tests" value="No"
                                            class="form-check-input" required>
                                        <label class="form-check-label" for="contactBeforeNo13">No</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr class="my-5">
                    <div class="row mb-4">
                        <div class="form-check mt-3">
                            <h5>Patient Consent Statement</h5>

                            <div class="mt-3">
                                <input class="form-check-input" type="checkbox" value="" id="exploreDecisionAid1">
                                <label class="form-check-label" for="exploreDecisionAid1">
                                    1. I acknowledge that I have explored the decision aid system.
                                </label>
                            </div>

                            <div class="mt-3">
                                <label for="consentOption1" class="form-check-label">
                                    2. I am ready to:
                                </label>
                                <div class="d-flex mt-2">
                                    <div class="form-check mr-4">
                                        <input type="radio" id="completeConsent1" name="consentDecision"
                                            value="Complete" class="form-check-input">
                                        <label class="form-check-label" for="completeConsent1">
                                            Complete and sign the consent form without needing further discussion.
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" id="needMoreDiscussion1" name="consentDecision"
                                            value="NeedDiscussion" class="form-check-input">
                                        <label class="form-check-label" for="needMoreDiscussion1">
                                            I need to have more discussion before deciding. Please arrange a meeting.
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <input class="form-check-input" type="checkbox" value="" id="readDocument3">
                                <label class="form-check-label" for="readDocument3">
                                    3. I have read this document in its entirety.
                                </label>
                            </div>

                            <div class="mt-3">
                                <input class="form-check-input" type="checkbox" value="" id="consentTest4">
                                <label class="form-check-label" for="consentTest4">
                                    4. I consent to being tested and will discuss the results with my Physician /
                                    Counselor.
                                </label>
                            </div>

                            <div class="mt-3">
                                <input class="form-check-input" type="checkbox" value="" id="disclosureConsent5">
                                <label class="form-check-label" for="disclosureConsent5">
                                    5. I consent to my Physician / Counselor disclosing my test results and history to
                                    a third party.
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Signature and Email -->
                    <hr class="my-5">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="signature" class="form-label fw-bold">Name of Patient (Signature)</label>
                            <input type="text" id="signature" name="signature" class="form-control"
                                value="<?php echo isset($consentForm->signature) ? htmlspecialchars($consentForm->signature, ENT_QUOTES, 'UTF-8') : ''; ?>"
                                required placeholder="Please type your full name">
                        </div>

                        <div class="col-md-6">
                            <label for="email" class="form-label fw-bold">Email</label>
                            <input type="email" id="email" name="email" class="form-control"
                                value="<?php echo isset($consentForm->email) ? htmlspecialchars($consentForm->email, ENT_QUOTES, 'UTF-8') : ''; ?>"
                                required placeholder="Please enter a valid email">
                        </div>
                    </div>

                    <hr class="my-5">
                    <div class="row mb-4">
                        <div class="form-group">
                            <div class="form-check">
                                <!-- <input type="checkbox" name="consent_agreed" value="1" class="form-check-input"
                                    id="consentAgreed" required>
                                <label class="form-check-label" for="consentAgreed">
                                    I agree to the informed consent terms.
                                </label> -->
                                <label>
                                    <input type="checkbox" name="consent_agreed" value="1" id="consentAgreed" required
                                        <?php if (!empty($consentForm->consent_agreed)) echo 'checked'; ?>>
                                    I agree to the terms and give my consent.
                                </label>
                            </div>
                        </div>
                    </div>

                </div>
                <?php
            // Generate and output CSRF token
            if (session_status() === PHP_SESSION_NONE) {
                session_start();
            }
            $csrfToken              = bin2hex(random_bytes(32));
            $_SESSION['csrf_token'] = $csrfToken;
        ?>
                <input type="hidden" name="csrf_token" value="<?php echo $csrfToken; ?>">
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary float-right" name="submit_consent_form">Submit</button>
                </div>
            </form>
        </div>
    </section>
</div>