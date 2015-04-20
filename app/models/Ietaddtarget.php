<?php
    class Ietaddtarget {
        public function get_unused_volumes($logicalvolumes) {
            require_once 'Database.php';
            $database = new Database();

            $volumes = file_get_contents($database->getConfig('proc_volumes'));

            if (!empty($volumes)) {
                // Extract volumes if existing

                preg_match_all("/path:(.*)/", $volumes, $paths);
                // Filter already used ones

                $logicalvolumes = array_diff($logicalvolumes, $paths[1]);

                //Rebuild array index
                return array_values($logicalvolumes);
            } else {
                return array_values($logicalvolumes);
            }
        }

        public function check_target_name_already_in_use($NAME) {
            require_once 'Database.php';
            $database = new Database();

            $volumes = file_get_contents($database->getConfig('proc_volumes'));

            if (!empty($volumes)) {
                preg_match_all("/name:(.*)/", $volumes, $a_name);
                $key = array_search($database->getConfig('iqn') . ":" .  $NAME, $a_name[1]);

                $val = $database->getConfig('iqn') . ':' .  $NAME;
                if ($a_name[1][$key] == $val) {
                    return 4;
                }
            } else {
                return 0;
            }
        }

        public function get_tid($NAME) {
            require_once 'Database.php';
            $database = new Database();

            $volumes = file_get_contents($database->getConfig('proc_volumes'));
            preg_match_all("/tid:([0-9].*?) /", $volumes, $a_tid);
            preg_match_all("/name:(.*)/", $volumes, $a_name);
            $key = array_search($NAME, $a_name);
            return $a_tid[1][$key];
        }

        public function write_target_and_lun($NAME, $LV, $TYPE, $MODE) {
            require_once 'Database.php';
            $database = new Database();

            $current = "\nTarget " .  $database->getConfig('iqn') . ":" . $NAME . "\n Lun 0 Type=" . $TYPE . ",IOMode=" . $MODE . ",Path=" . $LV . "\n";
            $return = file_put_contents($database->getConfig('ietd_config_file'), $current, FILE_APPEND | LOCK_EX);
            if ($return == "FALSE" or $return == 0) {
                return 6;
            } else {
                return 0;
            }
        }
    }
?>