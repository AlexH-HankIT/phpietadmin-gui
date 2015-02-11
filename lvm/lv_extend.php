<?php
    require '../views/header.html';
    require '../views/nav.html';
    require '../views/lvm/display/lv/header.html';
    require '../views/lvm/menu.html';

    try {
        $data = get_volume_groups();
        $count = count($data);

        if ($data == "error") {
            throw new Exception("Error - Could not list volume groups");
        }

        if (isset($_POST['size'])) {
            $LV = $_COOKIE["logicalvolume"];
            exec("$sudo $lvextend -L ${_POST['size']}G $LV 2>&1", $status, $result);

            if ($result ==! 0) {
                throw new Exception("Error - Could not resize volume ${LV}. Server said: $status[0]");
            } else {
                require '../views/lvm/extend/success.html';
            }
        } else {
            if (isset($_POST['volumes'])) {
                $var = $_POST['volumes'] - 1;
                $VG = $_COOKIE["volumegroup"];

                $lvmdata = get_lvm_data($lvs, $VG);

                for ($i = 0; $i < count($lvmdata); $i++) {
                    $data2[$i] = "/dev/" . $VG . "/" . $lvmdata[$i][0];
                }

                $groups = get_lvm_data($vgs, $VG);

                // Get max possible size of volume
                preg_match("/(.*?)(?=\.|$)/", $groups[0][6], $maxsize);

                if ($maxsize[1] <= 1) {
                    throw new Exception("Error - Volume group $VG is too small for the extention of logical volumes");
                }

                // Get min (current) size of volume
                $LV = get_lvm_data($lvs, $data2[$var]);

                setcookie("logicalvolume", $data2[$var]);
                preg_match("/(.*?)(?=\.|$)/", $LV[0][3], $minsize);


                // Leave 1 gig free in volume group
                $maxsize2 = $maxsize[1] + $minsize[1] - 1;
                $minsize2 = $minsize[1] + 1;

                require '../views/lvm/extend/input.html';

            } else {
                if (!isset($_POST['vg_post'])) {
                    require '../views/lvm/extend/vg.html';
                } else {
                    $VG = $data[$_POST['vg_post'] - 1];
                    setcookie("volumegroup", $VG);
                    $lvmdata = get_lvm_data($lvs, $VG);

                    for ($i = 0; $i < count($lvmdata); $i++) {
                        $data2[$i] = "/dev/" . $VG . "/" . $lvmdata[$i][0];
                    }

                    require '../views/lvm/extend/lv.html';
                }
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        require '../views/error.html';
    }

    require '../views/div.html';
    require '../views/footer.html';
?>