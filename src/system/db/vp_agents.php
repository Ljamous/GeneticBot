<?php
require_once 'mycon.php';

function add_vp_agent($agent_name, $sample_context, $sample_json, $userid){
    global $con;
    $st = $con->prepare("insert into vp_agents(agent_name, sample_context, sample_json, userid) values(?,?,?,?)");
    $st->bind_param("sssi", $agent_name, $sample_context, $sample_json, $userid);
    $st->execute();
}

function get_last_vp_id($userid){
    global $con;
    $st = $con->prepare("select max(id) as lastid from vp_agents where userid=?");
    $st->bind_param("i", $userid);
    $st->execute();
    $rs = $st->get_result();
    $row = $rs->fetch_assoc();
    return $row["lastid"];
}

function get_vp_agents($userid){
    global $con;
    $st = $con->prepare("select id, agent_name from vp_agents where userid=?");
    $st->bind_param("i", $userid);
    $st->execute();
    $rs = $st->get_result();
    return $rs;
}

function get_vp_agent($id){
    global $con;
    $st = $con->prepare("select agent_name, sample_context, sample_json from vp_agents where id=?");
    $st->bind_param("i", $id);
    $st->execute();
    $rs = $st->get_result();
    $row = $rs->fetch_assoc();
    return $row;
}

function delete_vp_agent($id){
    global $con;
    $st = $con->prepare("delete from vp_agents where id=?");
    $st->bind_param("i", $id);
    $st->execute();
}

function edit_vp_agent($agent_name, $sample_context, $sample_json, $id){
    global $con;
    $st = $con->prepare("update vp_agents set agent_name=?, sample_context=?, sample_json=? where id=?");
    $st->bind_param("sssi",$agent_name, $sample_context, $sample_json, $id);
    $st->execute();
}

function show_text_files($filename){
    // Check if the file exists
    if (file_exists($filename)) {
        // Read the file content and store it in a variable
        $fileContent = file_get_contents($filename);
        // Convert special HTML characters to HTML entities to prevent XSS attacks
        $safeContent = htmlspecialchars($fileContent);
        // Remove all HTML tags
        //$plainText = strip_tags($safeContent);
       // Display the content
        return $safeContent; 
    }
}