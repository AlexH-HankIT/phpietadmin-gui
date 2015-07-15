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

        public function get_iet_allow($allowfile, $iqn = '') {
            $data = file_get_contents($allowfile);

            if (empty($data)) {
                return 3;
            } else {
                $data = explode("\n", $data);

                foreach ($data as $key => $value) {
                    for ($i=0; $i < count($value); $i++) {
                        if (empty($value[$i]) or (strpos($value[$i][0],'#') !== false)) {
                            unset($data[$key]);
                        }
                    }
                }

                if (empty($data)) {
                    return 3;
                } else {
                    foreach ($data as $key => $value) {
                        $data2[$key] = explode(",", $value);
                    }

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
                        $data3[$counter]['iqn'] = $value[0][0];
                        $data3[$counter][0] = $value[0][1];

                        for ($i = 1; count($value) > $i; $i++) {
                            $data3[$counter][$i] = str_replace(' ', '', $value[$i]);
                        }
                        $counter++;
                    }

                    if (empty($data3)) {
                        return 3;
                    } else {
                        if (!empty($iqn)) {
                            foreach ($data3 as $key => $value) {
                                if (strcmp($value['iqn'], $iqn) === 0) {
                                    $return[0] = $data3[$key];
                                }
                            }
                        }

                        $return[1] = $data3;
                        return $return;
                    }
                }
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

        public function get_volume_names($data) {
            preg_match_all("/name:(.*)/", $data, $a_name);
            return $a_name[1];
        }

        public function delete_object_from_iqn($iqn, $stringtodelete, $file) {
            if (!is_writeable($file)) {
                return 1;
            } else {
                // Read data in array
                $data = file($file);

                foreach ($data as $key => $value) {
                    if (strpos($value, '#') !== false) {
                        true;
                    } else {
                        if (strpos($value, $iqn) !== false) {
                            $strtodeletepo = strpos($value, $stringtodelete);
                            if ($strtodeletepo !== false) {
                                $strtodeletelen = strlen($stringtodelete);

                                // If the $stringtodelete isn't the last, we have to delete a space and a comma after the string ended
                                if ($value[$strtodeletepo + $strtodeletelen] == ',') {
                                    $temp = substr_replace($value, '', $strtodeletepo, $strtodeletelen + 2);
                                    $data[$key] = $temp;
                                } else {
                                    // If the string is the last, we have to remove the previous space and comma
                                    $temp = substr_replace($value, '', $strtodeletepo - 2, $strtodeletelen + 2);
                                    $data[$key] = $temp;
                                }

                                // If iqn has the same length than value, there is only the iqn in this line
                                // therefore we just delete it
                                if (strlen($iqn) == strlen($temp)) {
                                    unset($data[$key]);
                                }
                            }
                        }
                    }
                }

                // Insert a newline at the end to prevent some issues
                if (end($data) !== "\n") {
                    array_push($data, "\n");
                }

                // Create string
                $data = implode($data);

                // Delete all empty lines from string
                $data = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $data);

                // Write back
                file_put_contents($file, $data);

                return 0;
            }
        }

        public function add_object_to_iqn($iqn, $stringtoadd, $file) {
            if (!is_writeable($file)) {
                return 1;
            } else {
                // Read data in array
                $data = file($file);

                if (!$this->std->array_find($iqn, $data)) {
                    if (end($data) == "\n") {
                        // Last element is a newline, delete it and add rule
                        array_pop($data);
                        array_push($data, $iqn . " " . $stringtoadd . "\n");
                    } else {
                        array_push($data, $iqn . " " . $stringtoadd . "\n");
                    }
                } else {
                    foreach ($data as $key => $value) {
                        if (strpos($value, '#') !== false) {
                            true;
                        } else {
                            // If iqn is there, we have to add a object to it
                            if (strpos($value, $iqn) !== false) {
                                $temp = trim(preg_replace('/\s\s+/', ' ', $value));
                                $temp .= ", " . $stringtoadd . "\n";
                                $data[$key] = $temp;
                            }
                        }
                    }
                }
            }

            // Create string
            $data = implode($data);

            // Delete all empty lines from string
            $data = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $data);

            // Write back
            file_put_contents($file, $data);

            return 0;
        }
    }
?>