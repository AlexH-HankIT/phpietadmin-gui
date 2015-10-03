<?php namespace phpietadmin\app\models;
use phpietadmin\app\core;

class Session extends core\BaseModel {
	private $username;

	public function __construct($username = false) {
		parent::__construct();

		$this->username = $username;

		if (empty(session_id())) {
			session_start();
		}

		if ($this->username !== false) {
			$_SESSION['username'] = $this->username;
		}

		if (isset($_SESSION['username']) && $this->username === false) {
			$this->username = $_SESSION['username'];
		}
	}

	public function login($password) {
		$data = $this->database->get_phpietadmin_user($this->username);

		if (password_verify($password, $data[0]['password'])) {
			// check if user is already logged_in
			if (empty($data[0]['session_id'])) {
				$_SESSION['logged_in'] = true;
				return array(
					'login' => true,
					'status' => 'ok'
				);
			} else {
				$_SESSION['logged_in'] = true;
				return array(
					'login' => true,
					'status' => 'already'
				);
			}
		} else {
			$_SESSION['logged_in'] = false;
			return array(
				'login' => false,
				'status' => 'wrong'
			);
		}
	}

	public function logout() {
		session_destroy();

		if (isset($_COOKIE['PHPSESSID'])) {
			setcookie('PHPSESSID', '', time() - 7000000, '/');
		}

		// delete session id from $_SESSION['username']
	}
}