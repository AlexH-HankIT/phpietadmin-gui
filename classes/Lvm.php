<?php
    class Lvm {
        function get_lvm_data($bin, $name = 0) {
            global $a_config;

            // Read output from shell in var
            // Use specific name if supplied
            if ($name === 0) {
                $var = shell_exec("{$a_config['misc']['sudo']} $bin --noheadings --units g");
            } else {
                $var = shell_exec("{$a_config['misc']['sudo']} $bin --noheadings --units g $name");
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
                return $data;
            } else {
                return "error";
            }
        }

        function get_logical_volumes($vgroup) {
            global $a_config;
            $lv = shell_exec("{$a_config['misc']['sudo']} {$a_config['lvm']['lvs']} --noheadings --units g $vgroup");
            $lv_out = explode("\n", $lv);
            $count = count($lv_out) - 1;

            for ($i = 0; $i < $count; $i++) {
                $lv = shell_exec("{$a_config['misc']['sudo']} {$a_config['lvm']['lvs']} --noheadings --units g $vgroup");
                $lv_out = explode("\n", $lv);
                $lv_out = explode(" ", $lv_out[$i]);
                $lv_out = array_filter($lv_out, 'strlen');
                $lvs2[$i] = array_slice($lv_out, 0);
            }

            if (!empty($lvs2)) {
                return $lvs2;
            } else {
                return "error";
            }
        }

        function get_volume_groups() {
            global $a_config;

            // Read output from shell in var
            $vg = shell_exec("{$a_config['misc']['sudo']} {$a_config['lvm']['vgs']} --rows --noheadings");

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
                return "error";
            }
        }
    }

?>