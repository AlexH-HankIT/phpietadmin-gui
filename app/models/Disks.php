<?php
    class Disks {
        // Define global vars
        var $database;

        /**
         *
         * Create models
         *
         *
         */
        public function __construct($models = '') {
            if (isset($models['database'])) {
                $this->database = $models['database'];
            }
        }

        /**
         *
         * Close database connection
         *
         * @param   array $var_lsblk_output output of $this.>exec_lsblk()
         * @return   array
         *
         * ToDO: Error Handling!
         */
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


        /**
         *
         * Create table for view
         *
         * @return   array
         *
         * ToDO: Error Handling!
         */
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

        /**
         *
         * Get output of lsblk binary
         *
         * @return   string
         *
         * ToDO: Move this to the Exec model
         */
        private function exec_lsblk() {
            // We use shell exec, since we don't care about the return value
            $command = escapeshellcmd($this->database->get_config('sudo') . " " . $this->database->get_config('lsblk') . " -rn");
            return shell_exec($command);
        }

        /**
         *
         * Parse lsblk output and create readable array
         *
         * @return   array
         *
         */
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