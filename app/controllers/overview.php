<?php
class Overview extends Controller {
    public function index() {

    }

    public function disks() {
        $data = $this->disks->get_disks();

        if (!empty($data)) {
            $this->view('table', $data);
        } else {
            $this->view('message', "Boeser Fehler");
        }
    }

    public function ietvolumes() {
        $volumes = $this->ietvolumes->getIetVolumes();

        if ($volumes === 1 or $volumes === 2) {
            $this->view('message', "The ietvolumes file was not found or is empty!");
        } else {
            $this->view('ietvolumes', $volumes);
        }
    }

    public function ietsessions() {
        $sessions = $this->ietsessions->getIetSessions();

        if (isset($_POST['tid']) or isset($_POST['cid']) && isset($_POST['sid'])) {
            $return = $this->std->exec_and_return($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm') . ' --op delete --tid=' . $_POST['tid'] . ' --sid=' . $_POST['sid'] . ' --cid=' . $_POST['cid']);
        }

        if ($sessions == 2 or $sessions == 1) {
            $this->view('message', "The ietsessions file was not found or is empty!");
        } else {
            $this->view('ietsessions', $sessions);
        }
    }

    public function pv() {
        $data = $this->lvm->get_lvm_data('pvs');

        $this->view('table', $data);
    }

    public function vg() {
        $data = $this->lvm->get_lvm_data('vgs');

        $this->view('table', $data);
    }

    public function lv() {
        $data = $this->lvm->get_all_logical_volumes();

        if ($data == 3 ) {
            $this->view('message', "Error - No logical volumes found!");
        } else {
            $this->view('table', $data);
        }
    }

    /*public function initiators() {
        if (isset($_POST['iqn'])) {
            $data = $this->ietpermissions->get_iet_allow($this->database->get_config('ietd_init_allow'), $_POST['iqn']);
            $this->view('ietpermissions02', $data[0]);

        } else {
            $data = $this->ietpermissions->get_iet_allow($this->database->get_config('ietd_init_allow'));

            if (empty($data[1])) {
                $this->view('message', "Error - The iet initiator permissions file was not found or is empty!");
            } else {
                $this->view('ietpermissions01', $data[1]);
            }
        }
    }


    public function targets() {
        if (isset($_POST['iqn'])) {
            $data = $this->ietpermissions->get_iet_allow($this->database->get_config('ietd_target_allow'), $_POST['iqn']);
            $this->view('ietpermissions02', $data[0]);
        } else {
            $data = $this->ietpermissions->get_iet_allow($this->database->get_config('ietd_target_allow'));

            if (empty($data[1])) {
                $this->view('message', "Error - The iet targets permissions file was not found or is empty!");
            } else {
                $this->view('ietpermissions01', $data[1]);
            }
        }
    }*/
}
?>