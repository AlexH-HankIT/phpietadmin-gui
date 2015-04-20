<?php
    class targets extends controller {
        public function index() {
            $std = $this->model('Std');
            $this->view('header');
            $this->view('menu');
            $data = $std->get_service_status();
            $this->view('footer', $data);
        }

        function add() {
            $lvm = $this->model('Lvmdisplay');
            $database = $this->model('Database');
            $ietadd = $this->model('Ietaddtarget');
            $std = $this->model('Std');
            $data = $lvm->get_volume_groups();
            $this->view('header');
            $this->view('menu');
            if (isset($_POST['vg_post'])) {
                $VG = $lvm->get_data_from_drop_down($data, $_POST['vg_post']);
                $data = $lvm->get_lvm_data('lvs', $VG);
                if ($data == 3) {
                    $this->view('message', "Error - the volume group $VG has no logical volumes!");
                } else {
                    setcookie("volumegroup", $VG);
                    $logicalvolumes = $lvm->get_full_path_to_volumes($data, $VG);
                    $data = $ietadd->get_unused_volumes($logicalvolumes);
                    if (empty($data)) {
                        $this->view('message', "Error - In the volume group $VG are no volumes available!");
                    } else {
                        $this->view('targets/add', $data);
                    }
                }
            } elseif (isset($_POST['name']) && isset($_POST['path']) && isset($_POST['type'])) {
                $NAME = $_POST['name'];
                $VG = $_COOKIE["volumegroup"];
                $TYPE = $_POST['type'];
                $MODE = $_POST['mode'];

                $data = $lvm->get_lvm_data('lvs', $VG);
                if ($data == 3) {
                    $this->view('message', "Error - the volume group $VG has no logical volumes!");
                } else {
                    $logicalvolumes = $lvm->get_full_path_to_volumes($data, $VG);
                    $return = $ietadd->check_target_name_already_in_use($NAME);
                    if ($return == 4) {
                        $this->view('message', "Error - The name $NAME is already taken!");
                    } else {
                        $data = $ietadd->get_unused_volumes($logicalvolumes);
                        if (empty($data)) {
                            $this->view('message', "Error - In the volume group $VG are no volumes available!");
                        } else {
                            $LV = $lvm->get_data_from_drop_down($data, $_POST['path']);
                            $return = $std->exec_and_return($database->getConfig('sudo') . " " . $database->getConfig('ietadm') . " --op new --tid=0 --params Name=" . $database->getConfig('iqn') . ":" . $NAME);
                            if ($return != 0) {
                                $this->view('message', "Error - Could not add target $NAME. Server said: $return[0]");
                            } else {
                                $TID = $ietadd->get_tid($NAME);
                                $return = $std->exec_and_return($database->getConfig('sudo') . " " . $database->getConfig('ietadm') . " --op new --tid=" . $TID . " --lun=0 --params Path=" . $LV. ",Type=" . $TYPE . ",IOMode=" . $MODE);
                                if ($return != 0) {
                                    $this->view('message', "Error - Could not add lun. Server said: $return[0]");
                                } else {
                                    $return = $ietadd->write_target_and_lun($NAME, $LV, $TYPE, $MODE);
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
                $this->view('vginput', $data);
            }
            $data = $std->get_service_status();
            $this->view('footer', $data);
        }

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