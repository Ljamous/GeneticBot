# ==============================================================================
# Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
#
# This code is for educational and non-commercial purposes only and may not be 
# used or redistributed without explicit written permission from the publisher.
# ==============================================================================

from io import BytesIO
import base64
from ..config.config import DB_HOST, DB_USER, DB_PASSWORD, DB_NAME
import logging
from logging.handlers import RotatingFileHandler

from fastapi.encoders import jsonable_encoder
from fastapi import APIRouter, File, UploadFile, status, Request, HTTPException, Form
from fastapi.responses import HTMLResponse, JSONResponse
from fastapi.templating import Jinja2Templates
from src.services.analysis_service import analysis_service
from typing import Optional
from docx import Document
import mysql.connector
from PIL import Image
import PyPDF2
from pydantic import BaseModel

class AnalysisRequest(BaseModel):
    userId: Optional[int]
    useUploadedFile: Optional[bool]



# ✅ Setup logger properly
logger = logging.getLogger("medical_report_logger")
logger.setLevel(logging.DEBUG)

# Rotating File Handler
rfh = RotatingFileHandler('app.log', maxBytes=5*1024*1024, backupCount=3)
rfh.setLevel(logging.DEBUG)
formatter = logging.Formatter('%(asctime)s - %(name)s - %(levelname)s - %(message)s')
rfh.setFormatter(formatter)
logger.addHandler(rfh)

# Console handler
ch = logging.StreamHandler()
ch.setLevel(logging.DEBUG)
ch.setFormatter(formatter)
logger.addHandler(ch)


MedicalRouter = APIRouter()
templates = Jinja2Templates(directory="templates")

# Helper functions to handle raw binary bytes, PDF, and DOCX safely
def to_bytes(data) -> bytes:
    """Safely converts input data (bytes, bytearray, base64-string) to raw bytes."""
    if not data:
        return b""
    if isinstance(data, (bytes, bytearray)):
        raw = bytes(data)
        if raw.startswith(b"%PDF") or raw.startswith(b"PK") or raw.startswith(b"\xff\xd8") or raw.startswith(b"\x89PNG"):
            return raw
        try:
            decoded = base64.b64decode(raw)
            if decoded.startswith(b"%PDF") or decoded.startswith(b"PK") or decoded.startswith(b"\xff\xd8") or decoded.startswith(b"\x89PNG"):
                return decoded
        except Exception:
            pass
        return raw
    if isinstance(data, str):
        cleaned = data.strip()
        if cleaned.startswith("data:"):
            cleaned = cleaned.split(",", 1)[-1]
        try:
            return base64.b64decode(cleaned)
        except Exception:
            return cleaned.encode("utf-8")
    return bytes(data)


def extract_text_from_pdf(pdf_bytes: bytes) -> str:
    """Extracts text content from PDF binary bytes."""
    pdf_reader = PyPDF2.PdfReader(BytesIO(pdf_bytes))
    text_parts = []
    for page in pdf_reader.pages:
        page_text = page.extract_text()
        if page_text:
            text_parts.append(page_text.strip())
    return "\n".join(text_parts)


def extract_text_from_docx(docx_bytes: bytes) -> str:
    """Extracts text content from DOCX binary bytes."""
    doc = Document(BytesIO(docx_bytes))
    return "\n".join([p.text for p in doc.paragraphs if p.text])


# Function to read and extract text from the docx file
def read_docx_file(file_content): 
    try:
        return extract_text_from_docx(to_bytes(file_content))
    except Exception as e:
        logger.error(f"Error reading .docx file: {str(e)}")
        return f"Error reading .docx file: {str(e)}"

# @MedicalRouter.post("/analysis/")
# async def analysis(userId: Optional[int] = Form(None), useUploadedFile: Optional[bool] = Form(None)):
#     logger.info(f"Starting analysis for userId: {userId}")
    
