<?php
    class targets extends controller {
        public function addtarget() {
            if (isset($_POST['name'])) {
                $json = array(
                    'status' => 'Success',
                    'message' => 'The target ' . $_POST['name'] . ' was successfully added'
                );

                $return = $this->ietadd->check_target_name_already_in_use($_POST['name']);
                try {
                    if ($return == 4) {
                        throw new exception('The name ' . htmlspecialchars($_POST['name']) .  ' is already taken!');
                    } else {
                        $return = $this->exec->add_target_to_daemon($_POST['name']);
                        if ($return != 0) {
                            throw new exception('Could not add target ' . htmlspecialchars($_POST['name']) . '. Server said: ' . htmlspecialchars($return[0]));
                        } else {
                            $return = $this->ietadd->add_iqn_to_file($_POST['name'], $this->database->get_config('ietd_config_file'));
                            if ($return !== 0) {
                                if ($return == 1 ) {
                                    throw new exception('The target was added to the daemon, but not to the config file, because it\'s read only.');
                                } else if ($return == 4) {
                                    throw new exception('The target was added to the daemon, but not to the config file, because it was already there.');
                                } else {
                                    throw new exception('The target was added to the daemon, but not to the config file. Reason is unknown.');
                                }
                            }
                        }
                    }
                } catch (Exception $e) {
                    // Override success message with error
                    $json = array(
                        'status' => 'Error',
                        'message' => $e->getMessage()
                    );
                }

                // Output json string
                echo json_encode($json);
            } else {
                $this->view('targets/addtarget', $this->database->get_config('iqn') . ":");
            }
        }

        public function maplun() {
            if (isset($_POST['target'], $_POST['type'], $_POST['mode'], $_POST['path'])) {
                // Default success message
                // Might be overwritten, if exception is thrown
                $json = array(
                    'status' => 'Success',
                    'message' => 'The lun ' . $_POST['path'] . ' was successfully added to the target ' . $_POST['target']
                );

                try {
                    if (!$this->std->mempty($_POST['target'], $_POST['type'], $_POST['mode'], $_POST['path'])) {
                        if (file_exists($_POST['path'])) {
                            $tid = $this->ietadd->get_tid($_POST['target']);
                            $lun = $this->ietadd->get_next_lun($_POST['target']);
                            $return = $this->ietadd->check_path_already_in_use($_POST['path']);
                            if ($return != 0) {
                                throw new exception('The path ' . htmlspecialchars($_POST['path']) . ' is already in use');
                            } else {
                                $return = $this->exec->add_lun_to_daemon($tid, $lun, $_POST['path'], $_POST['type'], $_POST['mode']);
                                if ($return != 0) {
                                    throw new exception('Could not add lun to target ' . htmlspecialchars($_POST['target']) . ' Server said: ' . htmlspecialchars($return[0]));
                                } else {
                                    $return = $this->ietadd->add_option_to_iqn_in_file($_POST['target'], $this->database->get_config('ietd_config_file'), 'Lun ' . $lun . ' Type=' . $_POST['type'] . ',IOMode=' . $_POST['mode'] . ',Path=' . $_POST['path']);

                                    if ($return != 0) {
                                        if ($return == 1) {
                                            throw new exception('The lun was added to the daemon, but not to the config file, because it\'s read only.');
                                        } else if ($return == 3) {
                                            throw new exception('The lun was added to the daemon, but not to the config file, because the target isn\'t there.');
                                        } else {
                                            throw new exception('The lun was added to the daemon, but not to the config file. Reason is unkown.');
                                        }
                                    }
                                }
                            }
                        } else {
                            throw new exception('The file ' . $_POST['path'] . ' was not found!');
                        }
                    } else {
                        throw new exception('No data');
                    }
                } catch (Exception $e) {
                    $json = array(
                        'status' => 'Error',
                        'message' => $e->getMessage()
                    );
                }

                echo json_encode($json);
            } else {
                $data = $this->lvm->get_all_logical_volumes();
                if ($data == 3) {
                    $this->view('message', "Error - No logical volumes found!");
                } else {
                    $data = $this->ietadd->get_unused_volumes($data[2]);
                    if (empty($data)) {
                        $this->view('message', "Error - No logical volumes available!");
                    } else {
                        $data['logicalvolumes'] = $data;
                        $data['targets'] = $this->ietadd->get_targets();

                        if ($data['targets'] == 3) {
                            $this->view('message', "Error - No targets found");
                        } else {
                            $this->view('targets/maplun', $data);
                        }
                    }
                }
            }
        }

        public function deletelun() {
            // Get luns for selected target
            $data = $this->ietadd->get_targets_with_lun();

            if (!empty($data)) {
                if (isset($_POST['iqn'], $_POST['lun'])) {
                    $luns = $this->ietdelete->get_all_luns_of_iqn($data, $_POST['iqn']);

                    if ($luns == 3) {
                        $this->view('message', "Error - No luns available");
                    } else {
                        // get sesssions for
                        $sessions = $this->ietsessions->getIetSessionsforiqn($_POST['iqn']);

                        if (is_array($sessions)) {
                            // position 0 contains the iqn
                            // we already have it
                            unset($sessions[0][0]);
                        }

                        // Display page for ajax request
                        $this->view('targets/deletelun', $luns);
                    }
                } else if (isset($_POST['iqn'], $_POST['lun'], $_POST['path'])) {
                    if (file_exists($_POST['path'])) {
                        // Delete lun from daemon
                        $tid = $this->ietadd->get_tid($_POST['iqn']);
                        $return = $this->exec->delete_lun_from_daemon($tid, $_POST['lun']);

                        if ($return != 0) {
                            echo 'Could not delete lun ' . htmlspecialchars($_POST['lun']) . ' from target ' . htmlspecialchars($_POST['iqn']) . ' Server said:' . htmlspecialchars($return[0]);
                        } else {
                            $luns = $this->ietdelete->get_all_luns_of_iqn($data, $_POST['iqn']);

                            // get the data for the selected lun
                            $key = $this->std->recursive_array_search($_POST['lun'], $luns);

                            $return = $this->ietdelete->delete_option_from_iqn($_POST['iqn'], 'Lun ' . $_POST['lun'] . ' Type=' . $luns[$key]['type'] . ',IOMode=' . $luns[$key]['mode'] . ',Path=' . $_POST['path'], $this->database->get_config('ietd_config_file'));

                            if ($return !== 0) {
                                echo 'Lun wasn\'t defined in the config file!';
                            } else {
                                echo "Success";
                            }
                        }
                    } else {
                        echo 'The file ' . htmlspecialchars($_POST['path']) . ' was not found!';
                    }
                }
            } else {
                $this->view('message', "Error - No luns available");
            }
        }

        public function deletetarget() {
            if (isset($_POST['iqn'], $_POST['action'], $_POST['deleteaacl'], $_POST['force'])) {
                // Get tid of target
                $tid = $this->ietadd->get_tid($_POST['iqn']);

                // Get luns for selected target
                $data = $this->ietadd->get_targets_with_lun();

                if (!empty($data)) {
                    $luns = $this->ietdelete->get_all_luns_of_iqn($data, $_POST['iqn']);

                    if (!is_int($luns)) {
                        // luns are always deleted from the config file and the daemon
                        foreach ($luns as $key => $lun) {
                            try {
                                // delete lun from daemon
                                $return = $this->exec->delete_lun_from_daemon($tid, $_POST['lun']);

                                if ($return != 0) {
                                    throw new exception('Lun ' . $lun['lun'] . ' Error - Could not delete lun ' . $lun['lun'] . ' from daemon');
                                } else {
                                    // delete lun from config file
                                    $return = $this->ietdelete->delete_option_from_iqn($_POST['iqn'], 'Lun ' . $lun['lun'] . ' Type=' . $lun['type'] . ',IOMode=' . $lun['mode'] . ',Path=' . $lun['path'], $this->database->get_config('ietd_config_file'));

                                    if ($return != 0) {
                                        throw new exception('Lun ' . $lun['lun'] . ' Error - Could not delete lun ' . $lun['lun'] . ' from config');
                                    } else {
                                        if ($_POST['action'] == 'delete') {
                                            $return = $this->exec->delete_logical_volume($lun['path']);

                                            if ($return != 0) {
                                                throw new exception('Lun ' . $lun['lun'] . ' Error - Cannot delete logical volume ' . $lun['path']);
                                            } else {
                                                echo 'Lun ' . $lun['lun']  . ': ' . ' Success. LV ' . $lun['path'] . ' deleted' . "\n";
                                            }
                                        } else {
                                            echo 'Lun ' . $lun['lun'] . ': ' . ' Success' . "\n";
                                        }
                                    }
                                }
                            } catch (Exception $e) {
                                echo $e->getMessage() . '. Please remove it manually!';
                            }
                        }
                    } else {
                        echo 'No luns attached!' . "\n";
                    }
                } else {
                    echo 'No luns attached!' . "\n";
                }

                try {
                    // delete acl if set
                    if ($_POST['deleteaacl'] == 'true') {
                        $files[0] = $this->database->get_config('ietd_init_allow');
                        $files[1] = $this->database->get_config('ietd_target_allow');

                        foreach ($files as $file) {
                            $return = $this->ietdelete->delete_iqn_from_allow_file($_POST['iqn'], $file);
                            if ($return != 0) {
                                throw new exception('Error - Could not delete ACLs from file ' . $file);
                            } else {
                                echo 'Success: ACLs from ' . $file . " deleted. \n";
                            }
                        }
                    }

                    // just to be safe, delete all remaining options from $_POST['iqn']
                    $return = $this->ietdelete->delete_all_options_from_iqn($_POST['iqn'], $this->database->get_config('ietd_config_file'));
                    if ($return != 0) {
                        throw new exception('Error - Could not delete other options from iqn ' . $_POST['iqn']);
                    } else {
                        // delete target from daemon

                        if ($_POST['force'] == 'true') {
                            if ($_POST['deleteaacl'] == 'true') {
                                $sessions = $this->ietsessions->getIetSessionsforiqn($_POST['iqn']);

                                if (is_array($sessions))
                                // position 0 contains the iqn
                                // we already have it
                                unset($sessions[0][0]);

                                // force deletion if set and acl is set
                                foreach ($sessions[0] as $key => $session) {
                                    $return = $this->ietdelete->delete_session($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm'), $tid, $session['sid'], $session['cid']);

                                    if ($return != 0) {
                                        throw new exception('Error - Could not delete session ' . $session['sid']);
                                    } else {
                                        echo 'Success: Session ' . $session['sid'] .  " deleted. \n";
                                    }
                                }
                            } else {
                                throw new exception('Error - Cannot force delete without \'Delete acl\'');
                            }
                        }

                        $return = $this->exec->delete_target_from_daemon($tid);
                        if ($return != 0) {
                            throw new exception('Error - Could not delete the iqn from the daemon. Maybe it\'s in use? Try the \'Force\' option');
                        } else {
                            // delete target from file
                            $return = $this->ietdelete->delete_iqn_from_config_file($_POST['iqn'], $this->database->get_config('ietd_config_file'));
                            if ($return != 0) {
                                throw new exception('Error - Could not delete the iqn from the config file');
                            } else {
                                echo 'Target deleted successfully' . "\n";
                            }
                        }
                    }
                } catch (Exception $e) {
                    echo $e->getMessage() . '. Please remove it manually!' . "\n";
                }
            } else {
                $this->view('targets/deletetarget');
            }
        }

        public function configuretarget() {
            $data['targets'] = $this->ietadd->get_targets();
            if ($data['targets'] == 3) {
                $this->view('message', "Error - No targets found");
            } else {
                $this->view('targets/targetselect', $data);
                $this->view('targets/configuretargetmenu');
            }
        }

        public function settings() {
            // change or set value
            if (isset($_POST['option'], $_POST['oldvalue'], $_POST['newvalue'], $_POST['iqn'], $_POST['type'])) {
                // This is already validated on the client and should normally not occur
                if ($_POST['oldvalue'] == $_POST['newvalue']) {
                    echo "No changes!";
                } else {
                    // Change option in daemon config
                    $tid = $this->ietadd->get_tid($_POST['iqn']);

                    $return = $this->exec->add_config_to_daemon($tid, $_POST['option'], $_POST['newvalue']);

                    if ($return !== 0) {
                        echo 'Could not change the daemon config!';
                    } else {
                        // if the default value is the new value, we don't add or delete it to/from the config file
                        $default_settings = $this->database->get_iet_settings();
                        $key = $this->std->recursive_array_search($_POST['option'], $default_settings);
                        
                        $return = $this->ietdelete->delete_option_from_iqn($_POST['iqn'], $_POST['option'] . ' ' . $_POST['oldvalue'], $this->database->get_config('ietd_config_file'));

                        // $return == 0 => option deleted from config file
                        // $return == 1 => file is not found
                        // $return == 3 => option not found in config file
                        if ($return == 1) {
                            echo 'Config file is read-only!';
                        } else {
                            if ($_POST['type'] == 'input') {
                                $return = $this->ietadd->add_option_to_iqn_in_file($_POST['iqn'], $this->database->get_config('ietd_config_file'), $_POST['option'] . ' ' . $_POST['newvalue']);
                            } else if ($_POST['type'] == 'select') {
                                // if the type is 'select', we have to check for the default value

                                if ($default_settings[$key]['defaultvalue'] !== $_POST['newvalue']) {
                                    $return = $this->ietadd->add_option_to_iqn_in_file($_POST['iqn'], $this->database->get_config('ietd_config_file'), $_POST['option'] . ' ' . $_POST['newvalue']);
                                }
                            }

                            if ($return == 1) {
                                echo 'Config file is read-only!';
                            } else if ($return == 3) {
                                echo 'Target not found!';
                            } else {
                                echo 'Success';
                            }
                        }
                    }
                }
            // Resets value to default
            } else if (isset($_POST['option'], $_POST['value'], $_POST['iqn']) && $_POST['action'] == 'reset') {
                // delete value from daemon config
                $tid = $this->ietadd->get_tid($_POST['iqn']);
                $return = $this->std->exec_and_return($this->database->get_config('sudo') . ' ' . $this->database->get_config('ietadm') . ' --op update --tid=' . $tid . ' --params=' . $_POST['option'] . '=');

                if ($return !== 0) {
                    echo 'error';
                } else {
                    // reset value to default
                    $return = $this->ietdelete->delete_option_from_iqn($_POST['iqn'], $_POST['option'] . ' ' . $_POST['value'], $this->database->get_config('ietd_config_file'));

                    if ($return !== 0) {
                        echo 'error';
                    } else {
                        $default_settings = $this->database->get_iet_settings();
                        $key = $this->std->recursive_array_search($_POST['option'], $default_settings);
                        echo htmlspecialchars($default_settings[$key]['defaultvalue']);
                    }
                }
            } else if (isset($_POST['iqn'])) {
                // get options with values
                $data = $this->ietadd->get_all_options_from_iqn($_POST['iqn'], $this->database->get_config('ietd_config_file'));

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

                // cut array in to pieces
                $len = count($default_settings);
                $viewdata[0] = array_slice($default_settings, 0, $len / 2);
                $viewdata[1] = array_slice($default_settings, $len / 2);

                $this->view('targets/settingstable', $viewdata);
            }
        }

        public function deletesession() {
            if (isset($_POST['iqn'], $_POST['cid'], $_POST['sid'])){
                // delete session
                if (is_numeric($_POST['cid']) && is_numeric($_POST['sid'])) {
                    $return = $this->ietdelete->delete_session($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm'), $this->ietadd->get_tid($_POST['iqn']), $_POST['sid'], $_POST['cid']);
                    if ($return != 0) {
                        echo 'Could not delete session ' . htmlspecialchars($_POST['sid']) . ' Server said:' . htmlspecialchars($return[0]);
                    } else {
                        echo "Success";
                    }
                } else {
                    echo "Error - cid and sid are not numeric!";
                }
            } else if (isset($_POST['iqn'])) {
                // display all sessions for this target
                $data = $this->ietsessions->getIetSessionsforiqn($_POST['iqn']);

                if (!$data) {
                    $this->view('message', 'Error - No initiators connected');
                } else {
                    $this->view('targets/sessiondelete', $data);
                }
            }
        }
    }