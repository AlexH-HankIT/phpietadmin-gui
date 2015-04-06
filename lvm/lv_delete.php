<?php
    require '../views/header.html';
    require '../views/nav.html';
    print_title("Logical Volumes");
    require '../views/lvm/menu.html';

    try {
        $lvm = new Lvm;

        // Get array with volume groups and count
        $groups = $lvm->get_volume_groups();

        if ($groups == "error") {
            throw new Exception("Error - Could not list volume groups");
        }

        if (!isset($_POST['vg_post']) && !isset($_POST['volumes'])) {
            require '../views/lvm/delete/lv/vg.html';
        } else {
            if (isset($_POST['volumes'])) {
                $VG = $_COOKIE["volumegroup"];

                $data = $lvm->get_lvm_data($a_config['lvm']['lvs'], $VG);

                for ($i = 0; $i < count($data); $i++) {
                    $data2[$i] = "/dev/" . $VG . "/" . $data[$i][0];
                }

                // Get array with volumes and paths
                $volumes = file_get_contents($a_config['iet']['proc_volumes']);
                preg_match_all("/path:(.*)/", $volumes, $paths);

                // Filter all used volumes
                $data2 = array_diff($data2, $paths[1]);

                // Rebuild array index
                $data2 = array_values($data2);

                $var = $_POST['volumes'] - 1;
                exec("{$a_config['misc']['sudo']} {$a_config['lvm']['lvremove']} -f $data2[$var] 2>&1", $status, $result);

                if ($result ==! 0) {
                    throw new Exception("Error - Could not delete volume $data2[$var] Server said: $status[0]");
                } else {
                    require '../views/lvm/delete/lv/success.html';
                }
            } else {
                if (empty($_POST['vg_post'])) {
                    throw new Exception("Error - Please choose a volume group");
                }

                // Write name of volume group in var and save it as cookie for later use
                $VG = $groups[$_POST['vg_post'] - 1];
                setcookie("volumegroup", $VG);

                $data = $lvm->get_lvm_data($a_config['lvm']['lvs'], $VG);

                if ($data == "error") {
                    throw new Exception("Error - Volume group $VG is empty");
                }

                // Get array with full path to the volumes and ignore already used ones
                for ($i = 0; $i < count($data); $i++) {
                    $data2[$i] = "/dev/" . $VG . "/" . $data[$i][0];
                }

                // Get array with volumes and paths
                $volumes = file_get_contents($a_config['iet']['proc_volumes']);
                preg_match_all("/path:(.*)/", $volumes, $paths);

                // Filter all used volumes
                $data2 = array_diff($data2, $paths[1]);

                // Rebuild array index
                $data2 = array_values($data2);

                require '../views/lvm/delete/lv/input.html';
            }
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
        require '../views/error.html';
    }

    require '../views/div.html';
    require '../views/footer.html';
?>