@MedicalRouter.post("/analysis/")
async def analysis(request: AnalysisRequest):
    logger.info(f"Starting analysis for userId: {request.userId}")
    try:
        userId = request.userId
        useUploadedFile = request.useUploadedFile
        db_config = {
            "host": DB_HOST,
            "user": DB_USER,
            "password": DB_PASSWORD,
            "database": DB_NAME
        }
        service = analysis_service(db_config)
        db_data = service.get_user_data(userId)
        logger.info(f"Data retrieved for userId: {userId}")

        if not db_data:
            logger.warning("User data not found.")
            raise HTTPException(status_code=404, detail="No data found for the provided userId.")

        pedigree = db_data.get("pedigree")
        clinical = db_data.get("clinical")

        if not pedigree:
            logger.warning("No pedigree data.")
            raise HTTPException(status_code=404, detail="No pedigree data found.")

        img_data = pedigree.get("img")

        if not clinical or not clinical.get("histologyReportContent"):
            logger.warning("No clinical history.")
            raise HTTPException(status_code=404, detail="No clinical history report found.")

        frame = None
        docs = None
        filetype = (pedigree.get("filetype") or "").lower()

        # Decide source of pedigree data
        has_img = bool(img_data)
        has_csv = bool(pedigree.get("csv"))

        use_uploaded = False
        if useUploadedFile is True:
            use_uploaded = has_img
        elif useUploadedFile is False:
            use_uploaded = not has_csv and has_img
        else:
            use_uploaded = has_img

        if use_uploaded and has_img:
            raw_bytes = to_bytes(img_data)
            if filetype == "application/pdf" or raw_bytes.startswith(b"%PDF"):
                try:
                    frame = extract_text_from_pdf(raw_bytes)
                    filetype = "application/pdf"
                    logger.info("PDF text extracted from pedigree.")
                except Exception as e:
                    logger.error(f"Error processing PDF: {e}")
                    raise HTTPException(status_code=500, detail=f"Error processing PDF: {e}")

            elif filetype.startswith("image/") or raw_bytes.startswith(b"\xff\xd8") or raw_bytes.startswith(b"\x89PNG"):
                frame = base64.b64encode(raw_bytes).decode("utf-8")
                if not filetype.startswith("image/"):
                    filetype = "image/png" if raw_bytes.startswith(b"\x89PNG") else "image/jpeg"
                logger.info("Image from pedigree encoded as base64.")

            elif filetype == "application/vnd.openxmlformats-officedocument.wordprocessingml.document" or raw_bytes.startswith(b"PK"):
                try:
                    frame = extract_text_from_docx(raw_bytes)
                    filetype = "application/vnd.openxmlformats-officedocument.wordprocessingml.document"
                    logger.info("DOCX text extracted from pedigree.")
                except Exception as e:
                    logger.error(f"Error processing DOCX: {e}")
                    raise HTTPException(status_code=500, detail=f"Error processing DOCX: {e}")
            else:
                logger.warning(f"Unsupported file type in 'img': {filetype}")

        elif has_csv:
            frame = pedigree.get("csv")
            filetype = "json"
            logger.info("CSV from pedigree loaded as JSON string.")
        else:
            logger.warning("No image or CSV data found in pedigree.")
            frame = None
            filetype = None

        # Parse clinical history document (supports both DOCX and PDF)
        histology_content = to_bytes(clinical.get("histologyReportContent"))
        histology_type = (clinical.get("histologyReportType") or "").lower()
        histology_name = (clinical.get("histologyReportName") or "").lower()

        try:
            if histology_type == "application/pdf" or histology_name.endswith(".pdf") or histology_content.startswith(b"%PDF"):
                docs = extract_text_from_pdf(histology_content)
                logger.info("PDF content parsed from clinical report.")
            else:
                docs = extract_text_from_docx(histology_content)
                logger.info("DOCX content parsed from clinical report.")
        except Exception as e:
            logger.error(f"Failed to parse clinical report content: {str(e)}")
            raise HTTPException(status_code=500, detail=f"Failed to parse clinical report file: {str(e)}")

        logger.info("Preparing to generate AI report with the following parameters:")
        logger.info(f"User ID: {userId}")
        logger.info(f"Use Uploaded File: {useUploadedFile}")
        logger.info(f"Filetype: {filetype}")
        logger.info(f"Docs length: {len(docs) if docs else 0}")

        result = service.generate_ai_report(
            frame=frame,
            docs=docs,
            userId=userId,
            filetype=filetype,
            userUploadedFile=useUploadedFile
        )

        logger.info("AI report generated successfully.")
        return JSONResponse(content=result, status_code=result.get("status_code", 200))

    except mysql.connector.Error as e:
        logger.error(f"Database error: {str(e)}")
        raise HTTPException(status_code=500, detail=f"Database error: {str(e)}")
    except HTTPException:
        raise
    except Exception as e:
        error_message = str(e).strip() or "Unknown internal error"
        logger.error(f"Unexpected error: {error_message}")
        raise HTTPException(status_code=500, detail=f"{error_message}")

    
