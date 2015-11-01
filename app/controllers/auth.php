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
			if (isset($_POST['username'], $_POST['password1'])) {
				// filter user input
				$username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
				$password1 = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING);

				if (file_exists('/usr/share/phpietadmin/install/auth')) {
					if (isset($_POST['password2'], $_POST['auth_code'])) {
						$password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING);
						$auth_code = filter_input(INPUT_POST, 'auth_code', FILTER_SANITIZE_STRING);
						$user = $this->model('User', $username);
						$user->addFirstUser($auth_code, $password1, $password2);
					}
				}

				// create session object
				$session = $this->model('Session', $username);

				// login user
				$return = $session->login($password1);

				if ($return === true) {
					header("Location: /phpietadmin/dashboard");
					die();
				} else {
					$this->view('message', 'Wrong username or password!');
					header("refresh:2;url=/phpietadmin/auth/login");
					die();
				}
			} else {
				if (file_exists('/usr/share/phpietadmin/install/auth')) {
					$this->view('login/first_signin');
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
			$session->logout();
			header("Location: /phpietadmin/auth/login");;
			die();
        }
    }