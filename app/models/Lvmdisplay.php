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
            } elseif ($bin == 'lvs') {
                $bin = $database->getConfig('lvs');
                $table = array(
                    0 => "Name",
                    1 => "VG",
                    2 => "Attr",
                    3 => "Size"
                );
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
    }
?>