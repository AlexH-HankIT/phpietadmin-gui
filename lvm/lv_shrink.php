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

            exec("{$a_config['misc']['sudo']} {$a_config['lvm']['lvreduce']} -f -L ${_POST['size']}G $LV 2>&1", $status, $result);

            if ($result ==! 0) {
                throw new Exception("Error - Could not resize volume ${LV}. Server said: $status[0]");
            } else {
                require '../views/lvm/shrink/success.html';
            }
        } else {
            if (isset($_POST['volumes'])) {
                $var = $_POST['volumes'] - 1;

                if ($var < 0) {
                    throw new Exception("Error - Please select a logical volume");
                }

                $VG = $_COOKIE["volumegroup"];

                $lvmdata = get_lvm_data($a_config['lvm']['lvs'], $VG);

                for ($i = 0; $i < count($lvmdata); $i++) {
                    $data2[$i] = "/dev/" . $VG . "/" . $lvmdata[$i][0];
                }

                // Get max (current) size of volume
                $LV = get_lvm_data($a_config['lvm']['lvs'], $data2[$var]);

                setcookie("logicalvolume", $data2[$var]);
                preg_match("/(.*?)(?=\.|$)/", $LV[0][3], $maxsize);

                if ($maxsize[1] <= 1) {
                    throw new Exception("Error - Volume $data2[$var] can't be shrunk");
                }

                $maxsize2 = $maxsize[1] - 1;

                require '../views/lvm/shrink/input.html';
            } else {
                if (!isset($_POST['vg_post'])) {
                    require '../views/lvm/shrink/vg.html';
                } else {
                    if (empty($_POST['vg_post'])) {
                        throw new Exception("Error - Please choose a volume group");
                    }

                    $VG = $data[$_POST['vg_post'] - 1];
                    setcookie("volumegroup", $VG);

                    $lvmdata = get_lvm_data($a_config['lvm']['lvs'], $VG);

                    if ($lvmdata == "error") {
                        throw new Exception("Error - Volume group is empty");
                    }

                    for ($i = 0; $i < count($lvmdata); $i++) {
                        $data2[$i] = "/dev/" . $VG . "/" . $lvmdata[$i][0];
                    }

                    require '../views/lvm/shrink/lv.html';
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