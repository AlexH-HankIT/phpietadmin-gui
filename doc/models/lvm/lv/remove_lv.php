<?php
use phpietadmin\app\models\lvm\lv;

require_once __DIR__ . '/../../../autoloader.php';

// create object with volume group and existing logical volume name
// works with snapshots and normal volumes
$lv = new lv\Lv('VG_data01', 'test2');

$lv->remove_lv();

// error handling output: (see the logging documentation)
// example success:
/*
Array
(
    [message] => The logical volume test2 was successfully removed from the volume group VG_data01
    [code] => 0
    [code_type] => intern
    [method] => Lv::remove_lv
)
*/

// example failure:
/*
no example available
*/