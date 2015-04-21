<?php
    class targets extends controller {
        public function index() {
            $session = $this->model('Session');
            $session->setUsername($_SESSION['username']);
            $session->setPassword($_SESSION['password']);

            if ($session->check()) {
                $std = $this->model('Std');
                $this->view('header');
                $this->view('menu');
                $data = $std->get_service_status();
                $this->view('footer', $data);
            } else {
                header("Location: /phpietadminv02/auth/login");
            }
        }

        function add() {
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
                header("Location: /phpietadminv02/auth/login");
            }
        }

        public function delete() {
            $session = $this->model('Session');
            $session->setUsername($_SESSION['username']);
            $session->setPassword($_SESSION['password']);

            if ($session->check()) {
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
            } else {
                header("Location: /phpietadminv02/auth/login");
            }
        }
    }
?>