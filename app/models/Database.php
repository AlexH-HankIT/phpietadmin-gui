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

        public function get_all_objects() {
            $query = $this->prepare('select objects.id as objectid, objects.name as name, objects.value, types.value as type from objects, types where objects.type_id=types.type_id');
            $query = $query->execute();

            $counter=0;
            while ($result = $query->fetchArray(SQLITE3_ASSOC)) {
                $data[$counter] = $result;
                $counter++;
            }

            return $data;
        }

        public function __destruct() {
            $this->close();
        }

        public function return_last_error() {
            return SQLite3::lastErrorCode();
        }
    }
?>
