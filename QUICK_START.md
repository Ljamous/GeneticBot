# Lama Application - Quick Reference

## 🚀 Quick Start (New Machine)

### 1. One-Time Setup
```powershell
# Run automated setup script
powershell -ExecutionPolicy Bypass -File setup.ps1

# OR specify custom path
powershell -ExecutionPolicy Bypass -File setup.ps1 -InstallPath "D:\MyProjects\Lama"
```

### 2. Configure Environment
Edit these files with your credentials:
- `src\Medical-Analysis-main\.env`
- `src\genetic_bot\.env`

### 3. Setup Database
- Create database: `lamadb`
- Create user: `Lama` / `Lama@2025`
- Import SQL if provided

### 4. Configure Apache
Add to `C:\xampp\apache\conf\extra\httpd-vhosts.conf`:
```apache
<VirtualHost *:80>
    ServerName lama.local
    DocumentRoot "C:/xampp/htdocs/Lama2/src/Lama"
    <Directory "C:/xampp/htdocs/Lama2/src/Lama">
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

Add to `C:\Windows\System32\drivers\etc\hosts`:
```
127.0.0.1 lama.local
```

### 5. Update Start Script
Edit `src\lama\start_servers.bat`:
```batch
set "BASE_PATH=C:\xampp\htdocs\Lama2\src"
set "XAMPP_PATH=C:\xampp"
```

### 6. Launch Application
```batch
cd src\lama
start_servers.bat
```

---

## 📁 Project Structure

```
Lama2/
├── src/
│   ├── Lama/                    # Main PHP application
│   │   └── start_servers.bat    # Launch script
│   ├── Medical-Analysis-main/   # FastAPI service (Port 8000)
│   │   ├── .env                 # Configuration (create from .env.example)
│   │   └── venv/                # Python virtual environment
│   ├── Lama_pedegree/          # Pedigree service (Port 8001)
│   │   └── venv/               # Python virtual environment
│   └── genetic_bot/            # Streamlit GeneticBot (Port 8501)
│       ├── .env                # Configuration (create from .env.example)
│       └── env/                # Python virtual environment
├── DEPLOYMENT.md               # Full deployment guide
├── setup.ps1                   # Automated setup script
└── README.md
```

---

## 🔧 Configuration Files

### Medical-Analysis (.env)
```env
DB_HOST=localhost
DB_USER=Lama
DB_PASSWORD=Lama@2025
DB_NAME=lamadb
OPENAI_API_KEY=your_key_here
```

### GeneticBot (.env)
```env
OPENAI_API_KEY=your_key_here
```

---

## 🌐 Service URLs

| Service | URL | Description |
|---------|-----|-------------|
| Main App | http://lama.local | PHP/Apache application |
| Medical API | http://localhost:8000 | FastAPI medical analysis |
| Pedigree | http://localhost:8001 | Pedigree visualization |
| GeneticBot | http://localhost:8501 | Streamlit GeneticBot |

---

## 🛠️ Common Commands

### Start All Services
```batch
cd src\lama
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
cd src\Lama_pedegree
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
3. Test: `mysql -u Lama -p`

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
