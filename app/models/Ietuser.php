<?php namespace phpietadmin\app\models;
    use phpietadmin\app\models\logging;

    class Ietuser extends logging\Logging {
        protected $username;
        protected $database;
        protected $std;

        public function __construct($username = false) {
            $this->username = $username;
            $this->database = new Database();
            $this->std = new Std();
        }

        public function add_user_to_db($password, $type = 'IncomingUser') {
            $this->log_action_result('The user was successfully added!', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if ($this->username !== false) {
                if($this->check_user_already_added_to_db() === true) {
                    $this->log_action_result('Already added!', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
                } else {
                    $this->database->add_iet_user($this->username, $password);
                }
            } else {
                $this->log_action_result('Please instantiate the object with a username!', array('result' => 9, 'code_type' => 'intern'), __METHOD__);
            }
        }

        /**
         * Deletes a user and handles errors
         *
         */
        public function delete_user_from_db() {
            $this->log_action_result('The user was successfully deleted!', array('result' => 0, 'code_type' => 'intern'), __METHOD__, true);

            if ($this->username !== false) {
                // don't delete user if it's in use
                // because we loose the password and can't delete the line
                // create a better function to check this
                // if a user is commented, this will alert anyway...
                $return[0] = $this->std->check_if_file_contains_value($this->database->get_config('ietd_config_file')['value'], 'IncomingUser ' . $this->username);
                $return[1] = $this->std->check_if_file_contains_value($this->database->get_config('ietd_config_file')['value'], 'OutgoingUser ' . $this->username);

                if ($return[0] === true || $return[1] === true) {
                    $this->log_action_result('The username ' . $this->username . ' is used by the iet daemon!', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
                } else {
                    $return = $this->database->delete_iet_user($this->username);

                    if ($return !== 0) {
                        $this->log_action_result('The user was not deleted!', array('result' => $return, 'code_type' => 'intern'), __METHOD__);
                    }
                }
            } else {
                $this->log_action_result('Please instantiate the object with a username!', array('result' => 9, 'code_type' => 'intern'), __METHOD__);
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