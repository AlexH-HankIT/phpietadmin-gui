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

        /**
         * This function adds a global option to the config file
         * Global options are inserted before any target definitions
         * If the option is already added a error is returned
         *
         * @param string $option
         * @param array $file
         * @return array|bool
         *
         */
        private function add_global_option_to_file($option, array $file) {
            $option_index = array_search($option, $file);

            if ($option_index === false) {
                array_unshift($file, $option);
                $return['file'] = $file;
                return $return;
            } else {
                // option is already added
                return 4;
            }
        }

        /**
         * This functions adds a target definition to the config file
         * Duplications are handled
         *
         * @param array $file
         * @return array|int
         */
        private function add_iqn_to_file(array $file) {
            $iqn = 'Target ' . $this->iqn;

            // Check if $iqn is already added
            $key = array_search($iqn, $file);

            if ($key === false) {
                $file[] = $iqn;
                $return['file'] = $file;
                return $return;
            } else {
                return 4;
            }
        }

        /**
         * This function adds a entry to the ietd allow files
         * $file needs to be the last parameter!
         *
         * @param string $object
         * @param array $file
         * @return array
         */
        private function add_object_to_iqn($object, array $file) {
            $iqn_index = $this->std->array_find_iqn($this->iqn, $file);

            if ($iqn_index !== false) {
                if (end($file) === "\n") {
                    // if last element is a newline, delete it
                    array_pop($file);
                }

                $file[$iqn_index] .= ', ' . $object;
            } else {
                $file[] = $this->iqn . ' ' . $object;
            }

            $return['file'] = $file;
            return $return;
        }

        /**
         * This function adds a option to a target definition
         * $file needs to be the last parameter!
         * No duplication checks here, because the same option can be configured for multiple targets
         *
         * @param string $option
         * @param array $file
         * @return int|bool
         */
        private function add_option_to_iqn($option, array $file) {
            $iqn = 'Target ' . $this->iqn;

            // Search for the line containing the iqn
            $key = array_search($iqn, $file);

            // If key is false, the iqn doesn't exist
            if($key !== false) {
                // Add the option to the array, one line after the match
                // The other indexes will be correct automatically
                $file[$key] .= "\n" . $option;
                $return['file'] = $file;
                return $return;
            } else {
                return 3;
            }
        }

        /**
         * This function deletes all options from a iqn
         * This will ensure, that no config pieces are left before a target is deleted
         *
         * @param array $file
         * @return int
         */
        private function delete_all_options_from_iqn(array $file) {
            $iqn = 'Target ' . $this->iqn;

            // Get indexes of all target definitions
            foreach ($file as $key => $line) {
                // delete all whitespaces and check if the first six letters spell target
                if (substr(preg_replace('/\s+/', '', $line), 0, 6) === 'Target') {
                    $keys[] = $key;
                }
            }

            if (isset($keys) && is_array($keys)) {
                // Get index of the iqn from which the option should be deleted
                $iqn_index = array_search($iqn, $file);

                if ($iqn_index !== false) {
                    // get key of this iqn
                    $this_target_definition_key_index = array_search($iqn_index, $keys);

                    if ($this_target_definition_key_index !== false) {
                        if ($this_target_definition_key_index === count($keys) - 1) {
                            // target is last one

                            // get count of $file (-1 to match array indexes)
                            $file_count = count($file) - 1;
                            $options_count = $file_count - $keys[$this_target_definition_key_index];

                            if ($options_count !== 0) {
                                $this_target_definition_first_option = $keys[$this_target_definition_key_index] + 1;

                                // since this is the last target, we can delete everything from here
                                // save the values which will be deleted in array
                                $return['deleted'] = array_slice($file, $this_target_definition_first_option, NULL, true);

                                // delete options
                                array_splice($file, $this_target_definition_first_option);
                            } else {
                                return 0;
                            }
                        } else {
                            // target is not last one
                            $this_target_definition_options_end = $keys[$this_target_definition_key_index + 1];
                            $this_target_definition_options_start = $iqn_index + 1;
                            $this_target_definition_count = $this_target_definition_options_end - $this_target_definition_options_start;

                            if ($this_target_definition_count > 0) {
                                // since this is the last target, we can delete everything from here
                                // save the values which will be deleted in array
                                $return['deleted'] = array_slice($file, $this_target_definition_options_start, $this_target_definition_count, true);
                                array_splice($file, $this_target_definition_options_start, $this_target_definition_count);
                            } else {
                                return 0;
                            }
                        }
                    }
                    $return['file'] = $file;
                    return $return;
                } else {
                    return 3;
                }
            } else {
                return 3;
            }
        }

        /**
         * This function deletes a option from target definitions
         *
         * @param string $option
         * @param array $file
         * @return array|int
         */
        private function delete_option_from_iqn($option, array $file) {
            // Create iqn line
            $iqn = 'Target ' . $this->iqn;

            // Get indexes of all target definitions
            foreach ($file as $key => $line) {
                if (substr(preg_replace('/\s+/', '', $line), 0, 6) === 'Target') {
                    $keys[] = $key;
                }
            }

            if (isset($keys) && is_array($keys)) {
                // Get index of the iqn from which the option should be deleted
                $key = array_search($iqn, $file);
                if ($key !== false) {
                    // Get the index of the position of the next target definition
                    $temp = array_search($key, $keys);
                    if ($temp !== false) {
                        // If $keys[$temp+1], there is another target definitions after this one
                        if (isset($keys[$temp + 1])) {
                            $end = $keys[$temp + 1];
                        } else {
                            // If it's not set, the count of the array will be the last line
                            end($file);
                            $end = key($file);
                        }

                        // Options for $iqn are defined from $key+1 till $end - $key
                        // Create array with iqn options
                        $options = array_slice($file, $key + 1, $end - $key - 1, true);

                        $val = array_search($option, $options);

                        if ($val !== false) {
                            // delete option and index
                            unset($file[$val]);
                        } else {
                            return 3;
                        }

                        $return['deleted'] = $val;
                        $return['file'] = $file;
                        return $return;
                    } else {
                        return 3;
                    }
                } else {
                    return 3;
                }
            } else {
                return 3;
            }
        }

        /**
         * This function deletes a target definition from the config ifle
         * If the target has options, a error is returned
         *
         * @param array $file
         * @return int|array
         */
        private function delete_iqn_from_config_file(array $file) {
            $iqn = 'Target ' . $this->iqn;

            $key = array_search($iqn, $file);

            if($key !== false) {
                // Delete the position only, if the next line contains 'Target', otherwise the iqn has options defined
                if (isset($file[$key + 1])) {
                    if (substr(preg_replace('/\s+/', '', $file[$key + 1]), 0, 6) === 'Target') {
                        unset($file[$key]);
                        $return['deleted'] = $key;
                        $return['file'] = $file;
                        return $return;
                    } else {
                        return 4;
                    }
                } else {
                    return 3;
                }
            } else {
                return 3;
            }
        }

        /**
         * Return all options of a target definition
         * The array will contain two indexes. Index one contains the target definition.
         * Index two contains another array with all gathered options
         *
         * @param array $file
         * @return bool|array
         *
         */
        private function get_all_options_from_iqn(array $file) {
            $iqn = 'Target ' . $this->iqn;

            // Get indexes of all target definitions
            foreach ($file as $key => $line) {
                if (substr(preg_replace('/\s+/', '', $line), 0, 6) === 'Target') {
                    $keys[] = $key;
                }
            }

            // Get index of the iqn from which the option should be deleted
            $key = array_search($iqn, $file);

            if($key !== false) {
                // Get the index of the position of the next target definition
                $temp = array_search($key, $keys);

                if($key !== false) {
                    // If $keys[$temp+1], there is another target definitions after this one
                    if (isset($keys[$temp + 1])) {
                        $end = $keys[$temp + 1];
                    } else {
                        // If it's not set, the count of the array will be the last line
                        $end = count($file);
                    }

                    // If key and ned have the same value, the target definition is only one line
                    // This means there are no options to delete!
                    if ($key + 1 == $end) {
                        return 0;
                    } else {
                        // Options for $iqn are defined between $key+1 and $end-1
                        // If they are the same, the iqn has only one option
                        if (strcmp($file[$key + 1], $file[$end - 1]) === 0) {
                            if (isset($file[$key + 1])) {
                                // return array so we can always use a loop
                                return array(
                                    0 => explode(' ', trim($iqn, "\n")),
                                    1 => explode(' ', trim($file[$key + 1], "\n"))
                                );
                            } else {
                                return 3;
                            }
                        } else {
                            // Create array with iqn options
                            $options = array_splice($file, $key, $end - $key);
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
            } else {
                return 3;
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
         * Get all information from a iet allow file
         *
         * If no rules for this iqn are found, this function might return the 'ALL ALL' rule if available
         *
         * @param array $file
         * @return array|bool
         *
         */
        private function parse_target_acl(array $file) {
            foreach ($file as $key => $line) {
                $acls[$key] = explode(',', $line);
            }

            if (!empty($acls)) {
                $acls = array_values($acls);

                foreach ($acls as $key => $acl) {
                    $acl[0] = trim(trim($acl[0], ' '), "\n");

                    $values[$key] = explode(' ', $acl[0]);
                    unset($acl[0]);

                    foreach ($acl as $rule) {
                        array_push($values[$key], trim(trim($rule, ' '), "\n"));
                    }
                }
            } else {
                return 3;
            }

            if (!empty($values)) {
                $key = array_search($this->iqn, $values);
                return $values[$key];
            } else {
                return 3;
            }
        }

        /**
         * This function deletes a object from a iqn definition (initiator & target allow files)
         * Valid separators between the objects are ',' and ', '
         * If a line contains only the iqn and a object, it will be deleted completely
         * Multiple spaces in the line which is edited will be replaced by a single one
         *
         * @param string $object
         * @param array $file
         * @return int|array
         *
         */
        private function delete_object_from_iqn($object, array $file) {
            $iqn_index = $this->std->array_find_iqn($this->iqn, $file);

            if ($iqn_index !== false) {
                // replace multiple spaces with a single on
                $file[$iqn_index] = preg_replace('!\s+!', ' ', $file[$iqn_index]);
                $object_length = strlen($object);
                $line_length = strlen($file[$iqn_index]);
                $iqn_length = strlen($this->iqn);
                $object_start_position = strpos($file[$iqn_index], $object);

                if ($object_start_position !== false) {
                    // check if $object is the only one in this line
                    // if that's true we also delete the iqn, which means we kill the whole line
                    if ($iqn_length + 1 + $object_length === $line_length) {
                        unset($file[$iqn_index]);
                        $return['file'] = $file;
                        $return['deleted'] = $iqn_index;
                        return $return;
                    }

                    // Check if object is the last one
                    // If start position + object length is as long as the whole line
                    // we have a winner
                    if ($object_start_position + $object_length === $line_length) {
                        $line_with_deleted_object = str_replace(', ' . $object, '', $file[$iqn_index], $count);

                        // Normally ist should only be one
                        // but who knows?
                        if ($count >= 1) {
                            $file[$iqn_index] = $line_with_deleted_object;
                            $return['file'] = $file;
                            return $return;
                        } else {
                            // try again here without space
                            $line_with_deleted_object = str_replace(',' . $object, '', $file[$iqn_index], $count);

                            if ($count >= 1) {
                                $file[$iqn_index] = $line_with_deleted_object;
                                $return['file'] = $file;
                                return $return;
                            } else {
                                // something went really wrong
                                // can't find the object
                                return 3;
                            }
                        }
                        // Check if object is first one
                        // If the object start position - 1 (for the space between the first object and the iqn) is equal
                        // to the iqn length we have a winner
                    } else if ($object_start_position - 1 === $iqn_length) {
                        $line_with_deleted_object = str_replace($object . ', ', '', $file[$iqn_index], $count);

                        // Normally ist should only be one
                        // but who knows?
                        if ($count >= 1) {
                            $file[$iqn_index] = $line_with_deleted_object;
                            $return['file'] = $file;
                            return $return;
                        } else {
                            // try again here without space
                            $line_with_deleted_object = str_replace($object . ',', '', $file[$iqn_index], $count);

                            if ($count >= 1) {
                                $file[$iqn_index] = $line_with_deleted_object;
                                $return['file'] = $file;
                                return $return;
                            } else {
                                // something went really wrong
                                // can't find the object
                                return 3;
                            }
                        }
                        // Object is somewhere in the middle
                    } else {
                        $line_with_deleted_object = str_replace($object . ', ', '', $file[$iqn_index], $count);

                        // Normally ist should only be one
                        // but who knows?
                        if ($count >= 1) {
                            $file[$iqn_index] = $line_with_deleted_object;
                            $return['file'] = $file;
                            return $return;
                        } else {
                            // try again here without space
                            $line_with_deleted_object = str_replace($object . ',', '', $file[$iqn_index], $count);

                            if ($count >= 1) {
                                $file[$iqn_index] = $line_with_deleted_object;
                                $return['file'] = $file;
                                return $return;
                            } else {
                                // something went really wrong
                                // can't find the object
                                return 3;
                            }
                        }
                    }
                } else {
                    return 3;
                }
            }
        }

        /**
         *
         *  This function deletes a iqn from (+ all acls) from one ietd allow file
         *
         * @param   array $file
         * @return  int
         *
         */
        private function delete_iqn_from_allow_file(array $file) {
            $iqn_index = $this->std->array_find_iqn($this->iqn, $file);

            if ($iqn_index !== false) {
                unset($file[$iqn_index]);
                $return['deleted'] = $iqn_index;
                $return['file'] = $file;
                return $return;
            } else {
                return 3;
            }
        }

        /**
         * This function deletes a global option from the config file
         *
         * @param string $option
         * @param array $file
         * @return array|int
         */
        private function delete_global_option_from_file($option, array $file) {
            foreach ($file as $key => $line) {
                if (substr(preg_replace('/\s+/', '', $line), 0, 6) === 'Target') {
                    // $keys[0] will contain the first target definition
                    // Everything before $keys[0] is global
                    $keys[] = $key;
                    break;
                }
            }

            if (isset($keys) && is_array($keys)) {
                $global_section = array_splice($file, 0, $keys[0]);
                $option_index = array_search($option, $global_section);

                if ($option_index !== false) {
                    unset($global_section[$option_index]);
                    $return['file'] = array_merge($global_section, $file);
                    $return['deleted'] = $option_index;
                    return $return;
                } else {
                    // options not found
                    return 3;
                }
            } else {
                // no target definitions
                // everything is global
                $option_index = array_search($option, $file);
                if ($option !== false) {
                    unset($option[$option_index]);
                    $return['file'] = $file;
                    $return['deleted'] = $option_index;
                    return $return;
                } else {
                    // option not found
                    return 3;
                }
            }
        }
    }