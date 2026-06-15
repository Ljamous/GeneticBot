# Lama Application - Deployment Guide

## Overview

This guide explains how to deploy the Lama application on a different machine with minimal configuration. The application consists of 4 main services:

1. **Lama** - Main PHP/Apache web application (http://lama.local)
2. **Medical-Analysis** - FastAPI service for medical analysis (Port 8000)
3. **Lama_pedegree** - Pedigree visualization service (Port 8001)
4. **genetic_bot** - Streamlit GeneticBot interface (Port 8501)

## Prerequisites

### Required Software

1. **Python 3.8+** - [Download](https://www.python.org/downloads/)
2. **XAMPP** - [Download](https://www.apachefriends.org/)
   - Includes Apache and MySQL/MariaDB
3. **Git** (optional) - For cloning the repository

### System Requirements

- Windows OS (the current scripts are Windows-specific)
- At least 4GB RAM
- 5GB free disk space

## Installation Steps

### 1. Install Prerequisites

1. Install Python 3.8 or higher
   - During installation, check "Add Python to PATH"
   - Verify: `python --version`

2. Install XAMPP
   - Default installation path: `C:\xampp`
   - Start Apache and MySQL from XAMPP Control Panel

### 2. Clone/Copy Project Files

Copy the entire project to your desired location. For this guide, we'll use:

```
C:\xampp\htdocs\Lama2\
```

**Note**: You can use any location, but you'll need to update the configuration accordingly.

### 3. Configure Environment Variables

#### Create `.env` file for Medical-Analysis

Create a file at `src\Medical-Analysis-main\.env`:

```env
# Project Details
PROJECT_NAME=Phonetics
DESCRIPTION=Phonetic Transliteration API

# FastAPI Config
HOST=0.0.0.0
PORT=8000
DEBUG=false
VERSION=1.0.0
LOG_LEVEL=info

# OpenAI API (if needed)
OPENAI_API_KEY=your_openai_key_here

# MySQL Database
DB_HOST=localhost
DB_USER=db_user
DB_PASSWORD=db_password
DB_NAME=app_db
```

#### Create `.env` file for genetic_bot

Create a file at `src\genetic_bot\.env`:

```env
# Add any required API keys or configuration
OPENAI_API_KEY=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
```

### 4. Set Up Python Virtual Environments

Open PowerShell or Command Prompt and run:

#### For Medical-Analysis

```powershell
cd C:\xampp\htdocs\GeneticBot\src\Medical-Analysis-main
python -m venv venv
.\venv\Scripts\activate
pip install -r requirements.txt
```

#### For genetic_bot

```powershell
cd C:\xampp\htdocs\GeneticBot\src\genetic_bot
python -m venv env
.\env\Scripts\activate
pip install -r requirements.txt
```

#### For pedigree

```powershell
cd C:\xampp\htdocs\GeneticBot\src\pedigree
python -m venv venv
.\venv\Scripts\activate
# Install any dependencies if requirements.txt exists
```

### 5. Configure Database

1. Open phpMyAdmin: `http://localhost/phpmyadmin`
2. Create a new database named `app_db`
3. Create a user:
   - Username: `db_user`
   - Password: `db_password`
   - Grant all privileges on `app_db`
4. Import any SQL files if provided (e.g., `ptsdb.sql`)

### 6. Configure Apache Virtual Host

1. Edit `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
2. Add the following configuration:

```apache
<VirtualHost *:80>
    ServerName geneticbot.local
    DocumentRoot "C:/xampp/htdocs/GeneticBot/src/system"
    <Directory "C:/xampp/htdocs/GeneticBot/src/system">
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

3. Edit `C:\Windows\System32\drivers\etc\hosts` (as Administrator)
4. Add this line:

```
127.0.0.1 geneticbot.local
```

5. Restart Apache from XAMPP Control Panel

### 7. Update Start Script

Edit `src\system\start_servers.bat` and update the paths:

```batch
@echo off
:: Set base path - CHANGE THIS TO YOUR INSTALLATION PATH
set "BASE_PATH=C:\xampp\htdocs\Lama2\src"
set "XAMPP_PATH=C:\xampp"

:: Start pedigree server (Port 8001) in minimized CMD
start "" /min cmd /k "cd /d %BASE_PATH%\pedigree && call venv\Scripts\activate && python -m http.server 8001"

:: Start Medical-Analysis-main server (Port 8000) in minimized CMD
start "" /min cmd /k "cd /d %BASE_PATH%\Medical-Analysis-main && call venv\Scripts\activate && uvicorn main:app --host 0.0.0.0 --port 8000"

:: Start genetic_bot (Streamlit - Port 8501) in minimized CMD
start "" /min cmd /k "cd /d %BASE_PATH%\genetic_bot && call env\Scripts\activate && streamlit run ui_app.py --server.port 8501"

:: Start Apache directly using httpd.exe
start "" /min "%XAMPP_PATH%\apache\bin\httpd.exe"

:: Open the local site in browser
start "" http://lama.local

:: Keep main window open if needed
echo All services launched minimized. Press any key to close this launcher...
pause >nul
```

## Running the Application

### Option 1: Using the Start Script (Recommended)

1. Double-click `src\system\start_servers.bat`
2. All services will start automatically
3. Your browser will open to `http://lama.local`

### Option 2: Manual Start

Start each service individually:

```powershell
# Terminal 1 - Medical Analysis
cd C:\xampp\htdocs\Lama2\src\Medical-Analysis-main
.\venv\Scripts\activate
uvicorn main:app --host 0.0.0.0 --port 8000

# Terminal 2 - Pedigree Service
cd C:\xampp\htdocs\Lama2\src\pedigree
.\venv\Scripts\activate
python -m http.server 8001

# Terminal 3 - GeneticBot
cd C:\xampp\htdocs\Lama2\src\genetic_bot
.\env\Scripts\activate
streamlit run ui_app.py --server.port 8501

# Start Apache from XAMPP Control Panel
```

## Accessing the Services

- **Main Application**: http://lama.local
- **Medical Analysis API**: http://localhost:8000
- **Pedigree Service**: http://localhost:8001
- **GeneticBot**: http://localhost:8501

## Troubleshooting

### Port Already in Use

If you get "port already in use" errors:

1. Check what's using the port:
   ```powershell
   netstat -ano | findstr :8000
   ```
2. Kill the process or change the port in the configuration

### Database Connection Failed

1. Verify MySQL is running in XAMPP Control Panel
2. Check database credentials in `.env` file
3. Ensure database `app_db` exists
4. Test connection: `mysql -u db_user -p`

### Apache Won't Start

1. Check if port 80 is available
2. Check Apache error logs: `C:\xampp\apache\logs\error.log`
3. Verify virtual host configuration

### Python Module Not Found

```powershell
# Activate the virtual environment first
.\venv\Scripts\activate
# Then install missing packages
pip install <package-name>
```

## Quick Setup Script

For even faster deployment, you can use this PowerShell script. Save as `setup.ps1`:

```powershell
# Quick Setup Script for GeneticBot Application
param(
    [string]$InstallPath = "C:\xampp\htdocs\GeneticBot"
)

Write-Host "Setting up GeneticBot Application at: $InstallPath" -ForegroundColor Green

# Create virtual environments
Write-Host "`nCreating virtual environments..." -ForegroundColor Yellow

Set-Location "$InstallPath\src\Medical-Analysis-main"
python -m venv venv
.\venv\Scripts\activate
pip install -r requirements.txt
deactivate

Set-Location "$InstallPath\src\genetic_bot"
python -m venv env
.\env\Scripts\activate
pip install -r requirements.txt
deactivate

Set-Location "$InstallPath\src\pedigree"
python -m venv venv

Write-Host "`nSetup complete!" -ForegroundColor Green
Write-Host "Next steps:" -ForegroundColor Yellow
Write-Host "1. Configure .env files"
Write-Host "2. Set up database"
Write-Host "3. Configure Apache virtual host"
Write-Host "4. Run start_servers.bat"
```

Run with:

```powershell
powershell -ExecutionPolicy Bypass -File setup.ps1
```

## Configuration Summary

### Files to Update for New Machine

1. **`src\system\start_servers.bat`** - Update `BASE_PATH` and `XAMPP_PATH`
2. **`src\Medical-Analysis-main\.env`** - Database and API configuration
3. **`src\genetic_bot\.env`** - API keys and configuration
4. **Apache virtual host** - Update DocumentRoot path
5. **Windows hosts file** - Add `geneticbot.local` entry

### Default Credentials

- **Database User**: db_user
- **Database Password**: db_password
- **Database Name**: app_db

**Important**: Change these credentials in production!

## Next Steps

After successful deployment:

1. Test all services are running
2. Verify database connectivity
3. Check all application features
4. Update any hardcoded URLs in the PHP code if needed
5. Configure backups for database and uploaded files

## Support

For issues or questions, refer to the project documentation or contact the development team.
