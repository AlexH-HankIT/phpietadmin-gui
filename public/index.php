<?php
use app\core;

// Require files
require_once __DIR__ . '/../app/core/const.inc.php';
require_once MODEL_DIR . '/functions.php';

// Register autoloader
spl_autoload_register('loader');

// Create application
$app = new core\App();

// Read version file
try {
    $versionFile = getVersionFile();
} catch (Exception $e) {
    die('<h1>Invalid version file!</h1>');
}

// Check if installation is installed correctly
$app->app();
