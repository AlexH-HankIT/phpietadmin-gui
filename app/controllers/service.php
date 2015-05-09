<?php
    class Service extends Controller {
        public function __construct() {
            $session = $this->model('Session');
            $session->setUsername($_SESSION['username']);
            $session->setPassword($_SESSION['password']);

            // Check if user is logged in
            if (!$session->check()) {
                header("Location: /phpietadmin/auth/login");
                // Die in case browser ignores header redirect
                die();
            }
        }

        public function index() {
            $database = $this->model('Database');
            $std = $this->model('Std');

            $this->view('header');
            $this->view('menu');

            if (isset($_POST['start'])) {
                $output = shell_exec($database->get_config('sudo') . " " . $database->get_config('service') . " " . $database->get_config('servicename') . " start");
            } else if (isset($_POST['stop'])) {
                $output = shell_exec($database->get_config('sudo') . " " . $database->get_config('service') . " " . $database->get_config('servicename') . " stop");
            } else if (isset($_POST['restart'])) {
                $output = shell_exec($database->get_config('sudo') . " " . $database->get_config('service') . " " . $database->get_config('servicename') . " restart");
            }

            if (!empty($output)) {
                $this->view('service', $output);
            } else {
                $this->view('service');
            }

            $return = $std->get_service_status();

            if ($return[1] != 0) {
                $this->view('message', "Service is not running!");
            } else {
                $this->view('message', "Service is running!");
            }


            $this->view('footer', $return);
        }

        public function status() {
            $std = $this->model('Std');
            $return = $std->get_service_status();
            $this->view('ietdstatus', $return);
        }

    }
?>