<?php
    class Auth extends Controller {
        public function login() {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                // Create pw hash
                $pwhash = hash('sha256', $_POST['password']);

                // Save username and hash in session var
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['password'] = $pwhash;

                // Write username and hash to session object
                $this->session->setUsername($_POST['username']);
                $this->session->setPassword($pwhash );
                $this->session->setTime();

                if($this->session->check()) {
                    header("Location: /phpietadmin");
                    die();
                } else {
                    $this->view('message', 'Wrong username or password!');
                    header( "refresh:2;url=/phpietadmin/auth/login" );
                    die();
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
                    die();
                }
            } else {
                header("Location: /phpietadmin/auth/login");
                die();
            }
        }
    }
?>