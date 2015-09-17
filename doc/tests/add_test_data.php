<?php use phpietadmin\app\models, phpietadmin\app\models\lvm\lv;

// require the class
require_once __DIR__ . '/../../app/models/autoloader.php';

$vg = 'VG_System';
$size = 1;

for ($i=0; $i < 9; $i++) {
    $logical_volumes[] = 'LV_phpietadmin_test_' . $i;
}

for ($i=0; $i < 5; $i++) {
    $targets[] = 'iqn.target.dings.egal_' . $i;
}

function add_target() {
    global $targets;
    foreach ($targets as $target) {
        new models\target\Target($target);
    }
}

function delete_target() {
    global $targets;
    foreach ($targets as $target) {
        $iqn = new models\target\Target($target);
        $iqn->delete_target(true, true, false);
    }
}

function add_lv() {
    global $logical_volumes, $vg, $size;
    foreach ($logical_volumes as $logical_volume) {
        $lv = new lv\Lv($vg, $logical_volume);
        if ($lv->return_lv_status() !== true) {
            $lv->add_lv($size);
        }
    }
}

function delete_lv() {
    global $logical_volumes, $vg;
    foreach ($logical_volumes as $logical_volume) {
        $lv = new lv\Lv($vg, $logical_volume);
        if ($lv->return_lv_status() !== false) {
            $lv->remove_lv();
        }
    }
}

add_lv();
//delete_lv();