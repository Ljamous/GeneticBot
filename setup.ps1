# ============================================
# GeneticBot Application - Quick Setup Script
# ============================================
# This script automates the initial setup of the GeneticBot application
# Run this script after copying the project to a new machine
# ============================================

param(
    [string]$InstallPath = "C:\xampp\htdocs\Lama2",
    [switch]$SkipVenv,
    [switch]$Help
)

function Show-Help {
    Write-Host @"
GeneticBot Application - Quick Setup Script

USAGE:
    .\setup.ps1 [-InstallPath <path>] [-SkipVenv] [-Help]

PARAMETERS:
    -InstallPath <path>  : Installation path (default: C:\xampp\htdocs\Lama2)
    -SkipVenv           : Skip virtual environment creation
    -Help               : Show this help message

EXAMPLES:
    .\setup.ps1
    .\setup.ps1 -InstallPath "D:\Projects\Lama"
    .\setup.ps1 -SkipVenv

"@ -ForegroundColor Cyan
    exit 0
}

if ($Help) {
    Show-Help
}

Write-Host @"
============================================
GeneticBot Application - Quick Setup
============================================
Installation Path: $InstallPath
============================================
"@ -ForegroundColor Green

# Check if Python is installed
Write-Host "`nChecking prerequisites..." -ForegroundColor Yellow
try {
    $pythonVersion = python --version 2>&1
    Write-Host "✓ Python found: $pythonVersion" -ForegroundColor Green
} catch {
    Write-Host "✗ Python not found! Please install Python 3.8+ first." -ForegroundColor Red
    Write-Host "  Download from: https://www.python.org/downloads/" -ForegroundColor Yellow
    exit 1
}

# Check if installation path exists
if (-not (Test-Path $InstallPath)) {
    Write-Host "✗ Installation path not found: $InstallPath" -ForegroundColor Red
    exit 1
}

Write-Host "✓ Installation path found" -ForegroundColor Green

if (-not $SkipVenv) {
    # Create virtual environments
    Write-Host "`n============================================" -ForegroundColor Cyan
    Write-Host "Creating Virtual Environments" -ForegroundColor Cyan
    Write-Host "============================================" -ForegroundColor Cyan

    # Medical-Analysis-main
    Write-Host "`n[1/3] Setting up Medical-Analysis service..." -ForegroundColor Yellow
    $medicalPath = Join-Path $InstallPath "src\Medical-Analysis-main"
    if (Test-Path $medicalPath) {
        Set-Location $medicalPath
        
        Write-Host "  Creating virtual environment..." -ForegroundColor Gray
        python -m venv venv
        
        Write-Host "  Installing dependencies..." -ForegroundColor Gray
        & ".\venv\Scripts\activate.ps1"
        pip install --upgrade pip -q
        if (Test-Path "requirements.txt") {
            pip install -r requirements.txt -q
        }
        deactivate
        
        Write-Host "  ✓ Medical-Analysis setup complete" -ForegroundColor Green
    } else {
        Write-Host "  ✗ Medical-Analysis directory not found" -ForegroundColor Red
    }

    # genetic_bot
    Write-Host "`n[2/3] Setting up GeneticBot service..." -ForegroundColor Yellow
    $chatbotPath = Join-Path $InstallPath "src\genetic_bot"
    if (Test-Path $chatbotPath) {
        Set-Location $chatbotPath
        
        Write-Host "  Creating virtual environment..." -ForegroundColor Gray
        python -m venv env
        
        Write-Host "  Installing dependencies..." -ForegroundColor Gray
        & ".\env\Scripts\activate.ps1"
        pip install --upgrade pip -q
        if (Test-Path "requirements.txt") {
            pip install -r requirements.txt -q
        }
        deactivate
        
        Write-Host "  ✓ GeneticBot setup complete" -ForegroundColor Green
    } else {
        Write-Host "  ✗ GeneticBot directory not found" -ForegroundColor Red
    }

    # pedigree
    Write-Host "`n[3/3] Setting up Pedigree service..." -ForegroundColor Yellow
    $pedigreePath = Join-Path $InstallPath "src\pedigree"
    if (Test-Path $pedigreePath) {
        Set-Location $pedigreePath
        
        Write-Host "  Creating virtual environment..." -ForegroundColor Gray
        python -m venv venv
        
        if (Test-Path "requirements.txt") {
            Write-Host "  Installing dependencies..." -ForegroundColor Gray
            & ".\venv\Scripts\activate.ps1"
            pip install --upgrade pip -q
            pip install -r requirements.txt -q
            deactivate
        }
        
        Write-Host "  ✓ Pedigree setup complete" -ForegroundColor Green
    } else {
        Write-Host "  ✗ Pedigree directory not found" -ForegroundColor Red
    }
}

