import json


def text_to_json(text):
    # Split the text into lines
    lines = text.strip().split('\n')

    # Initialize a list to hold the questions and answers
    qa_list = []

    # Temporary variables to hold the current question and answer
    question = ""
    answer = ""

    for line in lines:
        # Check if the line starts with "Question:"
        if line.lower().startswith("question:"):
            if question and answer:
                # Append the previous question and answer to the list
                qa_list.append({"question": question, "answer": answer})
                # Reset question and answer for the new pair
                question = ""
                answer = ""
            # Extract the question text
            question = line[len("question:"):].strip()
        # Check if the line starts with "Answer:"
        elif line.lower().startswith("answer:"):
            # Extract the answer text
            answer = line[len("answer:"):].strip()

    # Don't forget to add the last question and answer pair if exists
    if question and answer:
        qa_list.append({"question": question, "answer": answer})

    # Convert the list of dictionaries to JSON
    return json.dumps(qa_list, indent=4)


with open('/Users/tobialao/workspace/bcu/nccn-rag/data/qa_logs.txt', 'r') as f:
    # Convert the text to JSON
    json_output = text_to_json(f.read())
    print(json_output)


