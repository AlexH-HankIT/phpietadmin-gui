<?php
use phpietadmin\app\models\lvm\pv;

require_once __DIR__ . '/../../../autoloader.php';

// insert a existing physical volume, if set to false all physical volumes will be returned
$pv = new pv\Pv('/dev/sda2');

$data = $pv->get_pv();

// output
/*
Array
(
    [0] => Array
        (
            [PV] => /dev/sda2
            [VG] => VG_System
            [Fmt] => lvm2
            [Attr] => a--
            [PSize] => 111.70
            [PFree] => 48.07
        )

)
*/

// error handling output: (see the logging documentation)
//example success
/*
Array
(
    [message] => The data of the physical volume /dev/sda2 was successfully parsed
    [code] => 0
    [code_type] => intern
    [method] => Pv::get_pv
)

*/

// example failure
/*
Array
(
    [message] => The physical volume/dev/sda does not exist or some other error occurred!
    [code] => 3
    [code_type] => intern
    [method] => Pv::get_pv
)
*/