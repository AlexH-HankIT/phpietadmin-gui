<?php
    // Generic classes
    require_once('../../Database.php');
    require_once('../Std.php');
    require_once('../logging/Logging.php');

    // Target
    require_once('Parser.php');
    require_once('Logic.php');
    require_once('Exec.php');
    require_once('Target.php');

    class Target extends Exec {
        // true means target was already there
        // false means target is a new target
        public $target_status;

        public function __construct($iqn = '') {
            // Write database object into global var
            $this->set_database(new Database());
            $this->std = new Std();
            $this->ietd_config_file = $this->database->get_config('ietd_config_file');

            // Get paths for binaries in Exec class
            parent::__construct();

            // if the iqn is empty, we get the data for every target
            if (empty($iqn)) {
                $this->target_data = $this->parse_target();
            } else {
                $this->set_iqn($iqn);
                $this->get_target_data();
                $this->tid = $this->return_target_property('tid');

                $service_check = $this->check_ietd_running();

                if ($service_check === 0) {
                    // fill object with data
                    $return = $this->get_target_data();

                    // Check if we deal with an existing or new target
                    if ($return === false) {
                        // default message
                        $this->set_result('The target ' . $iqn . ' was successfully added', array('result' => 0, 'code_type' => 'intern'), __METHOD__);

                        // target is a new target
                        $this->target_status = false;

                        $return = $this->add_target_to_daemon();
                        if ($return['result'] != 0) {
                            $this->set_result('Could not add target ' . $iqn, $return, __METHOD__);
                        } else {
                            $return = $this->add_iqn_to_file();
                            if ($return != 0) {
                                if ($return == 1) {
                                    $this->set_result('The target ' . $iqn . ' was added to the daemon, but not to the config file, because it\'s read only.' . $iqn, array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                                } else if ($return == 3) {
                                    $this->set_result('The target ' . $iqn . ' was added to the daemon, but not to the config file, because it was already there.' . $iqn, array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                                } else {
                                    $this->set_result('The target ' . $iqn . ' was added to the daemon, but not to the config file. Reason is unknown.' . $iqn, array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                                }
                            }
                        }
                        // fill object we data from new target
                        $this->get_target_data();
                    } else {
                        $this->target_status = true;
                    }
                } else {
                    $this->set_result('The ietd service is not running!', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
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
            $this->check();

            $data = $this->check_target_name_already_in_use();

            if ($data !== false) {
                $this->target_data = $data;
                return true;
            } else {
                return false;
            }
        }

        /**
         *
         * this function is called from the view
         * it returns the specified properties
         *
         *
         * @param string $property property which should be returned: tid, iqn, lun, session
         * @return string, array, boolean
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
            $this->check();

            return $this->target_data;
        }

        public function get_iqn() {
            $this->check();

            return $this->iqn;
        }


        public function add_lun($path, $type, $mode) {
            $this->check();

            // add lun to config file and daemon
            $this->set_result('The lun ' . $path . ' was successfully added to the target ' . $this->iqn, array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if (file_exists($path)) {
                $result = $this->check_lun_in_use($path);

                if ($result !== false) {
                    $this->set_result('The lun ' . $path . ' is already in use by target ' . $result, array('result' => 4, 'code_type' => 'intern'), __METHOD__);
                } else {
                    $lun = $this->get_next_free_lun();

                    $return = $this->add_lun_to_daemon($lun, $path, $type, $mode);

                    if ($return['result'] != 0) {
                        $this->set_result('Could not add lun to target ' . $this->iqn, $return, __METHOD__);
                    } else {
                        $return = $this->add_option_to_iqn_in_file('Lun ' . $lun . ' Type=' . $type . ',IOMode=' . $mode . ',Path=' . $path);

                        if ($return != 0) {
                            if ($return == 1) {
                                $this->set_result('The lun was added to the daemon, but not to the config file, because it\'s read only.', array('result' => 1, 'code_type' => 'intern'), __METHOD__);
                            } else if ($return == 3) {
                                $this->set_result('The lun was added to the daemon, but not to the config file, because the target isn\'t there.', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
                            } else {
                                $this->set_result('The lun was added to the daemon, but not to the config file. Reason is unkown.', array('result' => 7, 'code_type' => 'intern'), __METHOD__);
                            }
                        }
                    }
                }
            } else {
                $this->set_result('The file ' . $path . ' was not found!', array('result' => 1, 'code_type' => 'intern'), __METHOD__);
            }
        }

        /**
         *
         * Deletes a lun from the target
         *
         * @param string  $path path of the lun which should be deleted
         * @param boolean   $disconnect_session optional, should connected initiators be disconnected? ("force")
         * @return string, array, boolean
         *
         */
        public function delete_lun($path, $disconnect_session = false) {
            $this->check();

            $this->set_result('The lun ' . $path . ' was successfully deleted from the target ' . $this->iqn, array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            $session = $this->return_target_property('session');

            if ($session !== false && $disconnect_session === false) {
                $this->set_result('The target ' . $this->iqn . ' has ' . count($session) . ' initiators connected', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
            } else {
                $luns = $this->return_target_property('lun');

                if ($luns !== false) {
                    // get index of lun
                    $key = $this->std->recursive_array_search($path, $luns);

                    if ($key !== false) {
                        // delete lun from daemon
                        $return = $this->delete_lun_from_daemon($luns[$key]['id']);

                        if ($return['result'] != 0) {
                            $this->set_result('Could not delete lun ' . $path . ' from target ' . $this->iqn, $return, __METHOD__);
                        } else {
                            $return = $this->delete_lun_from_iqn($path);

                            if ($return !== 0) {
                                $this->set_result('Lun wasn\'t defined in the config file!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
                            }
                        }
                    } else {
                        $this->set_result('The lun ' . $path . ' is not mapped on this target', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
                    }
                } else {
                    $this->set_result('The lun ' . $path . ' is not mapped on this target', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
                }
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

            $this->set_result('The object is successfully added!', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if ($type == "targets") {
                $file = $this->database->get_config('ietd_target_allow');
            } else {
                $file = $this->database->get_config('ietd_init_allow');
            }

            $return = $this->check_object_already_added($id, $type);

            $value = $this->database->get_object_value($id);

            if ($return !== false) {
                $this->set_result('The object ' . $value . ' is already added!', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
            } else {
                $return = $this->add_object_to_iqn($value, $file);

                if ($return != 0) {
                    $this->set_result('The object ' . $value . ' was not added!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
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

            $this->set_result('The object is successfully deleted!', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if ($type == 'targets') {
                $file = $this->database->get_config('ietd_target_allow');
            } else {
                $file = $this->database->get_config('ietd_init_allow');
            }

            $return = $this->delete_object_from_iqn($value, $file);

            if ($return != 0) {
                $this->set_result('Could not delete the object!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
            }
        }

        /**
         *
         * Deletes a target from the daemon and the config files
         *
         * @param   boolean $force delete the target even if initiators are connected (requires $deleteacl = true)
         * @param   boolean $deleteacl delete all acls from the ietd files regarding this target
         * @param   boolean $deletelun if set to true, all luns of the target will be deleted (data will be lost, works only for lvm), if set to false the luns will be detached
         * @return  boolean, array
         *
         */
        public function delete_target($force, $deleteacl, $deletelun) {
            $this->check();

            $this->set_result('The target ' . $this->iqn . ' was successfully deleted!', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            // get all data for $this->iqn
            $data = $this->get_target_data();

            // the force option must be used with the deleteacl option
            if ($force === true && $deleteacl === true) {
                // delete the allow rules
                $return = $this->delete_iqn_from_allow_file($this->database->get_config('ietd_target_allow'));

                if ($return != 0) {
                    $this->set_result('The targets acls of the target ' . $this->iqn . 'could not be deleted!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                } else {
                    $return = $this->delete_iqn_from_allow_file($this->database->get_config('ietd_init_allow'));;

                    if ($return != 0) {
                        $this->set_result('The initiators acls of the target ' . $this->iqn . 'could not be deleted!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                    }
                }

                // disconnect initiators
                if (isset($data['session'])) {
                    foreach ($data['session'] as $session) {
                        $return = $this->disconnect_session($session['sid'], $session['cid']);

                        if ($return['result'] != 0) {
                            $this->set_result('The session from the source ip ' . $session['ip'] . ' was not disconnected!', $return, __METHOD__);
                        }
                    }
                }
            } else if ($deleteacl === true) {
                // delete the allow rules
                $return = $this->delete_iqn_from_allow_file($this->database->get_config('ietd_target_allow'));

                if ($return != 0) {
                    $this->set_result('The targets acls of the target ' . $this->iqn . 'could not be deleted!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                } else {
                    $return = $this->delete_iqn_from_allow_file($this->database->get_config('ietd_init_allow'));

                    if ($return != 0) {
                        $this->set_result('The initiators acls of the target ' . $this->iqn . 'could not be deleted!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                    }
                }
            }

            // delete luns from daemon and config file here
            if (isset($data['lun']) && !empty($data['lun'])) {
                foreach ($data['lun'] as $luns) {
                    $return = $this->delete_lun_from_daemon($luns['id']);

                    if ($return['result'] != 0) {
                        $this->set_result('Could not delete lun ' . $luns['path'] . ' from target ' . $this->iqn, $return, __METHOD__);
                    }

                    if ($deletelun === true) {
                        // delete the lvm volumes here
                        $return = $this->delete_logical_volume($luns['path']);

                        if ($return['result'] != 0) {
                            $this->set_result('Could not delete logical volume ' . $luns['path'], $return, __METHOD__);
                        }
                    }
                }
            }

            // delete target options from ietd file
            $return = $this->delete_all_options_from_iqn();

            if ($return != 0) {
                $this->set_result('Could not delete all config options from target ' . $this->iqn . ' You must delete them manually, or this will cause problems!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
            }

            // delete target from daemon and ietd file
            $return = $this->delete_target_from_daemon();

            if ($return['result'] != 0) {
                $this->set_result('Could not delete the target ' . $this->iqn . ' from the daemon!', $return, __METHOD__);
            }

            $return = $this->delete_iqn_from_config_file();

            if ($return != 0) {
                $this->set_result('Could not delete the target ' . $this->iqn . ' from the config file!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
            }
        }

        /**
         *
         * Returns all settings of $this->iqn
         *
         * @return  int, array
         *
         * Index 0 will contain the target definition, all other indexes are settings
         * Returns 0 if nothing was found
         */
        public function get_settings() {
            $this->check();

            return $this->get_all_options_from_iqn();
        }

        /**
         *
         * Returns an array with all settings of $this->iqn (default and user made changes)
         *
         * @return  int, array
         *
         */
        public function get_all_settings() {
            $this->check();

            // get options with values
            $data = $this->get_all_options_from_iqn();

            $default_settings = $this->database->get_iet_settings();

            // Insert configured data into default settings array to display user made changes
            if (!empty($data)) {
                // every array with more than two indexes contains a target or a lun definition
                foreach ($data as $key => $value) {
                    if (count($data[$key]) > 2) {
                        unset($data[$key]);
                    }
                }

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

            $this->set_result('The option ' . $option . ' was successfully added/changed!', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if (!is_numeric($newvalue) && empty($newvalue)) {
                $this->set_result('The new value is empty, no changes!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
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
                        $return = $this->delete_option_from_iqn($option . ' ' . $targetsettings[$key][1]);

                        if ($return != 0) {
                            $this->set_result('Could not delete the old value of the option ' . $option, array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                        }
                    }
                }

                if ($default_settings[$default_settings_key]['defaultvalue'] == $newvalue) {
                    if (isset($targetsettings[$key])) {
                        $return = $this->delete_option_from_iqn($option . ' ' . $targetsettings[$key][1]);

                        if ($return != 0) {
                            $this->set_result('The new value is the default value, so i just deleted it!', array('result' => 0, 'code_type' => 'intern'), __METHOD__);
                        }
                    }
                } else {
                    // add option
                    $return = $this->add_option_to_iqn_in_file($option . ' ' . $newvalue);

                    if ($return != 0) {
                        $this->set_result('Could not add the value to the option ' . $option, array('result' => $return, 'code_type' => 'intern'), __METHOD__);
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
         * @param boolean $discovery add a discovery user
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

            $this->set_result('The user ' . $userdata['username'] . ' was successfully added!', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if (is_int($userdata)) {
                $this->set_result('The user was not found!', array('result' => $userdata, 'code_type' => 'intern'), __METHOD__);
            } else {
                // check if user is already added
                if ($discovery === true) {
                    $check = $this->check_user_already_added($userdata, $type, true);
                } else {
                    $check = $this->check_user_already_added($userdata, $type);
                }

                if ($check === false) {
                    // add user to daemon
                    if ($discovery === true) {
                        $return = $this->add_discovery_user_to_daemon($type, $userdata['username'], $userdata['password']);
                    } else {
                        $return = $this->add_user_to_daemon($type, $userdata['username'], $userdata['password']);
                    }

                    if (['result'] != 0) {
                        $this->set_result('The user ' . $userdata['username'] . ' was not added to the daemon or the config file!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                    } else {
                        // add user to config file
                        if ($discovery === true) {
                            $return = $this->add_global_option_to_file($type . ' ' . $userdata['username'] . ' ' . $userdata['password']);
                        } else {
                            $return = $this->add_option_to_iqn_in_file($type . ' ' . $userdata['username'] . ' ' . $userdata['password']);
                        }

                        if ($return != 0) {
                            $this->set_result('The user ' . $userdata['username'] . ' was not added to the config file!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                        }
                    }
                } else {
                    $this->set_result('The user ' . $userdata['username'] . ' was already added as ' . $type . '!', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
                }
            }
        }

        /**
         *
         * Delete a user from $this->iqn or, if $discovery = true delete a discovery user
         *
         * @param int $user_id database id of the user
         * @param boolean $discovery delete a discovery user
         * @param string $type user type
         *
         */
        public function delete_user($user_id, $discovery, $type = 'IncomingUser') {
            if ($discovery !== true) {
                $this->check();
            }

            // get the type
            if ($type != 'IncomingUser') {
                $type = 'OutgoingUser';
            }

            // retrieve user password and name
            $userdata = $this->database->get_ietuser(intval($user_id));

            $this->set_result('The user ' . $userdata['username'] . ' was successfully deleted!', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if (is_int($userdata)) {
                $this->set_result('The user was not found!', array('result' => $userdata, 'code_type' => 'intern'), __METHOD__);
            } else {
                if ($discovery === true) {
                    $return = $this->delete_discovery_user_from_daemon($type, $userdata['username']);
                } else {
                    // delete user from daemon
                    $return = $this->delete_user_from_daemon($type, $userdata['username']);
                }

                if ($return['result'] != 0) {
                    $this->set_result('The user ' . $userdata['username'] . ' could not be deleted from the daemon!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                } else {
                    if ($discovery === true) {
                        $return = $this->delete_global_option_from_file($type . ' ' . $userdata['username'] . ' ' . $userdata['password']);
                    } else {
                        // delete user from config file
                        $return = $this->delete_option_from_iqn($type . ' ' . $userdata['username'] . ' ' . $userdata['password']);
                    }

                    if ($return != 0) {
                        $this->set_result('The user ' . $userdata['username'] . ' could not be deleted from the config file!', array('result' => $return, 'code_type' => 'intern'), __METHOD__, true);
                    }
                }

            }
        }

        /**
         *
         * Return users from $this->iqn or if $discovery = true, it returns all discovery users
         *
         * @param boolean $discovery return discovery user?
         * @return boolean, array
         *
         */
        public function get_user($discovery = false) {
            if ($discovery !== true) {
                $this->check();
            }

            $data = $this->get_configured_iet_users($discovery);

            if (empty($data)) {
                return false;
            } else {
                return $data;
            }
        }
    }