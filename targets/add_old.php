<?php
    require '../views/header.html';
    require '../views/nav.html';
    require '../views/targets/add/header.html';
    require '../views/targets/menu.html';

    try {
        // Get array with volume groups and count
        $groups = get_volume_groups();

        if ($groups == "error") {
            throw new Exception("Error - Could not list volume groups");
        }

        $vg_out2 = $groups[0];

        if (!isset($_POST['vg_post']) && !isset($_POST['name'])) {
            require '../views/targets/vg.html';
        } else {
            //$VG = $vg_out2[$_POST['vg_post'] - 1];
            setcookie("volumegroup", $VG);

            // Get array with volume groups
            $data = get_one_lvm_data($lvs, $VG);

            // Get array with volumes and paths
            $a_volumes = get_file_cat($proc_volumes);
            $paths = get_data_regex($a_volumes, "/path:(.*)/");

            // Check if there are used volumes
            if ($paths !== "error") {
                // Filter all used volumes
                $data = array_diff($data, $paths);

                // Rebuild array index
                $data = array_values($data);
            }

            // Get array with full path to the volumes and ignore already used ones
            for ($i = 0; $i < count($data); $i++) {
                $data2[$i] = "/dev/" . $VG . "/" . $data[$i][0];
            }

            $VG = $_COOKIE["volumegroup"];

            echo "<pre>";
            print_r($data2);
            echo "</pre>";

            if (!isset($_POST['name']) && !empty($data2)) {
                require '../views/targets/add/input.html';
            } else if (empty($data2)) {
                throw new Exception("Error - No volumes available");
            } else {
                $count = $_POST['path'] - 1;

                echo $count;



                $path = $data2[$count];

                echo $path;

                if (!$_POST['name']) {
                    throw new Exception("Error - No name given");
                } else if (!$_POST['path']) {
                    throw new Exception("Error - No path given");
                }

                // file_exists does not work with block devices
                /*else if (!file_exists($path)) {
                    throw new Exception("Error - The file $path was not found");
                }*/

                if (file_exists($proc_volumes)) {
                    $a_volumes = get_file_cat($proc_volumes);

                    if ($a_volumes != "error") {
                        $paths = get_data_regex($a_volumes, "/path:(.*)/");
                        $key2 = array_search($path, $paths);
                        $name = get_data_regex($a_volumes, "/name:(.*)/");
                        $key3 = array_search("$iqn:$_POST[name]", $name);

                        if ($name[$key3] == "$iqn:$_POST[name]") {
                            throw new Exception("Error - The name $_POST[name] is already in use");
                        } else if ($paths[$key2] == $path) {
                            throw new Exception("Error - The path $path is already in use");
                        }
                    }

                    $add_target_output = shell_exec("$sudo $ietadm --op new --tid=0 --params Name=$iqn:$_POST[name]");
                    $a_volumes = get_file_cat($proc_volumes);
                    $tid = get_data_regex($a_volumes, "/tid:([0-9].*?)/");
                    $name = get_data_regex($a_volumes, "/name:(.*)/");
                    $key = array_search("$iqn:$_POST[name]", $name);

                    $add_lun_output = shell_exec("$sudo $ietadm --op new --tid=$tid[$key] --lun=0 --params Path=$path");
                    $current = "\nTarget $iqn:$_POST[name]\n Lun 0 Type=fileio,Path=$path\n";
                    file_put_contents($ietd_config_file, $current, FILE_APPEND | LOCK_EX);

                    require '../views/targets/add/success.html';
                } else {
                    throw new Exception("Error - The file $proc_volumes was not found");
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