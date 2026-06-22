<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

// config.php
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USERNAME', getenv('DB_USER') ?: 'db_user');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'db_password');
define('DB_NAME', getenv('DB_NAME') ?: 'app_db');
?>