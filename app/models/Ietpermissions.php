<?php
    class Ietpermissions {
        public function get_allow($file) {
            if (file_exists($file)) {
                // Read data in var
                $data = file_get_contents("$file");

                if (empty($data)) {
                    return 2;
                }

                // Create array
                $a_data = explode("\n", $data);

                // Filter empty elements
                $a_data = array_values(array_filter($a_data));

                // Delete all lines containing '#' and seperate remaining data by space
                for ($i = 0; $i < count($a_data); $i++) {
                    if (strpos($a_data[$i], '#') === false) {
                        # Ignore empty lines
                        if (strpos($a_data[$i], " ") !== false) {
                            $a_data2[$i] = explode(" ", $a_data[$i]);
                        }
                    }
                }

                if (!empty($a_data2)) {
                    return $a_data2;
                } else {
                        return 3;
                    }
            } else {
                return 1;
            }
        }

        public function get_initiator_permissions() {
            require 'Database.php';
            $database = new Database();
            $table = array(
                0 => "Initiator",
                1 => "Allow"
            );

            $data[0] = $table;
            $data[1] = $this->get_allow($database->getConfig('ietd_init_allow'));

            return $data;
        }

        public function get_target_permissions() {
            require 'Database.php';
            $database = new Database();
            $table = array(
                0 => "Targets",
                1 => "Allow"
            );

            $data[0] = $table;
            $data[1] = $this->get_allow($database->getConfig('ietd_target_allow'));

            return $data;
        }
    }
?>