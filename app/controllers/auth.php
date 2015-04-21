<?php
    class Auth extends Controller {
        public function login() {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $session = $this->model('Session');

                $session->setUsername($_POST['username']);
                $session->setPassword($_POST['password']);

                if($session->check()) {
                    header("Location: /phpietadminv02/home");
                } else {
                    $this->view('message', 'Wrong username or password!');
                }
            } else {
                $this->view('header', "login");
                $this->view('session');
            }
        }

        public function logout(){
            $session = $this->model('Session');
            if (!empty($_SESSION['username']) && !empty($_SESSION['password'])) {
                $session->setUsername($_SESSION['username']);
                $session->setPassword($_SESSION['password']);
                if ($session->check()) {
                    session_destroy();
                    $this->view('message', 'Logout successful!');
                    header( "refresh:2;url=/phpietadminv02/auth/login" );
                }
            } else {
                header("Location: /phpietadminv02/auth/login");
            }
        }
    }
?>