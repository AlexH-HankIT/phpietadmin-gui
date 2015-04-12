<?php
    class Lvm extends Controller {
        public function index() {
            $this->view('header');
            $this->view('menu');
            $this->view('lvm/index');
            $this->view('footer');
        }


        public function add() {
            $lvm = $this->model('Lvmdisplay');
            $std = $this->model('Std');
            $database = $this->model('Database');

            $this->view('header');
            $this->view('menu');
            $this->view('lvm/index');

            $data = $lvm->get_volume_groups();

            if ($data == 3) {
                $this->view('message', "Error - Can't display the volumes groups");
            } else {
                if (isset($_POST['name']) && isset($_POST['size'])) {
                    if (empty($_POST['name'])) {
                        $this->view('message', "Error - Please type a name");
                    } else {
                        $NAME = $_POST['name'];
                        $SIZE = $_POST['size'];
                        $VG = $_COOKIE["volumegroup"];

                        $return = $std->exec_and_return($database->getConfig('sudo') . " " .  $database->getConfig('lvcreate') . ' -L ' . $SIZE . 'G -n' . $NAME . " " . $VG);

                        if ($return != 0) {
                            $this->view('message', "Error - Could not add the logical volume $NAME. Server said: $return[0]");
                        } else {
                            $this->view('message', "Success");
                        }
                    }
                } else {
                    if (!isset($_POST['vg_post'])) {
                        $this->view('vginput', $data);
                    } else {
                        if (empty($_POST['vg_post'])) {
                            $this->view('message', "Error - Please choose a volume group");
                        } else {
                            // Get name of selected volume group
                            $VG = $data[$_POST['vg_post'] - 1];

                            // Get data from selected group
                            $data = $lvm->get_lvm_data("vgs", $VG);

                            // Extract free size of the volume group
                            preg_match("/(.*?)(?=\.|$)/", $data[1][0][6], $freesize);

                            if ($freesize[1] <= 1) {
                                $this->view('message', "Error - Volume group $VG is too small for new volumes");
                            } else {
                                setcookie("volumegroup", $VG);

                                $this->view('lvm/add', $freesize);
                            }
                        }
                    }
                }
            }

            $this->view('footer');
        }

        public function delete() {

        }

        public function extend() {

        }

        public function shrink() {

        }

        public function rename() {

        }
    }
?>