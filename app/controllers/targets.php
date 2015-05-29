<?php
    class targets extends controller {
        public function addtarget() {
            if (isset($_POST['name'])) {
                $return = $this->ietadd->check_target_name_already_in_use($_POST['name']);
                if ($return == 4) {
                    echo 'Error - The name ' . $_POST['name'] .  ' is already taken!';
                } else {
                    $return = $this->std->exec_and_return($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm') . " --op new --tid=0 --params Name=" . $_POST['name']);
                    if ($return != 0) {
                        echo 'Error - Could not add target ' . $_POST['name'] . '. Server said: ' . $return[0];
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
                        echo 'Error - The path '  . $_POST['path'] . ' is already in use';
                    } else {
                        $return = $this->std->exec_and_return($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm') . " --op new --tid=" . $TID . " --lun=" . $LUN . " --params Path=" . $_POST['path'] . ",Type=" . $_POST['type'] . ",IOMode=" . $_POST['mode']);
                        if ($return != 0) {
                            echo 'Error - Could not add lun to target ' .  $_POST['target'] . ' Server said: ' . $return[0];
                        } else {
                            $option = 'Lun ' . $LUN . ' Type=' . $_POST['type'] . ',IOMode=' . $_POST['mode'] . ',Path=' . $_POST['path'];

                            $return = $this->ietadd->add_option_to_iqn_in_file($_POST['target'], $this->database->get_config('ietd_config_file'), $option);

                            if ($return !== 0) {
                                if ($return == 1) {
                                    echo 'The lun was added to the daemon, but not to the config file, because it\'s read only.';
                                } else if ($return == 3) {
                                    echo 'The lun was added to the daemon, but not to the config file, because the target isn\'t there..';
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
                $this->view('targets/deletelun02', $paths);
            } else if (isset($_POST['iqn']) && isset($_POST['lun']) && isset($_POST['path'])) {
                if (file_exists($_POST['path'])) {

                    // Delete lun from daemon
                    $tid = $this->ietadd->get_tid($_POST['iqn']);
                    $return = $this->std->exec_and_return($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm') . " --op delete --tid=" . $tid . " --lun=" . $_POST['lun']);

                    if ($return != 0) {
                        $this->view('message', "Error - Could not delete lun " . $_POST['lun'] . " from target " . $_POST['iqn'] . " Server said:" . $return[0]);
                    } else {
                        foreach ($data as $value) {
                            if (strcmp($value[0]['name'], $_POST['iqn']) === 0) {
                                $_POST['type'] = $value[1]['iotype'];
                                $_POST['mode'] = $value[1]['iomode'];
                            }
                        }

                        $line = "Lun " . $_POST['lun'] . " Type=" . $_POST['type'] . ",IOMode=" . $_POST['mode'] . ",Path=" . $_POST['path'];

                        $return = $this->ietdelete->delete_option_from_iqn($_POST['iqn'], $line, $this->database->get_config('ietd_config_file'));

                        if ($return !== 0) {
                            $this->view('message', "Error - Lun wasn't defined in the config file!");
                        } else {
                            $this->view('message', "Success");
                        }

                        //$this->std->deletelineinfile($this->database->get_config('ietd_config_file'), $line);


                    }
                } else {
                    $this->view('message', "The file " . $_POST['path'] . " was not found!");
                }
            } else {
                $data = $this->ietadd->get_targets_with_lun();

                if (empty($data)) {
                    $this->view('message', "Error - No luns mapped or no targets available!");
                } else {
                // Extract target names
                    $counter = 0;
                    foreach ($data as $value) {
                        $targets[$counter] = $value[0]['name'];
                        $counter++;
                    }
                    $this->view('targets/deletelun01', $targets);
                }
            }
        }

        public function deletetarget() {
            if (isset($_POST['target'])) {
                $tid = $this->ietadd->get_tid($_POST['target']);
                $command = $this->database->get_config('sudo') . " " . $this->database->get_config('ietadm') . " --op delete --tid=" . $tid;
                $return = $this->std->exec_and_return($command);

                if ($return != 0) {
                    $this->view('message', "Error - Could not delete target " . $_POST['iqn'] . " Server said:" . $return[0]);
                } else {
                    $return = $this->ietdelete->delete_iqn_from_config_file($_POST['target'], $this->database->get_config('ietd_config_file'));

                    if ($return !== 0) {
                        if ($return == 1) {
                            $this->view('message', "Error - The iet config file is read-only");
                        } else if ($return == 3) {
                            $this->view('message', "Error - The target was not deleted, because is wasn't there");
                        } else {
                            $this->view('message', "Unknown error!");
                        }
                    } else {
                        $this->view('message', "Success");
                    }
                }
            } else {
                $data = $this->ietadd->get_targets_without_luns();

                if ($data == 3) {
                    $this->view('message', "Error - No targets found");
                } else {
                    $this->view('targets/deletetarget', $data);
                }
            }
        }
    }
?>