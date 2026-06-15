<?php
require_once 'mycon.php';

function save_inputs($pckId){
    global $con;
    $st = $con->prepare("SELECT items.ID, offer_details.qty 
    from items inner join offer_details
    on items.ID = offer_details.item_id
    where offer_details.offerno = (select offer.offerno from offer where offer.packageid = ? and offer.status = 'PO Created')");
    $st->bind_param("i", $pckId );
    $st->execute();
    $rs = $st->get_result();
    while($row = $rs->fetch_assoc())
    {
        $type="IN";
        $st2 = $con->prepare("insert into warehouse_transactions(itemid,qty,type) values(?,?,?)");
        $st2->bind_param("iis", $row["ID"], $row["qty"],  $type);
        $st2->execute();
    }
}

function show_inputs(){
    global $con;
    $st = $con->prepare("SELECT warehouse_transactions.id,items.ExternalItemId, items.ItemName, items.BrandId, warehouse_transactions.qty 
    from items inner join warehouse_transactions
    on items.ID = warehouse_transactions.itemid
    where warehouse_transactions.type='IN' and warehouse_transactions.approved='No'");
    $st->execute();
    $rs = $st->get_result();
    return $rs;
}

function approve_inputs(){
    global $con;
    $st = $con->prepare("SELECT warehouse_transactions.id,warehouse_transactions.itemid, warehouse_transactions.qty 
    from warehouse_transactions WHERE warehouse_transactions.type='IN' and warehouse_transactions.approved='No'");
    $st->execute();
    $rs = $st->get_result();
    while($row = $rs->fetch_assoc())
    {
        $st2 = $con->prepare("update warehouse_transactions set approved='Yes' where id=?");
        $st2->bind_param("i", $row["id"]);
        $st2->execute();

        //check if it is new item
        $st3 = $con->prepare("select itemid from warehouse_items where itemid=?");
        $st3->bind_param("i", $row["itemid"]);
        $st3->execute();
        $rs3 = $st3->get_result();
        if($rs3->num_rows == 0){
            $st4 = $con->prepare("insert into warehouse_items values(?,?)");
            $st4->bind_param("ii", $row["itemid"], $row["qty"]);
            $st4->execute();
        }
        else{
            $st4 = $con->prepare("update warehouse_items set qty=qty + ? where itemid=?");
            $st4->bind_param("ii", $row["qty"], $row["itemid"]);
            $st4->execute();
        }

    }
}

function show_warehouse_items(){
    global $con;
    $st = $con->prepare("SELECT warehouse_items.itemid, items.ExternalItemId, items.ItemName, items.BrandId, warehouse_items.qty 
    from items inner join warehouse_items
    on items.ID = warehouse_items.itemid");
    $st->execute();
    $rs = $st->get_result();
    return $rs;
}

function withDraw($itemid, $qty, $notes){
        global $con;
        $type="OUT";
        $approved="Yes";
        $st = $con->prepare("insert into warehouse_transactions(itemid,qty,type,notes,approved) values(?,?,?,?,?)");
        $st->bind_param("iisss", $itemid, $qty, $type, $notes, $approved);
        $st->execute();

        $st2 = $con->prepare("update warehouse_items set qty=qty - ? where itemid=?");
        $st2->bind_param("ii", $qty, $itemid);
        $st2->execute();
}

function show_transactions(){
    global $con;
    $st = $con->prepare("SELECT items.ExternalItemId, items.ItemName, items.BrandId, warehouse_transactions.qty,
    warehouse_transactions.type,warehouse_transactions.approved, warehouse_transactions.trans_date, warehouse_transactions.notes 
    from items inner join warehouse_transactions
    on items.ID = warehouse_transactions.itemid");
    $st->execute();
    $rs = $st->get_result();
    return $rs;
}

function reject_item($notes, $id){
    global $con;
    $st = $con->prepare("update warehouse_transactions set notes=?, approved='Rejected' where id=?");
    $st->bind_param("si", $notes, $id);
    $st->execute();
}

function show_rejected(){
    global $con;
    $st = $con->prepare("SELECT items.ExternalItemId, items.ItemName, items.BrandId, warehouse_transactions.qty,
    warehouse_transactions.type,warehouse_transactions.approved, warehouse_transactions.trans_date, warehouse_transactions.notes 
    from items inner join warehouse_transactions
    on items.ID = warehouse_transactions.itemid where approved='Rejected'");
    $st->execute();
    $rs = $st->get_result();
    return $rs;
}

function approve_inputs_byadmin($qty){
    global $con;
    $st = $con->prepare("SELECT warehouse_transactions.id,warehouse_transactions.itemid, warehouse_transactions.qty 
    from warehouse_transactions WHERE warehouse_transactions.type='IN' and warehouse_transactions.approved='Rejected'");
    $st->execute();
    $rs = $st->get_result();
    while($row = $rs->fetch_assoc())
    {
        $st2 = $con->prepare("update warehouse_transactions set qty=?, approved='Yes' where id=?");
        $st2->bind_param("ii", $qty ,$row["id"]);
        $st2->execute();

        //check if it is new item
        $st3 = $con->prepare("select itemid from warehouse_items where itemid=?");
        $st3->bind_param("i", $row["itemid"]);
        $st3->execute();
        $rs3 = $st3->get_result();
        if($rs3->num_rows == 0){
            $st4 = $con->prepare("insert into warehouse_items values(?,?)");
            $st4->bind_param("ii", $row["itemid"], $qty);
            $st4->execute();
        }
        else{
            $st4 = $con->prepare("update warehouse_items set qty=qty + ? where itemid=?");
            $st4->bind_param("ii", $qty, $row["itemid"]);
            $st4->execute();
        }

    }
}

