<?php namespace phpietadmin\app\models;
    define('dbpath', '/usr/share/phpietadmin/app/config.db');
    use Sqlite3;

    class Database extends \SQLite3 {
            /**
             *
             * Open database connection
             *
             *
             */
            public function __construct() {
                if (is_writable(dbpath)) {
                    $this->open(dbpath, SQLITE3_OPEN_READWRITE);
                } else {
                    echo "<h1>Database connection failed</h1>";
                    die();
                }
            }

            /**
             *
             * Adds a session to the database
             *
             * @return      int
             *
             */
            public function add_session($session_id, $username, $timestamp, $source_ip, $browser_agent) {
                $query = $this->prepare('INSERT INTO sessions (session_id, username_id, login_time, source_ip, browser_agent)
                                        VALUES (:session_id, (SELECT id from user where username=:username), :login_time, :source_ip, :browser_agent)');

                $query->bindValue('session_id', $session_id, SQLITE3_TEXT );
                $query->bindValue('username', $username, SQLITE3_TEXT );
                $query->bindValue('login_time', $timestamp, SQLITE3_TEXT );
                $query->bindValue('source_ip', $source_ip, SQLITE3_TEXT );
                $query->bindValue('browser_agent', $browser_agent, SQLITE3_TEXT );

                $query->execute();
                return $this->return_last_error();
            }

            /**
             *
             * Deletes a session from the database
             *
             * @return      int
             *
             */
            public function delete_session($session_id, $username) {
                $query = $this->prepare('DELETE FROM sessions where session_id=:session_id and username_id=(SELECT id from user where username=:username)');

                $query->bindValue('session_id', $session_id, SQLITE3_TEXT );
                $query->bindValue('username', $username, SQLITE3_TEXT );

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
            public function get_sessions_by_username($username) {
                $query = $this->prepare('select session_id, username_id, login_time, source_ip, browser_agent from sessions where username_id=(select id from user where username=:username)');

                $query->bindValue('username', $username, SQLITE3_TEXT );

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
             * Fetch value for config option
             * If the value is a binary sudo will be prepended
             *
             * @param     string   $option option to get the value from
             * @return    string
             *
             */
            /* ToDo: Error handling? */
            public function get_config($option) {
                $data = $this->prepare('SELECT category, value from config where option=:option');
                $data->bindValue('option', $option, SQLITE3_TEXT );
                $result = $data->execute();
                $result = $result->fetchArray();

                // if the fetched value is a binary we prepend sudo
                if ($result['category'] == 4) {
                    $sudo = $this->prepare('SELECT value from config where option=\'sudo\'');
                    $sudo = $sudo->execute();
                    $sudo = $sudo->fetchArray();

                    return $sudo['value'] . ' ' . $result['value'];
                } else {
                    return $result['value'];
                }
            }

            /**
             *
             * Fetch the ispath value from the database
             *
             * @param     string   $option option to get the value from
             * @return    string
             *
             */
            public function ispath($option) {
                $data = $this->prepare('SELECT ispath from config where option=:option');
                $data->bindValue('option', $option, SQLITE3_TEXT );
                $result = $data->execute();
                $result = $result->fetchArray();
                return $result['ispath'];
            }

            /**
             *
             * Insert option and value into the database
             *
             * @param     string   $option option which should be inserted
             * @param     string   $value value which should be inserted
             * @return    int
             *
             */
            public function set_config($option, $value) {
                $query = $this->prepare('UPDATE CONFIG SET VALUE=:value where option=:option');
                $query->bindValue('value', $value, SQLITE3_TEXT);
                $query->bindValue('option', $option, SQLITE3_TEXT);
                $query->execute();
                return $this->return_last_error();
            }

            /**
             *
             * Get all objects type, e.g. regex, iqn, ipv4 host
             *
             * @return    int
             *
             */
            public function get_object_types() {
                $query = $this->query('select value from types');

                $counter=0;
                while ($result = $query->fetchArray(SQLITE3_NUM)) {
                    $types[$counter] = $result;
                    $counter++;
                }
                return $types;
            }

            /**
             *
             * Get all objects type, e.g. regex, iqn, ipv4 host
             *
             * @param     int  $id id of the object, which should be deleted
             * @return    int
             *
             */
            public function delete_object($id) {
                $query = $this->prepare('DELETE FROM objects where id=:id');
                $query->bindValue('id', intval($id), SQLITE3_INTEGER);
                $query->execute();
                return $this->return_last_error();
            }

            /**
             *
             * Get value of an object identified by its id
             *
             * @param     int  $id id of the object which should be fetched
             * @return    string
             *
             */
            public function get_object_value($id) {
                $query = $this->prepare('SELECT value from objects where id=:id');
                $query->bindValue('id', $id, SQLITE3_INTEGER);
                $query = $query->execute();
                $result = $query->fetchArray();
                return $result['value'];
            }

            /**
             *
             * Get value of an object identified by its value
             *
             * @param     string  $value value of the object which should be fetched
             * @return    array
             *
             */
            public function get_object_by_value($value) {
                $query = $this->prepare('SELECT objects.id, objects.value, objects.name, types.value as type from objects, types where objects.type_id = types.type_id and objects.value=:value');
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
            public function get_all_object_values() {
                $query = $this->prepare('SELECT value from objects');
                $query = $query->execute();
                $counter=0;
                while ($result = $query->fetchArray(SQLITE3_NUM)) {
                    $data[$counter] = $result;
                    $counter++;
                }
                if (isset($data) && !empty($data)) {
                    $counter=0;
                    foreach ($data as $value) {
                        $objects[$counter] = $value[0];
                        $counter++;
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
            public function get_all_objects() {
                $query = $this->prepare('select objects.id as objectid, objects.name as name, objects.value, types.display_name as type from objects, types where objects.type_id=types.type_id');
                $query = $query->execute();

                $counter=0;
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
             * Get value of an object identified by its value
             *
             * @param     string  $type type of the object (from types table)
             * @param     string  $name name of the object
             * @param     string  $value value of the object which should be fetched
             * @return    int
             *
             */
            public function add_object($type, $name, $value) {
                $query = $this->prepare('INSERT INTO objects (type_id, value, name) VALUES ((SELECT type_id FROM types WHERE value=:type), :value, :name)');
                $query->bindValue('type', $type, SQLITE3_TEXT);
                $query->bindValue('name', $name, SQLITE3_TEXT);
                $query->bindValue('value', $value, SQLITE3_TEXT);
                $query->execute();
                return $this->return_last_error();
            }

            /**
             *
             * Get all users
             *
             * @return    array, int
             *
             */
            public function get_all_users() {
                $query = $this->prepare('SELECT id, username, password FROM ietusers');
                $query = $query->execute();

                $counter=0;
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
            public function get_ietuser($id) {
                $query = $this->prepare('SELECT username, password FROM ietusers where id=:id');
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
            public function get_user_by_name($username) {
                $query = $this->prepare('SELECT id, password FROM ietusers where username=:username');
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
            public function edit_login_user($pwhash, $username='admin') {
                $query = $this->prepare('UPDATE user SET password=:pwhash where username=:username');
                $query->bindValue('username', $username, SQLITE3_TEXT);
                $query->bindValue('pwhash', $pwhash, SQLITE3_TEXT);
                $query->execute();
                return $this->return_last_error();
            }

            /**
             *
             * Get all available ietd users
             *
             * @param     boolean $id true will fetch users with their id, default: false
             * @return    array, int
             *
             */
            public function get_all_usernames($id = false) {
                if (!$id) {
                    $query = $this->prepare('SELECT username from ietusers');
                    $query = $query->execute();

                    $counter=0;
                    while ($result = $query->fetchArray(SQLITE3_NUM)) {
                        $data[$counter] = $result[0];
                        $counter++;
                    }

                } else {
                    $query = $this->prepare('SELECT id, username from ietusers');
                    $query = $query->execute();

                    $counter=0;
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
             * Add a ietd user to the database
             *
             * @param     string $username name of the user
             * @param     string $password plain text password of the user
             * @return    array, int
             *
             */
            public function add_ietuser($username, $password) {
                $query = $this->prepare('INSERT INTO ietusers (username, password) VALUES (:username, :password)');
                $query->bindValue('username', $username, SQLITE3_TEXT);
                $query->bindValue('password', $password, SQLITE3_TEXT);
                $query->execute();
                return $this->return_last_error();
            }

            /**
             *
             * Delete a ietd user from the database
             *
             * @param     int $id id of the ietd user
             * @return    int
             *
             */
            public function delete_ietuser($id) {
                $data = $this->prepare('DELETE FROM ietusers where id=:id');
                $data->bindValue('id', $id, SQLITE3_INTEGER);
                $data->execute();
                return $this->return_last_error();
            }

            /**
             *
             * Change a service state
             *
             * @param     string $name name of the service
             * @param     string $option the option that should be changed, (right now only 'enabled')
             * @param     string $value
             * @return    array, int
             *
             */
            public function change_service($name, $option, $value) {
                // $name: servicename
                // $option: option to be changed
                // $value: new value
                if ($option == 'enabled') {
                    $query = $this->prepare('UPDATE services set enabled=:value where name = :name');
                } else if ($option == 'name') {
                    $query = $this->prepare('UPDATE services set name=:value where name = :name');
                } else {
                    return 1;
                }

                $query->bindValue('name', $name, SQLITE3_TEXT);
                //$query->bindValue('option', $option, SQLITE3_TEXT);
                $query->bindValue('value', $value, SQLITE3_TEXT);
                $query->execute();
                return $this->return_last_error();
            }


            /**
             *
             * Delete a service from the database
             *
             * @param     string $name name of the service
             * @return    int
             *
             */
            public function delete_service($name) {
                $query = $this->prepare('DELETE FROM services where name = :name');
                $query->bindValue('name', $name, SQLITE3_TEXT);
                $query->execute();
                return $this->return_last_error();
            }

            /**
             *
             * Add a service from the database
             *
             * @param     string $name name of the service
             * @return    int
             *
             */
            public function add_service($name) {
                $query = $this->prepare("INSERT INTO services ('name', 'enabled') VALUES (:name, 1)");
                $query->bindValue('name', $name, SQLITE3_TEXT);
                $query->execute();
                return $this->return_last_error();
            }

            /**
             *
             * Get all services or all enabled services
             *
             * @param     boolean $all fetch all services or only enabled once, default is false
             * @return    int
             *
             */
            public function get_services($all = false) {
                // If all is true, fetch all services
                // else fetch only enabled ones
                if ($all) {
                    $query = $this->prepare('SELECT name, enabled FROM services');
                } else {
                    $query = $this->prepare('SELECT name, enabled FROM services where enabled=1');
                }

                $query = $query->execute();

                $counter=0;
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
            public function get_iet_settings() {
                $query = $this->prepare('SELECT option, defaultvalue, type, state, chars, othervalue1 FROM ietsettings');
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

            /**
             *
             * Close database connection
             *
             * @return    void
             *
             */
            public function __destruct() {
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