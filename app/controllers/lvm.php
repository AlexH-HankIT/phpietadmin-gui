<?php
    class Lvm extends Controller {
        public function add(){
            if (!empty($_POST['vg'])) {
                if (isset($_POST['name']) && isset($_POST['size']) && isset($_POST['vg'])) {
                    $return = $this->lvm->check_logical_volume_exists_in_vg($_POST['name'], $_POST['vg']);

                    if ($return) {
                        $return = $this->exec->add_logical_volume($_POST['size'], $_POST['name'], $_POST['vg']);

                        if ($return != 0) {
                            echo 'Could not add the logical volume ' . htmlspecialchars($_POST['name']) . '. Server said: ' . $return[0];
                        } else {
                            echo "Success";
                        }
                    } else {
                        echo 'The logical volume ' . htmlspecialchars($_POST['name']) . ' already exists!';
                    }
                } else {
                    $data = $this->lvm->get_lvm_data("vgs", $_POST['vg']);

                    $freesize = $this->lvm->extract_free_size_from_volume_group($data);

                    if ($freesize <= 1) {
                        $this->view('message', "Error - Volume group " . htmlspecialchars($_POST['vg']) . " is too small for new volumes");
                    } else {
                        $this->view('lvm/add', $freesize);
                    }
                }
            } else {
                $data = $this->lvm->get_volume_groups();

                if ($data == 3) {
                    $this->view('message', "Error - Can't display the volumes groups");
                } else {
                    $this->view('lvm/vginput', $data);
                }
            }
        }

        public function delete() {
            if (isset($_POST['target']) && !empty($_POST['target'])) {
                $return = $this->exec->delete_logical_volume($_POST['target']);

                if ($return != 0) {
                    echo 'Error - Cannot delete logical volume ' . htmlspecialchars($_POST['target']);
                } else {
                    echo 'Success';
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
            }
        }
    }
?>