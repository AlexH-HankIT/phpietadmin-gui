<?php
    class Lvmdisplay {
        public function get_lvm_data($bin, $name = "str") {
            require_once 'Database.php';
            $database = new Database();

            if ($bin == 'pvs') {
                $bin = $database->getConfig('pvs');
                $table = array(
                    0 => "PV",
                    1 => "VG",
                    2 => "Fmt",
                    3 => "Attr",
                    4 => "PSize",
                    5 => "Pfree",
                );

                $data2[2] = "Physical volumes";
            } elseif ($bin == 'vgs') {
                $bin = $database->getConfig('vgs');
                $table = array(
                    0 => "PV",
                    1 => "VG",
                    2 => "LV",
                    3 => "SN",
                    4 => "Attr",
                    5 => "VSize",
                    6 => "Vfree"
                );

                $data2[2] = "Volume groups";
            } elseif ($bin == 'lvs') {
                $bin = $database->getConfig('lvs');
                $table = array(
                    0 => "Name",
                    1 => "VG",
                    2 => "Attr",
                    3 => "Size"
                );

                $data2[2] = "Logical volumes";
            }

            // Read output from shell in var
            // Use specific name if supplied
            if ($name == "str") {
                $var = shell_exec($database->getConfig('sudo') . " " . $bin . " --noheadings --units g");
            } else {
                $var = shell_exec($database->getConfig('sudo') . " " . $bin . " --noheadings --units g " . $name);
            }

            $database->close();
            // Explode string by line
            $a_var = array_filter(explode("\n", $var));
            for ($i=0; $i < count($a_var); $i++) {
            // Create array for every line
                $data[$i] = explode(" ", $a_var[$i]);
            // Filter empty lines
                $data[$i] = array_filter($data[$i], 'strlen');
            // Recreate array index
                $data[$i] = array_values($data[$i]);
            }
            // Return if not empty
            if (!empty($data)) {
                $data2[0] = $table;
                $data2[1] = $data;

                return $data2;
            } else {
                return 3;
            }
        }

        public function get_volume_groups() {
            require_once 'Database.php';
            $database = new Database();

            $vg = shell_exec($database->getConfig('sudo') . " " .  $database->getConfig('vgs') . " --rows --noheadings");

            // Take only first line, since it contains the names of all groups
            $vg = strtok($vg, "\n");

            // Create array from string
            $a_vg = explode(" ", $vg);

            // Filter empty array elements and recreate index
            $a_vg = array_values(array_filter($a_vg));

            // Return if not empty
            if (!empty($a_vg)) {
                return $a_vg;
            } else {
                return 3;
            }
        }

        public function get_all_logical_volumes() {
            require_once 'Database.php';
            $database = new Database();

            $lv = shell_exec($database->getConfig('sudo') . " " .  $database->getConfig('lvs') . " --noheadings --units g ");

            $lv = explode("\n", $lv);
            $count = count($lv) - 1;

            for ($i = 0; $i < $count; $i++) {
                $lv = shell_exec($database->getConfig('sudo') . " " .  $database->getConfig('lvs') . " --noheadings --units g ");
                $lv_out = explode("\n", $lv);
                $lv_out = explode(" ", $lv_out[$i]);
                $lv_out = array_filter($lv_out, 'strlen');
                $lvs2[$i] = array_slice($lv_out, 0);
            }

            for ($i = 0; $i < count($lvs2); $i++) {
                $paths[$i] = "/dev/" . $lvs2[$i][1] . "/" . $lvs2[$i][0];
            }

            if (empty($lvs2) or empty($paths)) {
                return 3;
            } else {
                $data = array(
                    0 => $lvs2,
                    1 => $paths
                );
                return $data;
            }
        }

        public function get_logical_volumes($vgroup) {
            require_once 'Database.php';
            $database = new Database();

            $lv = shell_exec($database->getConfig('sudo') . " " .  $database->getConfig('lvs') . " --noheadings --units g " . $vgroup);

            $lv_out = explode("\n", $lv);
            $count = count($lv_out) - 1;

            for ($i = 0; $i < $count; $i++) {
                $lv = shell_exec($database->getConfig('sudo') . " " .  $database->getConfig('lvs') . " --noheadings --units g " . $vgroup);
                $lv_out = explode("\n", $lv);
                $lv_out = explode(" ", $lv_out[$i]);
                $lv_out = array_filter($lv_out, 'strlen');
                $lvs2[$i] = array_slice($lv_out, 0);
            }

            $database->close();

            if (!empty($lvs2)) {
                return $lvs2;
            } else {
                return 3;
            }
        }

        public function get_logical_volumes_with_table($vgroup) {
            $data = $this->get_logical_volumes($vgroup);
            $table = array(
                0 => "Name",
                1 => "VG",
                2 => "Attr",
                3 => "Size"
            );

            $data2[0] = $table;
            $data2[1] = $data;
            $data2[2] = "Logical volumes";

            return $data2;
        }

        public function get_data_from_drop_down($data, $NR) {
            return $data[$NR - 1];
        }

        public function get_full_path_to_volumes($data, $VG) {
            for ($i = 0; $i < count($data[1]); $i++) {
                $logicalvolumes[$i] = "/dev/" . $VG . "/" . $data[1][$i][0];
            }

            return $logicalvolumes;
        }

        public function get_unused_logical_volumes($data) {
            require_once 'Database.php';
            $database = new Database();

            // Get array with volumes and paths
            $volumes = file_get_contents($database->getConfig('proc_volumes'));
            preg_match_all("/path:(.*)/", $volumes, $paths);

            // Filter all used volumes
            $data = array_diff($data, $paths[1]);

            // Rebuild array index
            $data =  array_values($data);

            if (empty($data)) {
                return 2;
            } else {
                return $data;
            }
        }

        public function extract_free_size_from_volume_group($data) {
            // Extract free size of the volume group
            preg_match("/(.*?)(?=\.|$)/", $data[1][0][6], $freesize);
            return $freesize[1] - 1;
        }
    }
?>