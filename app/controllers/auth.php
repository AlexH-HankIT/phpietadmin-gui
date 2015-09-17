<?php namespace phpietadmin\app\controllers;
use phpietadmin\app\core;

    class Auth extends core\BaseController {
        /**
         *
         * Handles the user login
         *
         * @return     void
         *
         */
        public function login() {
         	// first login
            if (file_exists(__DIR__ . '/../../install/auth.xml')) {
                // password1 = password
                // password2 = password check
                if (isset($_POST['username'], $_POST['password1'], $_POST['password2'], $_POST['auth_code']) && !$this->base_model->std->mempty($_POST['username'], $_POST['password1'], $_POST['password2'], $_POST['auth_code'])) {
					$user = $this->model('User', $_POST['username']);
					$user->add_first_user($_POST['auth_code'], $_POST['password1'], $_POST['password2']);
                } else {
                    // first login, display menu to add password
                    $this->view('login/first_signin');
                }
            } else {
                if (isset($_POST['username'], $_POST['password']) && !$this->base_model->std->mempty($_POST['username'], $_POST['password'])) {
                    $session = $this->model('Session', $_POST['username']);

					$return = $session->login($_POST['password']);

					if ($return['login'] === true) {
						header("Location: /phpietadmin/dashboard");
					} else {
						if ($return['status'] === 'already') {
							$this->view('login/overwrite', 'User is already logged in!');
						} else if ($return['status'] == 'wrong') {
							$this->view('message', 'Wrong username or password!');
							header("refresh:2;url=/phpietadmin/auth/login");
							die();
						}
					}
                } else if (isset($_POST['overwrite'])) {
					$session = $this->model('Session');
					if (isset($_SESSION['overwrite'])) {
						$session->overwrite();
						header("Location: /phpietadmin/dashboard");
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
        public function logout() {
			$session = $this->model('Session');
			if (isset($_SESSION['username'], $_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
				$session->logout();
			}
			header("Location: /phpietadmin/auth/login");;
			die();
        }
    }