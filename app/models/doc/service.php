<?php

use phpietadmin\app\models;

// require the class
require_once __DIR__ . '/../autoloader.php';

$service = new models\Service('cron');

//$service->action('stop');
//$service->action('status');
$service->delete_from_db();

print_r($service->get_action_result());