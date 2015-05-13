<?php
    class Ietpermissions {
        // Define global vars
        var $database;
        var $std;

        public function __construct() {
            $this->create_models();
        }

        private function create_models() {
            // Create other need models in this model
            require_once 'Database.php';
            require_once 'Std.php';
            $this->database = new Database();
            $this->std = new Std();
        }

        function get_iet_allow($iqn, $allowfile) {
            $data = file_get_contents($allowfile);

            if (empty($data)) {
                return 3;
            } else {

                $data = trim(preg_replace('/\s\s+/', ' ', $data));

                $data = explode("\n", $data);

                foreach ($data as $key => $value) {
                    $data2[$key] = explode(",", $value);
                }

                unset($data);

                $counter = 0;
                foreach ($data2 as $value) {
                    foreach ($value as $key => $value2) {
                        if ($key === 0) {
                            $teil[$counter][$key] = explode(' ', $value2);
                        } else {
                            $teil[$counter][$key] = $value2;
                        }
                    }
                    $counter++;
                }

                $counter = 0;
                foreach ($teil as $value) {
                    $data[$counter]['iqn'] = $value[0][0];
                    $data[$counter][0] = $value[0][1];

                    for ($i = 1; count($value) > $i; $i++) {
                        $data[$counter][$i] = str_replace(' ', '', $value[$i]);
                    }
                    $counter++;
                }

                foreach ($data as $key => $value) {
                    if (strcmp($value['iqn'], $iqn) === 0) {
                        return $data[$key];
                    }
                }

                return 3;
            }
        }


        public function get_allow($file) {
            if (file_exists($file)) {
                // Read data in var
                $data = file($file);

                if (empty($data)) {
                    return 2;
                }

                // Filter empty elements
                $a_data = array_values(array_filter($data));

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
            $table = array(
                0 => "Initiator",
                1 => "Allow"
            );

            $data[0] = $table;
            $data[1] = $this->get_allow($this->database->get_config('ietd_init_allow'));
            $data['title'] = "Initiator permission";

            return $data;
        }

        public function get_target_permissions() {
            $table = array(
                0 => "Targets",
                1 => "Allow"
            );

            $data[0] = $table;
            $data[1] = $this->get_allow($this->database->get_config('ietd_target_allow'));
            $data['title'] = "Target permission";

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
            if(!is_writable($this->database->get_config('ietd_init_allow'))) {
                return 1;
            } else {
                $d = $post - 1;
                $NAME = $array[$d];
                $current = "\n$NAME $_POST[ip]\n";
                file_put_contents($this->database->get_config('ietd_init_allow'), $current, FILE_APPEND | LOCK_EX);
            }
        }

        public function delete_allow_rule($a_initiators2) {
            if(!is_writable($this->database->get_config('ietd_init_allow'))) {
                return 1;
            } else {
                $d = $_POST['IQNs2'] - 1;
                $NAME = $a_initiators2[$d];
                $this->std->deleteLineInFile($this->database->get_config('ietd_init_allow'), "$NAME");
            }
        }

        public function get_volume_names($data) {
            preg_match_all("/name:(.*)/", $data, $a_name);
            return $a_name[1];
        }
    }
?>