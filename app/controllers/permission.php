<?php
    class permission extends Controller {
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
                header("Location: /phpietadmin/auth/login");
            }
        }

        public function addinitiator() {
            $session = $this->model('Session');
            $session->setUsername($_SESSION['username']);
            $session->setPassword($_SESSION['password']);

            if ($session->check()) {
                $std = $this->model('Std');
                $database = $this->model('Database');
                $ietpermissions = $this->model('Ietpermissions');
                $ietvolume = $this->model('IetVolumes');

                $this->view('header');
                $this->view('menu');

                $data = $ietvolume->getProcVolumes();

                if ($data == 2) {
                    $this->view('message', "Error - No targets found");
                } elseif ($data == 1) {
                    $this->view('message', "Error - The iet volumes file under /proc doesn't exist!");
                } else {
                    $a_name = $ietpermissions->get_volume_names($data);
                    $a_initiators = $ietpermissions->get_allow($database->getConfig('ietd_init_allow'));
                    $a_name = $ietpermissions->get_targets_without_rules($a_initiators, $a_name);

                    if ($a_name == 3) {
                        $this->view('message', "Error - Rules for all targets are already set!");
                    } else {
                        if (empty($_POST['IQNs'])) {
                            $this->view('permission/addip', $a_name);
                        } else {
                            $return = $ietpermissions->write_allow_rule($_POST['IQNs'], $a_name);

                            if ($return == 1) {
                                $this->view('message', "Error - The iet initiator allow file was not found or is read only!");
                            } else {
                                $this->view('message', "Success");
                            }
                        }
                    }
                }
                $data = $std->get_service_status();
                $this->view('footer', $data);
            } else {
                header("Location: /phpietadmin/auth/login");
            }
        }

        public function deleteinitiator() {
            $session = $this->model('Session');
            $session->setUsername($_SESSION['username']);
            $session->setPassword($_SESSION['password']);

            if ($session->check()) {
                $std = $this->model('Std');
                $database = $this->model('Database');
                $ietpermissions = $this->model('Ietpermissions');

                $this->view('header');
                $this->view('menu');

                $a_initiators = $ietpermissions->get_allow($database->getConfig('ietd_init_allow'));

                if ($a_initiators == 3) {
                    $this->view('message', "Error - No allow rules set");
                } elseif ($a_initiators == 1) {
                    $this->view('message', "Error - The iet initiator allow file was not found or is read only!");
                } else {
                    $a_initiators2 = $ietpermissions->get_initiator_array($a_initiators);

                    if (empty($_POST['IQNs2'])) {
                        $this->view('permission/deleteip', $a_initiators2);
                    } else {
                        $return = $ietpermissions->delete_allow_rule($a_initiators2);
                        if ($return == 1) {
                            $this->view('message', "Error - The iet initiator allow file was not found or is read only!");
                        } else {
                            $this->view('message', "Success");
                        }
                    }
                }
                $data = $std->get_service_status();
                $this->view('footer', $data);
            } else {
                header("Location: /phpietadmin/auth/login");
            }
        }

        public function adduser() {
            $session = $this->model('Session');
            $session->setUsername($_SESSION['username']);
            $session->setPassword($_SESSION['password']);

            if ($session->check()) {
                $std = $this->model('Std');
                $database = $this->model('Database');
                $ietvolume = $this->model('IetVolumes');
                $ietdelete = $this->model('Ietdeletetarget');
                $ietadd = $this->model('Ietaddtarget');

                $this->view('header');
                $this->view('menu');

                $data = $ietdelete->get_names();

                if ($data == 2) {
                    $this->view('message', "Error - No targets found");
                } else {
                    if (isset($_POST['iqn']) && isset($_POST['user']) && isset($_POST['pass'])) {
                        $IQN = $_POST['iqn'];
                        $USER = $_POST['user'];
                        $PASS = $_POST['pass'];

                        $TID = $ietadd->get_tid($IQN);

                        $return = $std->exec_and_return($database->getConfig('sudo') . " " . $database->getConfig('ietadm') . " --op new --tid=" . $TID . " --user --params=IncomingUser=" . $USER . ",Password=" . $PASS);

                        if ($return == 1) {
                            $this->view('message', "Error - The user was not added!");
                        } else {
                            $this->view('message', "Success");

                            // Only add user to config if cli was ok
                            $std->addlineafterpattern($IQN, $database->getConfig('ietd_config_file'), "IncomingUser " . $USER . " " . $PASS);
                        }
                    } else {
                        $this->view('permission/adduser', $data);
                    }
                }

                $data = $std->get_service_status();
                $this->view('footer', $data);
            } else {
                header("Location: /phpietadmin/auth/login");
            }
        }

        public function deleteuser() {

        }
    }
?>