# ==============================================================================
# Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
#
# This code is for educational and non-commercial purposes only and may not be 
# used or redistributed without explicit written permission from the publisher.
# ==============================================================================

from abc import ABC, abstractmethod

from llama_index.core.chat_engine.types import BaseChatEngine


class ChatEngine(ABC):

    @property
    @abstractmethod
    def engine(self) -> BaseChatEngine:
        pass

    def generate_response(self, user_query: str, stream=True):
        """ Generates the responses to user queries
            Args:
                user_query: The user query
                stream: Specifies if the response should be streamed
            Returns:
                 An async response stream or response object  with the model response
        """
        if stream:
            response = self.engine.stream_chat(message=user_query)
        else:
            response = self.engine.chat(message=user_query)
        return response
