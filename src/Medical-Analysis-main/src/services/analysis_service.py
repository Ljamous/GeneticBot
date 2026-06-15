import base64
import logging
import mysql.connector
import json
import sys
import datetime
from io import BytesIO
from docx import Document
import PyPDF2
from PIL import Image
import openai
import re
from ..config.config import get_db, OPENAI_API_KEY, DB_HOST, DB_USER, DB_PASSWORD, DB_NAME

# Configure the logger
logging.basicConfig(
    level=logging.DEBUG,
    format="%(asctime)s - %(name)s - %(levelname)s - %(message)s",
    handlers=[
        logging.FileHandler("analysis_service.log", mode='a', encoding='utf-8'),
        logging.StreamHandler(sys.stdout)  
    ]
)

logger = logging.getLogger("analysis_service")


class analysis_service:
    """Service to handle AI-based analysis of medical documents and family history data."""

    def __init__(self, db_config, context_path="assets/context.txt"):
        self.context_path = context_path
        self.openai_key = OPENAI_API_KEY
        openai.api_key = self.openai_key
        self.model = "gpt-4o"
        self.db_config = db_config

    def get_db(self):
        try:
            conn = mysql.connector.connect(**self.db_config)
            if conn.is_connected():
                logger.info("Database connection successful")
                return conn
            raise Exception("Failed to connect to the database")
        except Error as e:
            logger.error("Error connecting to the database: %s", e, exc_info=True)
            return None

    def get_user_data(self, user_id):
        logger.debug("Fetching user data for user_id: %s", user_id)
        try:
            conn = self.get_db()
            cursor = conn.cursor(dictionary=True)

            cursor.execute("""
                SELECT img, csv, filetype FROM family_pedigree WHERE userId = %s
            """, (user_id,))
            pedigree = cursor.fetchone()

            cursor.execute("""
                SELECT histologyReportContent, histologyReportName, histologyReportType
                FROM clinical_histories WHERE userId = %s
            """, (user_id,))
            clinical = cursor.fetchone()

            return {"pedigree": pedigree, "clinical": clinical}

        except mysql.connector.Error as e:
            logger.error("Error fetching user data: %s", e, exc_info=True)
            return None
        finally:
            if cursor: cursor.close()
            if conn: conn.close()

    def generate_ai_report(self, frame, docs, userId=None, filetype=None, userUploadedFile=False):
        prompt_context = self.create_prompt(frame, docs, filetype)
        file_id = None

        try:
            if filetype == "application/pdf":
                try:
                    file_data = base64.b64decode(frame)
                    with BytesIO(file_data) as pdf_file:
                        pdf_file.name = "report.pdf"
                        uploaded_file = openai.File.create(file=pdf_file, purpose='assistants')
                    file_id = uploaded_file.id
                    logger.info(f"PDF uploaded: file ID = {file_id}")
                except Exception as e:
                    logger.error("PDF upload error: %s", e)
                    return {"error": "Failed to upload PDF", "status_code": 500}

            messages = [{
                "role": "user",
                "content": [
                    {"type": "text", "text": self.create_prompt(frame, docs, filetype, file_id)}
                ]
            }]

            if frame and filetype.startswith("image/"):
                messages[0]["content"].append({
                    "type": "image_url",
                    "image_url": {"url": f"data:{filetype};base64,{frame}"}
                })

            response = openai.ChatCompletion.create(
                model=self.model,
                messages=messages,
                temperature=0.0,
                max_tokens=1500
            )

            content_blocks = response['choices'][0]['message']['content']
            report_text = ''.join([block.get("text", "") for block in content_blocks]) if isinstance(content_blocks, list) else content_blocks

            parsed_report = self.convert_to_json_object(report_text)
            if not parsed_report:
                return {"error": "Failed to parse AI report", "status_code": 500}

            self.store_report_in_db(userId, parsed_report)
            return {"report": parsed_report}

        except Exception as e:
            logger.error("Error generating AI report: %s", e, exc_info=True)
            return {"error": "AI processing failed", "status_code": 500}
            
    def convert_to_json_object(self, json_string: str):
        try:
            # Strip leading/trailing whitespace
            json_string = json_string.strip()
            logger.debug(f"Raw input string (first 200 chars): {json_string[:200]}...")

            # Remove markdown-style code block markers if present
            if json_string.startswith("```") and json_string.endswith("```"):
                lines = json_string.splitlines()
                if len(lines) >= 2:
                    # Remove the first and last lines (e.g., ```json and ```)
                    json_string = "\n".join(lines[1:-1]).strip()
            
            # Attempt to load JSON
            data = json.loads(json_string)

            # Ensure the loaded JSON is a dictionary (object)
            if not isinstance(data, dict):
                logger.warning("Parsed JSON is not a dictionary.")
                return None

            return data

        except json.JSONDecodeError as e:
            logger.error(f"JSON decode error: {e}", exc_info=True)
            return None

    def create_promptold(self, frame, docs, filetype,fileId=None):
        example_data = self.read_json_file("src/services/example.json")
        evaluation_rules = self.read_text_file(self.context_path)
        example_context = json.dumps(example_data, indent=4) if example_data else "No example available."
        prompt_text = (
            f"Context: Perform an analysis based on the provided document and image, using the guidelines and example provided below for structure and content.\n\n"
            f"Document: {docs}\n\n"
            f"Rules:\n"
            f"Evaluation Rules:\n{evaluation_rules}\n\n"
            f"Analysis Instructions: Please conduct a comprehensive analysis of the document and accompanying image. Format the results as a JSON object with the following structure:\n\n"
            f"Top-level fields should include:\n"
            f"1. 'eligible_for_NCCN': true or false — a flag that clearly states whether the patient meets the NCCN testing criteria.\n"
            f"2. 'Pathology Report Summary'\n"
            f"3. 'Pedigree Analysis'\n"
            f"4. 'NCCN Testing Criteria Assessment'\n"
            f"5. 'Conclusion'\n\n"
            f"The 'NCCN Testing Criteria Assessment' section should include specific criteria matched (if any), and reasons.\n\n"
            f"Example:\n{example_context}\n"
        )

        content = [{"type": "text", "text": prompt_text}]
        if frame and filetype:
            content.append({
                "type": "image_url",
                "image_url": {"url": f"data:{filetype};base64,{frame}"}
            })

        return {"role": "user", "content": content}

    def create_prompt2(self, frame, docs, filetype, file_id=None):
        example_data = self.read_json_file("src/services/example.json")
        evaluation_rules = self.read_text_file(self.context_path)
        example_context = json.dumps(example_data, indent=4) if example_data else "No example available."
        
        prompt_text = (
            f"Context: You are tasked with determining the patient's eligibility for NCCN genetic testing "
            f"based on a pathology report and pedigree analysis. Use the evaluation rules provided below, "
            f"and structure your output according to the provided format.\n\n"
            f"Document: {docs}\n\n"
            f"Evaluation Rules (Revised):\n{evaluation_rules}\n\n"
            f"Instructions:\n"
            f"- Carefully analyze the document.\n"
            f"- Check if the patient meets ANY of the listed NCCN testing criteria.\n"
            f"- If at least one criterion is matched, set 'eligible_for_NCCN': true and list ALL matched criteria in detail.\n"
            f"- If NO criteria are matched, set 'eligible_for_NCCN': false and explain clearly why the patient is ineligible.\n"
            f"- Use evidence from the pathology and pedigree data to justify your reasoning.\n"
            f"- Format your output strictly as a JSON object with the following fields:\n\n"
            f"1. 'eligible_for_NCCN' (true/false)\n"
            f"2. 'Pathology Report Summary'\n"
            f"3. 'Pedigree Analysis'\n"
            f"4. 'NCCN Testing Criteria Assessment' — include all criteria matched and relevant explanations.\n"
            f"5. 'Conclusion'\n\n"
            
            f"Example:\n{example_context}\n"
        )
        return prompt_text
        
    def create_prompt(self, frame, docs, filetype, file_id=None):
        example_data = self.read_json_file("src/services/example.json")
        evaluation_rules = self.read_text_file(self.context_path)
        example_context = json.dumps(example_data, indent=4) if example_data else "No example available."
        
        prompt_text = (
        f"Context: You are tasked with determining the patient's eligibility for NCCN genetic testing "
        f"(BRCA1, BRCA2, CDH1, PALB2, PTEN, STK11, TP53) based on a pathology report and pedigree analysis. "
        f"Use the evaluation rules provided below, and structure your output according to the specified format.\n\n"
        f"Document:\n{docs}\n\n"
        f"Evaluation Rules:\n{evaluation_rules}\n\n"
        f"Clarification for Rule Matching:\n"
        f"- Do NOT assume eligibility unless one or more NCCN criteria are explicitly met.\n"
        f"- Vague statements such as 'family history of cancer' are insufficient — specify the type, relation, and age (if required).\n"
        f"- Only Ashkenazi Jewish ancestry qualifies under Rule 6 — other Jewish ancestry does not.\n"
        f"- Rule 4 requires **lobular breast cancer AND personal or family history of diffuse gastric cancer**.\n"
        f"- If a rule requires age (e.g., Rule 1 or 7), it must be clearly stated in the report.\n"
        f"- For Rule 12, all three diagnoses (breast or prostate) must occur on the same side of the family.\n"
        f"- Cancers other than those listed (e.g., colon, lung) are irrelevant.\n"
        f"- When risk level is part of the rule (e.g., metastatic/high-risk prostate cancer), confirm it is explicitly stated.\n\n"

        f"Instructions:\n"
        f"- Carefully analyze the document.\n"
        f"- Check if the patient meets ANY of the listed NCCN testing criteria.\n"
        f"- If at least one criterion is matched, set 'eligible_for_NCCN': true and list ALL matched criteria in detail.\n"
        f"- If NO criteria are matched, set 'eligible_for_NCCN': false and explain clearly why the patient is ineligible.\n"
        f"- Base all conclusions strictly on information in the document. Avoid assumptions.\n"
        f"- Format your output strictly as a JSON object with the following fields:\n\n"
        f"1. 'eligible_for_NCCN' (true/false)\n"
        f"2. 'Pathology Report Summary'\n"
        f"3. 'Pedigree Analysis'\n"
        f"4. 'NCCN Testing Criteria Assessment' — include all criteria matched and relevant explanations.\n"
        f"5. 'Conclusion'\n\n"

        f"\nImportant:\n- The value of 'eligible_for_NCCN' must be consistent with the 'Conclusion' section. "
        f"If the patient is declared eligible in the conclusion, 'eligible_for_NCCN' must be true. "
        f"If declared not eligible, it must be false.\n"

        f"Double-check before concluding eligibility: Are any of the 12 NCCN criteria explicitly met with evidence? If not, the patient is ineligible.\n\n"

        f"Example Output Format:\n{example_context}\n"
        )
        return prompt_text
        
    def build_nccn_prompt(docs: str, evaluation_rules: str, example_context: str) -> str:
        prompt_text = (
            f"Context: You are tasked with determining the patient's eligibility for NCCN genetic testing "
            f"(BRCA1, BRCA2, CDH1, PALB2, PTEN, STK11, TP53) based on a pathology report and pedigree analysis. "
            f"Use the evaluation rules provided below, and structure your output according to the specified format.\n\n"

            f"Document:\n{docs}\n\n"

            f"Evaluation Rules:\n{evaluation_rules}\n\n"

            f"Clarification for Rule Matching:\n"
            f"- Do NOT assume eligibility unless one or more NCCN criteria are explicitly met.\n"
            f"- Vague statements such as 'family history of cancer' are insufficient — specify the type, relation, and age (if required).\n"
            f"- Only Ashkenazi Jewish ancestry qualifies under Rule 6 — other Jewish ancestry does not.\n"
            f"- Rule 4 requires **lobular breast cancer AND personal or family history of diffuse gastric cancer**.\n"
            f"- If a rule requires age (e.g., Rule 1 or 7), it must be clearly stated in the report.\n"
            f"- For Rule 12, all three diagnoses (breast or prostate) must occur on the same side of the family.\n"
            f"- Cancers other than those listed (e.g., colon, lung) are irrelevant.\n"
            f"- When risk level is part of the rule (e.g., metastatic/high-risk prostate cancer), confirm it is explicitly stated.\n\n"

            f"Instructions:\n"
            f"- Carefully analyze the document.\n"
            f"- Check if the patient meets ANY of the listed NCCN testing criteria.\n"
            f"- If at least one criterion is matched, set 'eligible_for_NCCN': true and list ALL matched criteria in detail.\n"
            f"- If NO criteria are matched, set 'eligible_for_NCCN': false and explain clearly why the patient is ineligible.\n"
            f"- Base all conclusions strictly on information in the document. Avoid assumptions.\n"
            f"- Format your output strictly as a JSON object with the following fields:\n\n"
            f"1. 'eligible_for_NCCN' (true/false)\n"
            f"2. 'Pathology Report Summary'\n"
            f"3. 'Pedigree Analysis'\n"
            f"4. 'NCCN Testing Criteria Assessment' — include all criteria matched and relevant explanations.\n"
            f"5. 'Conclusion'\n\n"

            f"Double-check before concluding eligibility: Are any of the 12 NCCN criteria explicitly met with evidence? If not, the patient is ineligible.\n\n"

            f"Example Output Format:\n{example_context}\n"
        )
        return prompt_text


    def create_prompt1(self, frame, docs, filetype, file_id=None):
        example_data = self.read_json_file("src/services/example.json")
        evaluation_rules = self.read_text_file(self.context_path)
        example_context = json.dumps(example_data, indent=4) if example_data else "No example available."
        prompt_text = (
            f"Context: Perform an analysis based on the provided document and image, using the guidelines and example provided below for structure and content.\n\n"
            f"Document: {docs}\n\n"
            f"Rules:\n"
            f"Evaluation Rules:\n{evaluation_rules}\n\n"
            f"Analysis Instructions: Please conduct a comprehensive analysis of the document and accompanying image. Format the results as a JSON object with the following structure:\n\n"
            f"Top-level fields should include:\n"
            f"1. 'eligible_for_NCCN': true or false — a flag that clearly states whether the patient meets the NCCN testing criteria.\n"
            f"2. 'Pathology Report Summary'\n"
            f"3. 'Pedigree Analysis'\n"
            f"4. 'NCCN Testing Criteria Assessment'\n"
            f"5. 'Conclusion'\n\n"
            f"The 'NCCN Testing Criteria Assessment' section should include specific criteria matched (if any), and reasons.\n\n"
            f"Example:\n{example_context}\n"
        )
        return prompt_text
    def read_json_file(self, file_path):
        try:
            with open(file_path, 'r') as file:
                return json.load(file)
        except FileNotFoundError:
            logger.error(f"Error: File not found at {file_path}.")
        except json.JSONDecodeError:
            logger.error("Error: Failed to parse JSON file.")
        except Exception as e:
            logger.error(f"Error reading JSON file: {e}")
        return None

    def read_text_file(self, file_path):
        try:
            with open(file_path, 'r', encoding='utf-8') as file:
                return file.read()
        except FileNotFoundError:
            logger.error(f"The file at {file_path} was not found.")
        except Exception as e:
            logger.error(f"An error occurred: {e}")
        return ""

    def store_report_in_db(self, user_id, report_dict):
        try:
            conn = self.get_db()
            if conn is None:
                raise Exception("Could not connect to database.")

            cursor = conn.cursor()
            cursor.execute("""
                INSERT INTO lamadb.analysis_reports (userId, report_text)
                VALUES (%s, %s)
                ON DUPLICATE KEY UPDATE report_text = VALUES(report_text)
            """, (user_id, json.dumps(report_dict)))  # Corrected usage

            conn.commit()
            logger.info("Report stored in database for user_id: %s", user_id)

        except Exception as e:
            logger.error("Failed to store report in database: %s", e, exc_info=True)
        finally:
            if conn and conn.is_connected():
                cursor.close()
                conn.close()
