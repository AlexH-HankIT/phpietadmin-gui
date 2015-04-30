<?php
class Overview extends Controller {
    public function index() {
        $session = $this->model('Session');
        $session->setUsername($_SESSION['username']);
        $session->setPassword($_SESSION['password']);

        if ($session->check()) {
            $std = $this->model('Std');

            $this->view('header');
            $this->view('menu');
            //$this->view('overview');
            $data = $std->get_service_status();
            $this->view('footer', $data);
        } else {
            header("Location: /phpietadmin/auth/login");
        }
    }

    public function disks() {
        $session = $this->model('Session');
        $session->setUsername($_SESSION['username']);
        $session->setPassword($_SESSION['password']);

        if ($session->check()) {
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
        } else {
            header("Location: /phpietadmin/auth/login");
        }
    }

    public function ietvolumes() {
        $session = $this->model('Session');
        $session->setUsername($_SESSION['username']);
        $session->setPassword($_SESSION['password']);

        if ($session->check()) {
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
        } else {
            header("Location: /phpietadmin/auth/login");
        }
    }

    public function ietsessions() {
        $session = $this->model('Session');
        $session->setUsername($_SESSION['username']);
        $session->setPassword($_SESSION['password']);

        if ($session->check()) {
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
        } else {
            header("Location: /phpietadmin/auth/login");
        }
    }

    public function pv() {
        $session = $this->model('Session');
        $session->setUsername($_SESSION['username']);
        $session->setPassword($_SESSION['password']);

        if ($session->check()) {
            $lvm = $this->model('Lvmdisplay');
            $data = $lvm->get_lvm_data('pvs');
            $std = $this->model('Std');

            $this->view('header');
            $this->view('menu');
            $this->view('table', $data);
            $data = $std->get_service_status();
            $this->view('footer', $data);
        } else {
            header("Location: /phpietadmin/auth/login");
        }
    }

    public function vg() {
        $session = $this->model('Session');
        $session->setUsername($_SESSION['username']);
        $session->setPassword($_SESSION['password']);

        if ($session->check()) {
            $lvm = $this->model('Lvmdisplay');
            $data = $lvm->get_lvm_data('vgs');
            $std = $this->model('Std');

            $this->view('header');
            $this->view('menu');
            $this->view('table', $data);
            $data = $std->get_service_status();
            $this->view('footer', $data);
        } else {
            header("Location: /phpietadmin/auth/login");
        }
    }

    public function lv() {
        $session = $this->model('Session');
        $session->setUsername($_SESSION['username']);
        $session->setPassword($_SESSION['password']);

        if ($session->check()) {
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
        } else {
            header("Location: /phpietadmin/auth/login");
        }
    }

    public function initiators() {
        $session = $this->model('Session');
        $session->setUsername($_SESSION['username']);
        $session->setPassword($_SESSION['password']);

        if ($session->check()) {
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
        } else {
            header("Location: /phpietadmin/auth/login");
        }
    }

    public function targets() {
        $session = $this->model('Session');
        $session->setUsername($_SESSION['username']);
        $session->setPassword($_SESSION['password']);

        if ($session->check()) {
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
        } else {
            header("Location: /phpietadmin/auth/login");
        }
    }

}
?>