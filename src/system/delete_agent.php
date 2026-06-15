<?php

require_once 'db/agents.php';
require_once 'db/vp_agents.php';

if(isset($_GET["agentid"]))
    delete_agent($_GET["agentid"]);
else
    delete_vp_agent($_GET["vpagentid"]);

echo '<script> window.location="home.php"; </script>';