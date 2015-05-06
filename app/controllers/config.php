<?php
    class config extends Controller {
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
            $std = $this->model('Std');
            $database = $this->model('Database');

            $this->view('header');
            $this->view('menu');

            $result = $database->query('select option, value from config');

            $this->view('table', $result);

            $data = $std->get_service_status();
            $this->view('footer', $data);
        }

        public function users() {
            $std = $this->model('Std');
            $database = $this->model('Database');

            $this->view('header');
            $this->view('menu');

            $data = $std->get_service_status();
            $this->view('footer', $data);
        }
    }
?>