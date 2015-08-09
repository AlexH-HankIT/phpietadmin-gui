<?php
    // Generic classes
    require_once('../../../Database.php');
    require_once('../../Std.php');
    require_once('../../logging/Logging.php');
    require_once('./../logic/Exec.php');
    require_once('./../logic/Parser.php');

    class Pv extends Exec {
        public function __construct() {
            // Get paths for binaries in Exec class
            parent::__construct();
        }

        public function get_pv($pv_name = false) {

        }
    }