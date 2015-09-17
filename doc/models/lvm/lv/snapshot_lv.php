<?php
use phpietadmin\app\models\lvm\lv;

require_once __DIR__ . '/../../../autoloader.php';

// create object with volume group and existing logical volume name
$lv = new lv\Lv('VG_data02', 'LV_test01');

// call snapshot function with size
// the snapshot name will be the name of the logical volume + _snapshot_$unixtimestamp
$lv->snapshot_lv(1);

// error handling output: (see the logging documentation)
// example success:
/*
Array
(
    [message] => The snapshot of the logical volume LV_test01 was successfully created
    [code] => 0
    [code_type] => intern
    [method] => Lv::snapshot_lv
)
*/

// example failure:
/*
no example available
*/