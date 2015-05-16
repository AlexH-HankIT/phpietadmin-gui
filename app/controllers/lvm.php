<?php
    class Lvm extends Controller {
        public function __construct() {
            $this->create_models();
            $this->session->setUsername($_SESSION['username']);
            $this->session->setPassword($_SESSION['password']);

            // Check if user is logged in
            if (!$this->session->check()) {
                header("Location: /phpietadmin/auth/login");
                // Die in case browser ignores header redirect
                die();
            }
        }

        public function index() {
                $this->view('header');
                $this->view('menu');
                $this->view('footer', $this->std->get_service_status());
        }

        public function add(){
            if (!empty($_POST['vg'])) {
                if (isset($_POST['name']) && isset($_POST['size']) && isset($_POST['vg'])) {
                    $return = $this->lvm->check_logical_volume_exists_in_vg($_POST['name'], $_POST['vg']);

                    $this->view('header');
                    $this->view('menu');

                    if ($return) {
                        $return = $this->std->exec_and_return($this->database->get_config('sudo') . " " . $this->database->get_config('lvcreate') . ' -L ' . $_POST['size'] . 'G -n' . $_POST['name'] . " " . $_POST['vg']);

                        if ($return != 0) {
                            $this->view('message', "Error - Could not add the logical volume " . $_POST['name'] . ". Server said: $return[0]");
                        } else {
                            $this->view('message', "Success");
                        }
                    } else {
                        $this->view('message', "The logical volume " . $_POST['name'] . " already exists!");
                    }

                    $data = $this->std->get_service_status();
                    $this->view('footer', $data);
                } else {
                    $data = $this->lvm->get_lvm_data("vgs", $_POST['vg']);

                    $freesize = $this->lvm->extract_free_size_from_volume_group($data);

                    if ($freesize <= 1) {
                        $this->view('message', "Error - Volume group " . $_POST['vg'] . " is too small for new volumes");
                    } else {
                        $this->view('lvm/add', $freesize);
                    }
                }
            } else {
                $this->view('header');
                $this->view('menu');

                $data = $this->lvm->get_volume_groups();

                if ($data == 3) {
                    $this->view('message', "Error - Can't display the volumes groups");
                } else {
                    $this->view('vginput', $data);
                }

                $this->view('footer', $this->std->get_service_status());
            }
        }

        public function delete() {
            $this->view('header');
            $this->view('menu');

            if (isset($_POST['target']) && !empty($_POST['target'])) {
                $return = $this->std->exec_and_return($this->database->get_config('sudo') . " " . $this->database->get_config('lvremove') . ' -f ' . $_POST['target']);
                if ($return != 0) {
                    $this->view('message', "Error - Cannot delete logical volume " . $_POST['target']);
                } else {
                    $this->view('message', "Success");
                }
            } else {
                $data = $this->lvm->get_all_logical_volumes();

                if ($data == 3) {
                    $this->view('message', "Error - No logical volumes available");
                } else {
                    $data = $this->lvm->get_unused_logical_volumes($data[2]);
                    if ($data == 2) {
                        $this->view('message', "Error - No logical volumes available");
                    } else {
                            $this->view('lvm/delete', $data);
                        }
                }
                $this->view('footer', $this->std->get_service_status());
            }
        }

        public function extend() {
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

            $data = $this->lvm->get_all_logical_volumes();
            if ($data == 3) {
                $this->view('message', "Error - No logical volumes available");
            } else {
                echo "<pre>";
                print_r($this->lvm->get_used_logical_volumes($data));
                echo "</pre>";
            }

            $this->view('footer', $this->std->get_service_status());
        }

        public function shrink() {

        }

        public function rename() {

        }
    }
?>