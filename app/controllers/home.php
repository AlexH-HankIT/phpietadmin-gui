<?php
class Home extends Controller {
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
        $this->view('header');
        $this->view('menu');
        $this->view('home');
        $data = $std->get_service_status();
        $this->view('footer', $data);
    }
}
?>