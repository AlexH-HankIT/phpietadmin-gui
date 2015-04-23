<?php
    class Lvm extends Controller {
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


        public function add() {
            $session = $this->model('Session');
            $session->setUsername($_SESSION['username']);
            $session->setPassword($_SESSION['password']);

            if ($session->check()) {
                $lvm = $this->model('Lvmdisplay');
                $std = $this->model('Std');
                $database = $this->model('Database');
                if (!empty($_POST['vg'])) {
                    if (isset($_POST['name']) && isset($_POST['size']) && isset($_POST['vg'])) {
                        $NAME = $_POST['name'];
                        $SIZE = $_POST['size'];

                        $return = $std->exec_and_return($database->getConfig('sudo') . " " .  $database->getConfig('lvcreate') . ' -L ' . $SIZE . 'G -n' . $NAME . " " . $_POST['vg']);

                        $this->view('header');
                        $this->view('menu');
                        if ($return != 0) {
                            $this->view('message', "Error - Could not add the logical volume $NAME. Server said: $return[0]");
                            header( "refresh:5;url=/phpietadmin/lvm/add" );
                        } else {
                            $this->view('message', "Success");
                            header( "refresh:2;url=/phpietadmin/lvm/add" );
                        }
                        $data = $std->get_service_status();
                        $this->view('footer', $data);
                    } else {
                        $data = $lvm->get_lvm_data("vgs", $_POST['vg']);

                        $freesize = $lvm->extract_free_size_from_volume_group($data);

                        if ($freesize <= 1) {
                            $this->view('message', "Error - Volume group " . $_POST['vg'] . " is too small for new volumes");
                        } else {
                            $this->view('lvm/add', $freesize);
                        }
                    }
                } else {
                    $this->view('header');
                    $this->view('menu');

                    $data = $lvm->get_volume_groups();

                    if ($data == 3) {
                        $this->view('message', "Error - Can't display the volumes groups");
                    } else {
                        $this->view('vginput', $data);
                    }

                    $data = $std->get_service_status();
                    $this->view('footer', $data);
                }
            } else {
                header("Location: /phpietadmin/auth/login");
            }
        }

        public function delete() {
            $session = $this->model('Session');
            $session->setUsername($_SESSION['username']);
            $session->setPassword($_SESSION['password']);

            if ($session->check()) {
                $lvm = $this->model('Lvmdisplay');
                $database = $this->model('Database');
                $std = $this->model('Std');

                $this->view('header');
                $this->view('menu');

                $data = $lvm->get_all_logical_volumes();

                if ($data == 3) {
                    $this->view('message', "Error - No logical volumes available");
                } else {
                    $data = $lvm->get_unused_logical_volumes($data[2]);
                    if ($data == 2) {
                        $this->view('message', "Error - No logical volumes available");
                    } else {
                        if (isset($_POST['volumes'])) {
                            $return = $std->exec_and_return($database->getConfig('sudo') . " " . $database->getConfig('lvremove') . ' -f ' . $_POST['volumes']);

                            if ($return != 0) {
                                $this->view('message', "Error - Cannot delete logical volume " . $_POST['volumes']);
                            } else {
                                $this->view('message', "Success");
                                header( "refresh:2;url=/phpietadmin/lvm/delete" );
                            }
                        } else {
                            $this->view('lvm/delete', $data);
                        }
                    }
                }
                $data = $std->get_service_status();
                $this->view('footer', $data);
            } else {
                header("Location: /phpietadmin/auth/login");
            }
        }

        public function extend() {
            $session = $this->model('Session');
            $session->setUsername($_SESSION['username']);
            $session->setPassword($_SESSION['password']);

            if ($session->check()) {
                $lvm = $this->model('Lvmdisplay');
                $std = $this->model('Std');
                $database = $this->model('Database');

                $this->view('header');
                $this->view('menu');

                $data = $lvm->get_volume_groups();

                if ($data == 3) {
                    $this->view('message', "Error - Can't display the volumes groups");
                } else {
                    if (isset($_POST['size'])) {
                        $LV = $_COOKIE["logicalvolume"];

                        $return = $std->exec_and_return($database->getConfig('sudo') . " " .  $database->getConfig('lvrextend') .  " -L" . $_POST['size'] . "G " . $LV);

                        if ($return != 0) {
                            $this->view('message', "Error - Cannot extend logical volume" . $LV);
                        } else {
                            $this->view('message', "Success");
                        }
                    } else {
                        if (isset($_POST['volumes'])) {
                            $VG = $_COOKIE["volumegroup"];
                            $var = $_POST['volumes'] - 1;

                            $data = $lvm->get_lvm_data("lvs", $VG);

                            $data = $lvm->get_full_path_to_volumes($data, $VG);

                            $groups = $lvm->get_lvm_data("vgs", $VG);

                            // Get max possible size of volume
                            preg_match("/(.*?)(?=\.|$)/", $groups[0][6], $maxsize);

                            if ($maxsize[1] <= 1) {
                                $this->view('message', "Error - Volume group " . $VG . " is too small for the extention of logical volumes");
                            } else {
                                // Get min (current) size of volume
                                $LV = $lvm->get_lvm_data("lvs", $data[$var]);

                                setcookie("logicalvolume", $data[$var]);
                                preg_match("/(.*?)(?=\.|$)/", $LV[0][3], $minsize);

                                // Leave 1 gig free in volume group
                                $maxsize2 = $maxsize[1] + $minsize[1] - 1;
                                $minsize2 = $minsize[1] + 1;

                                $values = array(
                                    0 => $maxsize2,
                                    1 => $minsize2
                                );

                                $this->view('lvm/extend', $values);
                            }
                        } else {
                            if (!isset($_POST['vg_post'])) {
                                $this->view('vginput', $data);
                            } else {
                                setcookie("volumegroup", $_POST['vg_post']);

                                $data = $lvm->get_lvm_data("lvs", $_POST['vg_post']);

                                if ($data == 3) {
                                    $this->view('message', "Error - Can't display the logical volumes");
                                } else {
                                    $data = $lvm->get_full_path_to_volumes($data, $_POST['vg_post']);
                                    $this->view('lvm/delete', $data);
                                }
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

        public function shrink() {

        }

        public function rename() {

        }
    }
?>