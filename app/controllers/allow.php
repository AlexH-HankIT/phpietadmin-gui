<?php
    class allow extends Controller {
        public function index() {
            $this->view('header');
            $this->view('menu');
            $this->view('footer');
        }

        public function addinitiator() {
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
                        $this->view('allow/add', $a_name);
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
            $this->view('footer');
        }

        public function deleteinitiator() {
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
                    $this->view('allow/delete', $a_initiators2);
                } else {
                    $return = $ietpermissions->delete_allow_rule($a_initiators2);
                    if ($return == 1) {
                        $this->view('message', "Error - The iet initiator allow file was not found or is read only!");
                    } else {
                        $this->view('message', "Success");
                    }
                }
            }
            $this->view('footer');
        }
    }
?>