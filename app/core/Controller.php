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

        public function check_loggedin($controller) {
            if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
                $this->session->setUsername($_SESSION['username']);
                $this->session->setPassword($_SESSION['password']);

                // Check if user is logged in
                // Checks for correct password, browser agent, source_ip and if the sessions is expired
                // if everything is ok, the session timestamp will be updated
                if (!$this->session->check_password() or !$this->session->check_other_params($this->database->get_sessions_by_username($_SESSION['username'])) or time() - $_SESSION['timestamp'] > intval($this->database->get_config('idle') * 60)) {
                    $this->session->destroy_session($this->std, $this->database);
                } else {
                    // Update time
                    // Don't update if controller is connection
                    // A connection to this controller is always established,
                    // even if the session is expired, but the site is still loaded
                    if ($controller !== 'connection') {
                        $time = time();
                        $this->session->setTime($time);
                    }
                }
            } else {
                if ($this->std->IsXHttpRequest()) {
                    echo 'false';
                } else {
                    header("Location: /phpietadmin/auth/login");
                }
                die();
            }
        }

        public function model($model) {
            $file = '../app/models/' . $model . '.php';
            if (file_exists($file)) {
                require_once $file;
                return new $model();
            } else {
                return false;
            }
        }

        public function view($view, $data = []) {
            $file = '../app/views/' . $view . '.php';
            if (file_exists($file)) {
                require_once $file;
            } else {
                echo 'File ' . $file . ' not found!';
            }
        }

        //public function scriptview($data = []) {
        //    require_once '../app/views/scripts.php';
        //}
    }
?>