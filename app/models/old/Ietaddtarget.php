<?php
    class Ietaddtarget {
        // Define global vars
        var $database;
        var $regex;
        var $models;

        /*
         * This model is a bit messy
         * ToDO: FIX THIS!
         */

        /**
         *
         * Create models
         *
         * @param   array $models needed models in this mdel
         *
         */
        public function __construct($models = '') {
            $this->models = $models;
            if (isset($models['database'], $models['regex'])) {
                $this->database = $models['database'];
                $this->regex = $models['regex'];
            }
        }

        /* --------------------------------------------------------------------------------------------------------------------------------------------

        Start // Functions to get the next available lun for a specified target

        -----------------------------------------------------------------------------------------------------------------------------------------------*/

        /**
         *
         * resursive array_search
         *
         * @return   array
         *
         * ToDo: Move this to std model
         *
         */
        private function in_array_r($needle, $haystack, $strict = false) {
            foreach ($haystack as $item) {
                if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
                    return true;
                }
            }

            return false;
        }

        /**
         *
         * Get the next usable lun for a target
         *
         * @param   string $target iqn of a target
         * @return  int
         *
         */
        public function get_next_lun($target) {
            $array_targets_with_lun = $this->get_targets_with_lun();
            // Empty or not in array means, the lun has no targets, therefore the first usable lun is 0
            if (empty($array_targets_with_lun)) {
                return 0;
            } else if (!$this->in_array_r($target, $array_targets_with_lun)) {
                return 0;
            } else {
                $array_with_name_lun = $this->parse_name_lun_from_array(array_values($array_targets_with_lun));
                return $this->get_highest_lun($array_with_name_lun, $target);
            }
        }

        /**
         *
         * Get the highest lun of a target
         *
         * @param   array $array_name_lun_correct_index
         * @return   array
         *
         *
         * ToDO: what the hell is this doing? Find out and document
         */
        private function get_highest_lun($array_name_lun_correct_index, $target) {
            foreach ($array_name_lun_correct_index as $value) {
                if (in_array($target, $value[0])) {
                    $highestlun = max($value['luns']);
                }
            }

            // Add 1 to get the next free lun
            return $highestlun + 1;
        }

        /**
         *
         * Get the highest lun of a target
         *
         * @param   array $array_targets_with_more_than_two_rows
         * @return   array
         *
         * ToDO: what the hell is this doing? Find out and document
         */
        private function parse_name_lun_from_array($array_targets_with_more_than_two_rows) {
            $counter = 0;
            foreach ($array_targets_with_more_than_two_rows as $value) {
                $array_with_name_lun[$counter][0]['name'] = $value[0]['name'];

                for ($i = 1; $i < count($value); $i++) {
                    $array_with_name_lun[$counter]['luns'][$i] = $value[$i]['lun'];
                }
                $counter++;
            }

            return $array_with_name_lun;
        }

        /* --------------------------------------------------------------------------------------------------------------------------------------------

        End // Functions to get the next available lun for a specified target

        -----------------------------------------------------------------------------------------------------------------------------------------------*/

        /**
         *
         * This function appends a target to the config file
         * Newlines and duplications are handled!
         * This function will delete all comments!
         * Don't bother with the 'Target ', it's added automatically
         *
         * @param   string $iqn iqn
         * @param   string $file file to which the iqn should be added
         * @return   int
         *
         * ToDO: Don't delete comments
         */
        public function add_iqn_to_file($iqn, $file) {
            if (!is_writeable($file)) {
                return 1;
            } else {
                // Add "Target" keyoword to the iqn
                $iqn = 'Target ' . $iqn . "\n";

                // Read file in array
                $data = file($file);

                // Delete all comments from file
                foreach ($data as $key => $value) {
                    if (strpos($value, '#') !== false) {
                        unset($data[$key]);
                    }
                }

                // Check if $option already exists
                $key = array_search($iqn, $data);

                // If $key is a integer, the option already exists
                if($key === false) {
                    // If last line is empty, replace it
                    if (end($data) == "\n") {
                        // Delete last array element
                        array_pop($data);

                        // Add data
                        array_push($data, $iqn);
                    } else {
                        // Add data
                        array_push($data, $iqn);
                    }

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
            }
        }

        /**
         *
         *  'Normal' options are added after a specific target definition
         *  This function looks for the target and adds the option one line after the match to the file
         *  Newlines are handled!
         *  No duplication checks here, because the same option can be configured for multiple targets
         *  This function will delete all comments!
         *
         * @param   string $iqn iqn to which the option should be added
         * @param   string $file file which will be changed
         * @param   string $option option to add
         * @return   int
         *
         * ToDO: Don't delete comments
         */
        public function add_option_to_iqn_in_file($iqn, $file, $option) {
            if (!is_writeable($file)) {
                return 1;
            } else {
                // Read data in array
                $data = file($file);

                // Delete all comments from file
                foreach ($data as $key => $value) {
                    if (strpos($value, '#') !== false) {
                        unset($data[$key]);
                    }
                }

                // Search for the line containing the iqn
                $key = array_search('Target ' . $iqn . "\n", $data);

                // If key is false, the iqn doesn't exist
                if($key === false) {
                    return 3;
                } else {
                    // Add the option to the array, one line after the match
                    // The other indexes will be correct automatically
                    array_splice($data, $key + 1, 0, $option . "\n");
                }

                // Create string
                $data = implode($data);

                // Delete all empty lines from string
                $data = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $data);

                // Write content back
                file_put_contents($file, $data);

                return 0;
            }
        }

        /**
         *
         *  This function adds a global option to the config file
         *  Global options are inserted before any target definitions
         *  Newlines and duplications are handled!
         *  This function will delete all comments!
         *
         * @param   string $file file which will be changed
         * @param   string $option option to add
         * @return   int
         *
         * ToDO: Don't delete comments
         */
        public function add_global_option_to_file($file, $option) {
            /*

            */

            if (!is_writeable($file)) {
                return 1;
            } else {
                // Read data in array
                $data = file($file);

                // Delete all comments from file
                foreach ($data as $key => $value) {
                    if (strpos($value, '#') !== false) {
                        unset($data[$key]);
                    }
                }

                // Check if $option already exists
                $key = array_search($option . "\n", $data);

                // If $key is a not false, the option already exists
                if($key === false) {
                    // Add option as first index, other indexes will be corrected
                    array_unshift($data, $option . "\n");

                    // Create string
                    $data = implode($data);

                    // Delete all empty lines from string
                    $data = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $data);

                    // Write data back
                    file_put_contents($file, $data);

                    return 0;
                } else {
                    return 4;
                }
            }
        }


        /* --------------------------------------------------------------------------------------------------------------------------------------------

        Start // Commonly used functions

        -----------------------------------------------------------------------------------------------------------------------------------------------*/

        /**
         *
         *  Return all targets with at least one lun
         *
         * @return   array, string
         *
         * ToDO: Return only array
         */
        public function get_targets_with_lun() {
            require_once 'IetVolumes.php';
            $ietvolumes = new IetVolumes($this->models);

            $data = $ietvolumes->parse_proc_volumes();

            if (!empty($data[1])) {
                return array_values($data[1]);
            } else {
                return $data[1] = '';
            }
        }

        /**
         *
         *  Read the ietd volumes file under proc
         *
         * @return   string, int
         *
         * ToDO: Return only array
         */
        public function get_proc_volume_content() {
            $file = $this->database->get_config('proc_volumes');
            if (file_exists($file)) {
                return file_get_contents($this->database->get_config('proc_volumes'));
            } else {
                return 2;
            }
        }

        /**
         *
         *  Get the tid for a target
         *
         * @param    string         $name  iqn of the target
         * @return   int
         *
         * ToDo: Error handling, what if $key = false?
         *
         */
        public function get_tid($name) {
            $volumes = $this->get_proc_volume_content();
            $a_name = $this->parse_all_names_from_proc_volumes($volumes);
            $a_tid = $this->parse_all_tids_from_proc_volumes($volumes);

            $key = array_search($name, $a_name);
            return $a_tid[$key];
        }

        /**
         *
         *  Get all targets from the daemon
         *
         * @return   int, array
         *
         * ToDo: Error handling, what if $data is empty?
         *
         */
        public function get_targets() {
            $volumes = $this->get_proc_volume_content();

            if (!empty($volumes)) {
                $a_name = $this->parse_all_names_from_proc_volumes($volumes);
                for ($i = 0; $i < count($a_name); $i++) {
                    $data[$i] = $a_name[$i];
                }
                return $data;
            } else {
                return 3;
            }
        }

        /**
         *
         *  Get all targets without any lun
         *
         * @return   int, array
         *
         *
         */
        public function get_targets_without_luns() {
            $alltargets = $this->get_targets();
            $withluns = $this->get_targets_with_lun();

            if (empty($withluns)) {
                return $alltargets;
            } else {

                // Get all target with lun in one array
                $counter = 0;
                foreach ($withluns as $value) {
                    $targetswithlun[$counter] = $value[0]['name'];
                    $counter++;
                }

                // Compaire these arrays, $data will contain all targets without any luns
                $data = array_diff($alltargets, $targetswithlun);


                if (empty($data)) {
                    return 3;
                } else {
                    return $data;
                }
            }
        }

        /**
         *
         *  This returns all targets without any luns or connections
         *
         *
         * @return   int, array
         *
         *

        public function get_targets_without_luns_or_connections($ietsessions) {
            $targets_without_luns = $this->get_targets_without_luns();

            // Delete not needed data
            unset($ietsessions[0]);
            unset($ietsessions['title']);

            if (is_array($ietsessions)) {
                // Extract targets with no connections and with (possibly) luns attachted
                $counter = 0;
                foreach ($ietsessions as $value) {

                    foreach ($value as $values) {
                        if (isset($values[0]['tid'])) {
                            $targets_with_connection[$counter] = $values[0]['name'];
                        }
                        $counter++;
                    }
                }
            }

            if (isset($targets_with_connection) && !empty($targets_with_connection) && is_array($targets_with_connection)) {
                $targets = array_diff($targets_without_luns, $targets_with_connection);

                if (!empty($targets)) {
                    return $targets;
                } else {
                    return 3;
                }
            } else {
                return $targets_without_luns;
            }


            /*if (is_array($targets_without_luns) && isset($targets_with_connection) && is_array($targets_with_connection)) {
                $data = array_intersect($targets_with_connection, $targets_with_luns);
                return $data;
            } else {
                if (is_array($targets_with_luns)) {
                    return $targets_with_luns;
                } else {
                    return 3;
                }
            }
        }*/

        /**
         *
         *  CHeck if a lun (=path to block device) is already added to a target
         *
         *
         * @return   int
         *
         * ToDo: Fix bad name
         *
         */
        public function check_path_already_in_use($path) {
            $volumes = $this->get_proc_volume_content();
            if (!empty($volumes)) {
                $data = $this->regex->get_all_paths_from_string($volumes);
                if ($data == 3) {
                    return 0;
                } else {
                    $key = array_search($path, $data);
                    // If $key contains a number, array_search found something, which is bad
                    if($key !== false) {
                        return 1;
                    } else {
                        return 0;
                    }
                }
            } else {
                return 0;
            }
        }

        /**
         *
         *  Check if the specified name is already in use by a target
         *
         *
         * @param   string $name name of the target
         * @return   int
         *
         *
         */
        public function check_target_name_already_in_use($name) {
            $volumes = $this->get_proc_volume_content();

            if (!empty($volumes)) {
                $a_name = $this->parse_all_names_from_proc_volumes($volumes);
                $key = array_search($name, $a_name);

                if ($a_name[$key] == $name) {
                    return 4;
                }
            } else {
                return 0;
            }
        }

        /**
         *
         *  Filters all used volumes from a given array
         *
         *
         * @param   array $logicalvolumes output[2] of function get_all_logical_volumes() from the file 'Lvmdisplay.php'
         * @return   array
         *
         */
        public function get_unused_volumes($logicalvolumes) {
            $volumes = $this->get_proc_volume_content();

            if (!empty($volumes)) {
                // Extract volumes if existing
                $paths = $this->parse_all_paths_from_proc_volumes($volumes);

                // Filter already used ones
                $logicalvolumes = array_diff($logicalvolumes, $paths);

                //Rebuild array index
                return array_values($logicalvolumes);
            } else {
                return array_values($logicalvolumes);
            }
        }

        /**
         *
         *  This function is similar to the delete_option_from_iqn function
         *  But it only returns all options from the target
         *
         *
         * @param   string $iqn name of the target
         * @param   string $file file from which the options should be fetched
         * @return   array, int
         *
         */
        public function get_all_options_from_iqn($iqn, $file) {

            if (!is_writeable($file)) {
                return 1;
            } else {
                // Read data in array
                $data = file($file);

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

                if($key !== false) {
                    // Get the index of the position of the next target definition
                    $temp = array_search($key, $keys);

                    if($key !== false) {
                        // If $keys[$temp+1], there is another target definitions after this one
                        if (isset($keys[$temp + 1])) {
                            $end = $keys[$temp + 1];
                        } else {
                            // If it's not set, the count of the array will be the last line
                            $end = count($data);
                        }


                        // If key and ned have the same value, the target definition is only one line
                        // This means there are not options to delete!
                        if ($key + 1 == $end) {
                            return 0;
                        } else {
                            // Options for $iqn are defined between $key+1 and $end-1
                            // If they are the same, the iqn has only one option
                            if (strcmp($data[$key + 1], $data[$end - 1]) == 0) {
                                if (isset($data[$key + 1])) {
                                    // return array so we can always use a loop
                                    return array(0 => explode(" ", $iqn),
                                        1 => explode(" ", $data[$key + 1]));
                                } else {
                                    return 3;
                                }
                            } else {
                                // Create array with iqn options
                                $options = array_splice($data, $key, $end - $key);
                                // Position 0 contains the iqn
                                // If there is only one option, this never gets executed
                                // Therefore we check for index 2 to be sure
                                if (!isset($options[2])) {
                                    return 3;
                                } else {
                                    foreach ($options as $key => $value) {
                                        $return[$key] = explode(" ", $value);
                                    }
                                    return $return;
                                }
                            }
                        }
                    } else {
                        return 3;
                    }
                }
            }
        }

        /* --------------------------------------------------------------------------------------------------------------------------------------------

        End // Commonly used functions

        -----------------------------------------------------------------------------------------------------------------------------------------------*/



        /* --------------------------------------------------------------------------------------------------------------------------------------------

        Start // Regex functions

        -----------------------------------------------------------------------------------------------------------------------------------------------*/

        /* ToDo: Move this functions to the regex model */

        // Uses regex to extract all target names from a given string
        private function parse_all_names_from_proc_volumes($var_volumes) {
            preg_match_all("/name:(.*)/", $var_volumes, $a_name);
            return $a_name[1];
        }

        // Uses regex to extract all tids from a given string
        private function parse_all_tids_from_proc_volumes($var_volumes) {
            preg_match_all("/tid:([0-9].*?) /", $var_volumes, $a_tid);
            return $a_tid[1];
        }

        private function parse_all_paths_from_proc_volumes($var_volumes) {
            preg_match_all("/path:(.*)/", $var_volumes, $paths);
            return $paths[1];
        }

        /* --------------------------------------------------------------------------------------------------------------------------------------------

       End // Regex functions

       -----------------------------------------------------------------------------------------------------------------------------------------------*/
    }