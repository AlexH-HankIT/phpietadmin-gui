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

                        $this->view('header');
                        $this->view('menu');

                        $return = $lvm->check_logical_volume_exists_in_vg($NAME, $_POST['vg']);

                        if($return) {
                            $return = $std->exec_and_return($database->getConfig('sudo') . " " . $database->getConfig('lvcreate') . ' -L ' . $SIZE . 'G -n' . $NAME . " " . $_POST['vg']);

                            if ($return != 0) {
                                $this->view('message', "Error - Could not add the logical volume $NAME. Server said: $return[0]");
                                header("refresh:5;url=/phpietadmin/lvm/add");
                            } else {
                                $this->view('message', "Success");
                                header("refresh:2;url=/phpietadmin/lvm/add");
                            }
                        } else {
                            $this->view('message', "The logical volume " . $NAME . " already exists!");
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
                $database = $this->model('Database');
                $std = $this->model('Std');

                $this->view('header');
                $this->view('menu');

                /*
                 * list logical volumes
                 * if volume is in use:
                 * resize it and disconnect the session, the initiator normally reconnects immediatly
                 *
                 * otherwise:
                 * just resize it
                 */

                $data = $lvm->get_all_logical_volumes();
                if ($data == 3) {
                    $this->view('message', "Error - No logical volumes available");
                } else {
                    echo "<pre>";
                    print_r($lvm->get_used_logical_volumes($data));
                    echo "</pre>";
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