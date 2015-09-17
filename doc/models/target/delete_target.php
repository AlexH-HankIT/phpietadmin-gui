<?php
use phpietadmin\app\models\target;

// require the class
require_once __DIR__ . '/../../autoloader.php';

// Create object
// if iqn doesn't exist, it will be created
$target = new target\Target('iqn.2014-12.com.example.iscsi:test4');

// param1: true/false disconnect initiators ("force" option)
// param2: true/false delete all access lists of the target
// param3: true/false delete all attached luns (if false, they will be detached)

$target->delete_target(true, true, false);

print_r($target->get_action_result(true));