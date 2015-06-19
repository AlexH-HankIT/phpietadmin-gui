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

        /* This function creates all necessary models */
        public function create_models($controller) {
            // These models are always needed
            $this->std = $this->model('Std');
            $this->session = $this->model('Session');
            $this->database = $this->model('Database');

            // Different models for specific controllers
            if ($controller == 'overview') {
                $this->disks = $this->model('Disks');
                $this->ietvolumes = $this->model('IetVolumes');
                $this->ietsessions = $this->model('IetSessions');
                $this->lvm = $this->model('Lvmdisplay');
            } else if ($controller == 'permission') {
                $this->ietpermissions = $this->model('Ietpermissions');
                $this->ietadd = $this->model('Ietaddtarget');
                $this->ietdelete = $this->model('Ietdelete');
            } else if ($controller == 'targets') {
                $this->ietadd = $this->model('Ietaddtarget');
                $this->ietdelete = $this->model('Ietdelete');
                $this->lvm = $this->model('Lvmdisplay');
                $this->ietsessions = $this->model('IetSessions');
            } else if ($controller == 'lvm') {
                $this->lvm = $this->model('Lvmdisplay');
            }
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