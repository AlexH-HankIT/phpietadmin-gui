<?php
    class Database extends SQLite3 {
        public function __construct() {
            $dbpath = "/usr/share/phpietadmin/app/config.db";

            if (is_writable($dbpath)) {
                $this->open($dbpath, SQLITE3_OPEN_READWRITE);
            } else {
                echo "<h1>Database connection failed</h1>";
                die();
            }
        }

        public function get_config($option) {
            $data = $this->prepare('SELECT value from config where option=:option');
            $data->bindValue('option', $option, SQLITE3_TEXT );
            $result = $data->execute();
            $result = $result->fetchArray();
            return $result['value'];
        }

        public function ispath($option) {
            $data = $this->prepare('SELECT ispath from config where option=:option');
            $data->bindValue('option', $option, SQLITE3_TEXT );
            $result = $data->execute();
            $result = $result->fetchArray();
            return $result['ispath'];
        }

        public function set_config($option, $value) {
            $data = $this->prepare('UPDATE CONFIG SET VALUE=:value where option=:option');
            $data->bindValue('value', $value, SQLITE3_TEXT);
            $data->bindValue('option', $option, SQLITE3_TEXT);
            $data->execute();
            return $this->return_last_error();
        }

        public function get_object_types() {
            $data = $this->query('select value from types');

            $counter=0;
            while ($result = $data->fetchArray(SQLITE3_NUM)) {
                $data2[$counter] = $result;
                $counter++;
            }
            return $data2;
        }

        public function delete_object($id) {
            $data = $this->prepare('DELETE FROM objects where id=:id');
            $data->bindValue('id', $id, SQLITE3_INTEGER);
            $data->execute();
            return $this->return_last_error();
        }

        public function get_object_value($id) {
            $query = $this->prepare('SELECT value from objects where id=:id');
            $query->bindValue('id', $id, SQLITE3_INTEGER);
            $query = $query->execute();
            $result = $query->fetchArray();
            return $result['value'];
        }

        public function get_object_by_value($value) {
            $query = $this->prepare('SELECT objects.id, objects.value, objects.name, types.value as type from objects, types where objects.type_id = types.type_id and objects.value=:value');
            $query->bindValue('value', $value, SQLITE3_TEXT);
            $query = $query->execute();
            return $query->fetchArray(SQLITE3_ASSOC);
        }

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

        public function add_object($type, $name, $value) {
            $query = $this->prepare('INSERT INTO objects (type_id, value, name) VALUES ((SELECT type_id FROM types WHERE value=:type), :value, :name)');
            $query->bindValue('type', $type, SQLITE3_TEXT);
            $query->bindValue('name', $name, SQLITE3_TEXT);
            $query->bindValue('value', $value, SQLITE3_TEXT);
            $query->execute();
            return $this->return_last_error();
        }

        public function get_all_users() {
            $query = $this->prepare('SELECT id, type, username, password FROM ietusers');
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

        public function get_all_usernames() {
            $query = $this->prepare('SELECT username FROM ietusers');
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
                return 3;
            }
        }

        public function add_ietuser($type, $username, $password) {
            $query = $this->prepare('INSERT INTO ietusers (type, username, password) VALUES (:type, :username, :password)');
            $query->bindValue('type', $type, SQLITE3_TEXT);
            $query->bindValue('username', $username, SQLITE3_TEXT);
            $query->bindValue('password', $password, SQLITE3_TEXT);
            $query->execute();
            return $this->return_last_error();
        }

        public function delete_ietuser($id) {
            $data = $this->prepare('DELETE FROM ietusers where id=:id');
            $data->bindValue('id', $id, SQLITE3_INTEGER);
            $data->execute();
            return $this->return_last_error();
        }

        public function __destruct() {
            $this->close();
        }

        public function return_last_error() {
            return SQLite3::lastErrorCode();
        }
    }
?>
