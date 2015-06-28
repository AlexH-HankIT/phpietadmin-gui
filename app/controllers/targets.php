<?php
    class targets extends controller {
        public function addtarget() {
            if (isset($_POST['name'])) {
                $return = $this->ietadd->check_target_name_already_in_use($_POST['name']);
                if ($return == 4) {
                    echo 'The name ' . $_POST['name'] .  ' is already taken!';
                } else {
                    $return = $this->std->exec_and_return($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm') . " --op new --tid=0 --params Name=" . $_POST['name']);
                    if ($return != 0) {
                        echo 'Could not add target ' . $_POST['name'] . '. Server said: ' . $return[0];
                    } else {
                        $return = $this->ietadd->add_iqn_to_file($_POST['name'], $this->database->get_config('ietd_config_file'));
                        if ($return !== 0) {
                            if ($return == 1 ) {
                                echo 'The target was added to the daemon, but not to the config file, because it\'s read only.';
                            } else if ($return == 4) {
                                echo 'The target was added to the daemon, but not to the config file, because it was already there.';
                            } else {
                                echo 'The target was added to the daemon, but not to the config file. Reason is unkown.';
                            }
                        } else {
                            echo 'Success';
                        }
                    }
                }
            } else {
                $this->view('targets/addtarget', $this->database->get_config('iqn') . ":");
            }
        }

        public function maplun() {
            $data = $this->lvm->get_all_logical_volumes();

            if (!empty($_POST['target']) && !empty($_POST['type']) && !empty($_POST['mode']) && !empty($_POST['path'])) {
                if (file_exists($_POST['path'])) {
                    $TID = $this->ietadd->get_tid($_POST['target']);
                    $LUN = $this->ietadd->get_next_lun($_POST['target']);
                    $return = $this->ietadd->check_path_already_in_use($_POST['path']);
                    if ($return != 0) {
                        echo 'The path '  . $_POST['path'] . ' is already in use';
                    } else {
                        $return = $this->std->exec_and_return($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm') . " --op new --tid=" . $TID . " --lun=" . $LUN . " --params Path=" . $_POST['path'] . ",Type=" . $_POST['type'] . ",IOMode=" . $_POST['mode']);
                        if ($return != 0) {
                            echo 'Could not add lun to target ' .  $_POST['target'] . ' Server said: ' . $return[0];
                        } else {
                            $option = 'Lun ' . $LUN . ' Type=' . $_POST['type'] . ',IOMode=' . $_POST['mode'] . ',Path=' . $_POST['path'];

                            $return = $this->ietadd->add_option_to_iqn_in_file($_POST['target'], $this->database->get_config('ietd_config_file'), $option);

                            if ($return !== 0) {
                                if ($return == 1) {
                                    echo 'The lun was added to the daemon, but not to the config file, because it\'s read only.';
                                } else if ($return == 3) {
                                    echo 'The lun was added to the daemon, but not to the config file, because the target isn\'t there.';
                                } else {
                                    echo 'The lun was added to the daemon, but not to the config file. Reason is unkown.';
                                }
                            } else {
                                echo "Success";
                            }
                        }
                    }
                } else {
                    $this->view('message', "The file " . $_POST['path'] . " was not found!");
                }
            } else if (!empty($_POST['target']) && !empty($_POST['type']) && !empty($_POST['mode']) && !empty($_POST['pathtoblockdevice'])) {
                print_r($_POST);
                // handle manual selection here
            } else {
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
                if (isset($_POST['iqn']) && !isset($_POST['lun'])) {
                    foreach ($data as $value) {
                        if (strcmp($value[0]['name'], $_POST['iqn']) === 0) {
                            for ($i = 1; $i < count($value); $i++) {
                                $paths[$i]['lun'] = $value[$i]['lun'];
                                $paths[$i]['path'] = $value[$i]['path'];
                            }
                        }
                    }
                    // Display page for ajax request
                    $this->view('targets/deletelun', $paths);
                } else if (isset($_POST['iqn']) && isset($_POST['lun']) && isset($_POST['path'])) {
                    if (file_exists($_POST['path'])) {
                        // Delete lun from daemon
                        $tid = $this->ietadd->get_tid($_POST['iqn']);
                        $return = $this->std->exec_and_return($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm') . " --op delete --tid=" . $tid . " --lun=" . $_POST['lun']);

                        if ($return != 0) {
                            echo 'Could not delete lun ' . $_POST['lun'] . ' from target ' . $_POST['iqn'] . ' Server said:' . $return[0];
                        } else {
                            foreach ($data as $value) {
                                if (strcmp($value[0]['name'], $_POST['iqn']) === 0) {
                                    for ($i = 1; $i < count($value); $i++) {
                                        if (strcmp($value[$i]['path'], $_POST['path']) === 0) {
                                            $_POST['type'] = $value[$i]['iotype'];
                                            $_POST['mode'] = $value[$i]['iomode'];
                                        }
                                    }
                                }
                            }

                            $line = "Lun " . $_POST['lun'] . " Type=" . $_POST['type'] . ",IOMode=" . $_POST['mode'] . ",Path=" . $_POST['path'];

                            $return = $this->ietdelete->delete_option_from_iqn($_POST['iqn'], $line, $this->database->get_config('ietd_config_file'));

                            if ($return !== 0) {
                                echo 'Lun wasn\'t defined in the config file!';
                            } else {
                                echo "Success";
                            }
                        }
                    } else {
                        echo 'The file ' . $_POST['path'] . ' was not found!';
                    }
                }
            } else {
                $this->view('message', "Error - No luns available");
            }
        }

        public function deletetarget() {
            if (isset($_POST['target'])) {
                $tid = $this->ietadd->get_tid($_POST['target']);
                $return = $this->std->exec_and_return($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm') . " --op delete --tid=" . $tid);

                if ($return != 0) {
                    echo 'Could not delete target ' . $_POST['iqn'] . ' Server said:' . $return[0];
                } else {
                    $return = $this->ietdelete->delete_all_options_from_iqn($_POST['target'], $this->database->get_config('ietd_config_file'));

                    if ($return !== 0) {
                        if ($return == 1) {
                            echo 'The iet config file is read-only';
                        } else if ($return == 3) {
                            echo 'The target was not deleted, because it wasn\'t there';
                        } else {
                            echo 'Unknown';
                        }
                    } else {
                        $return = $this->ietdelete->delete_iqn_from_config_file($_POST['target'], $this->database->get_config('ietd_config_file'));

                        if ($return !== 0) {
                            if ($return == 1) {
                                echo 'The iet config file is read-only';
                            } else if ($return == 3) {
                                echo 'The target was not deleted, because it wasn\'t there';
                            } else {
                                echo 'Unknown';
                            }
                        } else {
                            // Delete the rules of this iqn
                            $val[0] = $this->ietdelete->delete_iqn_from_allow_file($_POST['target'], $this->database->get_config('ietd_init_allow'));
                            $val[1] = $this->ietdelete->delete_iqn_from_allow_file($_POST['target'], $this->database->get_config('ietd_target_allow'));

                            if ($val[0] !== 0 or $val[1] !== 0) {
                                echo 'The target was deleted from the daemon and the config file, but i could not delete the access rules. Please do this manually!';
                            } else {
                                echo "Success";
                            }
                        }
                    }
                }
            } else {
                $data = $this->ietadd->get_targets_without_luns_or_connections($this->ietsessions->getIetSessions());

                if ($data == 3) {
                    $this->view('message', "Error - No targets found");
                } else {
                    $this->view('targets/deletetarget', $data);
                }
            }
        }

        public function configuretarget() {
            if (isset($_POST['iqn'])) {

            } else {
                $data['targets'] = $this->ietadd->get_targets();

                $this->view('breadcrumb', 'Configure target');
                if ($data['targets'] == 3) {
                    $this->view('message', "Error - No targets found");
                } else {
                    $this->view('targets/targetselect', $data);
                    $this->view('targets/configuretargetmenu');
                }
            }
        }

        public function settings() {
            print_r($_POST);
            if (isset($_POST['option']) && isset($_POST['oldvalue']) && isset($_POST['newvalue'])) {
                // Check if newvalue is default

                print_r($_POST);
            } else if (isset($_POST['iqn'])) {
                print_r($_POST);
                echo "foo";

                // get options with values
                $data = $this->ietadd->get_all_options_from_iqn($_POST['iqn'], $this->database->get_config('ietd_config_file'));

                $data['input'] = $this->database->get_iet_settings('input');

                if (is_int($data)) {
                    // iqn has no options
                    // display table with default values here

                } else {
                    // iqn has options
                    
                }

            } else {
                $data['targets'] = $this->ietadd->get_targets();

                if ($data['targets'] == 3) {
                    $this->view('message', "Error - No targets found");
                } else {
                    $this->view('targets/settings', $data);
                }
            }
        }

        public function deletesession() {
            if (isset($_POST['iqn']) && isset($_POST['cid']) && isset($_POST['sid'])){
                // delete session
                if (is_numeric($_POST['cid']) && is_numeric($_POST['sid'])) {
                    $return = $this->ietdelete->delete_session($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm'), $this->ietadd->get_tid($_POST['iqn']), $_POST['sid'], $_POST['cid']);
                    if ($return != 0) {
                        echo 'Could not delete session ' . $_POST['sid'] . ' Server said:' . $return[0];
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
?>