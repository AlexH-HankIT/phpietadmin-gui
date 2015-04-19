<?php
class Overview extends Controller {
    public function index() {
        $std = $this->model('Std');

        $this->view('header');
        $this->view('menu');
        //$this->view('overview');
        $data = $std->get_service_status();
        $this->view('footer', $data);
    }

    public function disks() {
        $disks = $this->model('Disks');
        $std = $this->model('Std');
        $data = $disks->getDisks();

        $this->view('header');
        $this->view('menu');
        if (!empty($data)) {
            $this->view('table', $data);
        } else {
            $this->view('message', "Boeser Fehler");
        }

        $data = $std->get_service_status();
        $this->view('footer', $data);
    }

    public function ietvolumes() {
        $ietvolumes = $this->model('IetVolumes');
        $volumes = $ietvolumes->getIetVolumes();
        $std = $this->model('Std');

        $this->view('header');
        $this->view('menu');
        if ($volumes == 1) {
            $this->view('message', "The ietvolumes file was not found or is empty!");
        } else {
            $this->view('table', $volumes);
        }
        $data = $std->get_service_status();
        $this->view('footer', $data);
    }

    public function ietsessions() {
        $ietsessions = $this->model('IetSessions');
        $sessions = $ietsessions->getIetSessions();
        $std = $this->model('Std');

        $this->view('header');
        $this->view('menu');
        if ($sessions == 2) {
            $this->view('message', "The ietsessions file was not found or is empty!");
        } else {
            $this->view('table', $sessions);
        }
        $data = $std->get_service_status();
        $this->view('footer', $data);
    }

    public function pv() {
        $lvm = $this->model('Lvmdisplay');
        $data = $lvm->get_lvm_data('pvs');
        $std = $this->model('Std');

        $this->view('header');
        $this->view('menu');
        $this->view('table', $data);
        $data = $std->get_service_status();
        $this->view('footer', $data);
    }

    public function vg() {
        $lvm = $this->model('Lvmdisplay');
        $data = $lvm->get_lvm_data('vgs');
        $std = $this->model('Std');

        $this->view('header');
        $this->view('menu');
        $this->view('table', $data);
        $data = $std->get_service_status();
        $this->view('footer', $data);
    }

    public function lv() {
        $lvm = $this->model('Lvmdisplay');
        $data = $lvm->get_volume_groups();
        $std = $this->model('Std');

        $this->view('header');
        $this->view('menu');

        if (isset($_POST['vg_post'])) {
            if ($_POST['vg_post'] > 0) {
                $a = $_POST['vg_post'] - 1;
                $data = $lvm->get_logical_volumes_with_table($data[$a]);

                if ($data[1] == 3 ) {
                    $this->view('message', "Volume group is empty!");
                } else {
                    $this->view('table', $data);
                }
            }
        } else {
            $this->view('vginput', $data);
        }

        $data = $std->get_service_status();
        $this->view('footer', $data);
    }

    public function initiators() {
        $iet = $this->model('Ietpermissions');
        $data = $iet->get_initiator_permissions();
        $std = $this->model('Std');

        $this->view('header');
        $this->view('menu');

        if ($data[1] == 1 ) {
            $this->view('message', "The iet initiators allow file was not found. Please check the path!");
        } elseif ($data[1] == 2) {
            $this->view('message', "The iet initiators allow file is empty!");
        } elseif ($data[1] == 3) {
            $this->view('message', "Could not get any data from the iet initiators allow file!");
        } else {
            $this->view('table', $data);
        }

        $data = $std->get_service_status();
        $this->view('footer', $data);
    }

    public function targets() {
        $iet = $this->model('Ietpermissions');
        $data = $iet->get_target_permissions();
        $std = $this->model('Std');

        $this->view('header');
        $this->view('menu');

        if ($data[1] == 1 ) {
            $this->view('message', "The iet targets allow file was not found. Please check the path!");
        } elseif ($data[1] == 2) {
            $this->view('message', "The iet targets allow file is empty!");
        } elseif ($data[1] == 3) {
            $this->view('message', "Could not get any data from the iet targets allow file!");
        } else {
            $this->view('table', $data);
        }

        $data = $std->get_service_status();
        $this->view('footer', $data);
    }

}
?>