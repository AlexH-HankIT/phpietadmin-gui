<?php
    class Database extends SQLite3 {
        function __construct() {
            $dbpath = "/usr/share/phpietadmin/app/config.db";
            $this->open($dbpath);
        }

        function getConfig($option) {
            $data = $this->prepare('SELECT value from config where option=:option');
            $data->bindValue('option', $option, SQLITE3_TEXT );
            $result = $data->execute();
            $result = $result->fetchArray();
            return $result['value'];
        }
    }
?>
