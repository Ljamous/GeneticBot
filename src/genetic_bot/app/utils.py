from typing import List

from llama_index.core import Document, SimpleDirectoryReader


def load_document_into_chucks(file_path: str) -> List[Document]:
    documents = SimpleDirectoryReader(input_files=[file_path]).load_data()
    if len(documents) < 1:
        raise LookupError("Invalid file path specified")
    return documents
