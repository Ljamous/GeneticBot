# ==============================================================================
# Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
#
# This code is for educational and non-commercial purposes only and may not be 
# used or redistributed without explicit written permission from the publisher.
# ==============================================================================

import uuid
from typing import List

from app.models import ChatSession, ChatMessage


class ChatSessionManager:

    def __init__(self):
        self._conversation_repo: dict[str, ChatSession] = {}

    def message_length(self, session_id: str) -> int:
        return len(self._conversation_repo[session_id].messages)

    def create_chat_session(self) -> str:
        """Creates a chat session with a message trail of the conversation history"""
        session_id = str(uuid.uuid4())
        self._conversation_repo[session_id] = ChatSession(id=session_id)
        return session_id

    def add_message(self, session_id, message: ChatMessage):
        session = self._conversation_repo[session_id]
        session.messages.append(message)

    def export_chat(self, session_id: str, messages: List[ChatMessage] = None) -> str:
        session_messages = messages if messages is not None else self._conversation_repo[session_id].messages
        chat = ""
        for msg in session_messages:
            chat += f"\nAuthor: {msg.author}\nContent: {msg.message}\n"
        return chat

    def reset_chat_history(self, session_id: str):
        self._conversation_repo[session_id].messages = []
