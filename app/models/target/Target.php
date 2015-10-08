<?php namespace phpietadmin\app\models\target;
    use phpietadmin\app\models;

    class Target extends Exec {
        // true means target was already there
        // false means target is a new target
        public $target_status;

        public function __construct($iqn = '') {
            // Get paths for binaries in Exec class
            parent::__construct();

            $this->ietd_config_file = $this->database->get_config('ietd_config_file')['value'];

            // if the iqn is empty, we get the data for every target
            if (empty($iqn)) {
                $this->target_data = $this->parse_target();
            } else {
                $this->set_iqn($iqn);
                $this->get_target_data();
                $this->tid = $this->return_target_property('tid');

                $service_check = $this->check_ietd_running();

                if ($service_check['result'] === 0) {
                    // fill object with data
                    $return = $this->get_target_data();

                    // Check if we deal with an existing or new target
                    if ($return === false) {
                        // target is a new target
                        $this->target_status = false;

                        $return = $this->add_target_to_daemon();
                        if ($return['result'] != 0) {
                            $this->logging->log_action_result('Could not add target ' . $iqn, $return, __METHOD__);
                        } else {
                            $return = $this->parse_file($this->ietd_config_file, [$this, 'add_iqn_to_file'], array(), false, false);
                            if ($return !== 0) {
                                if ($return === 1) {
                                    $this->logging->log_action_result('The target ' . $iqn . ' was added to the daemon, but not to the config file, because it\'s read only.', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                                } else if ($return === 3) {
                                    $this->logging->log_action_result('The target ' . $iqn . ' was added to the daemon, but not to the config file, because it was already there.', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                                } else {
                                    $this->logging->log_action_result('The target ' . $iqn . ' was added to the daemon, but not to the config file. Reason is unknown.', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                                }
                            } else {
                                $this->logging->log_action_result('The target ' . $iqn . ' was successfully added', array('result' => 0, 'code_type' => 'intern'), __METHOD__);
                            }
                        }
                        // fill object with data from new target
                        $this->get_target_data();
                    } else {
                        $this->logging->log_action_result('The target ' . $iqn . ' is already in use!', $return, __METHOD__);
                        $this->target_status = true;
                    }
                } else {
                    $this->logging->log_action_result('The ietd service is not running!', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
                }
            }
        }

        /**
         *
         * this function is called from the view
         * it returns the specified properties
         *
         *
         * @param string $property property which should be returned: tid, iqn, lun, session
         * @return string|array|bool
         *
         */
        public function return_target_property($property) {
            $this->check();

            if (isset($this->target_data[$property])) {
                return $this->target_data[$property];
            } else {
                return false;
            }
        }

        public function return_target_data() {
            $this->logging->log_action_result('The iet files were successfully parsed!', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if ($this->target_data === false) {
                $this->logging->log_action_result('No data from the iscsitarget available!', array('result' => 3, 'code_type' => 'intern'), __METHOD__, true);
                return false;
            } else {
                return $this->target_data;
            }
        }

        public function get_iqn() {
            $this->check();

            return $this->iqn;
        }


        public function add_lun($path, $iomode, $type) {
            $this->check();

            // add lun to config file and daemon
            $this->logging->log_action_result('The lun ' . $path . ' was successfully added to the target ' . $this->iqn, array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if (file_exists($path)) {
                $result = $this->check_lun_in_use($path);

                if ($result !== false) {
                    $this->logging->log_action_result('The lun ' . $path . ' is already in use by target ' . $result, array('result' => 4, 'code_type' => 'intern'), __METHOD__);
                } else {
                    $lun = $this->get_next_free_lun();

                    $return = $this->add_lun_to_daemon($lun, $path, $iomode, $type);

                    if ($return['result'] != 0) {
                        $this->logging->log_action_result('Could not add lun to target ' . $this->iqn, $return, __METHOD__);
                    } else {
                        $return = $this->parse_file($this->ietd_config_file, [$this, 'add_option_to_iqn'], array('Lun ' . $lun . ' Type=' . $type . ',IOMode=' . $iomode . ',Path=' . $path), false, false);

                        if ($return != 0) {
                            if ($return == 1) {
                                $this->logging->log_action_result('The lun was added to the daemon, but not to the config file, because it\'s read only.', array('result' => 1, 'code_type' => 'intern'), __METHOD__);
                            } else if ($return == 3) {
                                $this->logging->log_action_result('The lun was added to the daemon, but not to the config file, because the target isn\'t there.', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
                            } else {
                                $this->logging->log_action_result('The lun was added to the daemon, but not to the config file. Reason is unkown.', array('result' => 7, 'code_type' => 'intern'), __METHOD__);
                            }
                        }
                    }
                }
            } else {
                $this->logging->log_action_result('The file ' . $path . ' was not found!', array('result' => 1, 'code_type' => 'intern'), __METHOD__);
            }
        }

        /**
         *
         * Disconnect a session identified by $sid
         *
         * @param string $sid
         *
         */
        public function disconnect_session($sid) {
            $this->check();

            $this->logging->log_action_result('The session ' . $sid . ' was disconnected!', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if (isset($this->target_data['session'])) {
                $key = $this->std->recursive_array_search($sid, $this->target_data['session']);

                if ($key !== false) {
                    $return = $this->exec_disconnect_session($this->target_data['session'][$key]['sid'], $this->target_data['session'][$key]['cid']);

                    if ($return['result'] != 0) {
                        $this->logging->log_action_result('The session ' . $sid . ' was not disconnected!', $return, __METHOD__);
                    }
                } else {
                    // session not found
                    $this->logging->log_action_result('The session ' . $sid . ' was not found!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
                }
            } else {
                // target has no sessions
                $this->logging->log_action_result('The target ' . $this->iqn . ' has no sessions!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
            }
        }

        /**
         *
         * Deletes a lun from the target
         * No data is removed!
         *
         * @param string  $path path of the lun which should be deleted
         * @param bool   $ignore_session optional, delete lun even if a initiator is connected?
         * @return array|bool
         *
         */
        public function detach_lun($path, $ignore_session = false) {
            $this->check();

            $this->logging->log_action_result('The lun ' . $path . ' was successfully deleted from the target ' . $this->iqn, array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            $session = $this->return_target_property('session');

            if ($session !== false && $ignore_session === false) {
                $this->logging->log_action_result('The target ' . $this->iqn . ' has ' . count($session) . ' initiators connected', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
            } else {
                $luns = $this->return_target_property('lun');

                if ($luns !== false) {
                    // get index of lun
                    $key = $this->std->recursive_array_search($path, $luns);

                    if ($key !== false) {
                        // delete lun from daemon
                        $return = $this->delete_lun_from_daemon($luns[$key]['id']);

                        if ($return['result'] != 0) {
                            $this->logging->log_action_result('Could not delete lun ' . $path . ' from target ' . $this->iqn, $return, __METHOD__);
                        } else {
                            $return = $this->delete_lun_from_iqn($path);

                            if ($return !== 0) {
                                $this->logging->log_action_result('Lun wasn\'t defined in the config file!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
                            }
                        }
                    } else {
                        $this->logging->log_action_result('The lun ' . $path . ' is not mapped on this target', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
                    }
                } else {
                    $this->logging->log_action_result('The lun ' . $path . ' is not mapped on this target', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
                }
            }
        }

        /**
         *
         * Deletes all lun from $this->iqn
         * No data is removed!
         *
         * @param bool   $ignore_session optional, delete lun even if a initiator is connected?
         * @return string|array|bool
         *
         */
        public function detach_all_lun($ignore_session = false) {
            $luns = $this->return_all_used_lun();

            if ($luns !== false) {
                foreach ($luns as $key => $lun) {
                    $this->detach_lun($lun, $ignore_session);
                    $result = $this->logging->get_action_result();
                    if ($result['result'] != 0) {
                        $return[$key][] = $lun;
                    }
                }
                if (isset($return)) {
                    $this->logging->log_action_result(count($return) . ' lun were not deleted from the target ' . $this->iqn, array('result' => 5, 'code_type' => 'intern'), __METHOD__);
                } else {
                    $this->logging->log_action_result('All lun were successfully deleted from the target ' . $this->iqn, array('result' => 0, 'code_type' => 'intern'), __METHOD__);
                }
            } else {
                $this->logging->log_action_result('No lun available!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
            }
        }

        /**
         *
         * Add a acl to $this->iqn
         *
         * @param int $id id of the object, which should be added
         * @param string $type acl type (initiators, targets)
         *
         */
        public function add_acl($id, $type = 'initiators') {
            $this->check();

            $id = intval($id);

            $this->logging->log_action_result('The object was successfully added!', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if ($type == "targets") {
                $file = $this->database->get_config('ietd_target_allow')['value'];
            } else {
                $file = $this->database->get_config('ietd_init_allow')['value'];
            }

            $return = $this->check_object_already_added($id, $type);

            $value = $this->database->get_object_value($id);

            if ($return !== false) {
                $this->logging->log_action_result('The object ' . $value . ' is already added!', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
            } else {
                $return = $this->parse_file($file, [$this, 'add_object_to_iqn'], array($value), false, false);

                if ($return !== 0) {
                    $this->logging->log_action_result('The object ' . $value . ' was not added!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                }
            }
        }

        /**
         *
         * Delete a acl from $this->iqn
         *
         * @param string $value value of the acl that should be deleted (e. g. '127.0.0.1')
         * @param string $type acl type (initiators, targets)
         *
         */
        public function delete_acl($value, $type = 'initiators') {
            $this->check();

            $this->logging->log_action_result('The object is successfully deleted!', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if ($type === 'targets') {
                $file = $this->database->get_config('ietd_target_allow')['value'];
            } else {
                $file = $this->database->get_config('ietd_init_allow')['value'];
            }

            $return = $this->parse_file($file, [$this, 'delete_object_from_iqn'], array($value), false, false);

            if ($return !== 0) {
                $this->logging->log_action_result('Could not delete the object!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
            }
        }

        /**
         *
         * Deletes a target from the daemon and the config files
         *
         * @param   bool $force delete the target even if initiators are connected (requires $deleteacl = true)
         * @param   bool $delete_acl delete all acls from the ietd files regarding this target
         * @param   bool $delete_lun if set to true, all luns of the target will be deleted (data will be lost, works only for lvm), if set to false the luns will be detached
         * @return  bool|array
         *
         */
        public function delete_target($force, $delete_acl, $delete_lun) {
            $this->check();

            $this->logging->log_action_result('The target ' . $this->iqn . ' was successfully deleted!', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            // get all data for $this->iqn
            $this->get_target_data();

            // the force option must be used with the deleteacl option
            if ($force === true && $delete_acl === true) {
                // delete the allow rules
                $return = $this->parse_file($this->database->get_config('ietd_target_allow')['value'], [$this, 'delete_iqn_from_allow_file'], array(), false, false);

                if ($return !== 0) {
                    $this->logging->log_action_result('The target acls of the target ' . $this->iqn . ' could not be deleted!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                } else {
                    $return = $this->parse_file($this->database->get_config('ietd_init_allow')['value'], [$this, 'delete_iqn_from_allow_file'], array(), false, false);

                    if ($return !== 0) {
                        $this->logging->log_action_result('The initiators acls of the target ' . $this->iqn . 'could not be deleted!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                    }
                }

                // disconnect initiators
                if (isset($this->target_data['session'])) {
                    foreach ($this->target_data['session'] as $session) {
                        $return = $this->exec_disconnect_session($session['sid'], $session['cid']);

                        if ($return['result'] != 0) {
                            $this->logging->log_action_result('The session from the source ip ' . $session['ip'] . ' was not disconnected!', $return, __METHOD__);
                        }
                    }
                }
            } else if ($delete_acl === true) {
                // delete the allow rule

                $return = $this->parse_file($this->database->get_config('ietd_target_allow')['value'], [$this, 'delete_iqn_from_allow_file'], array(), false, false);

                var_dump($return);

                if ($return !== 0) {
                    $this->logging->log_action_result('The target acls of the target ' . $this->iqn . ' could not be deleted!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                } else {
                    $return = $this->parse_file($this->database->get_config('ietd_init_allow')['value'], [$this, 'delete_iqn_from_allow_file'], array(), false, false);

                    if ($return !== 0) {
                        $this->logging->log_action_result('The initiators acls of the target ' . $this->iqn . 'could not be deleted!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                    }
                }
            }

            // delete luns from daemon and config file here
            if (!empty($this->target_data['lun'])) {
                foreach ($this->target_data['lun'] as $luns) {
                    $return = $this->delete_lun_from_daemon($luns['id']);

                    if ($return['result'] !== 0) {
                        $this->logging->log_action_result('Could not delete lun ' . $luns['path'] . ' from target ' . $this->iqn, $return, __METHOD__);
                    }

                    if ($delete_lun === true) {
                        // delete the lvm volumes here
                        $return = $this->delete_logical_volume($luns['path']);

                        if ($return['result'] !== 0) {
                            $this->logging->log_action_result('Could not delete logical volume ' . $luns['path'], $return, __METHOD__);
                        }
                    }
                }
            }

            // delete target options from ietd file
            $return = $this->parse_file($this->ietd_config_file, [$this, 'delete_all_options_from_iqn'], array(), false, false);

            if ($return !== 0) {
                $this->logging->log_action_result('Could not delete all config options from target ' . $this->iqn . ' You must delete them manually, or this will cause problems!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
            } else {
                // delete target from daemon and ietd file
                $return = $this->delete_target_from_daemon();

                if ($return['result'] !== 0) {
                    $this->logging->log_action_result('Could not delete the target ' . $this->iqn . ' from the daemon!', $return, __METHOD__);
                } else {
                    $return = $this->parse_file($this->ietd_config_file, [$this, 'delete_iqn_from_config_file'], array(), false, false);

                    if ($return !== 0) {
                        $this->logging->log_action_result('Could not delete the target ' . $this->iqn . ' from the config file!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                    }
                }
            }
        }

        /**
         *
         * Returns all settings of $this->iqn
         *
         * @return  int|array
         *
         * Index 0 will contain the target definition, all other indexes are settings
         * Returns 0 if nothing was found
         */
        public function get_settings() {
            $this->check();

            return $this->parse_file($this->ietd_config_file, [$this, 'get_all_options_from_iqn'], array(), true, false);
        }

        /**
         *
         * Returns an array with all settings of $this->iqn (default and user made changes)
         *
         * @return  int|array
         *
         */
        public function get_all_settings() {
            $this->check();

            // get options with values
            $data = $this->parse_file($this->ietd_config_file, [$this, 'get_all_options_from_iqn'], array(), true, false);

            $default_settings = $this->database->get_iet_settings();

            // Insert configured data into default settings array to display user made changes
            if (!empty($data)) {
                // every array with more than two indexes contains a target or a lun definition
                foreach ($data as $key => $value) {
                    if (count($data[$key]) > 2) {
                        unset($data[$key]);
                    }
                }

                // maybe use array_replace() here?
                foreach ($data as $value) {
                    $key = $this->std->recursive_array_search($value[0], $default_settings);

                    if ($key !== false) {
                        $string = trim(preg_replace('/\s+/', ' ', $value[1]));
                        if ($default_settings[$key]['type'] == 'input') {
                            $default_settings[$key]['defaultvalue'] = $string;
                        } else if ($default_settings[$key]['type'] == 'select') {
                            if ($default_settings[$key]['defaultvalue'] != $string) {
                                $default_settings[$key]['othervalue1'] = $default_settings[$key]['defaultvalue'];
                                $default_settings[$key]['defaultvalue'] = $string;
                            }
                        }
                    }
                }
            }

            return $default_settings;
        }

        /**
         *
         * Add a setting to $this->iqn
         *
         * @param string $option the option which should be changed
         * @param string $newvalue the new value for $option
         *
         * ToDo: Use the $type variable to check if the option is a input or select, validate if $newvalue is a possible selection
         */
        public function add_setting($option, $newvalue) {
            $this->check();

            $this->logging->log_action_result('The option ' . $option . ' was successfully added/changed!', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if (!is_numeric($newvalue) && empty($newvalue)) {
                $this->logging->log_action_result('The new value is empty, no changes!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
            } else {
                // check if $newvalue is default value
                $default_settings = $this->database->get_iet_settings();
                $default_settings_key = $this->std->recursive_array_search($option, $default_settings);

                // get value before change
                $targetsettings = $this->get_settings();

                if (is_array($targetsettings)) {
                    // check if option is already defined
                    $key = $this->std->recursive_array_search($option, $targetsettings);

                    // delete old value
                    if ($key !== false) {
                        $return = $this->parse_file($this->ietd_config_file, [$this, 'delete_option_from_iqn'], array($option . ' ' . $targetsettings[$key][1]), false, false);

                        if ($return !== 0) {
                            $this->logging->log_action_result('Could not delete the old value of the option ' . $option, array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                        }
                    }
                }

                if ($default_settings[$default_settings_key]['defaultvalue'] == $newvalue) {
                    if (isset($targetsettings[$key])) {
                        $return = $this->parse_file($this->ietd_config_file, [$this, 'delete_option_from_iqn'], array($option . ' ' . $targetsettings[$key][1]), false, false);

                        if ($return !== 0) {
                            $this->logging->log_action_result('The new value is the default value, so i just deleted it!', array('result' => 0, 'code_type' => 'intern'), __METHOD__);
                        } else {
                            $return = $this->add_config_to_daemon($option, $newvalue);

                            if ($return['result'] !== 0) {
                                $this->logging->log_action_result('Could not set the default value for ' . $option . ' in daemon config', $return, __METHOD__);
                            }
                        }
                    }
                } else {
                    // add option
                    $return = $this->parse_file($this->ietd_config_file, [$this, 'add_option_to_iqn'], array($option . ' ' . $newvalue), false, false);

                    if ($return != 0) {
                        $this->logging->log_action_result('Could not add the value to the option ' . $option . ' in the config file', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                    } else {
                        $return = $this->add_config_to_daemon($option, $newvalue);

                        if ($return['result'] != 0) {
                            $this->logging->log_action_result('Could not add the value to the option ' . $option . ' in daemon config', $return, __METHOD__);
                        }
                    }
                }
            }
        }

        // reset setting to default value
        public function reset_setting() {
            // ToDo
        }

        /**
         *
         * Add a user to $this->iqn
         *
         * @param int $user_id database id of the user
         * @param bool $discovery add a discovery user
         * @param string $type user type
         */
        public function add_user($user_id, $discovery, $type = 'IncomingUser') {
            if ($discovery !== true) {
                $this->check();
            }
            // add a user to this target
            if ($type != 'IncomingUser') {
                $type = 'OutgoingUser';
            }

            // retrieve user password and name
            $userdata = $this->database->get_ietuser(intval($user_id));

            $this->logging->log_action_result('The user ' . $userdata['username'] . ' was successfully added!', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if (is_int($userdata)) {
                $this->logging->log_action_result('The user was not found!', array('result' => $userdata, 'code_type' => 'intern'), __METHOD__);
            } else {
                // check if user is already added
                if ($discovery === true) {
                    $check = $this->check_user_already_added_to_iet($userdata, $type, true);
                } else {
                    $check = $this->check_user_already_added_to_iet($userdata, $type);
                }

                if ($check === false) {
                    // add user to daemon
                    if ($discovery === true) {
                        $return = $this->add_discovery_user_to_daemon($type, $userdata['username'], $userdata['password']);
                    } else {
                        $return = $this->add_user_to_daemon($type, $userdata['username'], $userdata['password']);
                    }

                    if ($return['result'] !== 0) {
                        $this->logging->log_action_result('The user ' . $userdata['username'] . ' was not added to the daemon or the config file!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                    } else {
                        // add user to config file
                        if ($discovery === true) {
                            $return = $this->parse_file($this->ietd_config_file, [$this, 'add_option_to_iqn'], array($type . ' ' . $userdata['username'] . ' ' . $userdata['password']), false, false);
                        } else {
                            $return = $this->parse_file($this->ietd_config_file, [$this, 'add_option_to_iqn'], array($type . ' ' . $userdata['username'] . ' ' . $userdata['password']), false, false);
                        }

                        if ($return !== 0) {
                            $this->logging->log_action_result('The user ' . $userdata['username'] . ' was not added to the config file!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                        }
                    }
                } else {
                    $this->logging->log_action_result('The user ' . $userdata['username'] . ' was already added as ' . $type . '!', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
                }
            }
        }

        /**
         *
         * Delete a user from $this->iqn or, if $discovery = true delete a discovery user
         *
         * @param int $user_id database id of the user
         * @param bool $discovery delete a discovery user
         * @param string $type user type
         *
         */
        public function delete_user($user_id, $discovery, $type = 'IncomingUser') {
            if ($discovery !== true) {
                $this->check();
            }

            // get the type
            if ($type !== 'IncomingUser') {
                $type = 'OutgoingUser';
            }

            // retrieve user password and name
            $userdata = $this->database->get_ietuser(intval($user_id));

            $this->logging->log_action_result('The user ' . $userdata['username'] . ' was successfully deleted!', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if (is_int($userdata)) {
                $this->logging->log_action_result('The user was not found!', array('result' => $userdata, 'code_type' => 'intern'), __METHOD__);
            } else {
                if ($discovery === true) {
                    $return = $this->delete_discovery_user_from_daemon($type, $userdata['username']);
                } else {
                    // delete user from daemon
                    $return = $this->delete_user_from_daemon($type, $userdata['username']);
                }

                if ($return['result'] !== 0) {
                    $this->logging->log_action_result('The user ' . $userdata['username'] . ' could not be deleted from the daemon!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                } else {
                    if ($discovery === true) {
                        $return = $this->parse_file($this->ietd_config_file, [$this, 'delete_global_option_from_file'], array($type . ' ' . $userdata['username'] . ' ' . $userdata['password']), false, true);
                    } else {
                        // delete user from config file
                        $return = $this->parse_file($this->ietd_config_file, [$this, 'delete_option_from_iqn'], array($type . ' ' . $userdata['username'] . ' ' . $userdata['password']), false, true);
                    }

                    if ($return !== 0) {
                        $this->logging->log_action_result('The user ' . $userdata['username'] . ' could not be deleted from the config file!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                    }
                }

            }
        }

        /**
         *
         * Return users from $this->iqn or if $discovery = true, it returns all discovery users
         *
         * @param bool $discovery return discovery user?
         * @return bool|array
         *
         */
        public function get_user($discovery = false) {
            if ($discovery !== true) {
                $this->check();
            }

            $iet_user = $this->get_configured_iet_users($discovery);

            if (empty($iet_user) || $iet_user === 3) {
                return false;
            } else {
                $database_user = $this->database->get_all_usernames(true);

                if ($database_user !== 0) {
                    // get ids for the usernames
                    foreach ($iet_user as $key => $iet) {
                        $found = $this->std->recursive_array_search($iet['1'], $database_user);
                        $iet_user[$key][] = $database_user[$found]['id'];
                    }
                    return $iet_user;
                } else {
                    return false;
                }
            }
        }

        /**
         *
         * Returns all used lun (paths) in an array
         *
         * @return bool|array
         *
         */
        public function return_all_used_lun() {
            $targets = $this->parse_target();

            if ($targets !== false) {
                $return = [];
                foreach ($targets as $key => $target) {
                    if (isset($target['lun'])) {
                        foreach ($target['lun'] as $lun_key => $lun) {
                            $return[$key][$lun_key] = $lun['path'];
                        }
                    }
                }

                $return = $this->std->array_flatten(array_values($return));

                if (empty($return)) {
                    return false;
                } else {
                    return $return;
                }
            } else {
                return false;
            }
        }

        public function is_lun($path) {
            $data = $this->return_all_used_lun();
            if ($data !== false) {
                $return = $this->std->recursive_array_search($path, $data);
                if ($return !== false) {
                    return true;
                } else {
                    return false;
                }
            } else {
                return false;
            }
        }
    }