# @MedicalRouter.post("/analysis/")
# async def analysis(userId: int = Form(...), useUploadedFile: bool = Form(...)):
#     logger.info(f"Starting analysis for userId: {userId}")

#     try:
#         service = analysis_service()
#         db_data = service.get_user_data(userId)
#         logger.info(f"service.get_user_data userId: {userId}")
#         if not db_data:
#             logger.warning("User data not found.")
#             raise HTTPException(status_code=404, detail="No data found for the provided userId.")

#         # logger.info(f"Start gettings pedigree")

#         pedigree = db_data.get("pedigree")
#         # logger.info(f"==End  gettings pedigree")

#         # logger.info(f"Start gettings clinical")

#         clinical = db_data.get("clinical")
#         # logger.info(f"==End  gettings clinical")


#         if not pedigree:
#             logger.warning("No pedigree data.")
#             raise HTTPException(status_code=404, detail="No pedigree data found.")

#         frame = None
#         docs = None
#         filetype = pedigree.get("filetype", "").lower()
#         mime_type = None

#         if useUploadedFile == True:
        
#             # Ensure 'filetype' is available
#             if pedigree and "filetype" in pedigree:
#                 ext_to_mime = {
#                     "jpg": "image/jpeg",
#                     "jpeg": "image/jpeg",
#                     "png": "image/png",
#                     "gif": "image/gif",
#                     "webp": "image/webp",
#                     "pdf": "application/pdf"
#                 }
#                 extension = ext_to_mime.get(filetype, "bin")  # default to 'bin' for unknown types
#                 mime_type = filetype if filetype in mime_to_ext else "application/octet-stream"
#         else:
#             extension = "json"
#             mime_type = "application/json"

#         # Handle image
#         if not clinical or not clinical.get("histologyReportContent"):
#             logger.warning("No clinical history.")
#             raise HTTPException(status_code=404, detail="No clinical history report found.")
        
#         if extension in ext_to_mime and mime_type.startswith("image") and pedigree.get("img"):
#             frame = base64.b64encode(clinical["histologyReportContent"]).decode("utf-8")
#             logger.info(f"Image encoded to base64 with MIME type {clinical["histologyReportType"] }.")

#         # Handle json
#         elif extension == "json" and pedigree.get("csv"):
#             docs = pedigree["csv"]
#             docs_blob = clinical["histologyReportContent"]
#         try:
#             docx_obj = Document(BytesIO(docs_blob))
#             docs = "\n".join([p.text for p in docx_obj.paragraphs])
#             logger.info("DOCX file parsed.")
#         except Exception as e:
#             logger.error(f"Failed to parse DOCX: {str(e)}")
#             logger.info("json content loaded.")

#         else:
#             logger.warning("No valid filetype found in pedigree data.")


    
        
        

