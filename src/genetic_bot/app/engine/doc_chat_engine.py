# ==============================================================================
# Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
#
# This code is for educational and non-commercial purposes only and may not be 
# used or redistributed without explicit written permission from the publisher.
# ==============================================================================

from llama_index.core import VectorStoreIndex
from llama_index.core.chat_engine.types import ChatMode, BaseChatEngine
from llama_index.core.memory import ChatMemoryBuffer
from llama_index.llms.openai import OpenAI

from app.engine import ChatEngine
from app.utils import load_document_into_chucks


class DocumentChatEngine(ChatEngine):
    """A document chat Engine class that handles context retrieval and question answering based on specified document"""

    def __init__(self, path: str, model: str = "gpt-4o", **kwargs):
        """
            Args:
                path: Path to the document providing the QA context
                model: OpenAI model, defaults to gpt-4o
                kwargs: Additional arguments like the model prompt instructions and chat memory buffer size
        """
        # Validate constructor arguments
        if not kwargs["prompt"]:
            raise AttributeError("You should provide a default system prompt for the chat engine")
        if "chat_buffer" in kwargs and not isinstance(kwargs["chat_buffer"], int):
            raise TypeError("Integer value expected")

        # Initialize chat history memory buffer
        chat_mem_buffer = kwargs["chat_buffer"] if "chat_buffer" in kwargs and kwargs[
            "chat_buffer"] is not None else 4000

        # Load document and initialize document indexing and model
        docs = load_document_into_chucks(path)
        index = VectorStoreIndex.from_documents(docs)
        llm = OpenAI(model=model, temperature=1)
        memory = ChatMemoryBuffer.from_defaults(token_limit=chat_mem_buffer)

        self._engine = index.as_chat_engine(
            chat_mode=ChatMode.CONTEXT,
            memory=memory,
            similarity_top_k=4,
            llm=llm,
            system_prompt=kwargs["prompt"],
            verbose=True,
            streaming=True
        )

    @property
    def engine(self) -> BaseChatEngine:
        return self._engine
