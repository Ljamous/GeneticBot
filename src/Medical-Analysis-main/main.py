from urllib.error import HTTPError

from fastapi import FastAPI, status
from fastapi.middleware.cors import CORSMiddleware
from fastapi.responses import JSONResponse
from fastapi.staticfiles import StaticFiles
from fastapi.templating import Jinja2Templates
from src.controllers.analysis_controler import MedicalRouter
from src.config.config import DEBUG, DESCRIPTION, HOST, LOG_LEVEL, PORT, PROJECT_NAME


app = FastAPI(title=PROJECT_NAME, description=DESCRIPTION)

app.mount("/static", StaticFiles(directory="assets"), name="static")
app.mount("/static", StaticFiles(directory="templates"), name="static")


# Including routers in the main app
app.include_router(MedicalRouter, prefix="/medical", tags=["Medical"])


app.add_middleware(
    CORSMiddleware,
    allow_origins=["*"],  # Update this with your frontend origin
    allow_credentials=True,
    allow_methods=[
        "GET",
        "POST",
        "PUT",
        "DELETE",
    ],  # Update this with the methods you need
    allow_headers=[
        "Content-Type",
        "Authorization",
    ],  # Update this with the headers you need
)


@app.get("/")
def read_root():
    return {"Hello": f"Welcome to Medical Project."}



# To run the FastAPI application
if __name__ == "__main__":

    import uvicorn
    uvicorn.run(
        "main:app", host=HOST, port=int(PORT), log_level=LOG_LEVEL, reload=DEBUG
    )
