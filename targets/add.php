<?php
    require '../views/header.html';
    require '../views/nav.html';
    require '../views/targets/add/header.html';
    require '../views/targets/menu.html';

    try {
        // Check if service is running and abort if not
        check_service_status();

        // Get array with volume groups and count
        $volumegroups = get_volume_groups();

        // Check if something went wrong
        if($volumegroups == "error") {
            throw new Exception("Error - Could not list volume groups");
        }

        if (isset($_POST['name']) && isset($_POST['path'])) {
            if (empty($_POST['name'])) {
                throw new Exception("Error - Please enter a name");
            } elseif (empty($_POST['path'])) {
                throw new Exception("Error - Please choose a path");
            }

            // Read name in var
            $NAME = $_POST['name'];

            // Read VG from cookie in var
            $VG = $_COOKIE["volumegroup"];

            $data = get_lvm_data($lvs, $VG);

            // Abort if vg has no lvs
            if ($data == "error") {
                throw new Exception("Error - Volume Group $VG is empty");
            }

            // Get array with full path to the volumes and ignore already used ones
            for ($i = 0; $i < count($data); $i++) {
                $logicalvolumes[$i] = "/dev/" . $VG . "/" . $data[$i][0];
            }

            // Read existing volumes in var
            $volumes = file_get_contents($proc_volumes);

            if (!empty($volumes)) {
                // Check if name is already in use
                preg_match_all("/name:(.*)/", $volumes, $a_name);
                $key = array_search("$iqn:$NAME", $a_name[1]);

                if ($a_name[1][$key] == "$iqn:$NAME") {
                    throw new Exception("Error - The name $NAME is already in use");
                }

                // Extract volumes if existing
                preg_match_all("/path:(.*)/", $volumes, $paths);

                // Filter already used ones
                $logicalvolumes = array_diff($logicalvolumes, $paths[1]);

                //Rebuild array index
                $logicalvolumes = array_values($logicalvolumes);
            }

            if(empty($logicalvolumes)) {
                throw new Exception("Error - All volumes in volume group $VG are already in use");
            }

            // Read path to lv in var
            $LV = $logicalvolumes[$_POST['path']-1];

            // Add target and lun to daemon
            exec("$sudo $ietadm --op new --tid=0 --params Name=$iqn:$NAME 2>&1", $status, $result);
            if ($result ==! 0) {
                throw new Exception("Error - Could not add target $NAME. Server said: $status[0]");
            }

            $volumes = file_get_contents($proc_volumes);
            preg_match_all("/tid:([0-9].*?) /", $volumes, $a_tid);
            preg_match_all("/name:(.*)/", $volumes, $a_name);
            $key = array_search($NAME, $a_name);
            $TID = $a_tid[1][$key];

            // Add target and lun to config file
            exec("$sudo $ietadm --op new --tid=$TID --lun=0 --params Path=$LV 2>&1", $status, $result);
            if ($result ==! 0) {
                throw new Exception("Error - Could not add lun. Server said: $status[0]");
            }

            $current = "\nTarget $iqn:$NAME\n Lun 0 Type=fileio,Path=$LV\n";
            file_put_contents($ietd_config_file, $current, FILE_APPEND | LOCK_EX);

            require '../views/targets/add/success.html';

        } else {
    if (!isset($_POST['vg_post'])) {
        // Show VG Input if $_POST['vg_post'] is not set
        require '../views/targets/vg.html';
    } else {
        if (empty($_POST['vg_post'])) {
            throw new Exception("Error - Please choose a volume group");
        }
        // Write selected volume group in $VG
        $VG = $volumegroups[$_POST['vg_post'] - 1];

        // Get all logical volumes in group $VG
        $data = get_lvm_data($lvs, $VG);

        if ($data == "error") {
            throw new Exception("Error - Volume Group $VG is empty");
        }

        setcookie("volumegroup", $VG);

        // Get array with full path to the volumes and ignore already used ones
        for ($i = 0; $i < count($data); $i++) {
            $logicalvolumes[$i] = "/dev/" . $VG . "/" . $data[$i][0];
        }

        // Read existing volumes in array
        $volumes = file_get_contents($proc_volumes);
        if (!empty($volumes)) {
            // Extract volumes if existing
            preg_match_all("/path:(.*)/", $volumes, $paths);

            // Filter already used ones
            $logicalvolumes = array_diff($logicalvolumes, $paths[1]);

            //Rebuild array index
            $logicalvolumes = array_values($logicalvolumes);
        }

        if (empty($logicalvolumes)) {
            throw new Exception("Error - All volumes in volume group $VG are already in use");
        } else {
            require '../views/targets/add/input.html';
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