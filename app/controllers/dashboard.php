<?php
    class Dashboard extends Controller {
        public function __construct() {
            $this->create_models();
            $this->check_loggedin($this->session);
        }

        public function index() {
            $this->view('header');
            $this->view('menu');
            $this->view('dashboard');
            $this->view('footer', $this->std->get_service_status());
        }
    }
?>