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
			if (isset($_POST['username'], $_POST['password']) && !$this->base_model->std->mempty($_POST['username'], $_POST['password'])) {
				// filter user input
				$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
				$password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);

				// create session object
				$session = $this->model('Session', $username);

				// login user
				$return = $session->login($password);

				if (isset($_SESSION['overwrite']) && $_SESSION['overwrite'] === true) {
					// update session id in user table
					// redirect user to dashboard
				} else {
					if ($return['login'] === true) {
						if ($return['status'] === 'ok') {
							header("Location: /phpietadmin/dashboard");
						} else if ($return['status'] === 'already') {
							$this->view('login/overwrite', 'User is already logged in!');
						} else {
							die();
						}
					} else {
						$this->view('message', 'Wrong username or password!');
						header("refresh:2;url=/phpietadmin/auth/login");
						die();
					}
				}
			} else if (isset($_POST['overwrite'])) {
				if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
					$_SESSION['overwrite'] = true;
				}
			} else {
				$this->view('login/signin');
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
			if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
				$session->logout();
			}
			header("Location: /phpietadmin/auth/login");;
			die();
        }
    }