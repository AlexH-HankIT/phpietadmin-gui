<?php namespace phpietadmin\app\models;
use phpietadmin\app\core;

// configures the phpietadmin users
class User extends core\BaseModel {
    private $username;

    // contains username and password hash (bcrypt)
    private $data;

    // false if user doesn't exist
    private $status;

    public function __construct($username = false) {
		// instantiate parent class to access database and logging functions
		parent::__construct();

		if (!empty($username)) {
			$this->username = $username;
		} else {
			$this->username = false;
		}

		// check if option exists in database
		if ($username !== false) {
			$this->exists();
		}
    }

	private function exists() {
		if ($this->username !== false) {
			$this->data = $this->database->get_phpietadmin_user($this->username);

			if ($this->data === false) {
				$this->status = false;
			} else {
				$this->status = true;
			}
		} else {
			$this->logging->log_action_result('Please input a username!', array('result' => 10, 'code_type' => 'intern'), __METHOD__);
		}
	}

	public function returnStatus() {
		return $this->status;
	}

	private function hashPassword($password) {
		return password_hash($password, PASSWORD_BCRYPT);
	}

	public function addFirstUser($user_input_auth_code, $password1, $password2) {
		// workaround for getting all data
		$username = $this->username;
		$this->username = false;
		$users = $this->returnData();
		$this->username = $username;

		// validate user table is empty to prevent this from working if there are already users
		if ($users === false) {
			if ($password1 === $password2) {
				$authCode = file_get_contents('/usr/share/phpietadmin/install/auth');

				if ($authCode === $user_input_auth_code) {
					$return = $this->database->add_phpietadmin_user($this->username, $this->hashPassword($password1), 'root', true);

					if ($return !== 0) {
						$this->logging->log_action_result('The user ' . $this->username . ' was not added to the database!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
					} else {
						$this->logging->log_action_result('The user ' . $this->username . ' was successfully added to the database!', array('result' => 0, 'code_type' => 'intern'), __METHOD__);
						unlink('/usr/share/phpietadmin/install/auth');
					}
				} else {
					$this->logging->log_action_result('Auth code is wrong!', array('result' => 10, 'code_type' => 'intern'), __METHOD__);
				}
			} else {
				$this->logging->log_action_result('Passwords do not match!', array('result' => 10, 'code_type' => 'intern'), __METHOD__);
			}
		} else {
			$this->logging->log_access_result('The first user is already configured!', 1, 'first_login', __METHOD__);
		}
	}

    public function add($password) {
		if ($this->username !== false) {
			if ($this->status === false) {
				$return = $this->database->add_phpietadmin_user($this->username, $this->hashPassword($password), 'admin');

				if ($return !== 0) {
					$this->logging->log_action_result('The user ' . $this->username . ' was not added to the database!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
				} else {
					$this->logging->log_action_result('The user ' . $this->username . ' was successfully added to the database!', array('result' => 0, 'code_type' => 'intern'), __METHOD__);

					// fetch up2date data
					$this->data = $this->database->get_phpietadmin_user($this->username);
				}
			} else {
				$this->logging->log_action_result('The username is already in use!', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
			}
		} else {
			$this->logging->log_action_result('Please input a username!', array('result' => 9, 'code_type' => 'intern'), __METHOD__);
		}
    }

    public function delete() {
		if ($this->username !== false && $this->status !== false) {
			$count = $this->returnUserCount();

			if ($count <= 1) {
				$this->logging->log_action_result('The last user cannot be deleted!', array('result' => 3, 'code_type' => 'extern'), __METHOD__);
			} else {
				$return = $this->database->delete_phpietadmin_user($this->username);

				if ($return !== 0) {
					$this->logging->log_action_result('The user ' . $this->username . ' was not deleted from the database!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);

					// invalidate data
					$this->status = false;
					$this->data = false;
				} else {
					$this->logging->log_action_result('The user ' . $this->username . ' was successfully deleted from the database!', array('result' => 0, 'code_type' => 'intern'), __METHOD__);
				}
			}
		} else {
			$this->logging->log_action_result('Please input a username!', array('result' => 9, 'code_type' => 'intern'), __METHOD__);
		}
    }

    public function change($value) {
		if ($this->username !== false && $this->status !== false) {
			$return = $this->database->updatePhpietadminUserPassword($this->hashPassword($value), $this->username);

			if ($return != 0) {
				$this->logging->log_action_result('The password was not changed', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
			} else {
				$this->logging->log_action_result('The password was successfully changed', array('result' => 0, 'code_type' => 'intern'), __METHOD__);

				// fetch up2date data
				$this->data = $this->database->get_phpietadmin_user($this->username);
			}
		} else {
			$this->logging->log_action_result('Please input a username!', array('result' => 9, 'code_type' => 'intern'), __METHOD__);
		}
    }

    public function returnData() {
		if ($this->username !== false) {
			if ($this->status !== false) {
				// return $this->username
				return $this->data;
			} else {
				return false;
			}
		} else {
			$return = $this->database->get_phpietadmin_user(false);

			if ($return !== false) {
				$this->logging->log_action_result('The user were successfully retrieved!', array('result' => 0, 'code_type' => 'intern'), __METHOD__);
				return $return;
			} else {
				$this->logging->log_action_result('No users available!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
				return false;
			}
		}
    }

	private function returnUserCount() {
		// Save username in temp variable, because the returnData function can only return all users if $this->usrname === false
		$username = $this->username;
		$this->username = false;
		$data = $this->returnData();
		$this->username = $username;

		if ($data !== false) {
			return count($data);
		} else {
			return 0;
		}
	}
}