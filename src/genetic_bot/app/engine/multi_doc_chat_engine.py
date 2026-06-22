# ==============================================================================
# Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
#
# This code is for educational and non-commercial purposes only and may not be 
# used or redistributed without explicit written permission from the publisher.
# ==============================================================================

from typing import List

from llama_index.agent.openai import OpenAIAgent
from llama_index.core import VectorStoreIndex
from llama_index.core.chat_engine.types import BaseChatEngine
from llama_index.core.memory import ChatMemoryBuffer
from llama_index.core.tools import QueryEngineTool, ToolMetadata
from llama_index.llms.openai import OpenAI

from app.engine import ChatEngine
from app.models import ContextDocument
from app.utils import load_document_into_chucks


class MultiDocChatEngine(ChatEngine):
    """A multi document chat Engine class that handles context retrieval and
     question answering based on specified document"""

    def __init__(self, context_sources: List[ContextDocument], model: str = "gpt-4o", **kwargs):
        """
            Args:
                context_sources: A list of objects with the different document contexts QA context
                model: OpenAI model, defaults to gpt-4o
                kwargs: Additional arguments like the model prompt instructions and chat memory buffer size
        """

        # Initialize defaults history memory buffer
        chat_mem_buffer = 4000
        llm = OpenAI(model=model, temperature=1)

        # Validate constructor arguments
        if not kwargs["prompt"]:
            raise AttributeError("You should provide a default system prompt for the chat engine")
        if "chat_buffer" in kwargs:
            if not isinstance(kwargs["chat_buffer"], int):
                raise TypeError("Integer value expected")
            if kwargs["chat_buffer"] is not None:
                chat_mem_buffer = kwargs["chat_buffer"]

        memory = ChatMemoryBuffer.from_defaults(token_limit=chat_mem_buffer)
        self.model = model

        # Load document and initialize document indexing and model
        engine_tools = []
        for context_source in context_sources:
            docs = load_document_into_chucks(context_source.path)
            index = VectorStoreIndex.from_documents(docs)
            engine_tool = QueryEngineTool(
                query_engine=index.as_query_engine(llm, similarity_top_k=3),
                metadata=ToolMetadata(
                    name=context_source.name, description=context_source.description
                )
            )
            engine_tools.append(engine_tool)

        self._engine = OpenAIAgent.from_tools(
            engine_tools,
            memory=memory,
            llm=llm,
            max_function_calls=4,
            verbose=True,
            streaming=True,
            system_prompt=kwargs["prompt"],
        )

    @property
    def engine(self) -> BaseChatEngine:
        return self._engine
