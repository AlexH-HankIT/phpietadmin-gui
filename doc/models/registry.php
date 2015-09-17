<?php

use phpietadmin\app\models,
    phpietadmin\app\models\logging,
    phpietadmin\app\core;

// require the class
require_once __DIR__ . '/../../app/core/autoloader.php';

$registry = core\Registry::getInstance();
$registry->set('database', new models\Database());
$registry->set('logging', new logging\Logging());
$registry->set('std',  new models\Std());