<?php
use phpietadmin\app\models\target;

// require the class
require_once __DIR__ . '/../../autoloader.php';

// Create object
// if iqn doesn't exist, it will be created
$target = new target\Target('iqn.2014-12.com.example.iscsi:test2');

$target->add_setting('ImmediateData', 'No');

print_r($target->get_action_result());