<?php
class Overview extends Controller {
    public $a_config;

    public function index() {
        $this->view('header');
        $this->view('menu');
        $this->view('overview/index');
        $this->view('footer');
    }

    public function disks() {
        $disks = $this->model('Disks');
        $data = $disks->getDisks();

        $this->view('header');
        $this->view('menu');
        $this->view('overview/index');
        if (!empty($data)) {
            $this->view('table', $data);
        } else {
            $this->view('error', "Boeser Fehler");
        }
        $this->view('footer');
    }

    public function ietvolumes() {
        $ietvolumes = $this->model('IetVolumes');
        $volumes = $ietvolumes->getIetVolumes();

        $this->view('header');
        $this->view('menu');
        $this->view('overview/index');
        if ($volumes == 1) {
            $this->view('error', "The ietvolumes file was not found. Please check the path!");
        } elseif ($volumes == 2) {
            $this->view('error', "The ietvolumes file is empty.");
        } else {
            $this->view('table', $volumes);
        }
        $this->view('footer');
    }

    public function ietsessions() {
        $ietsessions = $this->model('IetSessions');
        $sessions = $ietsessions->getIetSessions();

        $this->view('header');
        $this->view('menu');
        $this->view('overview/index');
        if ($sessions == 1) {
            $this->view('error', "The ietsessions file was not found. Please check the path!");
        } elseif ($sessions == 2) {
            $this->view('error', "The ietsessions file is empty.");
        } else {
            $this->view('table', $sessions);
        }
        $this->view('footer');
    }

    public function pv() {
        $lvm = $this->model('Lvmdisplay');
        $data = $lvm->get_lvm_data('pvs');

        $this->view('header');
        $this->view('menu');
        $this->view('overview/index');
        $this->view('table', $data);
        $this->view('footer');
    }

    public function vg() {

    }

    public function lv() {

    }

    public function initiators() {

    }

    public function targets() {

    }

}
?>