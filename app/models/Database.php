<?php
    class Database extends SQLite3 {
        function __construct() {
        $dbpath = "/home/vm/ownCloud/Work/PhpstormProjects/phpietadmin/app/config.db";
		//$dbpath = "/home/dev/ownCloud/Work/PhpstormProjects/phpietadmin/app/config.db";
            $this->open($dbpath);
        }

        function getConfig($option) {
            $data = $this->querySingle('SELECT value from config where option="' . $option . '"', true);
            return $data['value'];
        }
    }
?>
