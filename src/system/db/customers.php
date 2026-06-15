<?php
require_once 'mycon.php';

function addCustomer($name, $country, $city, $contact_name, $phone, $email, $com_prc){
    global $con;
    $st = $con->prepare("insert into customers(name, country, city, contact_name, phone, email, com_prc) values(?,?,?,?,?,?,?)");
    $st->bind_param("ssssssd", $name, $country, $city, $contact_name, $phone, $email, $com_prc);
    $st->execute();
    return $con->insert_id;
}

function showCustomers(){
    global $con;
    $st = $con->prepare("select * from customers");
    $st->execute();
    $rs = $st->get_result();
    return $rs;
}

function editCustomer($id,$name, $country, $city, $contact_name, $phone, $email, $com_prc){
    global $con;
    $st = $con->prepare("update customers set name=?, country=?, city=?, contact_name=?, phone=?, email=?, com_prc=? where id=?");
    $st->bind_param("ssssssdi", $name, $country, $city, $contact_name, $phone, $email, $com_prc, $id);
    $st->execute();
}