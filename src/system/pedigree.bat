@echo off
REM ─ Change drive & directory
cd /d C:\xampp\htdocs\GeneticBot\src\pedigree

REM ─ Activate the venv
call venv\Scripts\activate.bat

REM ─ Start the Python HTTP server on port 8001
python -m http.server 8001

REM ─ Keep window open
pause
