<?php
    class Lvmdisplay {
        public function get_lvm_data($bin, $name = 0) {
            require 'Database.php';
            $database = new Database();

            if ($bin == 'pvs') {
                $bin = $database->getConfig('pvs');
            } elseif ($bin == 'vgs') {
                $database->getConfig('vgs');
            } elseif ($bin == 'lvs') {
                $database->getConfig('lvs');
            }

            // Read output from shell in var
            // Use specific name if supplied
            if ($name === 0) {
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
                $table = array(
                    0 => "PV",
                    1 => "VG",
                    2 => "Fmt",
                    3 => "Attr",
                    4 => "PSize",
                    5 => "Pfree",
                );

                $data2[0] = $table;
                $data2[1] = $data;

                return $data2;
            } else {
                return 3;
            }
        }
    }
?>