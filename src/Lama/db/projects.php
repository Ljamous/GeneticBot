<?php
require_once 'mycon.php';

function addProject($name, $abbrv, $prjtype, $scope, $location, $first_con_name, $first_con_number, $second_con_name, $second_con_number, $customerid, $companyid){
    global $con;
    $st = $con->prepare("insert into projects(name, abbrv, prjtype, scope, location, first_con_name, first_con_number, second_con_name, second_con_number, customerid, companyid) values(?,?,?,?,?,?,?,?,?,?,?)");
    $st->bind_param("sssssssssii", $name, $abbrv, $prjtype, $scope, $location, $first_con_name, $first_con_number, $second_con_name, $second_con_number, $customerid, $companyid);
    $st->execute();
}

function showProjects($roleid){
    global $con;
    if($roleid == 1){
        $st = $con->prepare("select projects.*, customers.id as cid, customers.name as cname from customers inner join projects on projects.customerid=customers.id");
        $st->execute();
    }
    else{
        $companyid = getParentId($_SESSION["userid"]);
        $st = $con->prepare("select projects.*, customers.id as cid, customers.name as cname from customers inner join projects on projects.customerid=customers.id and companyid=?");
        $st->bind_param("i", $companyid);
        $st->execute();
    }
    $rs = $st->get_result();
    return $rs;
}

function showCustomers(){
    global $con;
    $st = $con->prepare("select id,name from customers");
    $st->execute();
    $rs = $st->get_result();
    return $rs;
}

function editProject($id,$name, $abbrv, $prjtype, $scope, $location, $first_con_name, $first_con_number, $second_con_name, $second_con_number){
    global $con;
    $st = $con->prepare("update projects set name=?, abbrv=?, prjtype=?, scope=?, location=?, first_con_name=?, first_con_number=?, second_con_name=?, second_con_number=? where id=?");
    $st->bind_param("sssssssssi", $name, $abbrv, $prjtype, $scope, $location, $first_con_name, $first_con_number, $second_con_name, $second_con_number, $id);
    $st->execute();
}

function addMember($id, $member, $type){
    global $con;
    $sql = "update projects set designerid=? where id=?";
    if($type == 1)
        $sql = "update projects set accountantid=? where id=?";
    
    $st = $con->prepare($sql);
    $st->bind_param("ii", $member, $id);
    $st->execute();
}