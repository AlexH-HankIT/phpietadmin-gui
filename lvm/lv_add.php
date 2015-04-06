<?php
    require '../views/header.html';
    require '../views/nav.html';
    print_title("Logical Volumes");
    require '../views/lvm/menu.html';

    try {
            $lvm = new Lvm;

            // Get array with volume groups and count
            $data = $lvm->get_volume_groups();
            $count = count($data);

            if ($data == "error") {
                throw new Exception("Error - Could not list volume groups");
            }

            if (isset($_POST['name']) && isset($_POST['size'])) {
                if (empty($_POST['name'])) {
                    throw new Exception("Error - Please type a name");
                }

                $NAME = $_POST['name'];
                $SIZE = $_POST['size'];
                $VG = $_COOKIE["volumegroup"];

                exec("{$a_config['misc']['sudo']} {$a_config['lvm']['lvcreate']} -L${SIZE}G -n$NAME $VG 2>&1", $status, $result);

                if ($result ==! 0) {
                    throw new Exception("Error - Could not create volume $NAME. Server said: $status[0]");
                } else {
                    require '../views/lvm/add/lv/success.html';
                }
            } else {
                if (!isset($_POST['vg_post'])) {
                    require '../views/lvm/add/lv/vg.html';
                } else {
                    if (empty($_POST['vg_post'])) {
                        throw new Exception("Error - Please choose a volume group");
                    }

                    // Get name of selected volume group
                    $VG = $data[$_POST['vg_post'] - 1];

                    // Get data from selected group
                    $data = $lvm->get_lvm_data($a_config['lvm']['vgs'], $VG);

                    // Extract free size of the volume group
                    preg_match("/(.*?)(?=\.|$)/", $data[0][6], $freesize);

                    if ($freesize[1] <= 1) {
                        throw new Exception("Error - Volume group $VG is too small for new volumes");
                    }

                    setcookie("volumegroup", $VG);

                    require '../views/lvm/add/lv/input.html';
                }
            }
    } catch (Exception $e) {
        print_error($e);
    }

    require '../views/footer.html';
?>