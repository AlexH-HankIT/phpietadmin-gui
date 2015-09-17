<?php
use phpietadmin\app\models\lvm\lv;

require_once __DIR__ . '/../../../autoloader.php';

// create object with volume group and snapshot volume
$lv = new lv\Lv('VG_data02', 'LV_test01_snapshot_1439152999');

// call merge snapshot function
$lv->merge_snapshot();

// error handling output: (see the logging documentation)
// example success:
/*
Array
(
    [message] => The snapshot LV_test01_snapshot_1439152999 was successfully merged
    [code] => 0
    [code_type] => intern
    [method] => Lv::merge_snapshot
)
*/

// example failure:
/*
no example available
*/