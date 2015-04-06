<?php
class Home extends Controller {
    public function index() {
        $this->view('header');
        $this->view('menu');
        $this->view('home/index');
        $this->view('footer');
    }
}
?>