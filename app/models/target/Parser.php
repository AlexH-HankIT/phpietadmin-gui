<?php namespace phpietadmin\app\models\target;
    use phpietadmin\app\core;

    class Parser extends core\BaseModel {
        protected $iqn;
        protected $target_data;
        protected $tid;
        protected $ietd_config_file;

        protected function set_iqn($iqn) {
            $this->iqn = $iqn;
        }

        protected function set_target_data($target_data) {
            $this->target_data = $target_data;
        }

        /**
         * This function parses a file line by line ignoring comments and empty lines.
         * It executes a callback with the file array as parameter which contains only the actually important data.
         * You can modify this data in your callback and return it. The function will merge it with the comments
         * and empty lines leaving them exactly as before.
         *
         * Supported comment types: #
         *
         * @param string $filename
         * @param callback $callback
         * @param array $params
         * @param bool $return_after_callback If set to true this function will directly return the data from the callback without further processing
         * @param bool $test
         * @return array|int
         *
         * Basically this function can return two types:
         * int | which means there was an error or no changes at all (0 no changes/success, everything else is a error)
         * array | which contains the index 'file' and the changed elements on index 'deleted' or 'added'
         *
         */
        public function parse_file($filename, callable $callback, array $params, $return_after_callback = false, $test = false) {
            if (is_writeable($filename) === false) {
                return 1;
            }

            $file = file($filename, FILE_IGNORE_NEW_LINES);

            // loop through file
            foreach ($file as $key => $line) {
                if (!empty($line)) {
                    // check for comments
                    $offset = stripos($line, '#');
                    if ($offset !== false) {
                        // extract the whole line if it's commented
                        if ($offset === 0) {
                            $comments[$key] = $file[$key];
                            unset($file[$key]);
                        } else {
                            // extract only a part of the line
                            $comments[$key] = substr($file[$key], $offset);
                            $file[$key] = substr($line, 0, $offset);
                        }
                    }
                } else {
                    // save empty lines in comments array
                    $comments[$key] = $line;
                    unset($file[$key]);
                }
            }

            // get the lines with config and comments
            if (!empty($comments)) {
                $lines_with_comments_and_config = array_intersect_key($comments, $file);
            }

            // save array keys and create array with ongoing indexes
            $file_ongoing_index = array_values($file);
            $file_keys = array_keys($file);

            // do editing here
            if (empty($params)) {
                $params[0] = $file_ongoing_index;
            } else {
                array_push($params, $file_ongoing_index);
            }

            // call function
            if ($return_after_callback === true) {
                return call_user_func_array($callback, $params);
            } else {
                // call function and preserve indexes
                $callback_data = call_user_func_array($callback, $params);

                if (is_array($callback_data) === false) {
                    if (is_int($callback_data)) {
                        // if $callback_data is not an array, it contains a error code
                        // it could also be 0 which indicates that there weren't any changes...
                        return $callback_data;
                    }
                }

                $callback_data_count = count($callback_data['file']);
                $file_keys_count = count($file_keys);

                if ($callback_data_count < $file_keys_count) {
                    // if there are more keys than values
                    // the callback deleted something
                    // check the difference and delete the matching indexes from the $file_keys array
                    if (isset($callback_data['deleted'])) {
                        if (is_array($callback_data['deleted'])) {
                            $deleted_keys = array_keys($callback_data['deleted']);

                            // delete keys in key array
                            foreach ($deleted_keys as $key) {
                                unset($file_keys[$key]);
                            }
                        } else {
                            unset($file_keys[$callback_data['deleted']]);
                        }
                    }
                } else if ($callback_data_count > $file_keys_count) {
                    // if there are more keys than values
                    // the callback added something
                    // so we just add a index to the key array
                    // multiple keys are only hanlded in delete functions
                    // there is no function which adds multiple lines
                    $file_keys[] = max($file_keys) + 1;
                }

                $file = array_combine($file_keys, $callback_data['file']);
            }

            // add the comments to the config line
            if (!empty($lines_with_comments_and_config)) {
                foreach ($lines_with_comments_and_config as $key => $comment) {
                    $file[$key] .= $comment;
                }
            }

            // merge arrays
            if (!empty($comments)) {
                $data = $file + $comments;
            } else {
                $data = $file;
            }

            // clean up
            ksort($data);

            // create string
            $data = implode("\n", $data);

            // add last newline
            $data .= "\n";

            if ($test === false) {
                // Write back
                if (file_put_contents($filename, $data) !== false) {
                    return 0;
                } else {
                    return 6;
                }
            } else {
                echo htmlspecialchars($data);
                return 0;
            }
        }

        public function add_object_to_iqn($stringtoadd, $file) {
            if (!is_writeable($file)) {
                return 1;
            } else {
                // Read data in array
                $data = file($file);

                if (!$this->std->array_find($this->iqn, $data)) {
                    if (end($data) == "\n") {
                        // Last element is a newline, delete it and add rule
                        array_pop($data);
                        array_push($data, $this->iqn . " " . $stringtoadd . "\n");
                    } else {
                        array_push($data, $this->iqn . " " . $stringtoadd . "\n");
                    }
                } else {
                    foreach ($data as $key => $value) {
                        if (strpos($value, '#') === false) {
                            // If iqn is there, we have to add a object to it
                            if (strpos($value, $this->iqn) !== false) {
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

        /**
         *
         *  'Normal' options are added after a specific target definition
         *  This function looks for the target and adds the option one line after the match to the file
         *  Newlines are handled!
         *  No duplication checks here, because the same option can be configured for multiple targets
         *  This function will delete all comments!
         *
         * @param   string $option option to add
         * @return   int
         *
         * ToDO: Don't delete comments
         */
        protected function add_option_to_iqn_in_file($option) {
            if (!is_writeable($this->ietd_config_file)) {
                return 1;
            } else {
                // Read data in array
                $data = file($this->ietd_config_file);

                // Delete all comments from file
                foreach ($data as $key => $value) {
                    if (strpos($value, '#') !== false) {
                        unset($data[$key]);
                    }
                }

                // Search for the line containing the iqn
                $key = array_search('Target ' . $this->iqn . "\n", $data);

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
                file_put_contents($this->ietd_config_file, $data);

                return 0;
            }
        }

        /**
         *
         *  This function deletes a specific option from a iqn
         *  This will delete all comments from the config file
         *
         *
         * @param   string $option option to delete
         * @return  int
         *
         * ToDo: Don't remove comments
         */
        public function delete_option_from_iqn($option) {
            if (!is_writeable($this->ietd_config_file)) {
                return 1;
            } else {
                // Read data in array
                $data = file($this->ietd_config_file);

                // Add a newline to the option
                $option = $option . "\n";

                // Create iqn line
                $iqn = 'Target ' . $this->iqn . "\n";

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
                if ($key !== false) {
                    // Get the index of the position of the next target definition
                    $temp = array_search($key, $keys);
                    if ($temp !== false) {
                        //if (is_int($temp)) {
                        // If $keys[$temp+1], there is another target definitions after this one
                        if (isset($keys[$temp + 1])) {
                            $end = $keys[$temp + 1];
                        } else {
                            // If it's not set, the count of the array will be the last line
                            $end = count($data);
                        }
                        // Options for $iqn are defined between $key+1 and $end-1
                        // Create array with iqn options
                        $options = array_splice($data, $key, $end - $key);

                        $val = array_search($option, $options);
                        if ($val !== false) {
                            unset($options[$val]);
                            $data = array_merge($data, $options);
                        } else {
                            return 3;
                        }

                        // Create string
                        $data = implode($data);

                        // Delete all empty lines from string
                        $data = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $data);

                        // Write back
                        file_put_contents($this->ietd_config_file, $data);
                        return 0;
                    } else {
                        return 3;
                    }
                } else {
                    return 3;
                }
            }
        }

        /**
         *
         *  This function deletes a iqn from the config file
         *  No options of the iqn are deleted, so make sure it has none before calling this!
         *  This will delete all comments from the config file
         *
         * @return  int
         *
         * ToDo: Don't remove comments
         */
        public function delete_iqn_from_config_file() {
            if (!is_writeable($this->ietd_config_file)) {
                return 1;
            } else {
                // Add "Target" keyoword to the iqn
                $iqn = 'Target ' . $this->iqn . "\n";
                // Read file in array
                $data = file($this->ietd_config_file);
                // Delete all comments from file
                foreach ($data as $key => $value) {
                    if (strpos($value, '#') !== false) {
                        unset($data[$key]);
                    }
                }
                $key = array_search($iqn, $data);
                // If $key is an integer, delete that postion from the array
                if($key !== false) {
                    // Delete the position only, if the next line contains 'Target', otherwise the iqn has options defined
                    if (!isset($data[$key + 1]) or strpos($data[$key + 1], 'Target') !== false) {
                        unset($data[$key]);
                        // Create string from array
                        $data = implode($data);
                        // Delete all empty lines from string
                        $data = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $data);
                        // Write content back
                        file_put_contents($this->ietd_config_file, $data);
                        return 0;
                    } else {
                        return 4;
                    }
                } else {
                    return 3;
                }
            }
        }

        /**
         *
         *  This function is similar to the delete_option_from_iqn function
         *  But it only returns all options from the target
         *
         * @return   array|int
         *
         */
        public function get_all_options_from_iqn() {
            if (!is_writeable($this->ietd_config_file)) {
                return 1;
            } else {
                // Read data in array
                $data = file($this->ietd_config_file);

                // Create iqn line
                $iqn = 'Target ' . $this->iqn . "\n";

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
                                    return array(0 => explode(' ', trim($iqn, "\n")),
                                        1 => explode(" ", trim($data[$key + 1], "\n")));
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
                                        $return[$key] = explode(' ', trim($value, "\n"));
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

        /**
         *
         * Parse all information from the ietd proc volume file
         *
         * @param   array $arg optional, array should have the index 'tid' or 'iqn' set, if this is the case
         * this function will only return the identified data
         *
         * @return   array, boolean
         *
         * If data was found, return array, else return false
         *
         */
        protected function parse_target(array $arg = []) {
            $files = array(
                'volume' => $this->database->get_config('proc_volumes')['value'],
                'session' => $this->database->get_config('proc_sessions')['value']
            );

            foreach ($files as $fileindex => $file) {
                // read the file and get iqns
                if (file_exists($file)) {
                    // Read file into array
                    $data[$fileindex] = file($file);

                    if (!empty($data[$fileindex])) {
                        // get last array position
                        end($data[$fileindex]);
                        $last_array_position = key($data[$fileindex]);

                        // If the first line start with 't', it's a target line
                        // Parse the name and the tid
                        foreach ($data[$fileindex] as $key => $value) {
                            if ($value[0] == 't') {
                                preg_match("/tid:([0-9].*?) /", $value, $r_tid);
                                preg_match("/name:(.*)/", $value, $r_iqn);
                                $target_definitions[$fileindex][$key]['index'] = $key;
                                $target_definitions[$fileindex][$key]['tid'] = $r_tid[1];
                                $target_definitions[$fileindex][$key]['iqn'] = $r_iqn[1];
                            }
                        }

                        // Correct the array index
                        $target_definitions[$fileindex] = array_values($target_definitions[$fileindex]);

                        foreach ($target_definitions[$fileindex] as $key => $target_definition) {
                            if (isset($target_definitions[$fileindex][$key + 1]['index'])) {
                                $target_definitions[$fileindex][$key]['count_options'] = $target_definitions[$fileindex][$key + 1]['index'] - ($target_definition['index'] + 1);
                            } else if ($last_array_position == $target_definitions[$fileindex][$key]['index']) {
                                $target_definitions[$fileindex][$key]['count_options'] = 0;
                            } else {
                                $target_definitions[$fileindex][$key]['count_options'] = $last_array_position - $target_definitions[$fileindex][$key]['index'];
                            }
                        }
                    }
                }
            }

            // parse all information from ietd volume file
            if (isset($target_definitions['volume']) && !empty($target_definitions['volume'])) {
                foreach ($target_definitions['volume'] as $key => $target_definition) {
                    if ($target_definition['count_options'] != 0) {
                        for ($i=$target_definition['index']+1; $target_definition['count_options'] + $target_definition['index']>= $i; $i++) {
                            preg_match("/lun:([0-9])/", $data['volume'][$i], $id);
                            preg_match("/state:([0-9])/", $data['volume'][$i], $state);
                            preg_match("/iotype:(.*?) /", $data['volume'][$i], $iotype);
                            preg_match("/iomode:(.*?) /", $data['volume'][$i], $iomode);
                            preg_match("/blocks:(.*?) /", $data['volume'][$i], $blocks);
                            preg_match("/blocksize:(.*?) /", $data['volume'][$i], $blocksize);
                            preg_match("/path:(.*)/", $data['volume'][$i], $path);

                            $target_definitions['volume'][$key]['lun'][$i] = array(
                                'id' => $id[1],
                                'state' => $state[1],
                                'iotype' => $iotype[1],
                                'iomode' => $iomode[1],
                                'blocks' => $blocks[1],
                                'blocksize' => $blocksize[1],
                                'path' => $path[1]
                            );
                        }
                        $target_definitions['volume'][$key]['lun'] = array_values($target_definitions['volume'][$key]['lun']);
                    }
                }
            }

            // parse all information from ietd session file
            if (isset($target_definitions['session']) && !empty($target_definitions['session'])) {
                foreach ($target_definitions['session'] as $key => $target_definition) {
                    if ($target_definition['count_options'] != 0) {
                        for ($i=$target_definition['index']+1; $target_definition['count_options'] + $target_definition['index']>= $i; $i=$i+2) {
                            preg_match("/sid:(.*?) /", $data['session'][$i], $sid);
                            preg_match("/initiator:(.*)/", $data['session'][$i], $initiator);
                            preg_match("/cid:([0-9].*?) /", $data['session'][$i+1], $cid);
                            preg_match("/ip:(.*?) /", $data['session'][$i+1], $ip);
                            preg_match("/state:(.*?) /", $data['session'][$i+1], $state);
                            preg_match("/hd:(.*?) /", $data['session'][$i+1], $hd);
                            preg_match("/dd:(.*)/", $data['session'][$i+1], $dd);

                            $target_definitions['volume'][$key]['session'][$i] = array(
                                'sid' => $sid[1],
                                'initiator' => $initiator[1],
                                'cid' => $cid[1],
                                'ip' => $ip[1],
                                'state' => $state[1],
                                'hd' => $hd[1],
                                'dd' => $dd[1]
                            );
                        }
                        $target_definitions['volume'][$key]['session'] = array_values($target_definitions['volume'][$key]['session']);
                    }
                }
            }

            // return data
            if (!isset($target_definitions)) {
                return false;
            } else {
                if (isset($arg['tid']) && !empty($arg['tid'])) {
                    foreach ($target_definitions['volume'] as $key => $target_definition) {
                        if ($target_definition['tid'] == $arg['tid']) {
                            $target = $target_definitions['volume'][$key];
                        }
                    }

                    if (empty($target)) {
                        return false;
                    } else {
                        return $target;
                    }
                } else if (isset($arg['iqn']) && !empty($arg['iqn'])) {
                    foreach ($target_definitions['volume'] as $key => $target_definition) {
                        if ($target_definition['iqn'] == $arg['iqn']) {
                            $target = $target_definitions['volume'][$key];
                        }
                    }

                    if (empty($target)) {
                        return false;
                    } else {
                        return $target;
                    }
                } else {
                    return $target_definitions['volume'];
                }
            }
        }

        /**
         *
         * Parse all information from the iet allow files
         * return false if nothing was found or an array with the index 'initiators' and 'targets' for the data
         *
         * @param   $iqn string optional, only return acl for this iqn
         * @return   array, boolean
         *
         */
        protected function parse_target_acl($iqn = '') {
            $files = array(
                'initiators' => $this->database->get_config('ietd_init_allow')['value'],
                'targets' => $this->database->get_config('ietd_target_allow')['value']
            );

            foreach ($files as $fileindex => $file) {
                $data[$fileindex] = file($file);

                if (!empty($data[$fileindex])) {
                    foreach ($data[$fileindex] as $key => $line) {
                        $temp = trim($line, ' ');
                        if ($temp[0] != '#' && !empty($line) && $line != "\n") {
                            $acls[$fileindex][$key] = explode(',', $line);
                        }
                    }

                    if (isset($acls[$fileindex]) && !empty($acls[$fileindex])) {
                        $acls[$fileindex] = array_values($acls[$fileindex]);

                        foreach ($acls[$fileindex] as $key => $acl) {
                            $acl[0] = trim(trim($acl[0], ' '), "\n");

                            $values[$fileindex][$key] = explode(' ', $acl[0]);
                            unset($acl[0]);

                            foreach ($acl as $rule) {
                                array_push($values[$fileindex][$key], trim(trim($rule, ' '), "\n"));
                            }
                        }
                    }
                }
            }

            if (!empty($values)) {
                if (!empty($iqn)) {
                    if (isset($values['initiators'])) {
                        foreach ($values['initiators'] as $value) {
                            if ($value[0] == $iqn) {
                                $return['initiators'] = $value;
                            }
                        }
                    }

                    if (isset($values['targets'])) {
                        foreach ($values['targets'] as $value) {
                            if ($value[0] == $iqn) {
                                $return['targets'] = $value;
                            }
                        }
                    }

                    if (!empty($return)) {
                        return $return;
                    } else {
                        return false;
                    }
                } else {
                    return $values;
                }
            } else {
                return false;
            }
        }

        /**
         *
         * This function appends a target to the config file
         * Newlines and duplications are handled!
         * This function will delete all comments!
         * Don't bother with the 'Target ', it's added automatically
         *
         * @return   int
         *
         * ToDO: Don't delete comments
         */
        public function add_iqn_to_file() {
            if (!is_writeable($this->ietd_config_file)) {
                return 1;
            } else {
                // Add "Target" keyoword to the iqn
                $iqn = 'Target ' . $this->iqn . "\n";

                // Read file in array
                $data = file($this->ietd_config_file);

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
                    file_put_contents($this->ietd_config_file, $data);

                    return 0;
                } else {
                    return 4;
                }
            }
        }

        public function delete_object_from_iqn($stringtodelete, $file) {
            if (!is_writeable($file)) {
                return 1;
            } else {
                // Read data in array
                $data = file($file);

                foreach ($data as $key => $value) {
                    if (strpos($value, '#') === false) {
                        if (strpos($value, $this->iqn) !== false) {
                            $strtodeletepo = strpos($value, $stringtodelete);
                            if ($strtodeletepo !== false) {
                                $strtodeletelen = strlen($stringtodelete);

                                // If the $stringtodelete isn't the last, we have to delete a space and a comma after the string ended
                                if ($value[$strtodeletepo + $strtodeletelen] == ',') {
                                    $temp = substr_replace($value, '', $strtodeletepo, $strtodeletelen + 1);

                                    // check if there is really a space
                                    // some people don't do that
                                    if ($value[$strtodeletepo + $strtodeletelen +1] == ' ') {
                                        $temp = substr_replace($value, '', $strtodeletepo, $strtodeletelen + 2);
                                    }

                                    // write back into array
                                    $data[$key] = $temp;
                                } else {
                                    // If the string is the last, we have to remove the previous space and comma
                                    $temp = substr_replace($value, '', $strtodeletepo - 2, $strtodeletelen + 2);
                                    $data[$key] = $temp;
                                }

                                // If iqn has the same length than value, there is only the iqn in this line
                                // therefore we just delete it
                                if (strlen($this->iqn) == strlen($temp)) {
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

        /**
         *
         *  This function deletes a iqn from (+ all acls) from one ietd allow file
         *  This will delete all comments from the config file
         *
         *
         * @param   string $file file from which the the iqn should be deleted
         * @return  int
         *
         * ToDo: Don't remove comments
         */
        public function delete_iqn_from_allow_file($file) {
            if (!is_writeable($file)) {
                return 1;
            } else {
                // Read data in array
                $data = file($file);

                if ($this->std->array_find($this->iqn, $data)) {
                    foreach ($data as $key => $value) {
                        if (strpos($value, '#') !== true) {
                            if (strpos($value, $this->iqn) !== false) {
                                // Unset line containing iqn
                                unset($data[$key]);
                            }
                        }
                    }
                    // Create string from array
                    $data = implode($data);
                    // Delete all empty lines from string
                    $data = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $data);
                    // Write content back
                    file_put_contents($file, $data);
                }
                return 0;
            }
        }

        /**
         *
         *  This function is similar to the delete_option_from_iqn function,
         *  But it deletes all options from the target (target with luns cannot be deleted)
         *  This functions makes sure, that no config pieces are left over, when a target is deleted
         *
         * @return  int
         *
         */
        public function delete_all_options_from_iqn() {
            if (!is_writeable($this->ietd_config_file)) {
                return 1;
            } else {
                // Read data in array
                $data = file($this->ietd_config_file);
                // Create iqn line
                $iqn = 'Target ' . $this->iqn . "\n";
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
                        // If key and end have the same value, the target definition is only one line
                        // This means there are no options to delete!
                        if ($key + 1 == $end) {
                            return 0;
                        } else {
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
                                // Position 0 contains the iqn
                                // If there is only one option, this never gets executed
                                // Therefore we check for index 2 to be sure
                                if (!isset($options[2])) {
                                    return 3;
                                } else {
                                    $count = count($options);
                                    for ($i = 1; $i < $count; $i++) {
                                        unset($options[$i]);
                                    }
                                    $data = array_merge($data, $options);
                                }
                            }
                            // Create string
                            $data = implode($data);
                            // Delete all empty lines from string
                            $data = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $data);
                            // Write back
                            file_put_contents($this->ietd_config_file, $data);
                            return 0;
                        }
                    } else {
                        return 3;
                    }
                }
            }
        }

        /**
         *
         *  This function deletes a global option from the config file
         *  Comments and empty lines are also deleted
         *
         *
         * @param   string $option option to delete
         * @return  int
         *
         * ToDo: Don't remove comments
         * ToDo: Only parse until the first target definition
         */
        public function delete_global_option_from_file($option) {
            if (!is_writeable($this->ietd_config_file)) {
                return 1;
            } else {
                // Read file in array
                $data = file($this->ietd_config_file);
                // Add a newline to the option
                $option = $option . "\n";
                // Look for the first target definition
                $counter = 0;
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
                if($key !== false) {
                    // Delete option from array
                    unset($globalsection[$key]);
                    // Merge data and globalsection array
                    $data = array_merge($globalsection, $data);
                    // Create string from array
                    $data = implode($data);
                    // Delete all empty lines from string
                    $data = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "\n", $data);
                    // Write content back
                    file_put_contents($this->ietd_config_file, $data);
                    return 0;
                } else {
                    return 3;
                }
            }
        }

        /**
         *
         *  This function adds a global option to the config file
         *  Global options are inserted before any target definitions
         *  Newlines and duplications are handled!
         *  This function will delete all comments!
         *
         * @param   string $option option to add
         * @return   int
         *
         * ToDo: Don't delete comments
         * ToDo: Only parse until the first target definition
         */
        public function add_global_option_to_file($option) {
            if (!is_writeable($this->ietd_config_file)) {
                return 1;
            } else {
                // Read data in array
                $data = file($this->ietd_config_file);

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
                    file_put_contents($this->ietd_config_file, $data);

                    return 0;
                } else {
                    return 4;
                }
            }
        }
    }