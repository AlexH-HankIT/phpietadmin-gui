<?php
class Overview extends Controller {
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
        $std = $this->model('Std');

        $volumes = $ietvolumes->getIetVolumes();

        $this->view('header');
        $this->view('menu');
        if ($volumes == 1 or $volumes == 2) {
            $this->view('message', "The ietvolumes file was not found or is empty!");
        } else {
            $this->view('ietvolumes', $volumes);
        }
        $data = $std->get_service_status();
        $this->view('footer', $data);
    }

    public function ietsessions() {
        $ietsessions = $this->model('IetSessions');
        $sessions = $ietsessions->getIetSessions();
        $std = $this->model('Std');
        $database = $this->model('Database');

        $this->view('header');
        $this->view('menu');

        if (isset($_POST['tid']) or isset($_POST['cid']) && isset($_POST['sid'])) {
            $return = $std->exec_and_return($database->getConfig('sudo') . " " . $database->getConfig('ietadm') . ' --op delete --tid=' . $_POST['tid'] . ' --sid=' . $_POST['sid'] . ' --cid=' . $_POST['cid']);
        }

        if ($sessions == 2 or $sessions == 1) {
            $this->view('message', "The ietsessions file was not found or is empty!");
        } else {
            $this->view('ietsessions', $sessions);
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
        $std = $this->model('Std');

        $this->view('header');
        $this->view('menu');

        $data = $lvm->get_all_logical_volumes();

        if ($data == 3 ) {
            $this->view('message', "Error - No logical volumes found!");
        } else {
            $this->view('table', $data);
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
            $this->view('message', "The iet initiators permissions file was not found. Please check the path!");
        } elseif ($data[1] == 2) {
            $this->view('message', "The iet initiators permissions file is empty!");
        } elseif ($data[1] == 3) {
            $this->view('message', "Could not get any data from the iet initiators permissions file!");
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
            $this->view('message', "The iet targets permissions file was not found. Please check the path!");
        } elseif ($data[1] == 2) {
            $this->view('message', "The iet targets permissions file is empty!");
        } elseif ($data[1] == 3) {
            $this->view('message', "Could not get any data from the iet targets permissions file!");
            } else {
            $this->view('table', $data);
        }

        $data = $std->get_service_status();
        $this->view('footer', $data);
    }

}
?>