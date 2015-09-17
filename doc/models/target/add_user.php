<?php
use phpietadmin\app\models\target;

// require the class
require_once __DIR__ . '/../../app/models/autoloader.php';

// Create object
// if iqn doesn't exist, it will be created
$target = new target\Target('iqn.2014-12.com.example.iscsi:test2');

$target->add_user(1, false, 'IncomingUser');

print_r($target->get_action_result());

// success output:
/*
Array
(
    [message] => The user test was successfully added!
    [code] => 0
    [code_type] => intern
    [method] => phpietadmin\app\models\target\Target::add_user
)
*/

// error output:
/*
Array
(
    [message] => The user test was already added as IncomingUser!
    [code] => 4
    [code_type] => intern
    [method] => phpietadmin\app\models\target\Target::add_user
)
*/