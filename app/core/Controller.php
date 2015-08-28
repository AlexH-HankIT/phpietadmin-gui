<?php
    class Controller {
        // Define global vars
        public $database;
        public $std;
        public $session;
        public $logging;

        /* This function creates all necessary models */
        public function create_models() {
            // These models are always needed
            $this->database = $this->model('Database');
            $this->std = $this->model('Std');
            $this->logging = $this->model('logging\Logging');
            $this->session = $this->model('Session');
        }

        public function check_loggedin($controller) {
            if (isset($_SESSION['username'], $_SESSION['password'])) {
                $this->session->setUsername($_SESSION['username']);
                $this->session->setPassword($_SESSION['password']);

                // Check if user is logged in
                // Checks for correct password, browser agent, source_ip and if the sessions is expired
                // if everything is ok, the session timestamp will be updated
                if (!$this->session->check_password() or !$this->session->check_other_params($this->database->get_sessions_by_username($_SESSION['username']))) {
                    // ToDo: log something here..
                    $this->session->destroy_session();
                } else {
                    $idle_value = intval($this->database->get_config('idle')['value']);

                    if ($idle_value !== 0) {
                        if (time() - $_SESSION['timestamp'] > $idle_value * 60) {
                            $this->logging->log_access_result('The user was ' . $_SESSION['username'] . ' logged out due to inactivity!', 1, 'timeout_logout', __METHOD__);
                            $this->session->destroy_session();

                            // only update the timestamp if the inactivity logout feature is actually enabled
                            // Update time
                            // Don't update if controller is connection
                            // A connection to this controller is always established,
                            // even if the session is expired, but the site is still loaded
                            if ($controller !== 'connection') {
                                $time = time();
                                $this->session->setTime($time);
                            }
                        }
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

        // arg1 = model name with namespace from phpietadmin\app\models\
        // arg2 = arg1 for model
        // arg 3 = arg 2 for model
        public function model() {
            require_once __DIR__ . '/../models/autoloader.php';
            $arg = func_get_args();
            $model = 'phpietadmin\\app\\models\\' . $arg[0];

            if (func_num_args() === 3) {
                return new $model($arg[1], $arg[2]);
            } else if (func_num_args() === 2) {
                return new $model($arg[1]);
            } else {
                return new $model();
            }
        }

        public function view($view, $data = []) {
            if (file_exists(__DIR__ . '/../../app/views/' . $view . '.php')) {
                require_once __DIR__ . '/../../app/views/' . $view . '.php';
            } else if (file_exists(__DIR__ . '/../app/views/' . $view . '.php')) {
                require_once __DIR__ . '/../app/views/' . $view . '.php';
            } else {
                echo 'File ' . htmlspecialchars($view) . ' not found!';
            }
        }
    }