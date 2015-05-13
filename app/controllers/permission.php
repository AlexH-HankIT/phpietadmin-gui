<?php
    class permission extends Controller {
        public function __construct() {
            $this->create_models();
            $this->session->setUsername($_SESSION['username']);
            $this->session->setPassword($_SESSION['password']);

            // Check if user is logged in
            if (!$this->session->check()) {
                header("Location: /phpietadmin/auth/login");
                // Die in case browser ignores header redirect
                die();
            } else {
                // Check if ietd service is running
                $data = $this->std->get_service_status();
                if ($data[1] ==! 0) {
                    $this->view('header');
                    $this->view('menu');
                    $this->view('message', "Error - ietd service is not running!");
                    $this->view('footer', $data);
                    die();
                }
            }
        }

        public function index() {
            $this->view('header');
            $this->view('menu');
            $this->view('permissions/permissions');
            $this->view('footer', $this->std->get_service_status());
        }

        public function addip() {
            $this->view('header');
            $this->view('menu');

            $data = $this->ietvolumes->getProcVolumes();
            $data = $this->ietpermissions->get_volume_names($data);

            if ($data == 2) {
                $this->view('message', "Error - No targets found");
            } elseif ($data == 1) {
                $this->view('message', "Error - The iet volumes file under /proc doesn't exist!");
            } else {
                $this->view('permissions/addip', $data);
            }

            $this->view('footer', $this->std->get_service_status());
        }

        public function addinitiator() {
            $this->view('header');
            $this->view('menu');

            $data = $this->ietvolumes->getProcVolumes();

            if ($data == 2) {
                $this->view('message', "Error - No targets found");
            } elseif ($data == 1) {
                $this->view('message', "Error - The iet volumes file under /proc doesn't exist!");
            } else {
                $a_name = $this->ietpermissions->get_volume_names($data);
                $a_initiators = $this->ietpermissions->get_allow($this->database->get_config('ietd_init_allow'));
                $a_name = $this->ietpermissions->get_targets_without_rules($a_initiators, $a_name);

                if ($a_name == 3) {
                    $this->view('message', "Error - Rules for all targets are already set!");
                } else {
                    if (empty($_POST['IQNs'])) {
                        $this->view('permissions/addip', $a_name);
                    } else {
                        $return = $this->ietpermissions->write_allow_rule($_POST['IQNs'], $a_name);

                        if ($return == 1) {
                            $this->view('message', "Error - The iet initiator permissions file was not found or is read only!");
                        } else {
                            $this->view('message', "Success");
                        }
                    }
                }
            }
            $this->view('footer', $this->std->get_service_status());
        }

        public function deleteinitiator() {
            $this->view('header');
            $this->view('menu');

            $a_initiators = $this->ietpermissions->get_allow($this->database->get_config('ietd_init_allow'));

            if ($a_initiators == 3) {
                $this->view('message', "Error - No permissions rules set");
            } elseif ($a_initiators == 1) {
                $this->view('message', "Error - The iet initiator permissions file was not found or is read only!");
            } else {
                $a_initiators2 = $this->ietpermissions->get_initiator_array($a_initiators);

                if (empty($_POST['IQNs2'])) {
                    $this->view('permissions/deleteip', $a_initiators2);
                } else {
                    $return = $this->ietpermissions->delete_allow_rule($a_initiators2);
                    if ($return == 1) {
                        $this->view('message', "Error - The iet initiator permissions file was not found or is read only!");
                    } else {
                        $this->view('message', "Success");
                    }
                }
            }
            $this->view('footer', $this->std->get_service_status());
        }

        public function adduser() {
            $this->view('header');
            $this->view('menu');

            $data = $this->ietadd->get_names();

            if ($data == 2) {
                $this->view('message', "Error - No targets found");
            } else {
                if (isset($_POST['iqn']) && isset($_POST['user']) && isset($_POST['pass'])) {
                    $IQN = $_POST['iqn'];
                    $USER = $_POST['user'];
                    $PASS = $_POST['pass'];

                    $TID = $this->ietadd->get_tid($IQN);

                    $return = $this->std->exec_and_return($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm') . " --op new --tid=" . $TID . " --user --params=IncomingUser=" . $USER . ",Password=" . $PASS);

                    if ($return == 1) {
                        $this->view('message', "Error - The user was not added!");
                    } else {
                        $this->view('message', "Success");

                        // Only add user to config if cli was ok
                        $this->std->addlineafterpattern($IQN, $this->database->get_config('ietd_config_file'), "IncomingUser " . $USER . " " . $PASS);
                    }
                } else {
                    $this->view('permissions/adduser', $data);
                }
            }

            $this->view('footer', $this->std->get_service_status());
        }

        public function deleteuser() {

        }
    }
?>