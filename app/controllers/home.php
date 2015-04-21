<?php
class Home extends Controller {
    public function index() {
        $session = $this->model('Session');
        $session->setUsername($_SESSION['username']);
        $session->setPassword($_SESSION['password']);

        if ($session->check()) {
            $std = $this->model('Std');
            $this->view('header');
            $this->view('menu');
            $this->view('home');
            $data = $std->get_service_status();
            $this->view('footer', $data);
        } else {
            header("Location: /phpietadmin/auth/login");
        }
    }
}
?>