# GeneticBot Project - Genetic Testing & Medical Analysis Platform

> **Disclaimer**
> This code is for educational purposes only and may not be used or redistributed without explicit written permission from the original publisher.

A comprehensive web application for genetic counseling, pedigree analysis, and medical report generation. Moving from traditional manual processes to an automated, AI-driven platform.

## 📋 Table of Contents

- [Prerequisites](#prerequisites)
- [Project Architecture](#project-architecture)
- [Installation & Setup](#installation--setup)
  - [Docker Setup (Recommended)](#docker-setup-recommended)
  - [Native Setup (XAMPP / Manual)](#native-setup-xampp--manual)
- [Running the Application](#running-the-application)
- [Services Overview](#services-overview)
- [Troubleshooting](#troubleshooting)

---

## 🛠 Prerequisites

Before you begin, ensure you have the following installed on your machine depending on your Operating System:

### 💻 Windows
1. **Docker Desktop**: Download and install [Docker Desktop for Windows](https://www.docker.com/products/docker-desktop/). Ensure WSL 2 backend is enabled.
2. **Git**: Download and install [Git for Windows](https://git-scm.com/downloads).
3. **Python 3.8+** (For native execution): Download and install [Python](https://www.python.org/downloads/windows/).

### 🍎 macOS
1. **Docker Desktop**: Download and install [Docker Desktop for Mac](https://www.docker.com/products/docker-desktop/) (Choose Intel or Apple Silicon/M-series chips accordingly).
2. **Git**: Installed by default or via Homebrew (`brew install git`).
3. **Python 3.8+** (For native execution): Installed via Homebrew (`brew install python@3.11`).

### 🐧 Linux (Ubuntu/Debian/CentOS)
1. **Docker Engine & Docker Compose**: 
   - [Install Docker Engine](https://docs.docker.com/engine/install/)
   - [Install Docker Compose plugin](https://docs.docker.com/compose/install/) (Ensure you can run `docker compose` without `sudo` by adding your user to the `docker` group).
2. **Git**: Installed via package manager (e.g., `sudo apt install git`).
3. **Python 3.8+** (For native execution): Installed via package manager (`sudo apt install python3 python3-venv python3-pip`).

---

## 🏗 Project Architecture

The application is containerized using Docker and consists of the following services:

| Service              | Technology         | Internal Port | External Port | Description                                        |
| :------------------- | :----------------- | :------------ | :------------ | :------------------------------------------------- |
| **system**           | PHP 8.2 + Apache   | 80            | **80**        | Main web application frontend and logic.           |
| **db**               | MySQL 8.0          | 3306          | **3307**      | Relational database for user and application data. |
| **medical-analysis** | Python (FastAPI)   | 8000          | **8000**      | AI engine for analyzing medical reports.           |
| **pedigree**         | Python (HTTP)      | 8001          | **8001**      | Service for handling pedigree file processing.     |
| **genetic-bot**      | Python (Streamlit) | 8501          | **8501**      | Interactive AI GeneticBot for user assistance.     |

---

## 🚀 Installation & Setup

### 1. Clone the Repository
Open your terminal (Command Prompt/PowerShell on Windows, Terminal on macOS/Linux) and run:
```bash
git clone https://github.com/Ljamous/GeneticBot.git
cd GeneticBot
```

### Docker Setup (Recommended)

This is the easiest way to run the entire stack on any Operating System (Windows, macOS, or Linux).

1. **Verify Docker configuration:** Ensure `docker-compose.yml` is present in the root directory.
2. **Launch all containers:**
   ```bash
   docker compose up -d --build
   ```
3. **Access the application:** Once successfully built and running, skip to the [Running the Application](#running-the-application) section.

---

### Native Setup (XAMPP / Manual)

If you prefer to run services natively (outside Docker) using Apache + MySQL (e.g. XAMPP):

#### 1. Setup Project Directory
Copy the `GeneticBot` project folder to your local web root:
* **Windows (XAMPP)**: `C:\xampp\htdocs\GeneticBot`
* **macOS (XAMPP)**: `/Applications/XAMPP/htdocs/GeneticBot`
* **Linux (Apache)**: `/var/www/html/GeneticBot`

#### 2. Create Python Virtual Environments & Install Dependencies

##### 💻 Windows (PowerShell):
```powershell
# Medical Analysis
cd src\Medical-Analysis-main
python -m venv venv
.\venv\Scripts\activate.ps1
pip install -r requirements.txt
deactivate

# GeneticBot Chatbot
cd ..\genetic_bot
python -m venv env
.\env\Scripts\activate.ps1
pip install -r requirements.txt
deactivate

# Pedigree Service
cd ..\pedigree
python -m venv venv
deactivate
```

##### 🍎 macOS / 🐧 Linux (Terminal):
```bash
# Medical Analysis
cd src/Medical-Analysis-main
python3 -m venv venv
source venv/bin/activate
pip install -r requirements.txt
deactivate

# GeneticBot Chatbot
cd ../genetic_bot
python3 -m venv env
source env/bin/activate
pip install -r requirements.txt
deactivate

# Pedigree Service
cd ../pedigree
python3 -m venv venv
deactivate
```

#### 3. Database Creation & Credentials
1. Open phpMyAdmin (`http://localhost/phpmyadmin`) or your MySQL client.
2. Create a database named `app_db`.
3. Create a user:
   - **Username**: `db_user`
   - **Password**: `db_password`
4. Grant all privileges on `app_db` to `db_user`.
5. Import `src/system/database/app_db.sql` into the newly created `app_db` database.

#### 4. Configure Local Hosts
To map the custom local domain `geneticbot.local` to localhost:

* **Windows**: Edit `C:\Windows\System32\drivers\etc\hosts` (as Administrator) and add:
  ```text
  127.0.0.1 geneticbot.local
  ```
* **macOS / Linux**: Edit `/etc/hosts` (using `sudo nano /etc/hosts`) and add:
  ```text
  127.0.0.1 geneticbot.local
  ```

---

## ▶️ Running the Application

### Under Docker (All OS)
- **Start Services**: `docker compose up -d --build`
- **Check Status**: `docker ps`
- **Stop Services**: `docker compose down`

### Natively (Without Docker)

#### Windows
1. Open XAMPP Control Panel and start **Apache** and **MySQL**.
2. Launch the python services by running the startup batch script:
   ```batch
   cd src\system
   start_servers.bat
   ```

#### macOS / Linux
1. Start your local Apache & MySQL services.
2. Run individual background services in separate terminal windows:
   ```bash
   # Start Medical Analysis API (Port 8000)
   cd src/Medical-Analysis-main
   source venv/bin/activate
   uvicorn main:app --host 0.0.0.0 --port 8000
   
   # Start Pedigree Service (Port 8001)
   cd ../pedigree
   source venv/bin/activate
   python3 -m http.server 8001
   
   # Start GeneticBot Streamlit Interface (Port 8501)
   cd ../genetic_bot
   source env/bin/activate
   streamlit run ui_app.py --server.port 8501
   ```

### 🌐 Access URLs (All Environments)
- **Main Web Application**: [http://geneticbot.local](http://geneticbot.local) (or [http://localhost](http://localhost) if Docker/VHost is not mapped)
- **FastAPI Documentation**: [http://localhost:8000/docs](http://localhost:8000/docs)
- **GeneticBot chatbot**: [http://localhost:8501](http://localhost:8501)

---

## 🛑 Stopping the Application (Docker)
To stop and clean up containers:
```bash
docker compose down
```
To delete database volumes for a clean state:
```bash
docker compose down -v
```

---

## 🔧 Troubleshooting

### "Not Found" or 404 Errors on Pages (Linux/Docker)
- **Cause**: Linux is case-sensitive regarding filenames, unlike Windows.
- **Fix**: Ensure links and redirects match file case exactly (e.g., `signup.php` and not `Signup.php`).

### Database Connection Error
1. Verify database service is running (`docker ps` or XAMPP Panel).
2. Ensure your credentials in `src/Medical-Analysis-main/.env` match `db_user` and `db_password`.

---

## 📞 Support
For issues related to the codebase or deployment, please open an issue in the GitHub repository issues tracker.
