<?php
use phpietadmin\app\models\lvm\lv;

require_once('../../../autoloader.php');

// return data from the specified logical volume
$lv = new lv\Lv('VG_data02', 'LV_test01');

$data = $lv->get_lv();

// array with logical volume properties
/*
Array
(
    [0] => Array
        (
            [LV] => LV_test01
            [VG] => VG_data02
            [Attr] => -wi-a-----
            [LSize] => 2.00
            [Pool] =>
            [Origin] =>
            [Data%] =>
            [Meta%] =>
            [Move] =>
            [Log] =>
            [Cpy%Sync] =>
            [Convert] =>
        )

)
*/

// return data from all logical volume of the volume group
$lv = new lv\Lv('VG_data01', false);

$data = $lv->get_lv();

// array with all logical volumes
/*
Array
(
    [0] => Array
        (
            [LV] => test3
            [VG] => VG_data01
            [Attr] => -wi-a-----
            [LSize] => 1.00
            [Pool] =>
            [Origin] =>
            [Data%] =>
            [Meta%] =>
            [Move] =>
            [Log] =>
            [Cpy%Sync] =>
            [Convert] =>
        )

    [1] => Array
        (
            [LV] => test4
            [VG] => VG_data01
            [Attr] => -wi-a-----
            [LSize] => 1.00
            [Pool] =>
            [Origin] =>
            [Data%] =>
            [Meta%] =>
            [Move] =>
            [Log] =>
            [Cpy%Sync] =>
            [Convert] =>
        )

)
*/

// return data from all logical volumes
$lv = new lv\Lv(false, false);

$data = $lv->get_lv();

/*
Array
(
    [0] => Array
        (
            [LV] => test3
            [VG] => VG_data01
            [Attr] => -wi-a-----
            [LSize] => 1.00
            [Pool] =>
            [Origin] =>
            [Data%] =>
            [Meta%] =>
            [Move] =>
            [Log] =>
            [Cpy%Sync] =>
            [Convert] =>
        )

    [1] => Array
        (
            [LV] => test4
            [VG] => VG_data01
            [Attr] => -wi-a-----
            [LSize] => 1.00
            [Pool] =>
            [Origin] =>
            [Data%] =>
            [Meta%] =>
            [Move] =>
            [Log] =>
            [Cpy%Sync] =>
            [Convert] =>
        )

    [2] => Array
        (
            [LV] => LV_test01
            [VG] => VG_data02
            [Attr] => -wi-a-----
            [LSize] => 2.00
            [Pool] =>
            [Origin] =>
            [Data%] =>
            [Meta%] =>
            [Move] =>
            [Log] =>
            [Cpy%Sync] =>
            [Convert] =>
        )

)
*/