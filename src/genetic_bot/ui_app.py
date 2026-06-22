# ==============================================================================
# Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
#
# This code is for educational and non-commercial purposes only and may not be 
# used or redistributed without explicit written permission from the publisher.
# ==============================================================================

from typing import List, Union

import streamlit as st
from dotenv import load_dotenv, find_dotenv

from app.chat_session_manager import ChatSessionManager
from app.chat_ui import ChatUI
from app.engine import DocumentChatEngine, MultiDocChatEngine, ChatEngine
from app.models import ContextDocument

load_dotenv(find_dotenv())


@st.cache_resource(show_spinner=False)
def load_doc_engine(context_source: Union[List[ContextDocument], ContextDocument], prompt: str) -> ChatEngine:
    if isinstance(context_source, ContextDocument):
        chat_engine = DocumentChatEngine(path=context_source.path, model="gpt-4o", prompt=prompt, chat_buffer=4000)
    else:
        chat_engine = MultiDocChatEngine(context_sources=context_source, model="gpt-4o", prompt=prompt, chat_buffer=4000)
    return chat_engine


if __name__ == "__main__":
    context_data = [
        ContextDocument(
            name="guideline_context",
            path="data/NCCN-Chatbot_data.pdf",
            description="Contains NCCN guidelines"
        ),
        ContextDocument(
            name="qa_context",
            path="data/qa_logs.txt",
            description="Contains Question Answer pairs based on the previous interactions with the NCCN guidelines"
        ),
        ContextDocument(
            name="multi_qa_tool",
            path="data/multi_answer_qa_logs.txt",
            description="Contains Question and multiple Answer pairs based on the previous " +
                        "interactions with the NCCN guidelines"
        )
    ]
    chat_agent_profile = """ 
    You are GeneticBot, an AI assistant able to help with providing insights on guidelines for cancer detection and treatment.
    Use the provided information in the context in providing responses.
    Do not answer questions outside the scope of the context provided.
    Do not make up responses to answer the user queries or questions
    """

    with st.spinner('Loading Document Context ...'):
        engine = load_doc_engine(context_source=context_data, prompt=chat_agent_profile)

    session_manager = ChatSessionManager()

    # Initialize UI
    chat_ui = ChatUI(chat_engine=engine, session_manager=session_manager)
    chat_ui.init_chat_ui()
