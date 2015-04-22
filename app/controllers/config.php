<?php
    class config extends Controller {
        public function index() {
            $session = $this->model('Session');
            $session->setUsername($_SESSION['username']);
            $session->setPassword($_SESSION['password']);

            if ($session->check()) {
                $std = $this->model('Std');
                $database = $this->model('Database');

                $this->view('header');
                $this->view('menu');

                $result = $database->query('select option, value from config');

                $this->view('table', $result);

                $data = $std->get_service_status();
                $this->view('footer', $data);
            } else {
                header("Location: /phpietadmin/auth/login");
            }
        }

        public function users() {
            $session = $this->model('Session');
            $session->setUsername($_SESSION['username']);
            $session->setPassword($_SESSION['password']);

            if ($session->check()) {
                $std = $this->model('Std');
                $database = $this->model('Database');

                $this->view('header');
                $this->view('menu');

                $data = $std->get_service_status();
                $this->view('footer', $data);
            } else {
                header("Location: /phpietadmin/auth/login");
            }
        }
    }
?>