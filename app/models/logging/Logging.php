<?php namespace phpietadmin\app\models\logging;
	use phpietadmin\app\models,
		phpietadmin\app\core;

    class Logging {
        private $action_result;
        private $access_result;
        private $debug_result;
        private $write_debug_log;
        private $write_access_log;
        private $write_action_log;
		private $log_dir_path;
		private $action_log_file_path;
		private $debug_log_file_path;
		private $access_log_file_path;
		private $database;

        public function __construct() {
			// log file paths
			$registry = core\Registry::getInstance();
			$this->database = $registry->get('database');
			$this->log_dir_path = $this->database->get_config('log_base')['value'];
			$this->action_log_file_path = $this->log_dir_path . '/' . $this->database->get_config('action_log')['value'];
			$this->debug_log_file_path = $this->log_dir_path . '/' . $this->database->get_config('debug_log')['value'];
			$this->access_log_file_path = $this->log_dir_path . '/' . $this->database->get_config('access_log')['value'];

			// enabled logging options
			$value = $this->database->get_config('debug_log_enabled')['value'];
			if ($value == 0) {
				$this->write_debug_log = false;
			} else {
				$this->write_debug_log = true;
			}

			$value = $this->database->get_config('access_log_enabled')['value'];
			if ($value == 0) {
				$this->write_access_log = false;
			} else {
				$this->write_access_log = true;
			}

			$value = $this->database->get_config('action_log_enabled')['value'];
			if ($value == 0) {
				$this->write_action_log = false;
			} else {
				$this->write_action_log = true;
			}
        }

        private function write_to_access_log_file() {
			if ($this->write_access_log === true) {
				if (is_array($this->access_result)) {
					end($this->access_result);
					$key = key($this->access_result);

					// handle call via webserver and cli
					if (is_array($_SERVER) && isset($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'])) {
						$line = time() . ' ' . $_SESSION['username'] . ' ' . $_SERVER['REMOTE_ADDR'] . ' "' . $_SERVER['HTTP_USER_AGENT'] . '" ' . session_id() . ' "' . $this->access_result[$key]['message'] . '" ' . $this->access_result[$key]['status'] . ' ' . $this->access_result[$key]['type'] . ' ' . $this->access_result[$key]['method'] . "\n";
					} else {
						$line = time() .  ' "' . $this->action_result[$key]['message'] . '" ' . $this->action_result[$key]['status'] . ' ' . $this->action_result[$key]['type'] . ' ' . $this->action_result[$key]['method'] . "\n";
					}

					file_put_contents($this->access_log_file_path, $line, FILE_APPEND | LOCK_EX);
				}
			}
        }

        private function write_to_debug_log_file() {
			if ($this->write_debug_log === true) {
				if (is_array($this->debug_result)) {
					end($this->debug_result);
					$key = key($this->debug_result);

					// handle call via webserver and cli
					if (is_array($_SERVER) && isset($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'])) {
                        if (isset($this->debug_result[$key]['message'])) {
                            $line = time() . ' "' . $this->debug_result[$key]['message'] . "\"\n" . $_SERVER['REMOTE_ADDR'] . ' "' . $_SERVER['HTTP_USER_AGENT'] . '" ' . session_id() . ' '  . "\n" . $this->debug_result[$key]['trace'];
                        } else {
                            $line = time() . ' ' . $_SERVER['REMOTE_ADDR'] . ' "' . $_SERVER['HTTP_USER_AGENT'] . '" ' . session_id() . ' ' . "\n" . $this->debug_result[$key]['trace'];
                        }
					} else {
                        if (isset($this->debug_result[$key]['message'])) {
                            $line = time() . ' ' . $this->debug_result[$key]['message'] . "\n" . $this->debug_result[$key]['trace'];
                        } else {
                            $line = time() . "\n" . $this->debug_result[$key]['trace'];
                        }
					}

					file_put_contents($this->debug_log_file_path, $line, FILE_APPEND | LOCK_EX);
				}
			}
        }

        /**
         * Writes the data from $this->action_result to the phpietadmin action log file
         *
         * @return void
         *
         */
        private function write_to_action_log_file() {
			if ($this->write_action_log === true) {
				if (is_array($this->action_result)) {
					end($this->action_result);
					$key = key($this->action_result);

					// handle call via webserver and cli
					if (is_array($_SERVER) && isset($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'])) {
						$line = time() .  ' ' . $_SERVER['REMOTE_ADDR'] . ' "' . $_SERVER['HTTP_USER_AGENT'] . '" ' . session_id() . ' "' . $this->action_result[$key]['message'] . '" ' . $this->action_result[$key]['code_type'] . ' ' . $this->action_result[$key]['code'] . ' ' . $this->action_result[$key]['method'] . "\n";
					} else {
						$line = time() . ' "' . $this->action_result[$key]['message'] . '" ' . $this->action_result[$key]['code_type'] . ' ' . $this->action_result[$key]['code'] . ' ' . $this->action_result[$key]['method'] . "\n";
					}

					file_put_contents($this->action_log_file_path, $line, FILE_APPEND | LOCK_EX);
				}
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
        public function log_action_result($message, $return, $function, $reset = false) {
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

        public function log_debug_result($message = false) {
            if (!is_array($this->debug_result)) {
                $this->debug_result = [];
            }

            $temp = array (
                'session_id' => session_id(),
                'trace' => $this->generateCallTrace()
            );

            if ($message !== false) {
                $temp['message'] = $message;
            };

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

        /**
         * @return string
         * @link http://php.net/manual/de/function.debug-backtrace.php
         */
        protected function generateCallTrace() {
            $e = new \Exception();
            $trace = explode("\n", $this->getExceptionTraceAsString($e));

            // reverse array to make steps line up chronologically
            $trace = array_reverse($trace);
            array_shift($trace); // remove {main}
            array_pop($trace); // remove call to this method
            array_pop($trace); // remove call to log_debug_result method
            $length = count($trace);
            $result = array();

            for ($i = 0; $i < $length; $i++)
            {
                $result[] = ($i + 1)  . ')' . substr($trace[$i], strpos($trace[$i], ' ')); // replace '#someNum' with '$i)', set the right ordering
            }

            return "\t" . implode("\n\t", $result) . "\n\n";
        }

        /**
         * php's internal getTraceAsString function truncates the output
         *
         * @link http://stackoverflow.com/questions/1949345/how-can-i-get-the-full-string-of-php-s-gettraceasstring
         */
        protected function getExceptionTraceAsString($exception) {
            $rtn = "";
            $count = 0;
            foreach ($exception->getTrace() as $frame) {
                $args = "";
                if (isset($frame['args'])) {
                    $args = array();
                    foreach ($frame['args'] as $arg) {
                        if (is_string($arg)) {
                            $args[] = "'" . $arg . "'";
                        } elseif (is_array($arg)) {
                            $args[] = "Array";
                        } elseif (is_null($arg)) {
                            $args[] = 'NULL';
                        } elseif (is_bool($arg)) {
                            $args[] = ($arg) ? "true" : "false";
                        } elseif (is_object($arg)) {
                            $args[] = get_class($arg);
                        } elseif (is_resource($arg)) {
                            $args[] = get_resource_type($arg);
                        } else {
                            $args[] = $arg;
                        }
                    }
                    $args = join(", ", $args);
                }
                if (isset($frame['file'], $frame['line'])) {
                    $rtn .= sprintf( "#%s %s(%s): %s(%s)\n",
                        $count,
                        $frame['file'],
                        $frame['line'],
                        $frame['function'],
                        $args );
                    $count++;
                } else {
                    $rtn .= sprintf( "#%s %s(%s)\n",
                        $count,
                        $frame['function'],
                        $args );
                    $count++;
                }
            }
            return $rtn;
        }
    }