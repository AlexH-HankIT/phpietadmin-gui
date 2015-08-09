<?php
// require the class
require_once('../../vg/Vg.php');

// return data from vgs for the volume group
$vg = new Vg('VG_data01');
$data = $vg->get_vg();

// example output
/*
Array
(
    [0] => Array
    (
        [VG] => VG_data01
            [#PV] => 1
            [#LV] => 4
            [#SN] => 1
            [Attr] => wz--n-
            [VSize] => 5.00
            [VFree] => 1.00
        )

)
*/

// return data from vgs for the all volume groups
$vg = new Vg(false);
$data = $vg->get_vg();

// example output
/*
Array
(
    [0] => Array
    (
        [VG] => VG_data01
        [#PV] => 1
        [#LV] => 4
        [#SN] => 1
        [Attr] => wz--n-
        [VSize] => 5.00
        [VFree] => 1.00
    )
    [1] => Array
    (
        [VG] => VG_data02
        [#PV] => 1
        [#LV] => 1
        [#SN] => 0
        [Attr] => wz--n-
        [VSize] => 10.00
         [VFree] => 9.00
    )
)
*/