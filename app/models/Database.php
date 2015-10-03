<?php namespace phpietadmin\app\models;
define('DBPATH', '/usr/share/phpietadmin/app/config.db');
use Sqlite3;

class Database extends \SQLite3 {
	public $database_result;
	private $database_action_log;
	private $database_log_file_path;
	private $log_dir_path;

    /**
     *
     * Open database connection
     *
     */
    public function __construct(){
        if (is_writable(DBPATH)) {
            $this->open(DBPATH, SQLITE3_OPEN_READWRITE);
			$this->busyTimeout(5000);

			$this->log_dir_path = $this->get_config('log_base')['value'];
			$this->database_log_file_path = $this->log_dir_path . '/' . $this->get_config('database_log')['value'];

			$value = $this->get_config('database_log_enabled')['value'];
			if ($value == 0) {
				$this->database_action_log = false;
			} else {
				$this->database_action_log = true;
			}
        } else {
            echo "<h1>Database connection failed</h1>";
            die();
        }
    }

    /**
     * Generic update/insert query
     *
     * ['query'] = query
     * ['param'] =
     *      [name] = name
     *      [value] = value
     *      [type] = type (SQLITE3_TEXT, SQLITE3_INTEGER)
     *
     * @param array $data
     * @return int
     */
    public function change(array $data) {
        $query = $this->prepare($data['query']);
        foreach ($data['params'] as $param) {
            $query->bindValue($param['name'], $param['value'], $param['type']);
        }
        $query->execute();
        return $this->return_last_error();
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
			$query->bindValue('line', $row, SQLITE3_TEXT);
			$query->bindValue('value', $value, SQLITE3_TEXT);
			$query->bindValue('option', $option, SQLITE3_TEXT);
			$query->execute();
			return $this->return_last_error();
		} else {
			$this->log_database_result('Unable to prepare statement!', __METHOD__);
			return false;
		}
    }

    public function add_phpietadmin_user($username, $hash) {
		$query = $this->prepare('INSERT INTO phpietadmin_phpietadmin_user (username, password) VALUES (:username, :pwhash)');
		$query->bindValue('username', $username, SQLITE3_TEXT);
		$query->bindValue('pwhash', $hash, SQLITE3_TEXT);
		$query->execute();
		return $this->return_last_error();
    }

    public function delete_phpietadmin_user($username) {
		$query = $this->prepare('DELETE FROM phpietadmin_phpietadmin_user WHERE username: username');
		$query->bindValue('username', $username, SQLITE3_TEXT);
		$query->execute();
		return $this->return_last_error();
    }

    public function update_phpietadmin_user($row, $value, $username) {
		$query = $this->prepare('UPDATE phpietadmin_phpietadmin_user SET :row = :value WHERE username = :username');
		$query->bindValue('row', $row, SQLITE3_TEXT);
		$query->bindValue('value', $value, SQLITE3_TEXT);
		$query->bindValue('username', $username, SQLITE3_TEXT);
		$query->execute();
		return $this->return_last_error();
    }

	/**
	 * Return all phpietadmin login users
	 *
	 * @param $username string|bool $username of the user, from which the data should be fetched, if false all data will be returned
	 * @return array|bool
	 *
	 */
    public function get_phpietadmin_user($username = false) {
		if ($username === false) {
			$query = $this->prepare('SELECT user_id, username, password, session_id FROM phpietadmin_user');
		} else {
            $query = $this->prepare('SELECT user_id, username, password, session_id FROM phpietadmin_user WHERE username = :username');
			$query->bindValue('username', $username, SQLITE3_TEXT);
		}

		$query = $query->execute();

		$data = array();
		while ($result = $query->fetchArray(SQLITE3_ASSOC)) {
			$data[] = $result;
		}

		if (empty($data)) {
			return false;
		} else {
			return $data;
		}
    }

	// Rework everything down here

    /**
     *
     * Get all objects type, e.g. regex, iqn, ipv4 host
     *
     * @return    int
     *
     */
    public function get_object_types() {
        $query = $this->query('select value from phpietadmin_object_type');

        $counter = 0;
        while ($result = $query->fetchArray(SQLITE3_NUM)) {
            $types[$counter] = $result;
            $counter++;
        }
        return $types;
    }

    /**
     *
     * Get value of an object identified by its id
     *
     * @param     int $id id of the object which should be fetched
     * @return    string
     *
     */
    public function get_object_value($id) {
        $query = $this->prepare('SELECT value from phpietadmin_object where id=:id');
        $query->bindValue('id', $id, SQLITE3_INTEGER);
        $query = $query->execute();
        $result = $query->fetchArray();
        return $result['value'];
    }

    /**
     *
     * Get value of an object identified by its value
     *
     * @param     string $value value of the object which should be fetched
     * @return    array
     *
     */
    public function get_object_by_value($value) {
        $query = $this->prepare('SELECT phpietadmin_object.type_id, phpietadmin_object.value, phpietadmin_object_type.display_name, phpietadmin_object.name, phpietadmin_object_type.value as type from phpietadmin_object, phpietadmin_object_type where phpietadmin_object.type_id = phpietadmin_object_type.type_id and phpietadmin_object.value=:value');
        $query->bindValue('value', $value, SQLITE3_TEXT);
        $query = $query->execute();
        return $query->fetchArray(SQLITE3_ASSOC);
    }

    /**
     *
     * Get all objects
     *
     * @return    array, int
     *
     */
    public function get_all_object_values()
    {
        $query = $this->prepare('SELECT value from phpietadmin_object');
        $query = $query->execute();
        while ($result = $query->fetchArray(SQLITE3_NUM)) {
            $data[] = $result;
        }
        if (isset($data) && !empty($data)) {
            foreach ($data as $value) {
                $objects[] = $value[0];
            }
            return $objects;
        } else {
            return 0;
        }
    }

    /**
     *
     * Get all objects
     *
     * @return    array, int
     *
     */
    public function get_all_objects()
    {
        $query = $this->prepare('select phpietadmin_object.id as objectid, phpietadmin_object.name as name, phpietadmin_object.value, phpietadmin_object_type.display_name as type from phpietadmin_object, phpietadmin_object_type where phpietadmin_object.type_id=phpietadmin_object_type.type_id');
        $query = $query->execute();

        $counter = 0;
        while ($result = $query->fetchArray(SQLITE3_ASSOC)) {
            $data[$counter] = $result;
            $counter++;
        }

        if (!empty($data)) {
            return $data;
        } else {
            return 3;
        }
    }

    /**
     *
     * Get all users
     *
     * @return    array|int
     *
     */
    public function get_all_users()
    {
        $query = $this->prepare('SELECT id, username, password FROM phpietadmin_iet_user');
        $query = $query->execute();

        $counter = 0;
        while ($result = $query->fetchArray(SQLITE3_ASSOC)) {
            $data[$counter] = $result;
            $counter++;
        }

        if (!empty($data)) {
            return $data;
        } else {
            return 3;
        }
    }

    /**
     *
     * Get iet user identified by its id
     *
     * @param     int $id id of the ietd user
     * @return    array, int
     *
     */
    public function get_ietuser($id)
    {
        $query = $this->prepare('SELECT username, password FROM phpietadmin_iet_user where id=:id');
        $query->bindValue('id', $id, SQLITE3_TEXT);
        $query = $query->execute();

        $data = $query->fetchArray(SQLITE3_ASSOC);

        if (!empty($data)) {
            return $data;
        } else {
            return 0;
        }
    }

    /**
     *
     * Get ietd user identified by its username
     *
     * @param     string $username name of the user
     * @return    array, int
     *
     */
    public function get_user_by_name($username)
    {
        $query = $this->prepare('SELECT id, password FROM phpietadmin_iet_user where username=:username');
        $query->bindValue('username', $username, SQLITE3_TEXT);
        $query = $query->execute();
        $data = $query->fetchArray(SQLITE3_ASSOC);

        if (!empty($data)) {
            return $data;
        } else {
            return 0;
        }
    }

    /**
     *
     * Change phpietadmin login user password
     *
     * @param     string $pwhash sha256 hash of the password
     * @param     string $username name of the user, default: admin
     * @return    int
     *
     */
    public function edit_login_user($pwhash, $username = 'admin')
    {
        $query = $this->prepare('UPDATE phpietadmin_phpietadmin_user SET password=:pwhash where username=:username');
        $query->bindValue('username', $username, SQLITE3_TEXT);
        $query->bindValue('pwhash', $pwhash, SQLITE3_TEXT);
        $query->execute();
        return $this->return_last_error();
    }

    /**
     *
     * Get all available iet users
     *
     * @param     boolean $id true will fetch users with their id, default: false
     * @return    array, int
     *
     */
    public function get_all_usernames($id = false)
    {
        if ($id === false) {
            $query = $this->prepare('SELECT username from phpietadmin_iet_user');
            $query = $query->execute();

            $counter = 0;
            while ($result = $query->fetchArray(SQLITE3_NUM)) {
                $data[$counter] = $result[0];
                $counter++;
            }

        } else {
            $query = $this->prepare('SELECT id, username from phpietadmin_iet_user');
            $query = $query->execute();

            $counter = 0;
            while ($result = $query->fetchArray(SQLITE3_ASSOC)) {
                $data[$counter] = $result;
                $counter++;
            }
        }

        if (!empty($data)) {
            return $data;
        } else {
            return 0;
        }
    }

    /**
     *
     * Get all services or all enabled services
     *
     * @param     boolean $all fetch all services or only enabled once, default is false
     * @return    int
     *
     */
    public function get_services($all = false)
    {
        // If all is true, fetch all services
        // else fetch only enabled ones
        if ($all) {
            $query = $this->prepare('SELECT name, enabled FROM phpietadmin_service');
        } else {
            $query = $this->prepare('SELECT name, enabled FROM phpietadmin_service where enabled=1');
        }

        $query = $query->execute();

        $counter = 0;
        while ($result = $query->fetchArray(SQLITE3_ASSOC)) {
            $data[$counter] = $result;
            $counter++;
        }

        if (empty($data)) {
            return 0;
        } else {
            return $data;
        }
    }

    /**
     *
     * Fetch all ietd settings from the database
     *
     * @return    boolean, array
     *
     */
    public function get_iet_settings()
    {
        $query = $this->prepare('SELECT option, defaultvalue, type, state, chars, othervalue1 FROM phpietadmin_iet_setting');
        $query = $query->execute();

        $counter = 0;
        while ($result = $query->fetchArray(SQLITE3_ASSOC)) {
            $data[$counter] = $result;
            $counter++;
        }

        if (empty($data)) {
            return false;
        } else {
            return $data;
        }
    }

    public function get_session($session_id) {
        $query = $this->prepare('SELECT last_activity, user_agent, remote_address, data FROM phpietadmin_session where session_id = :session_id');

        if ($query === false) {
            return false;
        } else {
            $query->bindValue('session_id', $session_id, SQLITE3_TEXT);
            $query = $query->execute();
            $data = $query->fetchArray(SQLITE3_ASSOC);

            if (empty($data)) {
                return false;
            } else {
                return $data;
            }
        }
    }

    /**
     * @param $session_id
     * @param $status int 0/1 1
     * @return int
     */
    public function set_session_status($session_id, $status) {
        $query = $this->prepare('UPDATE phpietadmin_session set logged_in = :status where session_id = :session_id');
        if ($query === false) {
            return false;
        } else {
            $query->bindValue('session_id', $session_id, SQLITE3_TEXT);
            $query->bindValue('status', $status, SQLITE3_INTEGER);
            $query->execute();
            return $this->return_last_error();
        }
    }

    public function add_session($session_id) {
        $sql = <<< EOT
		INSERT INTO phpietadmin_session (session_id, last_activity, user_agent, remote_address) values (:session_id, :last_activity, :user_agent, :remote_address)
EOT;
        $query = $this->prepare($sql);

        if ($query === false) {
            return false;
        } else {
            $query->bindValue('session_id', $session_id, SQLITE3_TEXT);
            $query->bindValue('last_activity', time(), SQLITE3_TEXT);
            $query->bindValue('user_agent', $_SERVER['HTTP_USER_AGENT'], SQLITE3_TEXT);
            $query->bindValue('remote_address', $_SERVER['REMOTE_ADDR'], SQLITE3_TEXT);
            $query->execute();
            return $this->return_last_error();
        }
    }

    public function delete_session($session_id) {
        $query = $this->prepare('DELETE FROM phpietadmin_session WHERE session_id = :session_id');
        $query->bindValue('session_id', $session_id, SQLITE3_TEXT);
        $query->execute();
        return $this->return_last_error();
    }

    public function update_session_data($session_id, $data) {
        $query = $this->prepare('UPDATE phpietadmin_session SET data=:data WHERE session_id = :session_id');
        $query->bindValue('session_id', $session_id, SQLITE3_TEXT);
        $query->bindValue('data', $data, SQLITE3_TEXT);
        $query->execute();
        return $this->return_last_error();
    }

    public function update_session_activity($session_id) {
        $query = $this->prepare('UPDATE phpietadmin_session SET last_activity = :time WHERE session_id = :session_id');
        $query->bindValue('session_id', $session_id, SQLITE3_TEXT);
        $query->bindValue('time', time(), SQLITE3_INTEGER);
        $query->execute();
        return $this->return_last_error();
    }

    public function delete_session_garbage($max_life_time) {
        $query = $this->prepare('DELETE FROM phpietadmin_session WHERE last_activity + :max_life_time < :time');
        $query->bindValue('max_life_time', $max_life_time, SQLITE3_INTEGER);
        $query->bindValue('time', time(), SQLITE3_INTEGER);
        $query->execute();
        return $this->return_last_error();
    }

    public function update_session_for_user($username, $session_id) {
        if ($session_id === NULL) {
            $query = $this->prepare('UPDATE phpietadmin_phpietadmin_user SET phpietadmin_session_id = ""');
        } else {
            $query = $this->prepare('UPDATE phpietadmin_phpietadmin_user SET phpietadmin_session_id = (SELECT id FROM phpietadmin_session WHERE phpietadmin_session.session_id = :session_id) WHERE phpietadmin_phpietadmin_user.username = :username');
            $query->bindValue('session_id', $session_id, SQLITE3_TEXT);
            $query->bindValue('username', $username, SQLITE3_TEXT);
        }

        $query->execute();
        return $this->return_last_error();
    }

    /**
     *
     * Fetch session data for username
     *
     * @return     array, boolean
     *
     */
    public function get_session_by_username($username) {
        $sql = <<< EOT
		SELECT phpietadmin_phpietadmin_user.username,
	   phpietadmin_phpietadmin_user.password,
	   phpietadmin_phpietadmin_user.permission,
	   phpietadmin_session.session_id as session_id,
	   phpietadmin_session.last_activity,
	   phpietadmin_session.user_agent,
	   phpietadmin_session.remote_address,
	   phpietadmin_session.data FROM
	   phpietadmin_phpietadmin_user,
	   phpietadmin_session WHERE
	   phpietadmin_phpietadmin_user.phpietadmin_session_id = phpietadmin_session.id AND
	   phpietadmin_phpietadmin_user.username = :username;
EOT;

        $query = $this->prepare($sql);

        $query->bindValue('username', $username, SQLITE3_TEXT);

        $result = $query->execute();

        $result = $result->fetchArray(SQLITE3_ASSOC);

        if (empty($result)) {
            return false;
        } else {
            return $result;
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

    /**
     *
     * Returns last error code
     *
     * @return    int
     *
     */
    public function return_last_error() {
        return SQLite3::lastErrorCode();
    }
}