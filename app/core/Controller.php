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
        public $exec;
        public $parser;
        public $models;

        /* This function creates all necessary models */
        public function create_models($controller) {
            // These models are always needed
            $this->database = $this->model('Database');
            $this->std = $this->model('Std', array('database' =>$this->database));
            $this->exec = $this->model('Exec', array('database' =>$this->database));
            $this->session = $this->model('Session', array('database' => $this->database, 'std' => $this->std));
            $this->regex = $this->model('Regex');
            $this->parser = $this->model('Parser');

            // sometimes models are needed inside other models
            $models = array (
                'std' => $this->std,
                'database' => $this->database,
                'exec' => $this->exec,
                'session' => $this->session,
                'regex' => $this->regex,
                'parser' => $this->parser
            );

            $this->models = $models;

            // Different models for specific controllers
            if ($controller == 'overview') {
                $this->disks = $this->model('Disks', $models);
                $this->ietvolumes = $this->model('IetVolumes', $models);
                $this->ietsessions = $this->model('IetSessions', $models);
                $this->lvm = $this->model('Lvmdisplay', $models);
            } else if ($controller == 'permission') {
                $this->ietpermissions = $this->model('Ietpermissions', $models);
                $this->ietadd = $this->model('Ietaddtarget', $models);
                $this->ietdelete = $this->model('Ietdelete', $models);
            } else if ($controller == 'targets') {
                $this->ietadd = $this->model('Ietaddtarget', $models);
                $this->ietdelete = $this->model('Ietdelete', $models);
                $this->lvm = $this->model('Lvmdisplay', $models);
                $this->ietsessions = $this->model('IetSessions', $models);
                $this->ietsettings = $this->model('Settings', $models);
                $this->ietpermissions = $this->model('Ietpermissions', $models);
            } else if ($controller == 'lvm') {
                $this->lvm = $this->model('Lvmdisplay', $models);
            }
        }

        public function check_loggedin($controller) {
            if (isset($_SESSION['username'], $_SESSION['password'])) {
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

        public function model($model, $other_models = '') {
            $file = '../app/models/' . $model . '.php';
            if (file_exists($file)) {
                require_once $file;
                if (!empty($other_models)) {
                    return new $model($other_models);
                } else {
                    return new $model();
                }
            } else {
                return false;
            }
        }

        public function view($view, $data = []) {
            $file = '../app/views/' . $view . '.php';
            if (file_exists($file)) {
                require_once $file;
            } else {
                echo 'File ' . htmlspecialchars($file) . ' not found!';
            }
        }

        //public function scriptview($data = []) {
        //    require_once '../app/views/scripts.php';
        //}
    }