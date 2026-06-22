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

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["userid"])) {
    header("Location: index.php");
    exit();
}

$needsUserChoice = $_SESSION['needsUserChoice'] ?? null;



include './db/mycon.php';
$userId = $_SESSION["userid"];
$pedigree = $_SESSION['selected_pedigree'] ?? null;
?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6"></div>
            </div>
        </div>
    </section>

    <section class="content">
        <form method="POST" enctype="multipart/form-data">
            <div class="card card-info mx-auto" style="width: 70%; border-radius: 10px;">
                <div class="card-header" style="background-color: #2D4B69;">
                    <h3 class="card-title">Generating Your Medical Report</h3>
                </div>

                <div class="card-body">
                    <div id="start-section" class="text-center mb-3">
                        <button id="generate-report" type="button" class="btn btn-primary">Start Generating
                            Report</button>
                    </div>

                    <div id="nccn-status" class="text-center mb-3" style="font-size: 18px; font-weight: bold;"></div>

                    <div id="loading" style="display: none;" class="text-center">
                        <div class="spinner-border text-info" role="status"></div>
                        <p>Processing... Please wait.</p>
                    </div>

                    <div id="error-message" class="alert alert-danger" style="display: none;"></div>

                    <div class="text-center mt-3">
                        <button id="download-report" type="button" class="btn btn-success"
                            style="display: none;">Download Report</button>
                    </div>

                    <div id="result" style="display: none; margin-top: 20px;">
                        <h4>Report Generated:</h4>
                        <div id="content"></div>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="button" class="btn btn-secondary float-left"
                        onclick="window.location.href='clinical_history.php'">Previous</button>
                    <button type="button" class="btn btn-info float-right" id="btnNext" disabled>Next</button>
                </div>
            </div>
        </form>
    </section>
</div>

<?php include './footer.php'; ?>

<script>
let isEligible = false; // Track eligibility globally


document.addEventListener('DOMContentLoaded', () => {
    const userId = <?= json_encode($userId); ?>;
    // const pedigree = <?= json_encode($pedigree); ?>;

    const generateBtn = document.getElementById('generate-report');
    const loadingDiv = document.getElementById('loading');
    const resultDiv = document.getElementById('result');
    const contentDiv = document.getElementById('content');
    const downloadBtn = document.getElementById('download-report');
    const nccnStatus = document.getElementById('nccn-status');
    const errorDiv = document.getElementById('error-message');
    const startSection = document.getElementById('start-section');
    const nextBtn = document.getElementById('btnNext');
    const needsUserChoice = <?= json_encode($needsUserChoice); ?>;


    nextBtn.addEventListener('click', () => {
        if (isEligible) {
            window.location.href = "Concept_of_genetics_and_mode_of_inheritance.php";
        } else {
            alert(
                "Thank you for submitting your information. According to the AI-generated report based on the details you shared, your current situation does not fulfill the NCCN criteria for genetic testing, so we cannot move forward at this time. Should there be any updates to your personal or family medical history moving forward, we recommend consulting your healthcare provider to assess whether testing might be suitable.\n\nWe value your time and patience."
            );
        }
    });

    generateBtn.addEventListener('click', async () => {
        loadingDiv.style.display = 'block';
        startSection.style.display = 'none';
        errorDiv.style.display = 'none';
        resultDiv.style.display = 'none';
        downloadBtn.style.display = 'none';
        nextBtn.disabled = true;

        try {
            const postData = {
                userId: userId,
                useUploadedFile: needsUserChoice
            };

            const response = await fetch('http://localhost:8000/medical/analysis/', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(postData)
            });

            loadingDiv.style.display = 'none';

            if (!response.ok) {
                const err = await response.json();
                // DEBUG: Dump the entire error object to see what's happening
                let errorMsg = JSON.stringify(err); 
                errorDiv.textContent = "Error Details: " + errorMsg;
                errorDiv.style.display = 'block';
                startSection.style.display = 'block';
                return;
            }

            const result = await response.json();
            const content = result.report;

            const eligibilityFlag = content.eligible_for_NCCN ?? false;
            isEligible = eligibilityFlag;

            nccnStatus.textContent = eligibilityFlag ?
                "Eligible for NCCN Genetic Testing" :
                "Not Eligible for NCCN Genetic Testing";
            nccnStatus.style.color = eligibilityFlag ? "green" : "red";


            // Store eligibility in session via AJAX
            fetch('store_eligibility.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    eligible: eligibilityFlag
                })
            }).then(res => res.json()).then(data => {
                if (data.status !== 'success') {
                    console.warn("Failed to store eligibility flag in session.");
                }
            });

            let html = "";

            const createSection = (title, obj) => {
                let section =
                    `<button type="button" class="collapsible">${title}</button><div class="content">`;
                for (const key in obj) {
                    const value = obj[key];
                    if (typeof value === 'object' && value !== null) {
                        section += `<b>${key}:</b><ul>`;
                        for (const subKey in value) {
                            section += `<li><b>${subKey}:</b> ${value[subKey]}</li>`;
                        }
                        section += `</ul>`;
                    } else {
                        section += `<p><b>${key}:</b> ${value}</p>`;
                    }
                }
                section += '</div><br>';
                return section;
            };

            if (content["Pathology Report Summary"]) {
                html += createSection("Pathology Report Summary", content[
                    "Pathology Report Summary"]);
            }

            if (content["Pedigree Analysis"]?. ["Generations"]) {
                html +=
                    '<button type="button" class="collapsible">Pedigree Analysis</button><div class="content">';
                for (const gen in content["Pedigree Analysis"]["Generations"]) {
                    html += `<p><b>${gen}:</b></p><ul>`;
                    content["Pedigree Analysis"]["Generations"][gen].forEach(item => {
                        if (typeof item === 'object') {
                            for (const k in item) {
                                html += `<li><b>${k}:</b> ${item[k]}</li>`;
                            }
                        } else {
                            html += `<li>${item}</li>`;
                        }
                    });
                    html += '</ul>';
                }
                html += '</div><br>';
            }

            if (content["NCCN Testing Criteria Assessment"]) {
                html += createSection("NCCN Testing Criteria Assessment", content[
                    "NCCN Testing Criteria Assessment"]);
            }

            if (content.Conclusion) {
                html += createSection("Conclusion", content.Conclusion);
            }

            contentDiv.innerHTML = html;
            resultDiv.style.display = 'block';
            downloadBtn.style.display = 'inline-block';
            nextBtn.disabled = false;

            downloadBtn.onclick = () => {
                window.location.href = 'download_report_text.php';
            };

            document.querySelectorAll(".collapsible").forEach(button => {
                button.addEventListener("click", function() {
                    this.classList.toggle("active");
                    const panel = this.nextElementSibling;
                    panel.style.display = panel.style.display === "block" ? "none" :
                        "block";
                });
            });

        } catch (error) {
            loadingDiv.style.display = 'none';
            errorDiv.textContent = "Error: Unable to connect to the server.";
            errorDiv.style.display = 'block';
            startSection.style.display = 'block';
        }
    });
});
</script>