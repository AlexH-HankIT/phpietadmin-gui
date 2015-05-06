<?php
    class targets extends controller {
        public function __construct() {
            $session = $this->model('Session');
            $session->setUsername($_SESSION['username']);
            $session->setPassword($_SESSION['password']);

            // Check if user is logged in
            if (!$session->check()) {
                header("Location: /phpietadmin/auth/login");
                // Die in case browser ignores header redirect
                die();
            }
        }

        public function index() {
            $std = $this->model('Std');
            $this->view('header');
            $this->view('menu');
            $data = $std->get_service_status();
            $this->view('footer', $data);
        }

        public function addtarget() {
            $lvm = $this->model('Lvmdisplay');
            $database = $this->model('Database');
            $ietadd = $this->model('Ietaddtarget');
            $std = $this->model('Std');

            $this->view('header');
            $this->view('menu');

            if (isset($_POST['name'])) {
                $data = $lvm->get_all_logical_volumes();
                if ($data == 3) {
                    $this->view('message', "Error - No logical volumes found!");
                } else {
                    $return = $ietadd->check_target_name_already_in_use($_POST['name']);
                    if ($return == 4) {
                        $this->view('message', "Error - The name " . $_POST['name'] . " is already taken!");
                    } else {
                        $data = $ietadd->get_unused_volumes($data[2]);
                        if (empty($data)) {
                            $this->view('message', "Error - No logical volumes found!");
                        } else {
                            $return = $std->exec_and_return($database->getConfig('sudo') . " " . $database->getConfig('ietadm') . " --op new --tid=0 --params Name=" . $_POST['name']);
                            if ($return != 0) {
                                $this->view('message', "Error - Could not add target " . $_POST['name'] . ". Server said: $return[0]");
                            } else {
                                $line = "Target " . $_POST['name'] . "\n";
                                $std->add_line_to_file($line, $database->getConfig('ietd_config_file'));
                                $this->view('message', "Success");
                            }
                        }
                    }
                }
            } else {
                $this->view('targets/addtarget', $database->getConfig('iqn') . ":");
            }

            $data = $std->get_service_status();
            $this->view('footer', $data);
        }

        public function maplun() {
            $lvm = $this->model('Lvmdisplay');
            $database = $this->model('Database');
            $ietadd = $this->model('Ietaddtarget');
            $std = $this->model('Std');

            $this->view('header');
            $this->view('menu');

            $data = $lvm->get_all_logical_volumes();

            if (!empty($_POST['target']) && !empty($_POST['type']) && !empty($_POST['mode']) && !empty($_POST['path'])) {
                // selection via dropdown
                $TID = $ietadd->get_tid($_POST['target']);
                $LUN = $ietadd->get_next_lun($_POST['target']);
                $return = $std->exec_and_return($database->getConfig('sudo') . " " . $database->getConfig('ietadm') . " --op new --tid=" . $TID . " --lun=" . $LUN . " --params Path=" . $_POST['path'] . ",Type=" . $_POST['type'] . ",IOMode=" . $_POST['mode']);

                if ($return != 0) {
                    $this->view('message', "Error - Could not add lun to target " . $_POST['target'] . " Server said:" . $return[0]);
                } else {
                    // add lun to config file here
                    $line = "Lun " . $LUN . " Type=" . $_POST['type'] . ",IOMode=" . $_POST['mode'] . ",Path=" . $_POST['path'];
                    $std->addlineafterpattern("Target " . $_POST['target'], $database->getConfig('ietd_config_file'), $line);
                }

            } else if (!empty($_POST['target']) && !empty($_POST['type']) && !empty($_POST['mode']) && !empty($_POST['pathtoblockdevice'])) {
                // handle manuel selection here
            } else {
                if ($data == 3) {
                    $this->view('message', "Error - No logical volumes found!");
                } else {
                    $data = $ietadd->get_unused_volumes($data[2]);
                    if (empty($data)) {
                        $this->view('message', "Error - No logical volumes available!");
                    } else {
                        $data['logicalvolumes'] = $data;
                        $data['targets'] = $ietadd->get_targets();

                        if ($data == 4) {
                            $this->view('message', "Error - No targets found");
                        } else {
                            $this->view('targets/maplun', $data);
                        }
                    }
                }
            }

            $data = $std->get_service_status();
            $this->view('footer', $data);
        }


        /*function add() {
            $session = $this->model('Session');
            $session->setUsername($_SESSION['username']);
            $session->setPassword($_SESSION['password']);

            if ($session->check()) {
                $lvm = $this->model('Lvmdisplay');
                $database = $this->model('Database');
                $ietadd = $this->model('Ietaddtarget');
                $std = $this->model('Std');

                $this->view('header');
                $this->view('menu');
                if (isset($_POST['name']) && isset($_POST['path']) && isset($_POST['type'])) {
                    $NAME = $_POST['name'];
                    $TYPE = $_POST['type'];
                    $MODE = $_POST['mode'];

                    $data = $lvm->get_all_logical_volumes();
                    if ($data == 3) {
                        $this->view('message', "Error - No logical volumes found!");
                    } else {
                        $return = $ietadd->check_target_name_already_in_use($NAME);
                        if ($return == 4) {
                            $this->view('message', "Error - The name $NAME is already taken!");
                        } else {
                            $data = $ietadd->get_unused_volumes($data[2]);
                            if (empty($data)) {
                                $this->view('message', "Error - No logical volumes found!");
                            } else {
                                $return = $std->exec_and_return($database->getConfig('sudo') . " " . $database->getConfig('ietadm') . " --op new --tid=0 --params Name=" . $database->getConfig('iqn') . ":" . $NAME);
                                if ($return != 0) {
                                    $this->view('message', "Error - Could not add target $NAME. Server said: $return[0]");
                                } else {
                                    $TID = $ietadd->get_tid($NAME);
                                    $return = $std->exec_and_return($database->getConfig('sudo') . " " . $database->getConfig('ietadm') . " --op new --tid=" . $TID . " --lun=0 --params Path=" . $_POST['path'] . ",Type=" . $TYPE . ",IOMode=" . $MODE);
                                    if ($return != 0) {
                                        $this->view('message', "Error - Could not add lun. Server said: $return[0]");
                                    } else {
                                        $return = $ietadd->write_target_and_lun($NAME, $_POST['path'], $TYPE, $MODE);
                                        if ($return == 6) {
                                            $this->view('message', "Error - Could not write into the ietd config file!");
                                        } elseif ($return == 0) {
                                            $this->view('message', "Success");
                                        }
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $data = $lvm->get_all_logical_volumes();

                    if ($data == 3) {
                        $this->view('message', "Error - No logical volumes found!");
                    } else {
                        $data = $ietadd->get_unused_volumes($data[2]);
                        if (empty($data)) {
                            $this->view('message', "Error - No logical volumes available!");
                        } else {
                            $this->view('targets/add', $data);
                        }
                    }
                }
                $data = $std->get_service_status();
                $this->view('footer', $data);
            } else {
                header("Location: /phpietadmin/auth/login");
            }
        }*/

        public function delete() {
            $ietdelete = $this->model('Ietdeletetarget');
            $database = $this->model('Database');
            $std = $this->model('Std');
            $this->view('header');
            $this->view('menu');
            if (isset($_POST['IQN'])) {
                $data = $ietdelete->parse_data($_POST['IQN']);
                $command = $database->getConfig('sudo') . " " . $database->getConfig('ietadm') . " --op delete --tid=" . $data[0];
                $std->exec_and_return($command);
                $ietdelete->delete_from_config_file($data);
                $this->view('message', "Success");
            } else {
                $data = $ietdelete->get_names();
                if ($data == 2) {
                    $this->view('message', "Error - No targets found");
                } else {
                    $this->view('targets/delete', $data);
                }
            }
            $data = $std->get_service_status();
            $this->view('footer', $data);
        }
    }
?>