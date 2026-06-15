from dataclasses import dataclass


@dataclass
class ContextDocument:
    name: str
    description: str
    path: str
