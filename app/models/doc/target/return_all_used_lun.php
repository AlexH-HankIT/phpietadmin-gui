<?php
use phpietadmin\app\models\target;
use phpietadmin\app\models\lvm\lv;

// no error handling via logging function get_result() included!

// require the class
require_once __DIR__ . '/../../autoloader.php';

// Create object
// if iqn doesn't exist, it will be created
$target = new target\Target('iqn.2014-12.com.example.iscsi:test2');

// call function
$data = $target->return_all_used_lun();

if ($data !== false) {
    print_r($data);
} else {
    var_dump($data);
}

// success output:
/*
Array
(
    [0] => /dev/VG_data01/test3
    [1] => /dev/VG_data02/LV_test01
    [2] => /dev/VG_data01/test4
)
*/

// error output
/*
bool(false)
*/