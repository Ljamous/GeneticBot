<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */


require_once 'db/agents.php';
require_once 'db/vp_agents.php';

if(isset($_GET["agentid"]))
    delete_agent($_GET["agentid"]);
else
    delete_vp_agent($_GET["vpagentid"]);

echo '<script> window.location="home.php"; </script>';