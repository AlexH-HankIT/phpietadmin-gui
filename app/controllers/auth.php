<?php
    class Auth extends Controller {
        var $session;

        public function __construct() {
            $this->create_models();
        }

        private function create_models() {


            $this->session = $this->model('Session');
        }


        public function login() {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                // Create pw hash
                $PWHASH = hash('sha256', $_POST['password']);

                // Save username and hash in session var
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['password'] = $PWHASH;

                // Write username and hash to session object
                $this->session->setUsername($_POST['username']);
                $this->session->setPassword($PWHASH);

                if($this->session->check()) {
                    header("Location: /phpietadmin/home");
                } else {
                    $this->view('message', 'Wrong username or password!');
                    header( "refresh:2;url=/phpietadmin/auth/login" );
                }
            } else {
                $this->view('header', "login");
                $this->view('signin');
            }
        }

        public function logout(){
            if (!empty($_SESSION['username']) && !empty($_SESSION['password'])) {
                $this->session->setUsername($_SESSION['username']);
                $this->session->setPassword($_SESSION['password']);
                if ($this->session->check()) {
                    session_unset();
                    session_destroy();
                    header("Location: /phpietadmin/auth/login");
                }
            } else {
                header("Location: /phpietadmin/auth/login");
            }
        }
    }
?>