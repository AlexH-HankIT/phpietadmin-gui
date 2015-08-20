<?php namespace phpietadmin\app\models\logging;
    use phpietadmin\app\models;

    /* ToDo: Define paths in database */
    define('log_dir_path', '/var/log/phpietadmin');
    define('log_file_name', 'phpietadmin.log');
    define('action_log_file_path', log_dir_path . '/phpietadmin_action.log');
    define('debug_log_file_path', log_dir_path . '/phpietadmin_debug.log');
    define('access_log_file_path', log_dir_path . '/phpietadmin_access.log');

    class Logging extends models\Std {
        private $result;

        protected function write_to_access_log_file() {
            // login user, login time
            // logout user, logout time, logout reason (button or inactivity)
        }

        protected function write_to_debug_log_file() {
            // executed shell commands
            // added/deleted config files
        }

        /**
         * Writes the data from $this->result to the phpietadmin action log file
         *
         * @return void
         */
        protected function write_to_action_log_file() {
            if (is_array($this->result)) {
                end($this->result);
                $key = key($this->result);

                // handle call via webserver and cli
                if (is_array($_SERVER) && isset($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'])) {
                    $line = time() . ' ' . $_SERVER['REMOTE_ADDR'] . ' ' . $_SERVER['HTTP_USER_AGENT'] . ' ' . session_id() . ' ' . $this->result[$key]['message'] . ' ' . $this->result[$key]['code_type'] . ' ' . $this->result[$key]['code'] . ' ' . $this->result[$key]['method'] . "\n";
                } else {
                    $line = time() . ' ' . $this->result[$key]['message'] . ' ' . $this->result[$key]['code_type'] . ' ' . $this->result[$key]['code'] . ' ' . $this->result[$key]['method'] . "\n";

                }

                file_put_contents(action_log_file_path, $line, FILE_APPEND | LOCK_EX);
            }
        }

        /**
         *
         * set a success or error message
         * all messages will be save in an array
         * the last message of the array should be checked for errors
         *
         * @param   string $message error or success message
         * @param   array $return to be documented
         * @param   string $function called function
         * @param   boolean $reset delete all indexes from the array, optional
         * @return  array|bool
         *
         */
        protected function log_action_result($message, $return, $function, $reset = false) {
            if (!is_array($this->result)) {
                $this->result = [];
            } else if ($reset === true) {
                $this->result = [];
            }

            // use array push, so we can save all messages
            // after a method is called, this array will contain a action log
            if (isset($return['status'])) {
                $temp = array (
                    'message' => htmlspecialchars($message),
                    'code' => $return['result'],
                    'code_type' => $return['code_type'],
                    'status' => $return['status'],
                    'method' => $function
                );
            } else {
                $temp = array (
                    'message' => htmlspecialchars($message),
                    'code' => $return['result'],
                    'code_type' => $return['code_type'],
                    'method' => $function
                );
            }

            array_push($this->result, $temp);

            // if there is an error write it to the log file
            if ($return['result'] != 0) {
                $this->write_to_action_log_file();
            }

            // the first message is always a success message
            // right now, this function should only log errors
            // so never log if the return code is 0
        }

        public function log_debug_result() {

        }

        public function log_access_result() {

        }

        /**
         *
         * returns the result of the last time log_action_result() was called
         * next time you should call log_action_result() with $reset = true, to delete old results
         *
         * @param   boolean $all by default this function only returns the last result, their could be multiple
         * @return  array|bool
         *
         */
        public function get_action_result($all = false) {
            if (is_array($this->result)) {
                if ($all === true) {
                    return $this->result;
                } else {
                    // Get last array position
                    end($this->result);
                    $key = key($this->result);

                    return $this->result[$key];
                }
            } else {
                return false;
            }
        }
    }