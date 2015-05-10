<?php
    class Disks {
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

        private function parse_lsblk_output($var_lsblk_output) {
            // Seperate output by lines
            $array_lsblk_output = explode("\n", $var_lsblk_output);

            // Filter empty lines
            $array_lsblk_output = array_filter($array_lsblk_output, 'strlen');

            $counter = 0;
            foreach ($array_lsblk_output as $value) {
                if (strpos($value, "dm") === false) {
                    $array_lsblk_output_sperated_by_space[$counter] = explode(" ", $value);
                }
                $counter++;
            }

            return $array_lsblk_output_sperated_by_space;
        }

        // Create table array for view
        private function create_table() {
            return $table = array(
                0 => "Name",
                1 => "MAJ:MIN",
                2 => "RM",
                3 => "Size",
                4 => "RO",
                5 => "Type",
                6 => "Mountpoint"
            );
        }

        private function exec_lsblk() {

            // We use shell exec, since we don't care about the return value
            return shell_exec($this->database->get_config('sudo') . " " . $this->database->get_config('lsblk') . " -rn");
        }

        public function get_disks() {
            // Get lsblk output
            $var_lsblk_output = $this->exec_lsblk();

            // Return 2 if no block devices exist (unlikely)
            if (empty($var_lsblk_output)) {
                return 2;
            } else {
                // Get readable lsblk output
                $array_lsblk_output_sperated_by_space = $this->parse_lsblk_output($var_lsblk_output);

                // Get table
                $table = $this->create_table();

                // Create array to return
                $disks[0] = $table;
                $disks[1] = $array_lsblk_output_sperated_by_space;
                $disks['title'] = "Disks";

                return $disks;
            }
        }
    }
?>