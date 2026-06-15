<?php
require_once 'mycon.php';

function searchItem($srch){
    global $con;
    $st = $con->prepare("select id, externalitemid  from items where externalitemid like '".$srch."%' ORDER BY itemid ASC");
    $st->execute();
    $rs = $st->get_result();
    $ar = array();
    while($row = $rs->fetch_assoc())
    {
        $data['id'] = $row['id']; 
        $data['value'] = $row['externalitemid']; 
        array_push($ar, $data);
    }
    return json_encode($ar);
}

function searchItemById($id){
    global $con;
    $st = $con->prepare("select items.id, externalitemid, brandid, itemDescription, externalitemid, countryid, currency, 
    purchaseunitid, suppliername from items inner join vendors 
    on items.supplierid = vendors.supplierid where items.id= ? limit 1");
    $st->bind_param("i", $id);
    $st->execute();
    $rs = $st->get_result();
    $row = $rs->fetch_assoc();
    return json_encode($row);
}

