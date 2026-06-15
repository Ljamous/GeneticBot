<?php
require_once 'mycon.php';

function add_aia_agent($agent_name, $agent_type, $agent_host, $agent_username, $agent_password, $agent_database, $agent_port, $userid){
    global $con;
    $st = $con->prepare("insert into aia_agents(agent_name, agent_type, agent_host, agent_username, agent_password, agent_database, agent_port, userid) values(?,?,?,?,?,?,?,?)");
    $st->bind_param("ssssssii", $agent_name, $agent_type, $agent_host, $agent_username, $agent_password, $agent_database, $agent_port,$userid);
    $st->execute();
}

function get_last_id($userid){
    global $con;
    $st = $con->prepare("select max(id) as lastid from aia_agents where userid=?");
    $st->bind_param("i", $userid);
    $st->execute();
    $rs = $st->get_result();
    $row = $rs->fetch_assoc();
    return $row["lastid"];
}

function get_agents($userid){
    global $con;
    $st = $con->prepare("select id, agent_name from aia_agents where agent_type=1 and userid=?");
    $st->bind_param("i", $userid);
    $st->execute();
    $rs = $st->get_result();
    return $rs;
}

function get_agent($id){
    global $con;
    $st = $con->prepare("select agent_tables_views, agent_examples, agent_rules from aia_agents where id=?");
    $st->bind_param("i", $id);
    $st->execute();
    $rs = $st->get_result();
    $row = $rs->fetch_assoc();
    return $row;
}

function delete_agent($id){
    global $con;
    $st = $con->prepare("delete from aia_agents where id=?");
    $st->bind_param("i", $id);
    $st->execute();
}

function edit_agent($agent_tables_views, $agent_examples, $agent_rules, $id){
    global $con;
    $st = $con->prepare("update aia_agents set agent_tables_views=?, agent_examples=?, agent_rules=? where id=?");
    $st->bind_param("sssi",$agent_tables_views, $agent_examples, $agent_rules, $id);
    $st->execute();
}

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

