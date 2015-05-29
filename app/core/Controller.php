<?php
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
        public $ietdelete;

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
            $this->ietdelete = $this->model('Ietdelete');
        }

        public function check_loggedin($session) {
            if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
                $session->setUsername($_SESSION['username']);
                $session->setPassword($_SESSION['password']);

                // Check if user is logged in
                if (!$session->check()) {
                    header("Location: /phpietadmin/auth/login");
                    // Die in case browser ignores header redirect
                    die();
                }
            } else {
                header("Location: /phpietadmin/auth/login");
            }
        }

        public function check_logged_in_service_running($session) {
            if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
                $session->setUsername($_SESSION['username']);
                $session->setPassword($_SESSION['password']);

                // Check if user is logged in
                if (!$session->check()) {
                    header("Location: /phpietadmin/auth/login");
                    // Die in case browser ignores header redirect
                    die();
                } else {
                    // Check if ietd service is running
                    $data = $this->std->get_service_status();
                    if ($data[1] !== 0) {
                        $this->view('header');
                        $this->view('menu');
                        $this->view('message', "Error - ietd service is not running!");
                        $this->view('footer', $data);
                        die();
                    }
                }
            } else {
                header("Location: /phpietadmin/auth/login");
            }
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