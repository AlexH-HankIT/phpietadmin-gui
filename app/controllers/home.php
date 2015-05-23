<?php
class Home extends Controller {
    public function __construct() {
        // Creates all available models
        $this->create_models();
        $this->check_loggedin($this->session);
    }

    public function index() {
        $this->view('header');
        $this->view('menu');
        $this->view('home');

        $this->view('footer', $this->std->get_service_status());
    }
}
?>