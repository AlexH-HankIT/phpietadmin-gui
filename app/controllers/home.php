<?php
class Home extends Controller {
    public function __construct() {
        // Creates all available models
        $this->create_models();

        if (isset($_SESSION['username']) && isset($_SESSION['password'])) {
            $this->session->setUsername($_SESSION['username']);
            $this->session->setPassword($_SESSION['password']);
        }

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
        $this->view('home');

        $this->view('footer', $this->std->get_service_status());
    }
}
?>