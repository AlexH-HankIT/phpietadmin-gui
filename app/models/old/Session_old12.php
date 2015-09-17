<?php namespace phpietadmin\app\models;

class Session2_old extends logging\Logging {
    public function __construct() {
		parent::__construct();

        session_set_save_handler(
            array($this, 'open'),
            array($this, 'close'),
            array($this, 'read'),
            array($this, 'write'),
            array($this, 'destroy'),
            array($this, 'gc'));

        // start session if not already started
        if (empty(session_id())) {
            session_start();
        }
    }

    /*
     * Mini Doc:
     *
     * Login:
     * call login()
     *
     * Logout:
     * call logout()
     *
     * Check:
     * call check_logged_in()
     */

    /*
     * ToDo:
     * Testing
     * Add logging
     */

    public function check_logged_in() {
        if (isset($_SESSION['username'])) {
			$session_id = session_id();
            $data = $this->database->get_session($session_id);
            if ($data['user_agent'] == $_SERVER['HTTP_USER_AGENT'] || $data['remote_address'] == $_SERVER['REMOTE_ADDR']) {
				// check if session id matches the one in the user table
				$user = $this->database->get_phpietadmin_user($_SESSION['username']);
				if ($user[0]['session_id'] == $session_id) {
					$idle_timeout = $this->database->get_config('idle') * 60;
					if ($data['inactive'] < $idle_timeout + time()) {
						return false;
					} else {
						// update timestamp
						$this->database->update_session_activity(session_id());
						return true;
					}
				} else {
					return false;
				}
            } else {
                // logout
                return false;
            }
        } else {
            return false;
        }
    }

    public function login($username, $password) {
        $data = $this->database->get_phpietadmin_user($username);

        if ($data[0]['password'] === hash('sha256', $password)) {
            $return = $this->check_already_logged_in($username);

            if ($return === false) {
                $_SESSION['username'] = $username;
                $this->database->update_session_for_user($username, session_id());

                return array(
                    'login' => true,
                    'status' => 'ok'
                );
            } else {
                return array(
                    'login' => false,
                    'status' => 'already'
                );
            }
        } else {
			return array(
				'login' => false,
				'status' => 'wrong'
			);
        }
    }

    private function check_already_logged_in($username) {
        if (isset($_SESSION['username']) && $_SESSION['username'] == $username) {
            $data = $this->database->get_phpietadmin_user($username);

            // if there is already a session id the user is logged in
            if (empty($data['session_id'])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function logout() {
        session_destroy();
    }

    public function open() {
        return true;
    }

    public function close() {
        return true;
    }

    public function read($id) {
        $data = $this->database->get_session($id);

        if ($data !== false) {
            return $data['data'];
        } else {
            return '';
        }
    }

    public function write($id, $data) {
        // check if session already exists
        $return = $this->database->get_session($id);

        if ($return !== false) {
            $this->database->update_session_data($id, $data);
        } else {
            $this->database->add_session($id, $data);
        }

        return true;
    }

    public function destroy($id) {
        $this->database->delete_session($id);

        // delete cookie
        if (isset($_COOKIE['PHPSESSID'])) {
            setcookie('PHPSESSID', '', time() - 7000000, '/');
        }

        return true;
    }

    public function gc() {
        $this->database->delete_session_garbage();
        return true;
    }
}