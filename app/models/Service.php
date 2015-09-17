<?php namespace phpietadmin\app\models;
	use phpietadmin\app\core;

	class Service extends core\BaseModel {
		// true if the service is in the database
		// false if the service is not in the database
		protected $service_status;
		protected $service_name;
		protected $service_bin;

		public function __construct($service_name) {
			parent::__construct();
			$this->service_name = $service_name;
            $this->check_already_added();
			$this->service_bin = $this->database->get_config('service')['value'];
		}

		// checks if the service is already added to the database
		protected function check_already_added() {
			$data = $this->database->get_services(true);

			$status = $this->std->recursive_array_search($this->service_name, $data);

			if ($status !== false) {
				$this->service_status = true;
			} else {
				$this->service_status = false;
			}
		}

		public function add_to_db() {
			if ($this->service_status === false) {
				$return = $this->database->add_service($this->service_name);
				if ($return == 0) {
					$this->logging->log_action_result('The service ' . $this->service_name . ' was added!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
				} else {
					$this->logging->log_action_result('The service ' . $this->service_name . ' was not added!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
				}
			} else {
				$this->logging->log_action_result('The service ' . $this->service_name . ' is already added!', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
			}
		}

		public function delete_from_db() {
			if ($this->service_status === true) {
				$return = $this->database->delete_service($this->service_name);
				if ($return == 0) {
					$this->logging->log_action_result('The service ' . $this->service_name . ' was deleted!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
				} else {
					$this->logging->log_action_result('The service ' . $this->service_name . ' was not deleted!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
				}
			} else {
				$this->logging->log_action_result('The service ' . $this->service_name . ' is not in the database!', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
			}
		}

		public function rename_in_database($new_service_name) {
			if ($this->service_status === true) {
				$return = $this->database->change_service($this->service_name, 'name', $new_service_name);
				if ($return == 0) {
					$this->logging->log_action_result('The service ' . $this->service_name . ' was renamed to ' . $new_service_name, array('result' => $return, 'code_type' => 'extern'), __METHOD__);
					$this->service_name = $new_service_name;
				} else {
					$this->logging->log_action_result('The service ' . $this->service_name . ' was not renamed to ' . $new_service_name, array('result' => $return, 'code_type' => 'extern'), __METHOD__);
				}
			} else {
				$this->logging->log_action_result('The service ' . $this->service_name . ' is not in the database!', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
			}
		}

		public function change_in_database($param) {
			if ($this->service_status === true) {
				if ($param == 'enable') {
					$return = $this->database->change_service($this->service_name, 'enabled', 1);
					if ($return == 0) {
						$this->logging->log_action_result('The service ' . $this->service_name . ' was enabled!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
					} else {
						$this->logging->log_action_result('The service ' . $this->service_name . ' was not enabled!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
					}
				} else if ($param == 'disable') {
					$return = $this->database->change_service($this->service_name, 'enabled', 0);
					if ($return == 0) {
						$this->logging->log_action_result('The service ' . $this->service_name . ' was disabled!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
					} else {
						$this->logging->log_action_result('The service ' . $this->service_name . ' was not disabled!', array('result' => $return, 'code_type' => 'extern'), __METHOD__);
					}
				} else {
					$this->logging->log_action_result('Invalid action', array('result' => 9, 'code_type' => 'intern'), __METHOD__);
				}
			} else {
				$this->logging->log_action_result('The service ' . $this->service_name . ' is not in the database!', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
			}
		}

		public function action($param) {
			if ($this->service_status === true) {
				if ($param == 'start') {
					$this->logging->log_debug_result($this->service_bin . ' ' . $this->service_name . ' start', __METHOD__, 'exec()');
					$return = $this->std->exec_and_return($this->service_bin . ' ' . $this->service_name . ' start');
					if ($return['result'] === 0) {
						$this->logging->log_action_result('The service ' . $this->service_name . ' was started!', $return, __METHOD__);
					} else {
						$this->logging->log_action_result('The service ' . $this->service_name . ' was not started!', $return, __METHOD__);
					}
				} else if ($param == 'stop') {
					$this->logging->log_debug_result($this->service_bin . ' ' . $this->service_name . ' stop', __METHOD__, 'exec()');
					$return = $this->std->exec_and_return($this->service_bin . ' ' . $this->service_name . ' stop');
					if ($return['result'] === 0) {
						$this->logging->log_action_result('The service ' . $this->service_name . ' was stopped!', $return, __METHOD__);
					} else {
						$this->logging->log_action_result('The service ' . $this->service_name . ' was not stopped!', $return, __METHOD__);
					}
				} else if ($param == 'restart') {
					$this->logging->log_debug_result($this->service_bin . ' ' . $this->service_name . ' restart', __METHOD__, 'exec()');
					$return = $this->std->exec_and_return($this->service_bin . ' ' . $this->service_name . ' restart');
					if ($return['result'] === 0) {
						$this->logging->log_action_result('The service ' . $this->service_name . ' was restarted!', $return, __METHOD__);
					} else {
						$this->logging->log_action_result('The service ' . $this->service_name . ' was not restarted!', $return, __METHOD__);
					}
				} else if ($param == 'status') {
					$this->logging->log_debug_result($this->service_bin . ' ' . $this->service_name . ' status', __METHOD__, 'exec()');
					$return = $this->std->exec_and_return($this->service_bin . ' ' . $this->service_name . ' status');

					if ($return['result'] === 0) {
						$this->logging->log_action_result('The service ' . $this->service_name . ' is running!', $return, __METHOD__);
					} else {
						$this->logging->log_action_result('The service ' . $this->service_name . ' is not running!', $return, __METHOD__);
					}
				} else {
					$this->logging->log_action_result('Invalid action', array('result' => 9, 'code_type' => 'intern'), __METHOD__);
				}
			} else {
				$this->logging->log_action_result('The service ' . $this->service_name . ' is not in the database!', array('result' => 4, 'code_type' => 'intern'), __METHOD__);
			}
		}
	}