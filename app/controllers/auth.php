<?php
    class Auth extends Controller {
        /**
         *
         * Handles the user login
         *
         * @return     void
         *
         */
        public function login() {
            if (isset($_POST['username'], $_POST['password'])) {
                // Create pw hash
                $pwhash = hash('sha256', $_POST['password']);

                // Save username and hash in session var
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['password'] = $pwhash;

                // Write username and hash to session object
                $this->session->setUsername($_POST['username']);
                $this->session->setPassword($pwhash);
                $login_time = time();
                $this->session->setTime($login_time);

                if($this->session->check_password()) {
                    // check here if session with user $_POST['username'] has already started
                    $data = $this->database->get_sessions_by_username($_POST['username']);

                    if (empty($data)) {
                        // redirect to dashboard page
                        header("Location: /phpietadmin/dashboard");

                        // add session data to database
                        $this->database->add_session(session_id(), $_POST['username'], $login_time, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
                    } else {
                        // todo: add override option
                        $this->view('message', 'User ' . $_POST['username'] . ' is already logged in from ' . $data['source_ip']);
                    }
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

        /**
         *
         * Handles the logout
         *
         * @return      void
         *
         */
        public function logout(){
            if (!$this->std->mempty($_SESSION['username'], $_SESSION['password'])) {
                $this->session->setUsername($_SESSION['username']);
                $this->session->setPassword($_SESSION['password']);
                if ($this->session->check_password()) {
                   $this->session->destroy_session($this->std, $this->database);
                }
            } else {
                header("Location: /phpietadmin/auth/login");
                die();
            }
        }
    }