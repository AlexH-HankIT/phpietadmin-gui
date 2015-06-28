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
        public $ietsettings;

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
                $this->ietsettings = $this->model('Settings');
            } else if ($controller == 'lvm') {
                $this->lvm = $this->model('Lvmdisplay');
            }
        }

        public function check_loggedin($controller, $method) {
            if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
                $this->session->setUsername($_SESSION['username']);
                $this->session->setPassword($_SESSION['password']);
                $time_till_logout = $this->database->get_config('idle') * 60;

                // Check if user is logged in
                if (!$this->session->check()) {
                    if ($this->std->IsXHttpRequest()) {
                        echo false;
                        die();
                    } else {
                        header("Location: /phpietadmin/auth/login");
                        // Die in case browser ignores header redirect
                        die();
                    }
                } elseif (time() - $_SESSION['timestamp'] > $time_till_logout) {
                    if ($this->std->IsXHttpRequest()) {
                        echo false;
                        die();
                    } else {
                        header("Location: /phpietadmin/auth/login");
                        // Die in case browser ignores header redirect
                        die();
                    }
                } else {
                    // Update time
                    // Don't update if controller is service/status
                    // A connection to this controller is always established,
                    // even if the session is expired, but the site is still loaded
                    if ($controller !== 'service' && $method !== 'status') {
                        $this->session->setTime();
                    }
                }
            } else {
                if ($this->std->IsXHttpRequest()) {
                    echo false;
                    die();
                } else {
                    header("Location: /phpietadmin/auth/login");
                    // Die in case browser ignores header redirect
                    die();
                }
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