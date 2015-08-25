<?php namespace phpietadmin\app\models\logging;
    use phpietadmin\app\models;

    // ToDo: Remove duplicated usage of std (extended and created)

    /* ToDo: Define paths in database */
    define('log_dir_path', '/var/log/phpietadmin');
    define('log_file_name', 'phpietadmin.log');
    define('action_log_file_path', log_dir_path . '/phpietadmin_action.log');
    define('debug_log_file_path', log_dir_path . '/phpietadmin_debug.log');
    define('access_log_file_path', log_dir_path . '/phpietadmin_access.log');

    class Logging extends models\Std {
        private $action_result;
        private $access_result;
        private $debug_result;
        protected $std;
        protected $database;
        private $write_debug_log;
        private $write_access_log;
        private $write_action_log;

        public function __construct() {
            $this->std = new models\std();
            $this->database = new models\database();
        }

        private function write_to_access_log_file() {
            if (is_array($this->access_result)) {
                end($this->access_result);
                $key = key($this->access_result);

                // handle call via webserver and cli
                if (is_array($_SERVER) && isset($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'])) {
                    $line = time() . ' ' . $_SERVER['REMOTE_ADDR'] . ' "' . $_SERVER['HTTP_USER_AGENT'] . '" ' . session_id() . ' "' . $this->access_result[$key]['message'] . '" ' . $this->access_result[$key]['status'] . ' ' . $this->access_result[$key]['type'] . ' ' . $this->access_result[$key]['method'] . "\n";
                } else {
                    $line = time() . ' "' . $this->action_result[$key]['message'] . '" ' . $this->action_result[$key]['status'] . ' ' . $this->action_result[$key]['type'] . ' ' . $this->action_result[$key]['method'] . "\n";
                }

                file_put_contents(access_log_file_path, $line, FILE_APPEND | LOCK_EX);
            }
        }

        private function write_to_debug_log_file() {
            if (is_array($this->debug_result)) {
                end($this->debug_result);
                $key = key($this->debug_result);

                // handle call via webserver and cli
                if (is_array($_SERVER) && isset($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'])) {
                    $line = time() . ' ' . $_SERVER['REMOTE_ADDR'] . ' "' . $_SERVER['HTTP_USER_AGENT'] . '" ' . session_id() . ' "' . $this->debug_result[$key]['command'] . ' ' . $this->debug_result[$key]['message'] . '" ' . $this->debug_result[$key]['method'] . "\n";
                } else {
                    $line = time() . $this->debug_result[$key]['command'] . ' "' . $this->debug_result[$key]['message'] . '" '  . $this->debug_result[$key]['method'] . "\n";
                }

                file_put_contents(debug_log_file_path, $line, FILE_APPEND | LOCK_EX);
            }
        }

        /**
         * Writes the data from $this->action_result to the phpietadmin action log file
         *
         * @return void
         *
         */
        private function write_to_action_log_file() {
            if (is_array($this->action_result)) {
                end($this->action_result);
                $key = key($this->action_result);

                // handle call via webserver and cli
                if (is_array($_SERVER) && isset($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'])) {
                    $line = time() . ' ' . $_SERVER['REMOTE_ADDR'] . ' "' . $_SERVER['HTTP_USER_AGENT'] . '" ' . session_id() . ' "' . $this->action_result[$key]['message'] . '" ' . $this->action_result[$key]['code_type'] . ' ' . $this->action_result[$key]['code'] . ' ' . $this->action_result[$key]['method'] . "\n";
                } else {
                    $line = time() . ' "' . $this->action_result[$key]['message'] . '" ' . $this->action_result[$key]['code_type'] . ' ' . $this->action_result[$key]['code'] . ' ' . $this->action_result[$key]['method'] . "\n";
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
         *
         */
        protected function log_action_result($message, $return, $function, $reset = false) {
            if (!is_array($this->action_result)) {
                $this->action_result = [];
            } else if ($reset === true) {
                $this->action_result = [];
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

            array_push($this->action_result, $temp);

            // if there is an error write it to the log file
            if ($return['result'] != 0) {
                $this->write_to_action_log_file();
            }

            // the first message is always a success message
            // right now, this function should only log errors
            // so never log if the return code is 0
        }

        public function log_debug_result($message, $function, $command = '') {
            if (!is_array($this->debug_result)) {
                $this->debug_result = [];
            }

            $temp = array (
                'message' => htmlspecialchars($message),
                'session_id' => session_id(),
                'method' => $function,
                'command' => $command
            );

            array_push($this->debug_result, $temp);

            $this->write_to_debug_log_file();
        }

        /**
         * @param $message string error/success message
         * @param $status string success/failure
         * @param $type string login/logout/timeout_logout/first_login/override
         * @param $function string __METHOD__
         *
         */
        public function log_access_result($message, $status, $type, $function) {
            if (!is_array($this->access_result)) {
                $this->access_result = [];
            }

            if ($status == 0) {
                $status = 'success';
            } else {
                $status = 'failure';
            }

            $temp = array (
                'message' => htmlspecialchars($message),
                'status' => $status,
                'session_id' => session_id(),
                'type' => $type,
                'method' => $function
            );

            array_push($this->access_result, $temp);

            $this->write_to_access_log_file();
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
            if (is_array($this->action_result)) {
                if ($all === true) {
                    return $this->action_result;
                } else {
                    // Get last array position
                    end($this->action_result);
                    $key = key($this->action_result);

                    return $this->action_result[$key];
                }
            } else {
                return false;
            }
        }
    }