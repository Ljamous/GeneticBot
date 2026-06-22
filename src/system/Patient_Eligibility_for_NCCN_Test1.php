<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

// report.php
ob_start();
include './header.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION["userid"])) {
    header("Location: index.php");
    exit();
}

include './db/mycon.php';
$userId = $_SESSION["userid"];
?>

<!-- Content Wrapper -->
<div class="content-wrapper">
  <section class="content-header">
    <div class="container-fluid">
      <div class="row mb-2"><div class="col-sm-6"></div></div>
    </div>
  </section>

  <section class="content">
    <form method="POST" enctype="multipart/form-data">
      <div class="card card-info mx-auto" style="width: 70%; border-radius: 10px;">
        <div class="card-header" style="background-color: #2D4B69;">
          <h3 class="card-title">Evaluating Patient Eligibility for NCCN Testing Criteria</h3>
        </div>
        
        <div class="card-body">
          <!-- Start Button -->
          <div id="start-section" class="text-center mb-3">
            <button id="generate-report" type="button" class="btn btn-primary">Start Generating Report</button>
          </div>

          <!-- Loading Indicator -->
          <div id="loading" style="display: none;" class="text-center">
            <div class="spinner-border text-info" role="status"></div>
            <p>Processing... Please wait.</p>
          </div>

          <!-- Error Alert -->
          <div id="error-message" class="alert alert-danger" style="display: none;"></div>

          <!-- Download Report Button -->
          <div class="text-center mt-3">
            <button id="download-report" type="button" class="btn btn-success" style="display: none;">Download Report</button>
          </div>

          <!-- Result Display -->
          <div id="result" style="display: none; margin-top: 20px;">
            <h4>Report Generated:</h4>
            <div id="content"></div>
          </div>
        </div>

        <!-- Card Footer -->
        <div class="card-footer">
          <button type="button" class="btn btn-secondary float-left" onclick="window.history.back()">Previous</button>
          <button type="submit" class="btn btn-info float-right" id="btnNext" disabled>Next</button>
        </div>
      </div>
    </form>
  </section>
</div>

<?php include './footer.php'; ?>

// Working script
<!-- <script>
document.addEventListener('DOMContentLoaded', () => {
  const userId = <?= json_encode($userId); ?>;
  const generateBtn = document.getElementById('generate-report');
  const loadingDiv = document.getElementById('loading');
  const resultDiv = document.getElementById('result');
  const contentDiv = document.getElementById('content');
  const downloadBtn = document.getElementById('download-report');
  const errorDiv = document.getElementById('error-message');
  const startSection = document.getElementById('start-section');
  const nextBtn = document.getElementById('btnNext');

  generateBtn.addEventListener('click', async () => {
    loadingDiv.style.display = 'block';
    startSection.style.display = 'none';
    errorDiv.style.display = 'none';
    resultDiv.style.display = 'none';
    downloadBtn.style.display = 'none';
    nextBtn.disabled = true;

    try {
      const response = await fetch('http://localhost:8000/medical/analysis/', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ userId })
      });

      loadingDiv.style.display = 'none';

      if (!response.ok) {
        const err = await response.json();
        errorDiv.textContent = "Error: " + (err.detail || "Failed to generate report.");
        errorDiv.style.display = 'block';
        startSection.style.display = 'block';
        return;
      }

      const result = await response.json();
      const content = result.report;
      let html = "";

      const createSection = (title, obj) => {
        let section = `<button type="button" class="collapsible">${title}</button><div class="content">`;
        for (const key in obj) {
          const value = obj[key];
          if (typeof value === 'object') {
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
        html += createSection("Pathology Report Summary", content["Pathology Report Summary"]);
      }

      if (content["Pedigree Analysis"]?.["Generations"]) {
        html += '<button type="button" class="collapsible">Pedigree Analysis</button><div class="content">';
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
        html += createSection("NCCN Testing Criteria Assessment", content["NCCN Testing Criteria Assessment"]);
      }

      if (content.Conclusion) {
        html += createSection("Conclusion", content.Conclusion);
      }

      contentDiv.innerHTML = html;
      resultDiv.style.display = 'block';
      downloadBtn.style.display = 'inline-block';
      nextBtn.disabled = false;

      if (result.docx_path) {
        downloadBtn.onclick = () => window.location.href = result.docx_path;
      }

      document.querySelectorAll(".collapsible").forEach(button => {
        button.addEventListener("click", function () {
          this.classList.toggle("active");
          const panel = this.nextElementSibling;
          panel.style.display = panel.style.display === "block" ? "none" : "block";
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
</script> -->


<script>
document.addEventListener('DOMContentLoaded', () => {
  const userId = <?= json_encode($userId); ?>;
  const generateBtn = document.getElementById('generate-report');
  const loadingDiv = document.getElementById('loading');
  const resultDiv = document.getElementById('result');
  const contentDiv = document.getElementById('content');
  const downloadBtn = document.getElementById('download-report');
  const errorDiv = document.getElementById('error-message');
  const startSection = document.getElementById('start-section');
  const nextBtn = document.getElementById('btnNext');

  generateBtn.addEventListener('click', async () => {
    loadingDiv.style.display = 'block';
    startSection.style.display = 'none';
    errorDiv.style.display = 'none';
    resultDiv.style.display = 'none';
    downloadBtn.style.display = 'none';
    nextBtn.disabled = true;

    try {
      const response = await fetch('http://localhost:8000/medical/analysis/', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ userId })
      });

      loadingDiv.style.display = 'none';

      if (!response.ok) {
        const err = await response.json();
        errorDiv.textContent = "Error: " + (err.detail || "Failed to generate report.");
        errorDiv.style.display = 'block';
        startSection.style.display = 'block';
        return;
      }

      const result = await response.json();
      const content = result.report; // ✅ Use correct key

      let html = "";

      const createSection = (title, obj) => {
        let section = `<button type="button" class="collapsible">${title}</button><div class="content">`;
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
        html += createSection("Pathology Report Summary", content["Pathology Report Summary"]);
      }

      if (content["Pedigree Analysis"]?.["Generations"]) {
        html += '<button type="button" class="collapsible">Pedigree Analysis</button><div class="content">';
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
        html += createSection("NCCN Testing Criteria Assessment", content["NCCN Testing Criteria Assessment"]);
      }

      if (content.Conclusion) {
        html += createSection("Conclusion", content.Conclusion);
      }

      contentDiv.innerHTML = html;
      resultDiv.style.display = 'block';
      downloadBtn.style.display = 'inline-block';
      nextBtn.disabled = false;

      if (result.docx_path) {
        downloadBtn.onclick = () => window.location.href = result.docx_path;
      }

      document.querySelectorAll(".collapsible").forEach(button => {
        button.addEventListener("click", function () {
          this.classList.toggle("active");
          const panel = this.nextElementSibling;
          panel.style.display = panel.style.display === "block" ? "none" : "block";
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

<!-- 
<style>
.collapsible {
  background-color: #2D4B69;
  color: white;
  cursor: pointer;
  padding: 10px;
  border: none;
  width: 100%;
  text-align: left;
  font-size: 16px;
  margin-top: 5px;
  border-radius: 5px;
}
.collapsible:hover,
.collapsible.active {
  background-color: #1b2e44;
}
.content {
  padding: 10px 15px;
  display: none;
  background-color: #f9f9f9;
  border: 1px solid #ddd;
  border-top: none;
  border-radius: 0 0 5px 5px;
}
</style> -->
