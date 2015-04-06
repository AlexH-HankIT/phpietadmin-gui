<?php
class Overview extends Controller {
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
        if ($volumes !== 1) {
            $this->view('table', $volumes);
        } else {
            $this->view('error', "Boeser Fehler");
        }
        $this->view('footer');
    }

    public function ietsessions() {
        $ietsessions = $this->model('IetSessions');
        $sessions = $ietsessions->getIetSessions();

        $this->view('header');
        $this->view('menu');
        $this->view('overview/index');
        if ($sessions !== 1) {
            $this->view('table', $sessions);
        } else {
            $this->view('error', "Boeser Fehler");
        }
        $this->view('footer');
    }

    public function pv() {

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