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
            $database = $this->model('Database');
            $ietadd = $this->model('Ietaddtarget');
            $std = $this->model('Std');

            if (isset($_POST['name'])) {
                $return = $ietadd->check_target_name_already_in_use($_POST['name']);
                if ($return == 4) {
                    $this->view('message', "Error - The name " . $_POST['name'] . " is already taken!");
                } else {
                    $return = $std->exec_and_return($database->get_config('sudo') . " " . $database->get_config('ietadm') . " --op new --tid=0 --params Name=" . $_POST['name']);
                    if ($return != 0) {
                        $this->view('message', "Error - Could not add target " . $_POST['name'] . ". Server said: $return[0]");
                    } else {
                        $line = "Target " . $_POST['name'] . "\n";
                        $std->add_line_to_file($line, $database->get_config('ietd_config_file'));
                        $this->view('message', "Success");
                    }
                }
            } else {
                $this->view('header');
                $this->view('menu');
                $this->view('targets/addtarget', $database->get_config('iqn') . ":");
                $data = $std->get_service_status();
                $this->view('footer', $data);
            }


        }

        public function maplun() {
            $lvm = $this->model('Lvmdisplay');
            $database = $this->model('Database');
            $ietadd = $this->model('Ietaddtarget');
            $std = $this->model('Std');

            $data = $lvm->get_all_logical_volumes();

            if (!empty($_POST['target']) && !empty($_POST['type']) && !empty($_POST['mode']) && !empty($_POST['path'])) {
                $TID = $ietadd->get_tid($_POST['target']);
                $LUN = $ietadd->get_next_lun($_POST['target']);
                $return = $ietadd->check_path_already_in_use($_POST['path']);
                if ($return != 0) {
                    $this->view('message', "Error - The path " . $_POST['path'] . " is already in use");
                } else {
                    $return = $std->exec_and_return($database->get_config('sudo') . " " . $database->get_config('ietadm') . " --op new --tid=" . $TID . " --lun=" . $LUN . " --params Path=" . $_POST['path'] . ",Type=" . $_POST['type'] . ",IOMode=" . $_POST['mode']);
                    if ($return != 0) {
                        $this->view('message', "Error - Could not add lun to target " . $_POST['target'] . " Server said:" . $return[0]);
                    } else {
                        $line = "Lun " . $LUN . " Type=" . $_POST['type'] . ",IOMode=" . $_POST['mode'] . ",Path=" . $_POST['path'] . "\n";
                        $std->addlineafterpattern("Target " . $_POST['target'], $database->get_config('ietd_config_file'), $line);
                        $this->view('message', "Success");
                    }
                }

            } else if (!empty($_POST['target']) && !empty($_POST['type']) && !empty($_POST['mode']) && !empty($_POST['pathtoblockdevice'])) {
                // handle manual selection here
            } else {
                $this->view('header');
                $this->view('menu');
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
                        } else {            $this->view('header');

                            $this->view('targets/maplun', $data);

                        }
                    }
                }
                $data = $std->get_service_status();
                $this->view('footer', $data);
            }
        }

        public function deletelun() {
            $std = $this->model('Std');
            $ietadd = $this->model('Ietaddtarget');

            if (isset($_POST['iqn'])) {
                // Get luns for selected target
                $data = $ietadd->get_targets_with_lun();

                foreach ($data as $value) {
                    if (strcmp($value[0]['name'], $_POST['iqn']) === 0) {
                        for ($i=1; $i < count($value); $i++) {
                            $paths[$i]['lun'] = $value[$i]['lun'];
                            $paths[$i]['path'] = $value[$i]['path'];
                        }
                    }
                }

                // Display page for ajax request
                $this->view('targets/deletelun02', $paths);
            } else {
                $this->view('header');
                $this->view('menu');

                $data = $ietadd->get_targets_with_lun();

                //echo "<pre>";
                //print_r($data);
                //echo "</pre>";


                // Extract target names
                $counter=0;
                foreach ($data as $value) {
                    $targets[$counter] = $value[0]['name'];
                    $counter++;
                }

                $this->view('targets/deletelun01', $targets);

                $data = $std->get_service_status();
                $this->view('footer', $data);
            }
        }

        public function delete() {
            $ietdelete = $this->model('Ietdeletetarget');
            $database = $this->model('Database');
            $std = $this->model('Std');
            $this->view('header');
            $this->view('menu');
            if (isset($_POST['IQN'])) {
                $data = $ietdelete->parse_data($_POST['IQN']);
                $command = $database->get_config('sudo') . " " . $database->get_config('ietadm') . " --op delete --tid=" . $data[0];
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