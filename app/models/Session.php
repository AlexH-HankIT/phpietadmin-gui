<?php
    class Session {
        protected $username;
        protected $password;
        protected $last_activity;

        public function __construct() {
            session_start();
        }

        private function getPassword() {
            require_once('Database.php');
            $database = new Database;

            $data = $database->prepare('SELECT password from user where username=:user');
            $data->bindValue('user', $this->username, SQLITE3_TEXT );
            $result = $data->execute();
            $result = $result->fetchArray();

            if (empty($result)) {
                return;
            } else {
                return $result['password'];
            }
        }

        public function check() {
            $DBPASS = $this->getPassword();

            if (empty($DBPASS)) {
                return false;
            } else {
                if ($DBPASS === $this->password) {
                    return true;
                }
            }
        }

        public function setUsername($NAME) {
            $this->username = $NAME;
        }

        public function setPassword($PASS) {
            $this->password = $PASS;
        }

        public function setLast_activity() {
            $time = time();
            $this->last_activity = $time;
        }
    }
?>