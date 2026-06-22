<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

if (!empty($_FILES)) {
    

    $tempFile = $_FILES['file']['tmp_name'];             
    $targetFile =  "../imgpo/". $_FILES['file']['name']; 
    move_uploaded_file($tempFile,$targetFile); 
    
}