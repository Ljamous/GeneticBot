<?php
require_once 'mycon.php';

function addCompany($name, $webapiurl, $logo){
    global $con;
    $st = $con->prepare("insert into companies(name,webapiurl, logo) values(?,?,?)");
    $st->bind_param("sss", $name,$webapiurl, $logo);
    $st->execute();
    return $con->insert_id;
    
}

function showCompanies(){
    global $con;
    $st = $con->prepare("select * from companies");
    $st->execute();
    $rs = $st->get_result();
    return $rs;
}

function editCompany($id,$name,$webapiurl, $logo){
    global $con;
    $st = $con->prepare("update companies set name=?, webapiurl=?, logo=? where id=?");
    $st->bind_param("sssi", $name, $webapiurl, $logo, $id);
    $st->execute();
}

function editCompanyName($id,$name,$webapiurl){
    global $con;
    $st = $con->prepare("update companies set name=?, webapiurl=? where id=?");
    $st->bind_param("ssi", $name, $webapiurl, $id);
    $st->execute();
}

function checkCompanyAdmin($comid){
    global $con;
    $st = $con->prepare("select userid, name from users inner join users_groups on users.id=users_groups.userid where groupid=2 and parentid=?");
    $st->bind_param("i", $comid);
    $st->execute();
    $rs = $st->get_result();
    return $rs;
}