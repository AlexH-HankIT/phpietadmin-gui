<?php namespace phpietadmin\app\models;
use phpietadmin\app\models\logging,
	phpietadmin\app\core;

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

        $this->username = $username;

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
			$this->logging->log_action_result('Please instantiate the object with a username!', array('result' => 10, 'code_type' => 'intern'), __METHOD__);
		}
	}

	private function hash_password($password) {
		return password_hash($password, PASSWORD_BCRYPT);
	}

	public function add_first_user($user_input_auth_code, $password1, $password2) {
		// workaround for getting all data
		$username = $this->username;
		$this->username = false;
		$users = $this->return_data();
		$this->username = $username;

		// validate user table is empty to prevent this from working if there are already users
		if ($users === false) {
			if ($password1 === $password2) {
				// parse xml file
				$auth_code = simplexml_load_file(__DIR__ . '/../../install/auth.xml');

				if ($auth_code->authcodes->authcode->code === $user_input_auth_code) {
					$this->add($password1);

					if ($this->logging->get_action_result()['result'] === 0) {
						// delete auth_code file
						if(unlink(__DIR__ . '/../../install/auth.xml') === false) {
							$this->logging->log_action_result('Could not delete the file ' . __DIR__ . '/../../install/auth.xml Something is wrong with your permissions. Please delete it manually', array('result' => 1, 'code_type' => 'intern'), __METHOD__);
						}
					}
				} else {
					$this->logging->log_access_result('The specified authentication code is incorrect!', 1, 'first_login', __METHOD__);
				}
			} else {
				$this->logging->log_action_result('Passwords do not match!', array('result' => 10, 'code_type' => 'intern'), __METHOD__);
			}
		} else {
			$this->logging->log_access_result('The first user is already configured!', 1, 'first_login', __METHOD__);
		}
		header("Location: /phpietadmin/auth/login");
		die();
	}

    public function add($password) {
		if ($this->username !== false) {
			$return = $this->database->add_phpietadmin_user($this->username, $this->hash_password($password));

			if ($return != 0) {
				$this->logging->log_action_result('The user ' . $this->username . ' was not added to the database!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
			} else {
				$this->logging->log_action_result('The user ' . $this->username . ' was successfully added to the database!', array('result' => 0, 'code_type' => 'intern'), __METHOD__);

				// fetch up2date data
				$this->data = $this->database->get_phpietadmin_user($this->username);
			}
		} else {
			$this->logging->log_action_result('Please instantiate the object with a username!', array('result' => 9, 'code_type' => 'intern'), __METHOD__);
		}
    }

    public function delete() {
		if ($this->username !== false && $this->status !== false) {
			$return = $this->database->delete_phpietadmin_user($this->username);

			if ($return != 0) {
				$this->logging->log_action_result('The user ' . $this->username . ' was not deleted from the database!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);

				// invalidate data
				$this->status = false;
				$this->data = false;
			} else {
				$this->logging->log_action_result('The user ' . $this->username . ' was successfully deleted from the database!', array('result' => 0, 'code_type' => 'intern'), __METHOD__);
			}
		} else {
			$this->logging->log_action_result('Please instantiate the object with a username!', array('result' => 9, 'code_type' => 'intern'), __METHOD__);
		}
    }

    public function change($value) {
		if ($this->username !== false && $this->status !== false) {
			$return = $this->database->updatePhpietadminUserPassword($value, $this->username);

			if ($return != 0) {
				$this->logging->log_action_result('The password was not changed', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
			} else {
				$this->logging->log_action_result('The password was successfully changed', array('result' => 0, 'code_type' => 'intern'), __METHOD__);

				// fetch up2date data
				$this->data = $this->database->get_phpietadmin_user($this->username);
			}
		} else {
			$this->logging->log_action_result('Please instantiate the object with a username!', array('result' => 9, 'code_type' => 'intern'), __METHOD__);
		}
    }

    public function return_data() {
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
				$this->logging->log_action_result('No users available!', array('result' => 3, 'code_type' => 'intern'), __METHOD__);
				return false;
			} else {
				$this->logging->log_action_result('The user were successfully retrieved!', array('result' => 0, 'code_type' => 'intern'), __METHOD__);
				return $return;
			}
		}
    }

    public function return_status() {
		return $this->status;
    }
}