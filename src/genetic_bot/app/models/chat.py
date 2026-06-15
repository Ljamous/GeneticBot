from dataclasses import dataclass, field
from typing import List


@dataclass
class ChatMessage:
    author: str
    message: str


@dataclass
class ChatSession:
    id: str
    label: str = ""
    has_user_message = False
    messages: List[ChatMessage] = field(default_factory=list)


@dataclass
class Chat:
    id: str
    messages: List[ChatMessage]