#         # filetype=pedigree.get("filetype") 
#         # logger.info(f"File type: {filetype}")
#         result  = service.generate_ai_report(
#             frame=frame,
#             docs=docs,
#             userId=userId,
#             filetype=filetype,
#             userUploadedFile=useUploadedFile
#         )

#         payload = result
#         status_code = result.get("status_code", 500)
#         logger.info("AI report generated.")
        
#         return JSONResponse(content=payload, status_code=status_code)

#     except mysql.connector.Error as e:
#         logger.error(f"DB error: {str(e)}")
#         raise HTTPException(status_code=500, detail="Database error.")
#     except Exception as e:
#         logger.error(f"Unexpected error: {str(e)}")
#         raise HTTPException(status_code=500, detail="Internal server error.")

# @MedicalRouter.post("/analysis/")
# async def analysis(userId: int = Form(...)):
#     db = None
#     cursor = None
#     try:
#         logger.info(f"Starting analysis for userId: {userId}")

#         # Connect to MySQL database
#         service = analysis_service()
#         db = service.get_db()
#         cursor = db.cursor(dictionary=True)

#         # Log database connection success
#         logger.info("Database connection established successfully.")

#         # Get data from family_pedigree
#         cursor.execute("""
#             SELECT img, csv, filetype
#             FROM family_pedigree
#             WHERE userId = %s
#         """, (userId,))
#         pedigree_data = cursor.fetchone()

#         if not pedigree_data:
#             logger.warning(f"No pedigree data found for userId: {userId}")
#             raise HTTPException(
#                 status_code=status.HTTP_404_NOT_FOUND,
#                 detail="No pedigree data found for the provided userId."
#             )
        
#         logger.info(f"Pedigree data retrieved for userId: {userId}")
        
#         frame = None
#         csv_content = None
#         filetype = pedigree_data.get("filetype", "").lower()

#         if filetype == "image" and pedigree_data.get("img"):
#             frame = base64.b64encode(pedigree_data["img"]).decode("utf-8")
#             logger.info("Image data found and base64 encoded.")
#         elif filetype == "csv" and pedigree_data.get("csv"):
#             csv_content = pedigree_data["csv"]
#             logger.info("CSV data found.")
        
#         # Get histology report from clinical_histories
#         cursor.execute("""
#             SELECT histologyReportContent, histologyReportName, histologyReportType
#             FROM clinical_histories
#             WHERE userId = %s
#         """, (userId,))
#         clinical_data = cursor.fetchone()

#         if not clinical_data or not clinical_data["histologyReportContent"]:
#             logger.warning(f"No clinical history report found for userId: {userId}")
#             raise HTTPException(
#                 status_code=status.HTTP_404_NOT_FOUND,
#                 detail="No clinical history report found for the provided userId."
#             )

#         # Extract .docx content from blob
#         docs = read_docx_file(clinical_data["histologyReportContent"])

#         # Log report generation
#         logger.info(f"Generating AI report for userId: {userId}.")

#         # Generate report
#         payload, status_code = service.generate_ai_report(
#             frame=frame,
#             docs=docs if docs else csv_content,
#             userId=userId
#         )

#         logger.info(f"Report successfully generated for userId: {userId}")
#         return JSONResponse(content=payload, status_code=status_code)

#     except mysql.connector.Error as e:
#         logger.error(f"Database error: {str(e)}")
#         raise HTTPException(
#             status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
#             detail=f"Database error: {str(e)}"
#         )
#     except Exception as e:
#         logger.error(f"Unexpected error: {str(e)}")
#         raise HTTPException(
#             status_code=status.HTTP_500_INTERNAL_SERVER_ERROR,
#             detail=f"Error: {str(e)}"
#         )
#     finally:
#         # Ensure the cursor and database connection are closed properly
#         if cursor:
#             cursor.close()
#             logger.info("Database cursor closed.")
#         if db:
#             db.close()
#             logger.info("Database connection closed.")
