<?php
use phpietadmin\app\models\lvm\vg;

// require the class
require_once __DIR__ . '/../../../autoloader.php';

// return all logical volumes from $this->vg_name
$vg = new vg\Vg('VG_data01');
$data = $vg->get_all_lv_from_vg('');

// example output
/*
Array
(
    [0] => Array
    (
        [LV] => test2
        [VG] => VG_data01
        [Attr] => owi-a-s---
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
        [LV] => test2_snapshot_1439079786
        [VG] => VG_data01
        [Attr] => swi-a-s---
        [LSize] => 1.00
        [Pool] =>
        [Origin] => test2
        [Data%] => 0,00
        [Meta%] =>
        [Move] =>
        [Log] =>
        [Cpy%Sync] =>
        [Convert] =>
    )
)
*/

// return all snapshots from $this->vg_name
$vg = new lvm_vg_Vg('VG_data01');
$data = $vg->get_all_lv_from_vg(true);

// example output
/*
Array
(
    [0] => Array
    (
        [LV] => test2_snapshot_1439079786
        [VG] => VG_data01
        [Attr] => swi-a-s---
        [LSize] => 1.00
        [Pool] =>
        [Origin] => test2
        [Data%] => 0,00
        [Meta%] =>
        [Move] =>
        [Log] =>
        [Cpy%Sync] =>
        [Convert] =>
    )
)
*/

// return all non-snapshot volumes from $this->vg_name
$vg = new lvm_vg_Vg('VG_data01');
$data = $vg->get_all_lv_from_vg(false);

// example output
/*
Array
(
    [0] => Array
    (
        [LV] => test2
        [VG] => VG_data01
        [Attr] => owi-a-s---
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
)
*/