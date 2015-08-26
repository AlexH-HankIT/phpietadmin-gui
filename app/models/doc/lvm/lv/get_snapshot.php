<?php
use phpietadmin\app\models\lvm\lv;

require_once __DIR__ . '/../../../autoloader.php';

// create object with volume group and non-snapshot volume
$lv = new lv\Lv('VG_data02', 'LV_test01');

$data = $lv->get_snapshot();

// returns array with all snapshots and their properties
/*
Array
(
    [0] => Array
        (
            [LV] => LV_test01_snapshot_1439151739
            [VG] => VG_data02
            [Attr] => swi-a-s---
            [LSize] => 1.00
            [Pool] =>
            [Origin] => LV_test01
            [Data%] => 0,00
            [Meta%] =>
            [Move] =>
            [Log] =>
            [Cpy%Sync] =>
            [Convert] =>
        )

)
*/

// error handling output: (see the logging documentation)
// example success:
/*
Array
(
    [message] => The snapshots of the logical volume LV_test01 were successfully fetched
    [code] => 0
    [code_type] => intern
    [method] => Lv::get_snapshot
)

*/

// example failure:
/*
no example available
*/