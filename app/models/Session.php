<?php
    class Session {
        private $username;
        private $password;

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
                return '';
            } else {
                return $result['password'];
            }
        }

        public function check() {
            $pass = $this->getPassword();

            if (empty($pass)) {
                return false;
            } else {
                if ($pass === $this->password) {
                    return true;
                } else {
                    return false;
                }
            }
        }

        public function setUsername($NAME) {
            $this->username = $NAME;
        }

        public function setPassword($PASS) {
            $this->password = $PASS;
        }

        public function setTime() {
            $_SESSION['timestamp'] = time();
        }
    }
?>