# Create .env files from examples if they don't exist
Write-Host "`n============================================" -ForegroundColor Cyan
Write-Host "Setting up Configuration Files" -ForegroundColor Cyan
Write-Host "============================================" -ForegroundColor Cyan

$medicalEnvExample = Join-Path $InstallPath "src\Medical-Analysis-main\.env.example"
$medicalEnv = Join-Path $InstallPath "src\Medical-Analysis-main\.env"
if ((Test-Path $medicalEnvExample) -and -not (Test-Path $medicalEnv)) {
    Copy-Item $medicalEnvExample $medicalEnv
    Write-Host "✓ Created .env for Medical-Analysis" -ForegroundColor Green
    Write-Host "  ⚠ Remember to update API keys and database credentials!" -ForegroundColor Yellow
} elseif (Test-Path $medicalEnv) {
    Write-Host "✓ .env already exists for Medical-Analysis" -ForegroundColor Green
}

$chatbotEnvExample = Join-Path $InstallPath "src\genetic_bot\.env.example"
$chatbotEnv = Join-Path $InstallPath "src\genetic_bot\.env"
if ((Test-Path $chatbotEnvExample) -and -not (Test-Path $chatbotEnv)) {
    Copy-Item $chatbotEnvExample $chatbotEnv
    Write-Host "✓ Created .env for GeneticBot" -ForegroundColor Green
    Write-Host "  ⚠ Remember to update API keys!" -ForegroundColor Yellow
} elseif (Test-Path $chatbotEnv) {
    Write-Host "✓ .env already exists for GeneticBot" -ForegroundColor Green
}

# Summary
Write-Host "`n============================================" -ForegroundColor Green
Write-Host "Setup Complete!" -ForegroundColor Green
Write-Host "============================================" -ForegroundColor Green

Write-Host "`nNext Steps:" -ForegroundColor Yellow
Write-Host "  1. Configure .env files with your API keys and database credentials" -ForegroundColor White
Write-Host "     - $medicalEnv" -ForegroundColor Gray
Write-Host "     - $chatbotEnv" -ForegroundColor Gray
Write-Host ""
Write-Host "  2. Set up MySQL database:" -ForegroundColor White
Write-Host "     - Create database 'app_db'" -ForegroundColor Gray
Write-Host "     - Create user 'db_user' with password 'db_password'" -ForegroundColor Gray
Write-Host "     - Import any SQL files if provided" -ForegroundColor Gray
Write-Host ""
Write-Host "  3. Configure Apache virtual host (see DEPLOYMENT.md)" -ForegroundColor White
Write-Host ""
Write-Host "  4. Update start_servers.bat with your installation path:" -ForegroundColor White
Write-Host "     - Edit: $InstallPath\src\system\start_servers.bat" -ForegroundColor Gray
Write-Host "     - Set BASE_PATH=$InstallPath\src" -ForegroundColor Gray
Write-Host ""
Write-Host "  5. Run start_servers.bat to launch all services" -ForegroundColor White
Write-Host ""
Write-Host "For detailed instructions, see: $InstallPath\DEPLOYMENT.md" -ForegroundColor Cyan
Write-Host ""
