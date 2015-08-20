<?php namespace phpietadmin\app\models;
    use phpietadmin\app\models\logging;

    class Ietuser extends logging\Logging {
        protected $username;

        public function __construct($username) {
            $this->username = $username;
        }

        public function add_user($password, $type = 'IncomingUser') {

        }

        /**
         * @param $identifier string|int either the username or the user id
         */
        public function delete_user($identifier) {
            // don't delete user if it's in use
            // because we loose the password and cant delete the line ever
            if (is_int($identifier)) {
                // use as int
            } else {
                // use as string
            }
        }

        protected function check_user_already_added() {

        }
    }