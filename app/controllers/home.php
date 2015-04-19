<?php
class Home extends Controller {
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