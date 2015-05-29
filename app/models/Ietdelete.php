<?php
    class Ietdelete {
        public function delete_global_option_from_file($option, $file) {
            /*
                This function deletes a global option from the config file
                Comments and empty lines are also deleted
            */
            if (!is_writeable($file)) {
                return 1;
            } else {
                // Read file in array
                $data = file($file);

                // Add a newline to the option
                $option = $option . "\n";

                // Look for the first target definition
                $counter=0;
                foreach ($data as $key => $value) {
                    // Check for the positions of all target definitions
                    if (substr($value, 0, 6) == 'Target') {
                        // $keys[0] will contain the first target definition
                        // Everything before $keys[0] is global
                        $keys[$counter] = $key;
                        $counter++;
                    }
                }

                // Extract the global section
                $globalsection = array_splice($data, 0, $keys[0]);

                // Search for the option which should be deleted
                $key = array_search($option, $globalsection);

                if (is_int($key)) {
                    // Delete option from array
                    unset($globalsection[$key]);

                    // Merge data and globalsection array
                    $data = array_merge($globalsection, $data);

                    // Create string from array
                    $data = implode($data);

                    // Delete all empty lines from string
                    $data = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $data);

                    // Write content back
                    file_put_contents($file, $data);

                    return 0;
                } else {
                    return 3;
                }
            }
        }

        public function delete_iqn_from_config_file($iqn, $file) {
            /*
                This function deletes a iqn from the config file
                No options of the iqn are deleted, so make sure it has none before calling this!
                This will delete all comments from the config file
            */

            if (!is_writeable($file)) {
                return 1;
            } else {
                // Add "Target" keyoword to the iqn
                $iqn = 'Target ' . $iqn . "\n";

                // Read file in array
                $data = file($file);

                // Delete all comments from file
                foreach ($data as $key => $value) {
                    if ($value[0] == '#') {
                        unset($data[$key]);
                    }
                }

                $key = array_search($iqn, $data);

                // If $key is an integer, delete that postion from the array
                if (is_int($key)) {
                    // Delete the position only, if the next line contains 'Target', otherwise the iqn has options defined
                    if (!isset($data[$key+1]) or strpos($data[$key+1],'Target') !== false) {
                        unset($data[$key]);

                        // Create string from array
                        $data = implode($data);

                        // Delete all empty lines from string
                        $data = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $data);

                        // Write content back
                        file_put_contents($file, $data);

                        return 0;
                    } else {
                        return 4;
                    }
                } else {
                    return 3;
                }
            }
        }

        public function delete_option_from_iqn($iqn, $option, $file) {
            if (!is_writeable($file)) {
                return 1;
            } else {
                // Read data in array
                $data = file($file);

                // Add a newline to the option
                $option = $option . "\n";

                // Create iqn line
                $iqn = 'Target ' . $iqn . "\n";

                // Get indexes of all target definitions
                $counter = 0;
                foreach ($data as $key => $value) {
                    if (substr($value, 0, 6) == 'Target') {
                        $keys[$counter] = $key;
                        $counter++;
                    }
                }

                // Get index of the iqn from which the option should be deleted
                $key = array_search($iqn, $data);

                if (is_int($key)) {
                    // Get the index of the position of the next target definition
                    $temp = array_search($key, $keys);

                    if (is_int($temp)) {
                        // If $keys[$temp+1], there is another target definitions after this one
                        if (isset($keys[$temp + 1])) {
                            $end = $keys[$temp + 1];
                        } else {
                            // If it's not set, the count of the array will be the last line
                            $end = count($data);
                        }

                        // Options for $iqn are defined between $key+1 and $end-1
                        // If they are the same, the iqn has only one option
                        if (strcmp($data[$key + 1], $data[$end - 1]) == 0) {
                            if (isset($data[$key + 1])) {
                                unset($data[$key + 1]);
                            } else {
                                return 3;
                            }
                        } else {
                            // Create array with iqn options
                            $options = array_splice($data, $key, $end - $key);

                            $val = array_search($option, $options);

                            if (is_int($val)) {
                                unset($options[$val]);
                                $data = array_merge($data, $options);
                            } else {
                                return 3;
                            }
                        }

                        // Create string
                        $data = implode($data);

                        // Delete all empty lines from string
                        $data = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $data);

                        // Write back
                        file_put_contents($file, $data);

                        return 0;
                    } else {
                        return 3;
                    }
                } else {
                    return 3;
                }
            }
        }
    }

?>