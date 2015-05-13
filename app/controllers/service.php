<?php
    class Service extends Controller {
        public function __construct() {
            $this->create_models();
            $this->session->setUsername($_SESSION['username']);
            $this->session->setPassword($_SESSION['password']);

            // Check if user is logged in
            if (!$this->session->check()) {
                header("Location: /phpietadmin/auth/login");
                // Die in case browser ignores header redirect
                die();
            }
        }

        public function index() {
            $this->view('header');
            $this->view('menu');

            if (isset($_POST['start'])) {
                $output = shell_exec($this->database->get_config('sudo') . " " . $this->database->get_config('service') . " " . $this->database->get_config('servicename') . " start");
            } else if (isset($_POST['stop'])) {
                $output = shell_exec($this->database->get_config('sudo') . " " . $this->database->get_config('service') . " " . $this->database->get_config('servicename') . " stop");
            } else if (isset($_POST['restart'])) {
                $output = shell_exec($this->database->get_config('sudo') . " " . $this->database->get_config('service') . " " . $this->database->get_config('servicename') . " restart");
            }

            if (!empty($output)) {
                $this->view('service', $output);
            } else {
                $this->view('service');
            }

            $return = $this->std->get_service_status();

            if ($return[1] != 0) {
                $this->view('message', "Service is not running!");
            } else {
                $this->view('message', "Service is running!");
            }


            $this->view('footer', $return);
        }

        public function status() {
            $this->view('ietdstatus',$this->std->get_service_status());
        }

    }
?>