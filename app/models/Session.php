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
            $_SESSION['logged_in'] = true;
            $_SESSION['REMOTE_ADDR'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['HTTP_USER_AGENT'] = $_SERVER['HTTP_USER_AGENT'];
            $_SESSION['last_activity'] = time();
            $_SESSION['username'] = $this->username;
            $this->logging->log_access_result('Login successful', 'failure', 'check', __METHOD__);
            return true;
		} else {
			$_SESSION['logged_in'] = false;
            $this->logging->log_access_result('Login failure. Wrong password', 'failure', 'check', __METHOD__);
            return false;
		}
	}

	public function logout() {
		session_destroy();

		if (isset($_COOKIE['PHPSESSID'])) {
			setcookie('PHPSESSID', '', time() - 7000000, '/');
		}
	}

    public function checkLoggedIn($controller) {
        if (is_array($_SESSION) && isset($_SESSION['logged_in'])) {
            if ($_SESSION['logged_in'] === true) {
                if ($_SESSION['REMOTE_ADDR'] === $_SERVER['REMOTE_ADDR']) {
                    if ($_SESSION['HTTP_USER_AGENT'] === $_SERVER['HTTP_USER_AGENT']) {
                        $idle_value = intval($this->database->get_config('idle')['value']);

                        // If $idle_value is 0, the auto logout feature is disabled
                        if ($idle_value !== 0) {
                            if (time() - $_SESSION['last_activity'] > $idle_value * 60) {
                                $this->logging->log_access_result('Logout due to timeout', 'failure', 'check', __METHOD__);
                                $this->logout();
                            } else {
                                // only update the timestamp if the inactivity logout feature is actually enabled
                                // Update time
                                // Don't update if controller is connection
                                // A connection to this controller is always established,
                                // even if the session is expired, but the site is still loaded
                                if ($controller !== 'phpietadmin\app\controllers\connection') {
                                    $this->updateLastActivity();
                                }
                            }
                        }
                        return true;
                    } else {
                        $this->logging->log_access_result('Invalid session. Wrong user agent', 'failure', 'check', __METHOD__);
                        return false;
                    }
                } else {
                    $this->logging->log_access_result('Invalid session. Wrong ip address', 'failure', 'check', __METHOD__);
                    return false;
                }
            } else {
                $this->logging->log_access_result('Invalid session. User is not logged in', 'failure', 'check', __METHOD__);
                return false;
            }
        } else {
            return false;
        }
    }

    private function updateLastActivity() {
        $_SESSION['last_activity'] = time();
    }
}