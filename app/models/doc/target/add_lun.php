<?php
use phpietadmin\app\models\target;

// require the class
require_once __DIR__ . '/../../autoloader.php';

// Create object
// if iqn doesn't exist, it will be created
$target = new target\Target('iqn.2014-12.com.example.iscsi:test2');

// @param   string  $type  fileio/blockio
// @param   string  $mode  wt/ro
$target->add_lun('/dev/VG_data01/test3', 'wt', 'fileio');

print_r($target->get_action_result());

// example failure:
/*
Array
(
    [message] => The lun /dev/VG_System/test2 is already in use by target iqn.2014-12.com.example.iscsi:test2
    [code] => 4
    [code_type] => intern
    [method] => phpietadmin\app\models\target\Target::add_lun
)
*/

// example success