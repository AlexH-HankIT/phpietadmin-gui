<?php
use phpietadmin\app\models\lvm\lv;

require_once __DIR__ . '/../../../autoloader.php';

// create object with volume group and existing logical volume name
$lv = new lv\Lv('VG_data01', 'test1');

// the property $this->lv_name is updated to the new name, if the rename was successful
// call the function with the new name
$lv->rename_lv("test1_renamed");

// error handling output: (see the logging documentation)
// example success:
/*
 Array
(
    [message] => The logical volume test1 was successfully renamed to test1_renamed
    [code] => 0
    [code_type] => intern
    [method] => 1
)
*/

// example failure:
/*
no example available
*/