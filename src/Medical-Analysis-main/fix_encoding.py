# ==============================================================================
# Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
#
# This code is for educational and non-commercial purposes only and may not be 
# used or redistributed without explicit written permission from the publisher.
# ==============================================================================


import os

file_path = 'd:\\Shared\\Lama2\\Lama\\src\\Medical-Analysis-main\\requirements.txt'

try:
    with open(file_path, 'rb') as f:
        raw = f.read(4)
        print(f"BOM: {raw}")
    
    # Try reading as utf-8
    with open(file_path, 'r', encoding='utf-8') as f:
        f.read()
        print("Successfully read as UTF-8")
except UnicodeDecodeError:
    print("Failed to read as UTF-8")
    try:
        with open(file_path, 'r', encoding='utf-16') as f:
            content = f.read()
            print("Successfully read as UTF-16")
            
            # Convert to UTF-8
            with open(file_path, 'w', encoding='utf-8') as out_f:
                out_f.write(content)
            print("Converted to UTF-8")
    except Exception as e:
        print(f"Error reading as UTF-16: {e}")
