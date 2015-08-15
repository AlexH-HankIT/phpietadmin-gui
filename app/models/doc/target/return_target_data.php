<?php
use phpietadmin\app\models\target;

// ToDo: Insert session examples

// require the class
require_once __DIR__ . '/../../autoloader.php';

// Create object
// if iqn doesn't exist, it will be created
$target = new target\Target('iqn.2014-12.com.example.iscsi:test2');

// the get_target_data() function is nerver called directly from outside the class
// the constructor will gather all available data and expose them via the return_target_data() method
print_r($target->return_target_data());

// example output:
// target without lun or connected sessions:
/*
Array
(
    [index] => 0
    [tid] => 6
    [iqn] => iqn.2014-12.com.example.iscsi:test2
    [count_options] => 0
)
*/

// target with lun:
/*
Array
(
    [index] => 0
    [tid] => 6
    [iqn] => iqn.2014-12.com.example.iscsi:test2
    [count_options] => 1
    [lun] => Array
        (
            [0] => Array
                (
                    [id] => 0
                    [state] => 0
                    [iotype] => fileio
                    [iomode] => wt
                    [blocks] => 2097152
                    [blocksize] => 512
                    [path] => /dev/VG_data01/test7
                )

        )

)
*/

// target with session:
/*
Array
(
    [index] => 0
    [tid] => 2
    [iqn] => iqn.2014-12.com.example.iscsi:test2
    [count_options] => 0
    [session] => Array
        (
            [0] => Array
                (
                    [sid] => 562950876233792
                    [initiator] => iqn.1991-05.com.microsoft:initiator.domain.local
                    [cid] => 1
                    [ip] => 127.0.0.1
                    [state] => active
                    [hd] => none
                    [dd] => none
                )

        )

)

*/