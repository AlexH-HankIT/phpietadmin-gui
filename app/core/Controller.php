<?php
    // create global objects contaning all needed models here

    class Controller {
        // Define global vars
        public $database;
        public $disks;
        public $ietadd;
        public $ietsessions;
        public $ietvolumes;
        public $regex;
        public $std;
        public $session;
        public $lvm;
        public $ietpermissions;

        public function create_models() {
            $this->database = $this->model('Database');
            $this->disks = $this->model('Disks');
            $this->ietadd = $this->model('Ietaddtarget');
            $this->ietsessions = $this->model('IetSessions');
            $this->ietvolumes = $this->model('IetVolumes');
            $this->regex = $this->model('Regex');
            $this->session = $this->model('Session');
            $this->std = $this->model('Std');
            $this->lvm = $this->model('Lvmdisplay');
            $this->ietpermissions = $this->model('Ietpermissions');
        }

        public function model($model) {
            require_once '../app/models/' . $model . '.php';
            return new $model();
        }

        public function view($view, $data = []) {
            require_once '../app/views/' . $view . '.php';
        }
    }
?>