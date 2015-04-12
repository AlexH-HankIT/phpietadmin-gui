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
                    return array_values($a_data2);
                } else {
                        return 3;
                }
            } else {
                return 1;
            }
        }

        public function get_initiator_permissions() {
            require_once 'Database.php';
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
            require_once 'Database.php';
            $database = new Database();
            $table = array(
                0 => "Targets",
                1 => "Allow"
            );

            $data[0] = $table;
            $data[1] = $this->get_allow($database->getConfig('ietd_target_allow'));

            return $data;
        }


        public function get_targets_without_rules($a_initiators, $a_name) {
            $a_initiators2 = $this->get_initiator_array($a_initiators);

            $a_name = array_diff($a_name, $a_initiators2);

            if (empty($a_name)) {
                return 3;
            } else {
                return array_values($a_name);
            }
        }

        public function get_initiator_array($a_initiators) {
            for ($i=0; $i < count($a_initiators); $i++) {
                $a_initiators2[$i] = $a_initiators[$i][0];
            }

            return $a_initiators2;
        }

        public function write_allow_rule($post, $array) {
            require_once 'Database.php';
            $database = new Database();

            if(!is_writable($database->getConfig('ietd_init_allow'))) {
                return 1;
            } else {
                $d = $post - 1;
                $NAME = $array[$d];
                $current = "\n$NAME $_POST[ip]\n";
                file_put_contents($database->getConfig('ietd_init_allow'), $current, FILE_APPEND | LOCK_EX);
            }
        }

        public function delete_allow_rule($a_initiators2) {
            require_once 'Std.php';
            require_once 'Database.php';

            $std = new Std;
            $database = new Database();

            if(!is_writable($database->getConfig('ietd_init_allow'))) {
                return 1;
            } else {
                $d = $_POST['IQNs2'] - 1;
                $NAME = $a_initiators2[$d];
                $std->deleteLineInFile($database->getConfig('ietd_init_allow'), "$NAME");
            }
        }
    }
?>