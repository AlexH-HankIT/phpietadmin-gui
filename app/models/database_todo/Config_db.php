<?php namespace phpietadmin\app\models\database;

define('CONFIG_DB_PATH', '/usr/share/phpietadmin/app/config.db');

class Config_db extends Base {
	public function __construct() {
		if (is_writable(CONFIG_DB_PATH)) {
			$this->open(CONFIG_DB_PATH, SQLITE3_OPEN_READWRITE);

			$this->log_dir_path = $this->get_config('log_base')['value'];
			$this->database_log_file_path = $this->log_dir_path . '/' . $this->get_config('database_log')['value'];

			$value = $this->get_config('database_log_enabled')['value'];
			if ($value == 0) {
				$this->database_action_log = false;
			} else {
				$this->database_action_log = true;
			}
		} else {
			echo "<h1>Connection to database config.db failed</h1>";
			die();
		}
	}

	public function get_config_by_category($category) {
		$query = $this->prepare('SELECT config_category_id from phpietadmin_config_category WHERE category = :category');
		$query->bindValue('category', $category, SQLITE3_TEXT);
		$query = $query->execute();

		if ($query !== false ) {
			$category = $query->fetchArray(SQLITE3_ASSOC);

			if (empty($category)) {
				return false;
			} else {
				$sql = <<< EOT
				SELECT phpietadmin_config.option,
				phpietadmin_config.value,
				(SELECT type FROM phpietadmin_config_type WHERE phpietadmin_config_type.config_type_id = phpietadmin_config.config_type_id) as type,
				(SELECT category FROM phpietadmin_config_category WHERE phpietadmin_config_category.config_category_id = phpietadmin_config.config_category_id) as category,
				phpietadmin_config.description,
				phpietadmin_config.field,
				phpietadmin_config.editable_via_gui,
				phpietadmin_config.optioningui FROM
				phpietadmin_config WHERE
				phpietadmin_config.config_category_id = :id
EOT;

				$query = $this->prepare($sql);
				$query->bindValue('id', $category['config_category_id'], SQLITE3_INTEGER);
				$query = $query->execute();

				while ($result = $query->fetchArray(SQLITE3_ASSOC)) {
					$data[] = $result;
				}

				if (empty($data)) {
					return false;
				} else {
					return $data;
				}
			}
		} else {
			$this->log_database_result('Unable to prepare statement!', __METHOD__);
			return false;
		}
	}

	/**
	 * @param $option
	 * @param $value
	 * @param $row
	 * @return int
	 * ToDo: Check if config is really editable via gui!!
	 */
	public function update_config($option, $value, $row) {
		$query = $this->prepare('UPDATE phpietadmin_config SET :row = :value WHERE option = :option');
		if ($query !== false ) {
			$query->bindValue('row', $row, SQLITE3_TEXT);
			$query->bindValue('value', $value, SQLITE3_TEXT);
			$query->bindValue('option', $option, SQLITE3_TEXT);
			$query->execute();
			return $this->return_last_error();
		} else {
			$this->log_database_result('Unable to prepare statement!', __METHOD__);
			return false;
		}
	}

	/**
	 *
	 * Close database connection
	 *
	 * @return    void
	 *
	 */
	public function __destruct()
	{
		$this->close();
	}
}