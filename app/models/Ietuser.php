<?php
namespace app\models;

use app\core;

// Configures user for the iscsi enterprise target
class Ietuser extends core\BaseModel {
    private $username;

    public function __construct($username = false) {
        parent::__construct();
        $this->username = $username;
    }

    public function add_user_to_db($password) {
        if ($this->username !== false) {
            if ($this->check_user_already_added_to_db() === true) {
                $this->logging->log_action_result('Already added!', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
            } else {
                $return = $this->database->change(array(
                        'query' => 'INSERT INTO phpietadmin_iet_user (username, password) VALUES (:username, :password)',
                        'params' => array(
                            0 => array(
                                'name' => 'username',
                                'value' => str_replace(' ', '', $this->username),
                                'type' => SQLITE3_TEXT
                            ),
                            1 => array(
                                'name' => 'password',
                                'value' => $password,
                                'type' => SQLITE3_TEXT
                            )
                        )
                    )
                );

                if ($return !== 0) {
                    $this->logging->log_action_result('The user was not added!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
                } else {
                    $this->logging->log_action_result('The user was successfully added!', array('result' => 0, 'code_type' => 'intern'), __METHOD__);
                }
            }
        } else {
            $this->logging->log_action_result('Please instantiate the object with a username!', array('result' => 9, 'code_type' => 'intern'), __METHOD__);
        }
    }

    /**
     * Deletes a user and handles errors
     *
     */
    public function delete_user_from_db() {
        if ($this->username !== false) {
            // don't delete user if it's in use
            // because we loose the password and can't delete the line
            $targets = new target\Target(false);
            $return = $targets->parse_file($this->database->get_config('ietd_config_file')['value'], [$this, 'checkUserInUse'], array(), true, false);

            if ($return === true) {
                $this->logging->log_action_result('The username ' . $this->username . ' is used by the iet daemon!', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
            } else {
                $return = $this->database->change(array(
                        'query' => 'DELETE FROM phpietadmin_iet_user WHERE username=:username',
                        'params' => array(
                            0 => array(
                                'name' => 'username',
                                'value' => str_replace(' ', '', $this->username),
                                'type' => SQLITE3_TEXT
                            )
                        )
                    )
                );

                if ($return !== 0) {
                    $this->logging->log_action_result('The user was not deleted!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                } else {
                    $this->logging->log_action_result('The user was successfully deleted!', array('result' => 0, 'code_type' => 'intern'), __METHOD__);
                }
            }
        } else {
            $this->logging->log_action_result('Please instantiate the object with a username!', array('result' => 9, 'code_type' => 'intern'), __METHOD__);
        }
    }

    public function checkUserInUse(array $file) {
        $data = $this->database->get_user_by_name($this->username);

        $key = array_search('IncomingUser ' . $this->username . ' ' . $data['password'], $file);

        if ($key === false) {
            $key = array_search('OutogingUser ' . $this->username . ' ' . $data['password'], $file);

            if ($key === false) {
                return false;
            } else {
                return true;
            }
        } else {
            return true;
        }
    }

    protected function check_user_already_added_to_db() {
        $data = $this->database->get_all_usernames();

        if (is_array($data)) {
            $result = array_search($this->username, $data);

            if ($result === false) {
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }
}