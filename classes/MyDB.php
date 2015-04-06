<?php
    class MyDB extends SQLite3 {
        public function __construct($database) {
            $this->open($database);
        }

        public function check_error() {
            return SQLite3::lastErrorCode();
        }

        public function set_config() {
            //Sqlite3::escapeString();
            $this->query('');
        }

        public function get_config($option) {
            $this->query('');
        }
    }
?>