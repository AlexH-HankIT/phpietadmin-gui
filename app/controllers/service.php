<?php
    class Service extends Controller {
        public function index() {
            $session = $this->model('Session');
            $session->setUsername($_SESSION['username']);
            $session->setPassword($_SESSION['password']);

            if ($session->check()) {
                $database = $this->model('Database');
                $std = $this->model('Std');

                $this->view('header');
                $this->view('menu');

                if (isset($_POST['start'])) {
                    $output = shell_exec($database->getConfig('sudo') . " " .   $database->getConfig('service') . " " . $database->getConfig('servicename') . " start");
                } else if (isset($_POST['stop'])) {
                    $output = shell_exec($database->getConfig('sudo') . " " .   $database->getConfig('service') . " " . $database->getConfig('servicename') . " stop");
                } else if (isset($_POST['restart'])) {
                    $output = shell_exec($database->getConfig('sudo') . " " .   $database->getConfig('service') . " " . $database->getConfig('servicename') . " restart");
                }

                $this->view('service');

                $return = $std->get_service_status();

                if ($return[1]!=0) {
                    $this->view('message', "Service is not running!");
                } else {
                    $this->view('message', "Service is running!");
                }

                $this->view('footer', $return);
            } else {
                header("Location: /phpietadmin/auth/login");
            }
        }
    }
?>