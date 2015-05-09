<?php
    class Auth extends Controller {
        public function login() {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $session = $this->model('Session');

                // Create pw hash
                $PWHASH = hash('sha256', $_POST['password']);

                // Save username and hash in session var
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['password'] = $PWHASH;

                // Write username and hash to session object
                $session->setUsername($_POST['username']);
                $session->setPassword($PWHASH);

                if($session->check()) {
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
            $session = $this->model('Session');
            if (!empty($_SESSION['username']) && !empty($_SESSION['password'])) {
                $session->setUsername($_SESSION['username']);
                $session->setPassword($_SESSION['password']);
                if ($session->check()) {
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