# ==============================================================================
# Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
#
# This code is for educational and non-commercial purposes only and may not be 
# used or redistributed without explicit written permission from the publisher.
# ==============================================================================

import streamlit as st
from streamlit.delta_generator import DeltaGenerator
st.set_page_config(page_title="NCCN Chatbot", layout="wide")

from app.chat_session_manager import ChatSessionManager
from app.engine import ChatEngine
from app.models import ChatMessage


class ChatUI:
    _session_id = None

    def __init__(self, chat_engine: ChatEngine, session_manager: ChatSessionManager):
        self.engine = chat_engine
        self._session_id = session_manager.create_chat_session()
        self._session_manager = session_manager

        if "messages" not in st.session_state:
            st.session_state.messages = []
        if "generating_response" not in st.session_state:
            st.session_state.generating_response = False



    def reset_chat(self):
        pass

    def add_message(self, chat_message: ChatMessage):
        st.session_state.messages.append(chat_message)
        self._session_manager.add_message(self._session_id, message=chat_message)

    def handle_user_query(self, container: DeltaGenerator, query: str):
        # Add user message
        with container:
            with st.chat_message("user"):
                st.write(query)
        self.add_message(ChatMessage(author="user", message=query))

        use_stream = True

        # Get response based on user message
        response = self.engine.generate_response(query, stream=use_stream)
        with container:
            with st.chat_message("ai"):
                if use_stream:
                    st.write_stream(response.response_gen)
                else:
                    st.write(response.response)
        self.add_message(ChatMessage(author="ai", message=response.response))
        st.session_state.generating_response = False
        st.rerun()

    def message_length(self) -> int:
        return len(st.session_state.messages)

    def init_chat_ui(self):
        # Add default message
        if len(st.session_state.messages) < 1:
            welcome = ChatMessage(author="ai", message="Hello, how can I help you today!")
            self.add_message(welcome)

        # Streamlined caption
        st.caption("🧬 I'm here to help with cancer detection and treatment guidelines. Ask me anything!")

        # Main chat area with dynamic full height
        chat_container = st.container()

        with chat_container:
            for msg in st.session_state.messages:
                with st.chat_message(msg.author):
                    st.markdown(msg.message)

        # Disable submit while responding
        def disable():
            st.session_state.generating_response = True

        if prompt := st.chat_input("Type your message", disabled=st.session_state.generating_response,
                                   on_submit=disable):
            self.handle_user_query(chat_container, prompt)

        # Custom styling for full layout
        # Custom styling for full layout and UI cleanup
        st.markdown("""
        <style>
        html, body, [data-testid="stAppViewContainer"] {
            height: 100vh;
            margin: 0;
            padding: 0;
        }
        [data-testid="stSidebar"], .st-emotion-cache-1jicfl2 {
            display: none !important;
        }
        [data-testid="stVerticalBlock"] {
            height: 100%;
        }
        .stChatInputContainer {
            position: fixed;
            bottom: 1rem;
            left: 0;
            right: 0;
            background: white;
            padding: 0.5rem 1rem;
            z-index: 999;
        }
        .stToolbar,
        .st-emotion-cache-17zi7gk,  /* Deploy button */
        .st-emotion-cache-1avcm0n,  /* Stop button */
        header, [data-testid="stToolbar"] {
            display: none !important;
        }
        </style>
        """, unsafe_allow_html=True)


        # Download button
        st.download_button(
            label="📄 Download Chat",
            data=self._session_manager.export_chat(self._session_id, st.session_state.messages),
            file_name=f"chat_session_{self._session_id}.txt",
            mime="text/plain",
            disabled=(self.message_length() < 2) or st.session_state.generating_response
        )


# Initialize the UI and start the chat session
if __name__ == '__main__':
    chat_ui = ChatUI(chat_engine=ChatEngine(), session_manager=ChatSessionManager())
    chat_ui.init_chat_ui()
