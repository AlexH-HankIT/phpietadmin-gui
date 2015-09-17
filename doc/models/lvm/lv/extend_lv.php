<?php
use phpietadmin\app\models\lvm\lv;

require_once __DIR__ . '/../../registry.php';

// create object with volume group and existing volume
$lv = new lv\Lv('VG_data01', 'LV_test1');

// call the function extend_lv with the target size as parameter
// if your volume has 2gb and you want to add 2gb, you have to specify 4gb as size
$lv->extend_lv(4);

print_r($lv->logging->get_action_result());

// error handling output: (see the logging documentation)
// example success:
/*
Array
(
    [message] => The logical volume LV_test01 was successfully extended to 4gb
    [code] => 0
    [code_type] => intern
    [method] => Lv::extend_lv
)
*/

// example failure:
/*
Array
(
    [message] => The size cannot be smaller or equal!
    [code] => 3
    [code_type] => intern
    [method] => Lv::extend_lv
)
*/