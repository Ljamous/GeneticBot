import sys
import uuid

from dotenv import load_dotenv, find_dotenv
from llama_index.core import SimpleDirectoryReader, Document, VectorStoreIndex
from llama_index.core.chat_engine.types import ChatMode, BaseChatEngine, StreamingAgentChatResponse
from llama_index.core.memory import ChatMemoryBuffer
from llama_index.llms.openai import OpenAI


load_dotenv(find_dotenv())

genetic_bot_prompt = """
You are GeneticBot, an AI assistant able to help with providing insights on guidelines for cancer detection and treatment.
Use the provided information in the context in providing responses.
Do not answer questions outside the scope of the context provided.
Your responses should be concise and accurate
"""

prompt_boundaries = """
Only answer based on the knowledge provided in the context, 
and simply respond:
"Sorry, I'm unable to Answer this Question. Is there anything else I can help you with ?" when any of the following applies:
1. when asked a question outside the scope of Cancer treatment
2. when there is no sufficient context information to provide relevant information to answer the question
"""


def load_document(file_path: str):
    documents: list[Document] = SimpleDirectoryReader(input_files=[file_path]).load_data()
    if len(documents) < 1:
        raise LookupError("Invalid file path specified")
    return documents


def init_rag_engine():
    # Components Initialization: Foundation Model, Doc Loader, Context Retriever Component, Response Generator
    doc = load_document("data/NCCN-Chatbot_data.pdf")
    index = VectorStoreIndex.from_documents(doc)
    llm = OpenAI(model="gpt-4o", temperature=0)
    memory = ChatMemoryBuffer.from_defaults(token_limit=4000)

    # embed_model
    engine = index.as_chat_engine(
        chat_mode=ChatMode.CONTEXT,
        memory=memory,
        similarity_top_k=4,
        llm=llm,
        system_prompt=genetic_bot_prompt,
        verbose=True,
        streaming=True
    )
    return engine


def query_document(engine: BaseChatEngine, query_str: str):
    return engine.stream_chat(query_str)


def init_conversational_interface():
    query_engine = init_rag_engine()

    while True:
        print("\nNCCN RAG APP - To Quit: Type \"end\" followed by a Enter/Return key to quit\n")
        query = input("Enter your prompt or query here: ")
        if query is not None and query != "end":
            streaming_response: StreamingAgentChatResponse = query_document(query_engine, query)
            for text in streaming_response.response_gen:
                print(text, end="")
        elif query == "end":
            break
        else:
            sys.exit(0)


if __name__ == '__main__':
    init_conversational_interface()

# sample_test_questions = ["What does the the absence of a P/LP variant for a given gene indicate ?",
#              "What are the common genes associated with cancer?",
#              "Give a description on how colon cancer can be treated"
#              ]
