<?php
require_once 'mycon.php';

function showOffer($pckId, $verNo){
    global $con;
    $st = $con->prepare("SELECT items.ID, items.ItemId, items.ItemDescription, items.ExternalItemId, items.CountryId, items.PurchaseUnitId, items.BrandId,
    offer_details.qty, offer_details.unit_price, offer_details.discount,offer_details.unit_price - offer_details.unit_price * offer_details.discount/100 as 'Price after discount', offer_details.conv_ratio, 
 (offer_details.unit_price - (offer_details.unit_price * offer_details.discount/100)) * offer_details.conv_ratio as 'Net price', offer_details.qty * ((offer_details.unit_price - (offer_details.unit_price * offer_details.discount/100)) * offer_details.conv_ratio) as 'Total'
    from items inner join offer_details
    on items.ID = offer_details.item_id
    where offer_details.offerno = (select offer.offerno from offer where offer.packageid = ? and offer.doc_version = ?)");
    $st->bind_param("ii", $pckId, $verNo );
    $st->execute();
    $rs = $st->get_result();
    return $rs;
}

function showOfferHeader($pckId){
    global $con;
    $st = $con->prepare("SELECT offer.offerno, offer.offer_date, companies.name, companies.logo from offer INNER JOIN companies 
    on (select users_groups.parentid from users_groups where users_groups.userid = offer.userid) = companies.id and offer.packageid=?");
    $st->bind_param("i", $pckId);
    $st->execute();
    $rs = $st->get_result();
    return $rs;
    
}

function getDocStatus($ver){
    global $con;
    $st = $con->prepare("SELECT status from docs where version=?");
    $st->bind_param("i", $ver);
    $st->execute();
    $rs = $st->get_result();
    $row = $rs->fetch_assoc();
    return $row["status"];
}

function getOfferNo($pckId){
    global $con;
    $st = $con->prepare("SELECT offerno from offer where packageid=?");
    $st->bind_param("i", $pckId);
    $st->execute();
    $rs = $st->get_result();
    $row = $rs->fetch_assoc();
    return $row["offerno"];
}



