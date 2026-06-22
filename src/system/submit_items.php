<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */
 include './header.php';
    if(isset($_GET["pckid"]))
    $_SESSION["pckid"] = $_GET["pckid"];
?>

<br/><br/>
<center>
    <form action="submit_items.php" method="post">
        <input type="text" name="expdate" placeholder="Expected Delivery Period"
        class="form-control" style="width:25%" />
        <br/><br/>
        <input type="submit" name="btnsub" value="Submit Items"
        class="btn btn-primary" style="width:25%" />
    </form>
</center>

<?php 

    if(isset($_POST["btnsub"])){
        require_once 'db/mycon.php';

        $status="Items Submitted";
        $st = $con->prepare("update packages set status=?, exp_delivery=? where id=?");
        $st->bind_param("ssi", $status, $_POST["expdate"] , $_SESSION["pckid"]);
        $st->execute();

        echo '<script>window.location="tracking_view.php";</script>';
    }

?>


<?php include './footer.php'; ?>