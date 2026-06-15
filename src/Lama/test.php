<?php
function edit_context_file($agent_tables_views, $agent_examples, $agent_rules, $agentid, $userid){

$context = 'You are an expert agent designed to interact with a HANA SQL database for SAP business one.
            Always put the column name inside double quotation';
$context = $context . $agent_tables_views;
$context = $context . $agent_rules;
$context = $context . 'Return the SQL statement from above view
Given an input question, create a syntactically correct SQL query.
Never query for all the columns from a specific view, only ask for the relevant columns given the question.
DO NOT make any DML statements (INSERT, UPDATE, DELETE, DROP etc.) to the database.
If the question does not seem related to the database, just return "I do not know" as the answer.

Show only and only the SQL statement as output';
$context = $context . $agent_examples;

$context = $context . 'Always return the SQL into HTML DIV as follow
<div> select "NetSalesAmountLC" from "_SYS_BIC"."sap.presaledemo.ar.case/SalesAnalysisQuery" where "PostingYear"=2022 </div>';

$file_path = $userid . "-". $agentid .".txt";
$file_handle = fopen($file_path, 'w');
fwrite($file_handle, $context);
fclose($file_handle);

}