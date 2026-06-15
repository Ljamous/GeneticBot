<?php
require_once 'mycon.php';

function getPOSuppliers($offerno){
    global $con;
    $st = $con->prepare("SELECT distinct vendors.SupplierID, vendors.SupplierName, vendors.Currency, po.pono, po.pofile, po.csfile
    from items inner join offer_details on items.ID = offer_details.item_id 
    inner join vendors on items.SupplierId = vendors.SupplierID
    left outer join po on vendors.SupplierID = po.vendorno
    where offer_details.offerno = ?");
    $st->bind_param("i", $offerno);
    $st->execute();
    $rs = $st->get_result();
    return $rs;
}

function genPO($offerno, $supId){
    global $con;
    $st = $con->prepare("SELECT items.ID, items.ItemId, items.ItemDescription, items.ExternalItemId, items.CountryId, items.SupplierId ,items.PurchaseUnitId, items.BrandId,
    offer_details.qty, offer_details.unit_price, offer_details.discount,offer_details.unit_price - offer_details.unit_price * offer_details.discount/100 as 'Price after discount', offer_details.conv_ratio, 
 (offer_details.unit_price - (offer_details.unit_price * offer_details.discount/100)) * offer_details.conv_ratio as 'Net price', offer_details.qty * ((offer_details.unit_price - (offer_details.unit_price * offer_details.discount/100)) * offer_details.conv_ratio) as 'Total'
    from items inner join offer_details on items.ID = offer_details.item_id
    where offer_details.offerno = ? and items.SupplierId=?");
    $st->bind_param("is", $offerno, $supId);
    $st->execute();
    $rs = $st->get_result();
    return $rs;
}