import codecs
from docx import Document

doc = Document(r'd:\Shared\Lama2\Lama\docs\GeneticBot_PLOS_resubmission_highlighted_FINAL.docx')
with codecs.open('extracted.txt', 'w', 'utf-8') as f:
    f.write('\n'.join([p.text for p in doc.paragraphs if p.text.strip()]))
