<?php
/* ==============================================================================
 * Copyright (c) 2026 Ljamous/GeneticBot. All rights reserved.
 *
 * This code is for educational and non-commercial purposes only and may not be 
 * used or redistributed without explicit written permission from the publisher.
 * ==============================================================================
 */

// Load central root .env file if available
$rootEnvPath = dirname(__DIR__, 3) . DIRECTORY_SEPARATOR . '.env';
if (file_exists($rootEnvPath)) {
    $lines = file($rootEnvPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        $line = trim($line);
        if ($line === '' || strpos($line, '#') === 0) continue;
        if (strpos($line, '=') !== false) {
            list($envKey, $envVal) = explode('=', $line, 2);
            $envKey = trim($envKey);
            $envVal = trim($envVal, " \t\n\r\0\x0B\"'");
            if (getenv($envKey) === false) {
                putenv("{$envKey}={$envVal}");
                $_ENV[$envKey] = $envVal;
            }
        }
    }
}

// config.php
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USERNAME', getenv('DB_USER') ?: 'db_user');
define('DB_PASSWORD', getenv('DB_PASSWORD') ?: 'db_password');
define('DB_NAME', getenv('DB_NAME') ?: 'app_db');
?>