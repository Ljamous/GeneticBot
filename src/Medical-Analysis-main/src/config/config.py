from os import getenv
from dotenv import load_dotenv

load_dotenv()


def str_to_bool(value: str) -> bool:
    return value.lower() in ("true", "1", "yes")


# ---------- Project Details ----------
PROJECT_NAME = getenv("PROJECT_NAME", "Phonetics")
DESCRIPTION = getenv("DESCRIPTION", "Phonetic Transliteration API")

# ---------- FastAPI Config ----------
HOST = getenv("HOST", "0.0.0.0")
PORT = int(getenv("PORT", 8001))
DEBUG = str_to_bool(getenv("DEBUG", "false"))
VERSION = getenv("VERSION", "1.0.0")
LOG_LEVEL = getenv("LOG_LEVEL", "info")

# ---------- OpenAI API ----------
OPENAI_API_KEY = getenv("OPENAI_API_KEY")

# ---------- MySQL Database ----------
DB_HOST = getenv("DB_HOST", "localhost")
DB_USER = getenv("DB_USER", "db_user")
DB_PASSWORD = getenv("DB_PASSWORD", "db_password")
DB_NAME = getenv("DB_NAME", "app_db")

# ---------- DB Connection Function ----------
import mysql.connector
from mysql.connector import MySQLConnection


def get_db() -> MySQLConnection:
    db = mysql.connector.connect(
        host=DB_HOST,
        user=DB_USER,
        password=DB_PASSWORD,
        database=DB_NAME
    )
    return db
