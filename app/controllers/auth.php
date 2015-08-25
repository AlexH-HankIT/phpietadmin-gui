<?php
    class Auth extends Controller {
        /**
         *
         * Handles the user login
         *
         * @return     void
         *
         */
        public function login()
        {
            // first login
            if (file_exists(__DIR__ . '/../../install/auth.xml')) {
                // password1 = password
                // password2 = password check
                // check this on the server to prevent user from sending false data
                if (isset($_POST['username'], $_POST['password1'], $_POST['password2'], $_POST['auth_code']) && !$this->std->mempty($_POST['username'], $_POST['password1'], $_POST['password2'], $_POST['auth_code'])) {
                    $this->logging->log_access_result('The user ' . $_POST['username'] . ' was successfully authenticated!', 0, 'first_login', __METHOD__, true);

                    $userdata = $this->database->get_phpietadmin_user();

                    // validate user table is empty to prevent this from working if there are already users
                    if ($userdata === false) {
                        // parse xml file
                        $auth_code = simplexml_load_file(__DIR__ . '/../../install/auth.xml');

                        if ($auth_code === $_POST['auth_code']) {
                            if ($_POST['password1'] === $_POST['password2']) {
                                // create hash from password
                                // insert hash and user into database
                                $return = $this->database->add_login_user($this->std->hash_sha256_string($_POST['password1']), $_POST['username']);

                                if ($return != 0) {
                                    $this->logging->log_action_result('An error occurred while adding the user ' . $_POST['username'], array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                                } else {
                                    // log success
                                    $this->logging->log_action_result('The user ' . $_POST['username'] . ' was successfully added to the database', array('result' => 0, 'code_type' => 'intern'), __METHOD__);

                                    // delete auth_code file
                                    if(unlink(__DIR__ . '/../../install/auth.xml') === false) {
                                        $this->logging->log_action_result('Could not delete the file ' . __DIR__ . '/../../install/auth.xml Something is wrong with your permissions. Please delete it manually', array('result' => 1, 'code_type' => 'intern'), __METHOD__);
                                    }
                                }
                                header("Location: /phpietadmin/auth/login");
                                die();
                            } else {
                                $this->logging->log_access_result('Passwords do not match!', 1, 'first_login', __METHOD__);
                            }
                        } else {
                            $this->logging->log_access_result('The specified authentication code is incorrect!', 1, 'first_login', __METHOD__);
                        }
                    } else {
                        $this->logging->log_access_result('The first user is already configured!', 1, 'first_login', __METHOD__);
                    }
                    $this->view('message', array('message' => 'An error occured. Please check the phpietadmin access and action log file.', 'type' => 'warning'));
                } else {
                    // first login, display menu to add password
                    $this->view('login/first_signin');
                }
            } else {
                if (isset($_POST['username'], $_POST['password']) && !$this->std->mempty($_POST['username'], $_POST['password'])) {
                    // Create pw hash
                    $pwhash = $this->std->hash_sha256_string($_POST['password']);

                    // Save username and hash in session var
                    $_SESSION['username'] = $_POST['username'];
                    $_SESSION['password'] = $pwhash;

                    // Write username and hash to session object
                    $this->session->setUsername($_POST['username']);
                    $this->session->setPassword($pwhash);
                    $login_time = time();
                    $this->session->setTime($login_time);

                    if ($this->session->check_password()) {
                        // check here if session with user $_POST['username'] has already started
                        $data = $this->database->get_sessions_by_username($_POST['username']);

                        if (empty($data) || $data === false) {
                            // redirect to dashboard page
                            $this->logging->log_access_result('The user ' . $_POST['username'] . ' was successfully logged in!', 0, 'login', __METHOD__);
                            header("Location: /phpietadmin/dashboard");

                            // add session data to database
                            $this->database->add_session(session_id(), $_POST['username'], $login_time, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);
                        } else {
                            $this->logging->log_access_result('The user ' . $_POST['username'] . ' is already logged in!', 1, 'login', __METHOD__);
                            $this->view('login/override', 'User ' . $_POST['username'] . ' is already logged in from ' . $data['source_ip']);
                        }
                    } else {
                        $this->logging->log_access_result('The user ' . $_POST['username'] . ' was not logged in. Wrong password or username!', 1, 'login', __METHOD__);
                        $this->view('message', 'Wrong username or password!');
                        header("refresh:2;url=/phpietadmin/auth/login");
                        die();
                    }
                } else if (isset($_POST['override'])) {
                    $this->session->setUsername($_SESSION['username']);
                    $this->session->setPassword($_SESSION['password'] );

                    if ($this->session->check_password()) {

                        $login_time = time();
                        $this->session->setTime($login_time);

                        // get data from session which should be overwritten
                        $data = $this->database->get_sessions_by_username($_SESSION['username']);

                        // delete session from database
                        $this->database->delete_session($data['session_id'], $_SESSION['username']);

                        // add new session to database
                        $this->database->add_session(session_id(), $_SESSION['username'], $login_time, $_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT']);

                        $this->logging->log_access_result('The session ' . $data['session_id'] . ' of the user ' . $_SESSION['username'] . ' was overwritten!', 0, 'login', __METHOD__);

                        // redirect to dashboard
                        header("Location: /phpietadmin/dashboard");
                    } else {
                        $this->logging->log_access_result('The user ' . $_POST['username'] . ' was not logged in. Wrong password or username!', 1, 'login', __METHOD__);
                        $this->view('message', 'Wrong username or password!');
                        header("refresh:2;url=/phpietadmin/auth/login");
                        die();
                    }
                } else {
                    $this->view('login/signin');
                }
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
                    $this->logging->log_access_result('The user ' . $_SESSION['username'] . ' was successfully logged out!', 0, 'logout', __METHOD__);
                    $this->session->destroy_session();
                }
            } else {
                header("Location: /phpietadmin/auth/login");
                die();
            }
        }
    }