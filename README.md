# GeneticBot Project - Genetic Testing & Medical Analysis Platform

A comprehensive web application for genetic counseling, pedigree analysis, and medical report generation. Moving from traditional manual processes to an automated, AI-driven platform.

## 📋 Table of Contents

- [Prerequisites](#prerequisites)
- [Project Architecture](#project-architecture)
- [Installation & Setup](#installation--setup)
- [Running the Application](#running-the-application)
- [Services Overview](#services-overview)
- [Troubleshooting](#troubleshooting)

## 🛠 Prerequisites

Before you begin, ensure you have the following installed on your machine:

1.  **Docker Desktop** (Required)

    - Download: [https://www.docker.com/products/docker-desktop/](https://www.docker.com/products/docker-desktop/)
    - _Note:_ Ensure Docker Engine is running.

2.  **Git** (Required for cloning)

    - Download: [https://git-scm.com/downloads](https://git-scm.com/downloads)

3.  **Visual Studio Code** (Recommended for editing)
    - Download: [https://code.visualstudio.com/](https://code.visualstudio.com/)

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

Open your terminal (PowerShell, Command Prompt, or Git Bash) and run:

```bash
git clone https://github.com/Ljamous/GeneticBot.git
cd GeneticBot
```

### 2. Verify Docker Configuration

Ensure your `docker-compose.yml` is present in the root directory. This file handles the orchestration of all services.

### 3. Database Initialization

The project includes an automatic initialization script for the database.

- **Source:** `src/system/database/app_db.sql`
- **Action:** When you run the project for the first time, this SQL file is automatically imported into the MySQL container to set up the schema and default tables.

---

## ▶️ Running the Application

### 1. Start All Services

To build and start the entire application stack, run:

```bash
docker compose up -d --build
```

- `--build`: Forces a rebuild of the images (useful if you've updated code).
- `-d`: Runs containers in "detached" mode (in the background).

> **Initial Startup:** The first run may take **10-15 minutes** as it needs to download large Docker images (MySQL, Python, PHP) and install dependencies. Please be patient.

### 2. Check Service Status

To verify that all containers are running correctly:

```bash
docker ps
```

- You should see 5 active containers listed (system, db, medical-analysis, pedigree, genetic-bot).

### 3. Access the Application

Once the services are running, access the application in your browser:

- **Main Application:** [http://localhost](http://localhost)
- **Signup Page:** [http://localhost/signup.php](http://localhost/signup.php)
- **Medical Analysis API:** [http://localhost:8000/docs](http://localhost:8000/docs) (Swagger UI)
- **GeneticBot:** [http://localhost:8501](http://localhost:8501)

---

## 🛑 Stopping the Application

To stop the containers:

```bash
docker compose stop
```

To stop and **remove** containers (preserves database data):

```bash
docker compose down
```

To stop, remove containers, and **delete database data** (Fresh Start):

> **Warning:** This deletes all registered users and data!

```bash
docker compose down -v
```

---

## 🔧 Troubleshooting

### Database Connection Error

If the PHP app cannot connect to the database:

1.  Ensure the `db` container is running: `docker ps`.
2.  If you recently changed schemas, try resetting the volume:
    ```bash
    docker compose down -v
    docker compose up -d --build
    ```

### "Not Found" or 404 Errors on Pages

- **Cause:** PHP filenames are **case-sensitive** in the Docker container (Linux environment).
- **Fix:** Ensure your code links point to the exact filename (e.g., `signup.php`, not `Signup.php`).

### Docker Build Stuck / Slow

- **Cause:** Slow internet connection causing large image downloads (MySQL/Python) to hang.
- **Fix:** You can try pulling images individually:
  ```bash
  docker pull mysql:8.0
  docker pull python:3.11-slim
  ```
  Or start only the core services first:
  ```bash
  docker compose up -d --build db system
  ```

### NCCN Test Error (NoneType)

- **Cause:** This usually happens if you try to run the NCCN test without uploading a pedigree or clinical history first.
- **Fix:** Ensure you have completed the previous steps in the workflow (Clinical History upload) before initiating the NCCN test. (Note: A code fix was applied in v2.0 to prevent the crash, showing a graceful error instead).

---

## 📞 Support

For issues related to the codebase or deployment, please check the GitHub repository issues tracker.
