<?php
    require '../includes.php';
    $layout->print_nav();
    $layout->print_title("Logical Volumes");
    require '../views/lvm/menu.html';

    try {
        $lvm = new Lvm;

        $data = $lvm->get_volume_groups();
        $count = count($data);

        if ($data == "error") {
            throw new Exception("Error - Could not list volume groups");
        }

        if (isset($_POST['size'])) {
            $LV = $_COOKIE["logicalvolume"];
            exec("{$a_config['misc']['sudo']} {$a_config['lvm']['lvextend']} -L ${_POST['size']}G $LV 2>&1", $status, $result);

            if ($result ==! 0) {
                throw new Exception("Error - Could not resize volume ${LV}. Server said: $status[0]");
            } else {
                require '../views/lvm/extend/success.html';
            }
        } else {
            if (isset($_POST['volumes'])) {
                $var = $_POST['volumes'] - 1;

                if ($var < 0) {
                    throw new Exception("Error - Please select a logical volume");
                }

                $VG = $_COOKIE["volumegroup"];

                $lvmdata = $lvm->get_lvm_data($a_config['lvm']['lvs'], $VG);

                for ($i = 0; $i < count($lvmdata); $i++) {
                    $data2[$i] = "/dev/" . $VG . "/" . $lvmdata[$i][0];
                }

                $groups = $lvm->get_lvm_data($a_config['lvm']['vgs'], $VG);

                // Get max possible size of volume
                preg_match("/(.*?)(?=\.|$)/", $groups[0][6], $maxsize);

                if ($maxsize[1] <= 1) {
                    throw new Exception("Error - Volume group $VG is too small for the extention of logical volumes");
                }

                // Get min (current) size of volume
                $LV = $lvm->get_lvm_data($a_config['lvm']['lvs'], $data2[$var]);

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
                    if (empty($_POST['vg_post'])) {
                        throw new Exception("Error - Please choose a volume group");
                    }

                    $VG = $data[$_POST['vg_post'] - 1];

                    setcookie("volumegroup", $VG);
                    $lvmdata = $lvm->get_lvm_data($a_config['lvm']['lvs'], $VG);

                    if ($lvmdata == "error") {
                        throw new Exception("Error - Volume group is empty");
                    }

                    for ($i = 0; $i < count($lvmdata); $i++) {
                        $data2[$i] = "/dev/" . $VG . "/" . $lvmdata[$i][0];
                    }

                    require '../views/lvm/extend/lv.html';
                }
            }
        }
    } catch (Exception $e) {
        $layout->print_error($e);
    }

    $layout->print_footer();
?>