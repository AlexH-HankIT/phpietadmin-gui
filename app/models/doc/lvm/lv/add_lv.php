<?php
use phpietadmin\app\models\lvm\lv;

require_once __DIR__ . '/../../../autoloader.php';

// create object with volume group and non-existent logical volume name
$lv = new lv\Lv('VG_data01', 'test1');

// call add_lv function with size in gb
$lv->add_lv(1);

// error handling output: (see the logging documentation)
// example failure:
/*
 Array
(
    [message] => The volume group VG_data01 has not enough space to create the logical volume test1
    [code] => 8
    [code_type] => intern
    [method] => Lv::add_lv
)

Array
(
    [message] => The logical volume test1 does already exist in the volume group VG_data01
    [code] => 4
    [code_type] => intern
    [method] => Lv::add_lv
)
*/

// phpietadmin doesn't handle all errors:
// This error was returned from lvcreate:
/*
Array
(
    [message] => The logical volume test1 was not added to the volume group VG_data01
    [code] => 5
    [code_type] => extern
    [status] => Array
        (
            [0] =>   Volume group "VG_data01" has insufficient free space (511 extents): 512 required.
        )

    [method] => Lv::add_lv
)
*/

// example success:
/*
Array
(
    [message] => The logical volume test1 was successfully added to the volume group VG_data01
    [code] => 0
    [code_type] => intern
    [method] => Lv::add_lv
)
*/