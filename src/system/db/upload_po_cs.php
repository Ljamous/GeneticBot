<?php
if (!empty($_FILES)) {
    

    $tempFile = $_FILES['file']['tmp_name'];             
    $targetFile =  "../cspo/". $_FILES['file']['name']; 
    move_uploaded_file($tempFile,$targetFile); 
    
}