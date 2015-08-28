<?php
use phpietadmin\app\models\lvm\lv;

require_once __DIR__ . '/../../../autoloader.php';

// create object with volume group and existing volume
$lv = new lv\Lv('VG_data02', 'LV_test01');

// call the function reduce_lv with the target size as parameter
// if your volume has 4gb and you want to shrink it to 2gb, you have to specify 2gb as size
// please shrink your filesystem before calling this or it will cause data loss!
$lv->reduce_lv(2);

// error handling output: (see the logging documentation)
// example success:
/*
Array
(
    [message] => The logical volume LV_test01 was successfully reduced to 2gb
    [code] => 0
    [code_type] => intern
    [method] => 1
)
*/

// example failure:
/*
no example available
*/