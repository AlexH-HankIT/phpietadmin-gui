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
                    $NAME = $_POST['name'];
                    $SIZE = $_POST['size'];
                    $VG = $_COOKIE["volumegroup"];

                    $return = $std->exec_and_return($database->getConfig('sudo') . " " .  $database->getConfig('lvcreate') . ' -L ' . $SIZE . 'G -n' . $NAME . " " . $VG);

                    if ($return != 0) {
                        $this->view('message', "Error - Could not add the logical volume $NAME. Server said: $return[0]");
                    } else {
                        $this->view('message', "Success");
                    }
                } else {
                    if (!isset($_POST['vg_post'])) {
                        $this->view('vginput', $data);
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

            $this->view('footer');
        }

        public function delete() {
            $lvm = $this->model('Lvmdisplay');
            $database = $this->model('Database');
            $std = $this->model('Std');

            $this->view('header');
            $this->view('menu');
            $this->view('lvm/index');

            $data = $lvm->get_volume_groups();

            if ($data == 3) {
                $this->view('message', "Error - Can't display the volumes groups");
            } else {
                if (!isset($_POST['vg_post']) && !isset($_POST['volumes'])) {
                    $this->view('vginput', $data);
                } else {
                    if (isset($_POST['volumes'])) {
                        $VG = $_COOKIE["volumegroup"];

                        $data = $lvm->get_lvm_data('lvs', $VG);

                        $data2 = $lvm->get_full_path_to_volumes($data, $VG);

                        // Get array with volumes and paths
                        $volumes = file_get_contents($database->getConfig('proc_volumes'));
                        preg_match_all("/path:(.*)/", $volumes, $paths);

                        // Filter all used volumes
                        $data2 = array_diff($data2, $paths[1]);

                        // Rebuild array index
                        $data2 = array_values($data2);

                        $var = $lvm->get_data_from_drop_down($data2, $_POST['volumes']);

                        $return = $std->exec_and_return($database->getConfig('sudo') . " " .  $database->getConfig('lvremove') . ' -f ' . $var);

                        if ($return != 0) {
                            $this->view('message', "Error - Cannot delete volume group " . $var);
                        } else {
                            $this->view('message', "Success");
                        }
                    } else {
                        // Write name of volume group in var and save it as cookie for later use
                        $VG = $lvm->get_data_from_drop_down($data, $_POST['vg_post']);
                        setcookie("volumegroup", $VG);

                        $data = $lvm->get_lvm_data('lvs', $VG);

                        if ($data == 3) {
                            $this->view('message', "Error - Volume group " . $VG . "is empty");
                        } else {
                             // Get array with full path to the volumes and ignore already used ones
                            $data2 = $lvm->get_full_path_to_volumes($data, $VG);

                             // Get array with volumes and paths
                            $volumes = file_get_contents($database->getConfig('proc_volumes'));
                            preg_match_all("/path:(.*)/", $volumes, $paths);

                            // Filter all used volumes
                            $data2 = array_diff($data2, $paths[1]);

                            // Rebuild array index
                            $data2 = array_values($data2);

                            if (empty($data2)) {
                                $this->view('message', "Error - The volume group " . $VG . " has no targets to delete!");
                            } else {
                                $this->view('lvm/delete', $data2);
                            }
                        }
                    }
                }
            }
            $this->view('footer');
        }

        public function extend() {

        }

        public function shrink() {

        }

        public function rename() {

        }
    }
?>