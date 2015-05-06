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

            $key = array_search($NAME, $a_name[1]);
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

        public function get_targets() {
            require_once 'Database.php';
            $database = new Database();

            $volumes = file_get_contents($database->getConfig('proc_volumes'));

            if (!empty($volumes)) {
                preg_match_all("/name:(.*)/", $volumes, $a_name);
                for ($i=0; $i < count($a_name[1]); $i++) {
                    $data[$i] = $a_name[1][$i];
                }
                return $data;
            } else {
                return 3;
            }
        }

        public function get_next_lun($TARGET) {
            require_once 'Database.php';
            $database = new Database();

            $data = file_get_contents($database->getConfig('proc_volumes'));

            // Replace all newlines with spaces
            $data = trim(preg_replace('/\s\s+/', ' ', $data));

            // Explode array by 'tid'
            $data = array_values(array_filter(explode('tid:', $data)));

            // Explode arrays by space
            $counter=0;
            foreach ($data as $value) {
                $data2[$counter] = explode(' ', $value);
                $counter++;
            }

            $counter=0;
            foreach ($data2 as $value) {
                // All arrays with less than two rows don't contain interesting data
                if (count($value) > 2) {
                    $volumes[$counter] = $value;
                }
                $counter++;
            }


            // Empty means, target has no luns, therefore we create the first lun with id 0
            if (empty($volumes)) {
                return 0;
            }

            $volumes = array_values($volumes);

            $counter=0;
            foreach ($volumes as $value) {
                preg_match("/name:(.*)/", $value[1], $result);
                $var[$counter][0]['name'] = $result[1];

                for ($i=2; $i < count($value); $i=$i+7) {
                    preg_match("/lun:([0-9].*)/", $value[$i], $result);
                    $var[$counter][$counter+$i]['lun'] = $result[1];
                }

                $counter++;
            }

            // Correct index
            for ($i=0; $i < count($var); $i++) {
                $var[$i] = array_values($var[$i]);
            }

            for ($i=0; $i < count($var); $i++) {
                if ($var[$i][0]['name'] === $TARGET) {
                    for ($b=1; $b < count($var[$i]); $b++) {
                        $highestlun = $var[$i][$b]['lun'];
                    }
                }
            }

            // Add 1 to get the next free lun
            return $highestlun + 1;
        }
    }
?>