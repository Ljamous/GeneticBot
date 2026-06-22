# ==============================================================================
# Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
#
# This code is for educational and non-commercial purposes only and may not be 
# used or redistributed without explicit written permission from the publisher.
# ==============================================================================

import codecs
from docx import Document

doc = Document(r'd:\Shared\Lama2\Lama\docs\GeneticBot_PLOS_resubmission_highlighted_FINAL.docx')
with codecs.open('extracted.txt', 'w', 'utf-8') as f:
    f.write('\n'.join([p.text for p in doc.paragraphs if p.text.strip()]))
