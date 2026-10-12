# GeneticBot Application - Quick Reference

## 🚀 Quick Start (New Machine)

### 1. One-Time Setup

```powershell
# Run automated setup script
powershell -ExecutionPolicy Bypass -File setup.ps1

# OR specify custom path
powershell -ExecutionPolicy Bypass -File setup.ps1 -InstallPath "D:\MyProjects\GeneticBot"
```

### 2. Configure Environment

Edit these files with your credentials:

- `src\Medical-Analysis-main\.env`
- `src\genetic_bot\.env`

### 3. Setup Database

- Create database: `app_db`
- Create user: `db_user` / `db_password`
- Import SQL if provided

### 4. Configure Apache

Add to `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:

```apache
<VirtualHost *:80>
    ServerName geneticbot.local
    DocumentRoot "C:/xampp/htdocs/GeneticBot/src/system"
    <Directory "C:/xampp/htdocs/GeneticBot/src/system">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Add to `C:\Windows\System32\drivers\etc\hosts`:

```
127.0.0.1 geneticbot.local
```

### 5. Update Start Script

Edit `src\system\start_servers.bat`:

```batch
set "BASE_PATH=C:\xampp\htdocs\GeneticBot\src"
set "XAMPP_PATH=C:\xampp"
```

### 6. Launch Application

```batch
cd src\system
start_servers.bat
```

---

## 📁 Project Structure

```
GeneticBot/
├── src/
│   ├── system/                  # Main PHP application
│   │   └── start_servers.bat    # Launch script
│   ├── Medical-Analysis-main/   # FastAPI service (Port 8000)
│   │   ├── .env                 # Configuration (create from .env.example)
│   │   └── venv/                # Python virtual environment
│   ├── pedigree/               # Pedigree service (Port 8001)
│   │   └── venv/               # Python virtual environment
│   └── genetic_bot/            # Streamlit GeneticBot (Port 8501)
│       ├── .env                # Configuration (create from .env.example)
│       └── env/                # Python virtual environment
├── DEPLOYMENT.md               # Full deployment guide
├── setup.ps1                   # Automated setup script
└── README.md
```

---

## 🔧 Central Configuration File

All configurations across all services and Docker containers are centralized in a single `.env` file at the project root (`.env`):

```env
# OpenAI API
OPENAI_API_KEY=your_openai_api_key_here
OPENAI_MODEL=gpt-4o

# Database (MySQL)
DB_HOST=localhost
DOCKER_DB_HOST=db
DB_PORT=3306
DB_EXTERNAL_PORT=3307
DB_USER=db_user
DB_PASSWORD=db_password
DB_NAME=app_db
MYSQL_ROOT_PASSWORD=db_password

# Medical Analysis API
PROJECT_NAME="Medical Analysis"
DESCRIPTION="Medical Analysis API"
HOST=0.0.0.0
PORT=8000
DEBUG=true
VERSION="1.0.0"
LOG_LEVEL="INFO"
SECRET_KEY="rE}.;m<:^xI2FEx~5Z&U:L3"

# Service Ports
SYSTEM_PORT=80
MEDICAL_ANALYSIS_PORT=8000
PEDIGREE_PORT=8001
GENETIC_BOT_PORT=8501
```

> **Note**: Update your OpenAI key or database settings directly in this single `.env` file. No code or container files need to be edited.

---

## 🌐 Service URLs

| Service     | URL                     | Description              |
| ----------- | ----------------------- | ------------------------ |
| Main App    | http://geneticbot.local | PHP/Apache application   |
| Medical API | http://localhost:8000   | FastAPI medical analysis |
| Pedigree    | http://localhost:8001   | Pedigree visualization   |
| GeneticBot  | http://localhost:8501   | Streamlit GeneticBot     |

---

## 🛠️ Common Commands

### Start All Services

```batch
cd src\system
start_servers.bat
```

### Start Individual Services

**Medical Analysis:**

```powershell
cd src\Medical-Analysis-main
.\venv\Scripts\activate
uvicorn main:app --host 0.0.0.0 --port 8000
```

**Pedigree:**

```powershell
cd src\pedigree
.\venv\Scripts\activate
python -m http.server 8001
```

**GeneticBot:**

```powershell
cd src\genetic_bot
.\env\Scripts\activate
streamlit run ui_app.py --server.port 8501
```

### Reinstall Dependencies

```powershell
# Medical-Analysis
cd src\Medical-Analysis-main
.\venv\Scripts\activate
pip install -r requirements.txt --upgrade

# GeneticBot
cd src\genetic_bot
.\env\Scripts\activate
pip install -r requirements.txt --upgrade
```

---

## 🐛 Troubleshooting

### Port Already in Use

```powershell
# Find process using port
netstat -ano | findstr :8000

# Kill process (replace PID)
taskkill /PID <PID> /F
```

### Database Connection Failed

1. Check MySQL is running in XAMPP
2. Verify credentials in `.env`
3. Test: `mysql -u db_user -p`

### Apache Won't Start

1. Check port 80: `netstat -ano | findstr :80`
2. Check logs: `C:\xampp\apache\logs\error.log`
3. Restart from XAMPP Control Panel

### Virtual Environment Issues

```powershell
# Recreate venv
Remove-Item -Recurse -Force venv
python -m venv venv
.\venv\Scripts\activate
pip install -r requirements.txt
```

---

## 📋 Checklist for New Machine

- [ ] Install Python 3.8+
- [ ] Install XAMPP
- [ ] Copy project files
- [ ] Run `setup.ps1`
- [ ] Create `.env` files from `.env.example`
- [ ] Configure database (create DB, user, import SQL)
- [ ] Configure Apache virtual host
- [ ] Update `hosts` file
- [ ] Update `start_servers.bat` paths
- [ ] Test: Run `start_servers.bat`
- [ ] Verify all services are accessible

---

## 🔒 Security Notes

**For Production:**

- Change default database password
- Use strong passwords
- Configure firewall rules
- Enable HTTPS
- Secure API keys (use environment variables)
- Regular backups

---

## 📞 Support

For detailed instructions, see [DEPLOYMENT.md](DEPLOYMENT.md)
