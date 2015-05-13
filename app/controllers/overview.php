<?php
class Overview extends Controller {
    public function __construct() {
        // Creates all available models
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

    public function disks() {
        $data = $this->disks->get_disks();

        $this->view('header');
        $this->view('menu');
        if (!empty($data)) {
            $this->view('table', $data);
        } else {
            $this->view('message', "Boeser Fehler");
        }

        $this->view('footer', $this->std->get_service_status());
    }

    public function ietvolumes() {
        $volumes = $this->ietvolumes->getIetVolumes();

        $this->view('header');
        $this->view('menu');
        if ($volumes === 1 or $volumes === 2) {
            $this->view('message', "The ietvolumes file was not found or is empty!");
        } else {
            $this->view('ietvolumes', $volumes);
        }
        $this->view('footer', $this->std->get_service_status());
    }

    public function ietsessions() {
        $sessions = $this->ietsessions->getIetSessions();

        $this->view('header');
        $this->view('menu');

        if (isset($_POST['tid']) or isset($_POST['cid']) && isset($_POST['sid'])) {
            $return = $this->std->exec_and_return($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm') . ' --op delete --tid=' . $_POST['tid'] . ' --sid=' . $_POST['sid'] . ' --cid=' . $_POST['cid']);
        }

        if ($sessions == 2 or $sessions == 1) {
            $this->view('message', "The ietsessions file was not found or is empty!");
        } else {
            $this->view('ietsessions', $sessions);
        }
        $this->view('footer', $this->std->get_service_status());
    }

    public function pv() {
        $data = $this->lvm->get_lvm_data('pvs');

        $this->view('header');
        $this->view('menu');
        $this->view('table', $data);
        $this->view('footer', $this->std->get_service_status());
    }

    public function vg() {
        $data = $this->lvm->get_lvm_data('vgs');

        $this->view('header');
        $this->view('menu');
        $this->view('table', $data);
        $this->view('footer', $this->std->get_service_status());
    }

    public function lv() {
        $this->view('header');
        $this->view('menu');

        $data = $this->lvm->get_all_logical_volumes();

        if ($data == 3 ) {
            $this->view('message', "Error - No logical volumes found!");
        } else {
            $this->view('table', $data);
        }

        $this->view('footer', $this->std->get_service_status());
    }

    public function initiators() {
        $data = $this->ietpermissions->get_initiator_permissions();

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

        $this->view('footer', $this->std->get_service_status());
    }

    public function targets() {
        $data = $this->ietpermissions->get_target_permissions();

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

        $this->view('footer', $this->std->get_service_status());
    }

}
?>