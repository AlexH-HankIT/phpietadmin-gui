<?php
    class Lvmdisplay {
        // Define global vars
        var $database;

        public function __construct() {
            $this->create_models();
        }

        private function create_models() {
            // Create other need models in this model
            require_once 'Database.php';
            $this->database = new Database();
        }

        public function get_lvm_data($bin, $name = "str") {
            if ($bin == 'pvs') {
                $bin = $this->database->get_config('pvs');
                $table = array(
                    0 => "PV",
                    1 => "VG",
                    2 => "Fmt",
                    3 => "Attr",
                    4 => "PSize",
                    5 => "Pfree",
                );

                $data2['title'] = "Physical volumes";
            } elseif ($bin == 'vgs') {
                $bin = $this->database->get_config('vgs');
                $table = array(
                    0 => "VG",
                    1 => 'Graph',
                    2 => "PV",
                    3 => "LV",
                    4 => "SN",
                    5 => "Attr",
                    6 => "VSize",
                    7 => "Vfree"
                );

                $data2['title'] = "Volume groups";
            } elseif ($bin == 'lvs') {
                $bin = $this->database->get_config('lvs');
                $table = array(
                    0 => "Name",
                    1 => "VG",
                    2 => "Attr",
                    3 => "Size"
                );

                $data2['title'] = "Logical volumes";
            }

            // Read output from shell in var
            // Use specific name if supplied
            if ($name == "str") {
                $command = escapeshellcmd($this->database->get_config('sudo') . " " . $bin . " --noheadings --units g");
                $var = shell_exec($command);
            } else {
                $command = escapeshellcmd($this->database->get_config('sudo') . " " . $bin . " --noheadings --units g " . $name);
                $var = shell_exec($command);
            }

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
            $command = escapeshellcmd($this->database->get_config('sudo') . " " .  $this->database->get_config('vgs') . " --rows --noheadings");
            $vg = shell_exec($command);

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
            $command = escapeshellcmd($this->database->get_config('sudo') . " " .  $this->database->get_config('lvs') . " --noheadings --units g");
            $lv = shell_exec($command);

            $lv = explode("\n", $lv);
            $count = count($lv) - 1;

            for ($i = 0; $i < $count; $i++) {
                //$lv = shell_exec($command);
                //$lv_out = explode("\n", $lv);
                $lv_out = explode(" ", $lv[$i]);
                $lv_out = array_filter($lv_out, 'strlen');
                $lvs2[$i] = array_slice($lv_out, 0);
            }

            if (!empty($lvs2)) {
                for ($i = 0; $i < count($lvs2); $i++) {
                    $paths[$i] = "/dev/" . $lvs2[$i][1] . "/" . $lvs2[$i][0];
                }
            }

            $table = array(
                0 => "Name",
                1 => "VG",
                2 => "Attr",
                3 => "Size"
            );

            if (empty($lvs2) or empty($paths)) {
                return 3;
            } else {
                $data = array(
                    0 => $table,
                    1 => $lvs2,
                    2 => $paths,
                    'title' => "Logical volumes"
                );
                return $data;
            }
        }

        public function get_logical_volumes($vgroup) {
            $command = escapeshellcmd($this->database->get_config('sudo') . " " .  $this->database->get_config('lvs') . " --noheadings --units g " . $vgroup);
            $data = shell_exec($command);

            // If data is empty return 3
            if (empty($data)) {
                return 3;
            } else {
                // Explode string by line and create array
                $data = array_filter(explode("\n", $data));
                $counter = 0;
                foreach ($data as $value) {
                    // Loop through the array and explode by space
                    $volumes[$counter] = explode(" ", $value);
                    // Filter empty values and create array
                    $volumes[$counter] = array_values(array_filter($volumes[$counter], 'strlen'));
                    $counter++;
                }
                return $volumes;
            }



            /*$lv_out = array_filter(explode("\n", $lv));

            print_r($lv_out);

            $count = count($lv_out);
            for ($i = 0; $i < $count; $i++) {
                //$lv_out = explode("\n", $lv);
                $lv_out = explode(" ", $lv_out[$i]);
                $lv_out = array_filter($lv_out, 'strlen');
                $lvs2[$i] = array_slice($lv_out, 0);
            }*/



            /*if (!empty($lvs2)) {
                return $lvs2;
            } else {

            }*/
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
            $data2['title'] = "Logical volumes";

            return $data2;
        }

        public function get_full_path_to_volumes($data, $VG) {
            for ($i = 0; $i < count($data[1]); $i++) {
                $logicalvolumes[$i] = "/dev/" . $VG . "/" . $data[1][$i][0];
            }

            return $logicalvolumes;
        }

        public function get_used_logical_volumes($data) {
            // Get array with volumes and paths
            $volumes = file_get_contents($this->database->get_config('proc_volumes'));
            preg_match_all("/path:(.*)/", $volumes, $paths);

            if (empty($paths[1])) {
                return 2;
            } else {
                return $paths[1];
            }
        }

        public function get_unused_logical_volumes($data) {
            // Get array with volumes and paths
            $volumes = file_get_contents($this->database->get_config('proc_volumes'));
            preg_match_all("/path:(.*)/", $volumes, $paths);

            // Filter all used volumes
            $data = array_diff($data, $paths[1]);

            // Rebuild array index
            $data = array_values($data);

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

        public function check_logical_volume_exists_in_vg($NAME, $VG) {
            $data = $this->get_logical_volumes($VG);

            for ($i = 0; $i < count($data); $i++) {
                $volumes[$i] = $data[$i][0];
            }

            $return = array_search($NAME, $volumes);

            if ($return === false) {
                return true;
            }else {
                return false;
            }
        }
    }
?>