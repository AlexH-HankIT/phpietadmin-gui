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
            $this->database = $this->generic_model('Database');
            $this->std = $this->generic_model('Std');
            $this->session = $this->generic_model('Session');
            $this->logging = $this->generic_model('logging\Logging');
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
                } else if (time() - $_SESSION['timestamp'] > intval($this->database->get_config('idle') * 60)) {
                    $this->logging->log_access_result('The user was ' . $_SESSION['username'] . ' logged out due to inactivity!', 1, 'timeout_logout', __METHOD__);
                    $this->session->destroy_session();
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

        public function lv_model($vg_name, $lv_name) {
            require_once __DIR__ . '/../models/autoloader.php';
            return new phpietadmin\app\models\lvm\lv\Lv($vg_name, $lv_name);
        }

        public function vg_model($vg_name = false) {
            require_once __DIR__ . '/../models/autoloader.php';
            return new phpietadmin\app\models\lvm\vg\Vg($vg_name);
        }

        public function pv_model($pv_name = false) {
            require_once __DIR__ . '/../models/autoloader.php';
            return new phpietadmin\app\models\lvm\pv\Pv($pv_name);
        }

        public function target_model($iqn = '') {
            require_once __DIR__ . '/../models/autoloader.php';
            return new phpietadmin\app\models\target\Target($iqn);
        }

        public function generic_model($model) {
            require_once __DIR__ . '/../models/autoloader.php';
            $model = 'phpietadmin\\app\\models\\' . $model;
            return new $model();
        }

        public function ietuser_model($username) {
            require_once __DIR__ . '/../models/autoloader.php';
            return new phpietadmin\app\models\Ietuser($username);
        }

        public function view($view, $data = []) {
            $file = __DIR__ . '/../../app/views/' . $view . '.php';
            if (file_exists($file)) {
                require_once $file;
            } else {
                echo 'File ' . htmlspecialchars($file) . ' not found!';
            }
        }
    }