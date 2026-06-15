<?php
require_once 'mycon.php';

function addSubmission($name, $abbrv, $type, $projectid){
    global $con;
    $st = $con->prepare("insert into submissions(name, abbrv, type, projectid) values(?,?,?,?)");
    $st->bind_param("sssi", $name, $abbrv, $type, $projectid);
    $st->execute();
}

function showSubmissions($roleid){
    global $con;
    if($roleid == 1){
        $st = $con->prepare("select submissions.*, projects.abbrv as abv from submissions INNER JOIN projects on submissions.projectid = projects.id");
        $st->execute();
    }
    else{
        $companyid = getParentId($_SESSION["userid"]);
        $st = $con->prepare("select submissions.*, projects.abbrv as abv from submissions INNER JOIN projects on submissions.projectid = projects.id and companyid=?");
        $st->bind_param("i", $companyid);
        $st->execute();
    }
    
    $rs = $st->get_result();
    return $rs;
}

function editSubmission($id, $name, $abbrv, $type, $projectid){
    global $con;
    $st = $con->prepare("update submissions set name=?, abbrv=?, type=?, projectid=? where id=?");
    $st->bind_param("sssii", $name, $abbrv, $type, $projectid, $id);
    $st->execute();
}

function getProjAbrv($roleid){
    global $con;
    if($roleid == 1){
        $st = $con->prepare("select id, abbrv from projects");
        $st->execute();
    }
    else{
        $companyid = getParentId($_SESSION["userid"]);
        $st = $con->prepare("select id, abbrv from projects where companyid=?");
        $st->bind_param("i", $companyid);
        $st->execute();
    }
    
    $rs = $st->get_result();
    return $rs;
}


