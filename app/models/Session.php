<?php
    class Session {
        protected $username;
        protected $password;
        protected $last_activity;

        public function __construct() {
            session_start();
        }

        protected function getPassword() {
            require_once('Database.php');
            $database = new Database;

            $data = $database->querySingle('SELECT password from user where username=' . '\'' . $this->username . '\'', true);

            if (empty($data)) {
                return;
            } else {
                return $data['password'];
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
            $_SESSION['username'] = $NAME;
        }

        public function setPassword($PASS) {
            $this->password = $PASS;
            $_SESSION['password'] = $PASS;
        }

        public function setLast_activity() {
            $time = time();
            $this->last_activity = $time;
            $_SESSION['last_activity'] =  $time;
        }
    }
?>