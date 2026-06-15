<?php
require_once 'mycon.php';

function addPackage($name, $abbrv, $type, $descr, $submissionid){
    global $con;
    $st = $con->prepare("insert into packages(name, abbrv, type, descr, submissionid) values(?,?,?,?,?)");
    $st->bind_param("ssssi", $name, $abbrv, $type, $descr, $submissionid);
    $st->execute();
}

function showPackages($roleid){
    global $con;
    if($roleid == 1){
        $sql = "select packages.*, submissions.abbrv as subabv, projects.abbrv as proabv from packages INNER JOIN submissions on packages.submissionid = submissions.id INNER JOIN projects on submissions.projectid = projects.id";
        $st = $con->prepare($sql);
        $st->execute();
    }
    else{
        $companyid = getParentId($_SESSION["userid"]);
        $groupid = getGroupId($_SESSION["userid"]);
        $sql = "select packages.*, submissions.abbrv as subabv, projects.abbrv as proabv from packages INNER JOIN submissions on packages.submissionid = submissions.id INNER JOIN projects on submissions.projectid = projects.id and companyid=?";
        if($groupid == 3)
        $sql = "select packages.*, submissions.abbrv as subabv, projects.abbrv as proabv from packages INNER JOIN submissions on packages.submissionid = submissions.id INNER JOIN projects on submissions.projectid = projects.id and customerid=? and status<>'Created'";

        $st = $con->prepare($sql);
        $st->bind_param("i", $companyid);
        $st->execute();
    }
    
    $rs = $st->get_result();
    return $rs;
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

function showLayouts($pckid){
    global $con;
    if($_SESSION["roleid"] == 1)
        $groupid = 0;
    else
        $groupid = getGroupId($_SESSION["userid"]);
    $sql = "select * from docs where packageid=?";
    if ($groupid==3)
    $sql = "select * from docs where packageid=? and status<> 'Reject'";
    $st = $con->prepare($sql);
    $st->bind_param("i", $pckid);
    $st->execute();
    $rs = $st->get_result();
    return $rs;
}

function editDocStatus($status, $version){
    global $con;
    $st = $con->prepare("update docs set status=? where version=?");
    $st->bind_param("si", $status, $version);
    $st->execute();
}

