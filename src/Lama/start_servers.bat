@echo off
:: ============================================
:: Lama Application - Server Startup Script
:: ============================================
:: This script starts all required services for the Lama application
:: 
:: CONFIGURATION REQUIRED:
:: Update the paths below to match your installation directory
:: ============================================

:: ============================================
:: CONFIGURATION SECTION - UPDATE THESE PATHS
:: ============================================

:: Set base path - CHANGE THIS TO YOUR INSTALLATION PATH
:: Example: C:\xampp\htdocs\Lama2\src or D:\Projects\Lama\src
set "BASE_PATH=d:\Shared\Lama2\Lama\src"

:: Set XAMPP installation path
set "XAMPP_PATH=C:\xampp"

:: ============================================
:: END CONFIGURATION SECTION
:: ============================================

echo ============================================
echo Starting Lama Application Services
echo ============================================
echo.
echo Base Path: %BASE_PATH%
echo XAMPP Path: %XAMPP_PATH%
echo.
echo Starting services...
echo.

:: Start Lama_pedegree server (Port 8001) in minimized CMD
echo [1/4] Starting Lama Pedigree Service (Port 8001)...
start "" /min cmd /k "cd /d %BASE_PATH%\Lama_pedigree && call venv\Scripts\activate && python -m http.server 8001"

:: Wait a moment before starting next service
timeout /t 2 /nobreak >nul

:: Start Medical-Analysis-main server (Port 8000) in minimized CMD
echo [2/4] Starting Medical Analysis API (Port 8000)...
start "" /min cmd /k "cd /d %BASE_PATH%\Medical-Analysis-main && call venv\Scripts\activate && uvicorn main:app --host 0.0.0.0 --port 8000"

:: Wait a moment before starting next service
timeout /t 2 /nobreak >nul

:: Start lama_chatbot (Streamlit - Port 8501) in minimized CMD
echo [3/4] Starting GeneticBot (Port 8501)...
start "" /min cmd /k "cd /d %BASE_PATH%\genetic_bot && call env\Scripts\activate && streamlit run ui_app.py --server.port 8501"

:: Wait a moment before starting next service
timeout /t 2 /nobreak >nul

:: Start Apache directly using httpd.exe
echo [4/4] Starting Apache Web Server...
start "" /min "%XAMPP_PATH%\apache\bin\httpd.exe"

:: Wait for services to initialize
echo.
echo Waiting for services to initialize...
timeout /t 5 /nobreak >nul

:: Open the local site in browser
echo.
echo Opening application in browser...
start "" http://lama.local

echo.
echo ============================================
echo All services launched successfully!
echo ============================================
echo.
echo Services running:
echo   - Main Application: http://lama.local
echo   - Medical Analysis API: http://localhost:8000
echo   - Pedigree Service: http://localhost:8001
echo   - GeneticBot: http://localhost:8501
echo.
echo All service windows are minimized.
echo To stop services, close their respective windows.
echo.
echo Press any key to close this launcher...
pause >nul
