<?php
    class Logging extends Std {
        private $result;

        public function __construct() {
            // open log file here
            // log all messages with $time, $user, $sourceip, $browseragent, $sessionid, $message, $return code
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
         * @return  array, boolean
         *
         */
        protected function set_result($message, $return, $function, $reset = false) {
            if (!is_array($this->result)) {
                $this->result = [];
            } else if ($reset === true) {
                $this->result = [];
            }

            // use array push, so we can save all messages
            // after a method is called, this array will contain a action log
            if (isset($return['status'])) {
                $temp = array (
                    'message' => $message,
                    'code' => $return['result'],
                    'code_type' => $return['code_type'],
                    'status' => $return['status'],
                    'method' => $function
                );
            } else {
                $temp = array (
                    'message' => $message,
                    'code' => $return['result'],
                    'code_type' => $return['code_type'],
                    'method' => $function
                );
            }

            array_push($this->result, $temp);

            // the first message is always a success message
            // right now, this function should only log errors
            // so never log if the return code is 0
        }

        /**
         *
         * returns the result of the last time set_result() was called
         * next time you should call set_result() with $reset = true, to delete old results
         *
         * @param   boolean $all by default this function only returns the last result, their could be multiple
         * @return  array, boolean
         *
         */
        public function get_result($all = false) {
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