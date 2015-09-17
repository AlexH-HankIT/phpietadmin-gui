<?php namespace phpietadmin\app\models;

    class Session_old extends User {
		private $status;

        public function __construct($username, $password) {
			if ($username === false && $password === false) {
				if ($this->status === true) {
					session_start();
				} else {
					//
				}
			} else {
				parent::__construct($username);

				$return = $this->compare_passwords($password);

				if ($return === false) {
					$this->status = false;
				} else {
					$this->database->add_session();
					$this->status = true;
				}
			}
        }

		public function return_status() {
			return $this->status;
		}

        public function check_other_params($sessions) {
            if ($_SERVER['REMOTE_ADDR'] == $sessions['source_ip'] && $_SERVER['HTTP_USER_AGENT'] == $sessions['browser_agent']) {
                return true;
            } else {
                return false;
            }
        }

        // this function deletes a session from memory and database
        // it also redirects the user to the login page
        // if the request was an ajax, it will echo false
        // function dies at the end
        public function destroy_session() {
            // delete session from database
            $this->database->delete_session(session_id());

            // destroy session
            session_unset();
            session_destroy();

            // delete php session cookie
            if (isset($_COOKIE['PHPSESSID'])) {
                setcookie('PHPSESSID', '', time() - 7000000, '/');
            }

            // redirect
            if ($this->std->IsXHttpRequest()) {
                echo 'false';
            } else {
                header("Location: /phpietadmin/auth/login");
            }
            // Die in case browser ignores header redirect
            die();
        }
    }