<?php namespace phpietadmin\app\models\database;

use SQLite3,
	phpietadmin\app\core;

class Base extends \SQLite3 {
	public $database_result;
	protected $database_action_log;
	protected $database_log_file_path;
	protected $log_dir_path;

	public function __construct(){
		$this->log_dir_path = $this->get_config('log_base')['value'];
		$this->database_log_file_path = $this->log_dir_path . '/' . $this->get_config('database_log')['value'];

		$value = $this->get_config('database_log_enabled')['value'];
		if ($value == 0) {
			$this->database_action_log = false;
		} else {
			$this->database_action_log = true;
		}
	}

	private function write_to_database_log_file() {
		if ($this->database_action_log === true) {
			if (is_array($this->database_result)) {
				end($this->database_result);
				$key = key($this->database_result);

				// handle call via webserver and cli
				if (is_array($_SERVER) && isset($_SERVER['REMOTE_ADDR'], $_SERVER['HTTP_USER_AGENT'])) {
					$line = time() .  ' ' . $_SERVER['REMOTE_ADDR'] . ' ' . $_SERVER['HTTP_USER_AGENT'] . ' ' . session_id() . ' ' . $this->database_result[$key]['sqlite_error_code'] . ' ' . $this->database_result[$key]['sqlite_error_string'] . ' ' . $this->database_result[$key]['message'] . ' ' . $this->database_result[$key]['method'] . "\n";
				} else {
					$line = time() . ' ' . $this->database_result[$key]['sqlite_error_code'] . ' ' . $this->database_result[$key]['sqlite_error_string'] . ' ' . $this->database_result[$key]['message'] . ' ' . $this->database_result[$key]['method'] . "\n";
				}

				file_put_contents($this->database_log_file_path, $line, FILE_APPEND | LOCK_EX);
			}
		}
	}

	public function log_database_result($function, $message) {
		if (!is_array($this->database_result)) {
			$this->database_result = [];
		}

		$temp = array(
			'session_id' => session_id(),
			'sqlite_error_code' => SQLite3::lastErrorCode(),
			'sqlite_error_string' => SQLite3::lastErrorMsg(),
			'method' => $function,
			'message' => $message
		);

		array_push($this->database_result, $temp);

		$this->write_to_database_log_file();
	}

	/**
	 *
	 * Fetch value for config option
	 * If the value is a "super user binary" sudo will be prepended
	 *
	 * @param     string $option option to get the value from
	 * @param     bool $su prepend sudo?
	 * @return    array|bool
	 *
	 */
	public function get_config($option, $su = true) {
		$query = $this->prepare('SELECT phpietadmin_config.option, phpietadmin_config.value, (SELECT type FROM phpietadmin_config_type WHERE phpietadmin_config_type.config_type_id = phpietadmin_config.config_type_id) as type, (SELECT category FROM phpietadmin_config_category WHERE phpietadmin_config_category.config_category_id = phpietadmin_config.config_category_id) as category, phpietadmin_config.description, phpietadmin_config.field, phpietadmin_config.editable_via_gui, phpietadmin_config.optioningui FROM phpietadmin_config WHERE phpietadmin_config.option = :option');

		if ($query !== false) {
			$query->bindValue('option', $option, SQLITE3_TEXT);
			$query = $query->execute();

			if ($query !== false) {
				$query = $query->fetchArray(SQLITE3_ASSOC);

				if (empty($query)) {
					$this->log_database_result('Query returned zero rows!', __METHOD__);
					return false;
				} else {
					// if type is subin, we prepend sudo
					if ($query['type'] == 'subin') {
						if ($su === true) {
							$sudo = $this->get_config('sudo');
							$query['value'] = $sudo['value'] . ' ' . $query['value'];
						}
					}
					return $query;
				}
			} else {
				$this->log_database_result('Unable to execute statement!', __METHOD__);
				return false;
			}
		} else {
			$this->log_database_result('Unable to prepare statement!', __METHOD__);
			return false;
		}
	}
}