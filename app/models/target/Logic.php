<?php namespace phpietadmin\app\models\target;
    class Logic extends Parser {
        public function __construct() {
            Parser::__construct();
        }

        /**
         *
         * Check if the specified name is already in use by a target
         * If it's in use, it will return all data about this target
         *
         *
         * @return  array|bool
         *
         *
         */
        protected function check_target_name_already_in_use() {
            $target = $this->parse_target(array('iqn' => $this->iqn));

            if ($target !== false) {
                return $target;
            } else {
                return false;
            }
        }

        /**
         *
         * Check if the specified path is already in use
         * Returns false if not and the iqn of the target using it if yes
         *
         * @param   string $path path to the block device or image
         * @return  bool|string
         *
         *
         */
        protected function check_lun_in_use($path) {
            $targets = $this->parse_target();

            if ($targets !== false) {
                foreach ($targets as $key => $target) {
                    if (isset($target['lun'])) {
                        for ($i=0; $i < count($target['lun']); $i++) {
                            $luns[$key][$i] = $target['lun'][$i]['path'];
                            $iqns[$key] = $target['iqn'];
                        }
                    }
                }

                if (empty($luns) || empty($iqns)) {
                    return false;
                } else {
                    $key = $this->recursive_array_search($path, $luns);

                    if ($key !== false) {
                        return $iqns[$key];
                    } else {
                        return false;
                    }
                }
            } else {
                return false;
            }
        }

        /**
         *
         * Returns the next free lun id
         * Gaps are handled!
         *
         * @return  int
         *
         */
        protected function get_next_free_lun() {
            if (isset($this->target_data['lun'])) {
                // extract lun ids
                $used_ids = array_column($this->target_data['lun'], 'id');

                // sort array
                sort($used_ids);

                // create range with all possible used ids
                $ids = range(0, max($used_ids));

                // delete all used ids from the array
                $ids = array_diff($ids, $used_ids);

                if (empty($ids)) {
                    // this means there are no gaps in the number sequence
                    // so we just continue with max($used_ids) + 1
                    return max($used_ids) + 1;
                } else {
                    // the smallest number in the $ids array is our id
                    return min($ids);
                }

            } else {
                return 0;
            }
        }

        protected function check_ietd_running() {
            return $this->get_service_status('iscsitarget');
        }

        /**
         *
         * Returns an array with all allow rules of one initiators
         * If the rule has an associated object in the database name and type will be also returned
         * If the rule is an orphane only the value will be returned
         *
         * @return  array|bool
         *
         */
        public function get_acls() {
            $data = $this->parse_target_acl($this->iqn);

            if ($data !== false) {
                foreach ($data as $index => $acls) {
                    for ($i=1; $i < count($acls); $i++) {
                        $value = $this->database->get_object_by_value($acls[$i]);

                        if (isset($value) && !empty($value)) {
                            $data[$index][$i] = array(
                                'id' => $value['id'],
                                'value' => $value['value'],
                                'name' => $value['name'],
                                'type' => $value['type']
                            );
                        } else {
                            $data[$index][$i] = array(
                                'value' => $acls[$i]
                            );
                        }
                    }
                }

                return $data;
            } else {
                return false;
            }
        }

        /**
         *
         * Checks if an object is already added to the target
         *
         * @param int $id id of the object
         * @param string $type type of the acl initiators/targets
         * @return bool
         *
         */
        protected function check_object_already_added($id, $type = 'initiators') {
            $data = $this->get_acls();

            if ($type == "targets") {
                if (isset($data['targets'])) {
                    $value = $this->std->recursive_array_search($id, $data['targets']);
                } else {
                    $value = false;
                }
            } else {
                if (isset($data['initiators'])) {
                    $value = $this->std->recursive_array_search($id, $data['initiators']);
                } else {
                    $value = false;
                }
            }

            if ($value !== false) {
                return true;
            } else {
                return false;
            }
        }

        /**
         *
         * Deletes the lun with $id or, if $lun = false all luns from the ietd config file
         *
         * @param bool|string $path path to the lun, that should be deleted, if $path = false, all lun will be deleted
         *
         * @return boolean|int|array
         *
         */
        protected function delete_lun_from_iqn($path) {
            $data = $this->target_data;

            if ($path === false) {
                // delete all luns
                if (isset($data['lun']) && !empty($data['lun'])) {
                    foreach ($data['lun'] as $key => $lun) {
                        $return[$key] = $this->delete_option_from_iqn('Lun ' . $data['lun'][$key]['id'] . ' Type=' . $data['lun'][$key]['iotype'] . ',IOMode=' . $data['lun'][$key]['iomode'] . ',Path=' . $data['lun'][$key]['path'], $this->database->get_config('ietd_config_file')['value']);
                    }

                    // return array with results
                    if (!empty($return)) {
                        return $return;
                    } else {
                        return false;
                    }
                } else {
                    return false;
                }
            } else {
                // delete lun with $path
                $key = $this->std->recursive_array_search($path, $data['lun']);

                if (isset($key) && isset($data['lun'][$key])) {
                    return $this->delete_option_from_iqn('Lun ' . $data['lun'][$key]['id'] . ' Type=' . $data['lun'][$key]['iotype'] . ',IOMode=' . $data['lun'][$key]['iomode']. ',Path=' . $data['lun'][$key]['path'], $this->database->get_config('ietd_config_file')['value']);
                } else {
                    return false;
                }
            }
        }

        protected function check_user_already_added_to_iet($userdata, $type, $discovery = false) {
            $users = $this->get_configured_iet_users($discovery);

            if ($users == 3) {
                return false;
            } else {
                // search for the index of the user
                $key = $this->std->recursive_array_search($userdata['username'], $users);

                if ($key === false) {
                    return false;
                } else {
                    if ($users[$key][0] == $type && $userdata['username'] == $users[$key][1]) {
                        return true;
                    } else {
                        // search for the index of the user
                        $key = $this->std->recursive_array_search($userdata['username'], $users);
                        if ($users[$key][0] == $type && $userdata['username'] == $users[$key][1]) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                }
            }
        }

        protected function check() {
            if (empty($this->iqn)) {
                echo 'To use this function, please instantiate the object with an iqn!';
                die();
            }
        }

        /**
         *
         * collects all available data for a specific target
         *
         * @return  boolean
         *
         */
        protected function get_target_data() {
            $data = $this->check_target_name_already_in_use();

            if ($data !== false) {
                $this->target_data = $data;
                return true;
            } else {
                return false;
            }
        }
    }