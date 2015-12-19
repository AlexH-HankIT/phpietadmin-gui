<?php
use app\core,
    app\models;

// Require files
require_once __DIR__ . '/../app/core/const.inc.php';
require_once MODEL_DIR . '/misc.php';

// Register autoloader
spl_autoload_register('app\models\Misc::loader');

// Create application
$app = new core\App();

// Read version file
try {
    $versionFile = models\Misc::getVersionFile();
} catch (Exception $e) {
    die('<h1>Invalid version file!</h1>');
}

// Check if installation is installed correctly
$app->app();