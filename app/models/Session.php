<?php namespace phpietadmin\app\models;
	use phpietadmin\app\core;

	// bugs
	// if a user is logged out due to inactivity
	// and logs in again
	// the inactive session is destroyed
	// and the user has to login again

class Session extends core\BaseModel {
	private $username;

	public function __construct($username = false) {
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

		if ($username === false && isset($_SESSION['username'])) {
			$this->username = $_SESSION['username'];
		} else {
			$this->username = $username;
		}
	}

	private function check_already_logged_in() {
		$data = $this->database->get_phpietadmin_user($this->username);
		// if there is already a session id the user is logged in
		if (empty($data[0]['session_id'])) {
			return false;
		} else {
			return true;
		}
	}

	public function open() {
		return true;
	}

	public function close() {
		return true;
	}

	public function read($session_id) {
		// select session data from table
		$data = $this->database->get_session($session_id);

		if ($data !== false && !empty($data['data'])) {
			return $data['data'];
		} else if($data !== false && !empty($data['last_activity']) && !empty($data['user_agent']) && !empty($data['remote_address'])) {
			return '';
		} else {
			$this->database->add_session($session_id);
			return '';
		}
	}

	public function write($session_id, $data) {
		$database = new Database();
		$database->update_session_data($session_id, $data);
		return true;
	}

	public function destroy($session_id) {
		// delete session from session table
		$this->database->delete_session($session_id);

		// delete cookie
		if (isset($_COOKIE['PHPSESSID'])) {
			setcookie('PHPSESSID', '', time() - 7000000, '/');
		}

		// delete session from user table
		$this->database->update_session_for_user($this->username, NULL);

		return true;
	}

	public function gc($max_life_time) {
        $database = new Database();

        // delete all sessions which reached max life time
		$database->delete_session_garbage($max_life_time);
		$database->update_session_for_user($this->username, session_id());

		return true;
	}

	public function login($password) {
		$_SESSION['username'] = $this->username;
		$data = $this->database->get_phpietadmin_user($this->username);
		if ($data[0]['password'] === hash('sha256', $password)) {
			$return = $this->check_already_logged_in($this->username);
			if ($return === false) {
				$_SESSION['logged_in'] = true;
				$this->database->update_session_for_user($this->username, session_id());
                $this->logging->log_access_result('The user ' . $this->username . ' was successfully logged in!', 0, 'login', __METHOD__);
				return array(
					'login' => true,
					'status' => 'ok'
				);
			} else {
				$_SESSION['logged_in'] = true;
                $this->logging->log_access_result('The user ' . $this->username . ' is already logged in!', 1, 'login', __METHOD__);
				// declare var so the override function can check
				// if it should really override
				$_SESSION['overwrite'] = true;
				return array(
					'login' => false,
					'status' => 'already'
				);
			}
		} else {
            $this->logging->log_access_result('The user ' . $this->username . ' was not logged in. Wrong password!', 1, 'login', __METHOD__);
			$_SESSION['logged_in'] = false;
			return array(
				'login' => false,
				'status' => 'wrong'
			);
		}
	}

	public function logout() {
        $this->logging->log_access_result('The user ' . $this->username . ' was logged out!', 0, 'logout', __METHOD__);
		session_destroy();
	}

	public function check_logged_in($controller) {
		if (isset($_SESSION['username'], $_SESSION['logged_in'])) {
			if ($_SESSION['logged_in'] === true) {
				$data = $this->database->get_session(session_id());

				if ($data['user_agent'] === $_SERVER['HTTP_USER_AGENT'] && $data['remote_address'] === $_SERVER['REMOTE_ADDR']) {
					$idle_value = intval($this->database->get_config('idle')['value']);
					if ($idle_value !== 0) {
						if (time() - $data['last_activity'] < $idle_value * 60) {
							// only update the timestamp if the inactivity logout feature is actually enabled
							// Update time
							// Don't update if controller is connection
							// A connection to this controller is always established,
							// even if the session is expired, but the site is still loaded
							if ($controller !== 'phpietadmin\app\controllers\connection') {
								$this->database->update_session_activity(session_id());
							}
						} else {
							$this->logging->log_access_result('The user ' . $this->username . ' will be logged out due to inactivity!', 1, 'check_logged_in', __METHOD__);
							$this->logout();
							return false;
						}
					}
					return true;
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			return false;
		}
	}

	public function overwrite() {
		$data = $this->database->get_session_by_username($this->username);

		// save original session id
		$session_id = session_id();

		// take session id for the session which will be overwritten
		session_id($data['session_id']);

		// destroy session
		$this->logout();

		// reset session id to original
		session_id($session_id);

		return true;
	}
}