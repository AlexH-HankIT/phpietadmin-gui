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

        public function check_password() {
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

        public function check_other_params(array $sessions) {
            if ($_SERVER['REMOTE_ADDR'] == $sessions['source_ip'] && $_SERVER['HTTP_USER_AGENT'] == $sessions['browser_agent']) {
                return true;
            } else {
                return false;
            }
        }

        // this function deletes a session from memory and database
        // it also redirects the user to the login page
        // if the request was an ajax, it will echo false
        // function dies at the end
        public function destroy_session($std, $database) {
            // delete session from database
            $database->delete_session(session_id(), $_SESSION['username']);

            // destroy session
            session_unset();
            session_destroy();

            // delete php session cookie
            if (isset($_COOKIE['PHPSESSID'])) {
                setcookie('PHPSESSID', '', time() - 7000000, '/');
            }

            // redirect
            if ($std->IsXHttpRequest()) {
                echo 'false';
            } else {
                header("Location: /phpietadmin/auth/login");
            }
            // Die in case browser ignores header redirect
            die();
        }

        public function setUsername($NAME) {
            $this->username = $NAME;
        }

        public function setPassword($PASS) {
            $this->password = $PASS;
        }

        public function setTime($login_time) {
            $_SESSION['timestamp'] = $login_time;
        }
    }
?>