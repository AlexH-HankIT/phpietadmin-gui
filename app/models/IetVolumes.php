<?php
    class IetVolumes {
        public function getProcVolumes() {
            require_once 'Database.php';
            $database = new Database();
            if (file_exists($database->getConfig('proc_volumes'))) {
                $return = file_get_contents($database->getConfig('proc_volumes'));
                $database->close();
                if (empty($return)) {
                    return 2;
                } else {
                    return $return;
                }
            } else {
                return 1;
            }
        }

        public function getIetVolumes() {
            require_once 'Database.php';
            $database = new Database();

            if (file_exists($database->getConfig('proc_volumes'))) {
                $data = file_get_contents($database->getConfig('proc_volumes'));
            } else {
                return 1;
            }

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
                // All arrays with less than two rows contain targets without luns
                if (count($value) > 2) {
                    $volumes[$counter] = $value;
                } else {
                    $noluns[$counter] = $value;
                }
                $counter++;
            }

            if (!empty($noluns)) {
                $counter=0;
                foreach ($noluns as $value) {
                    $volumeswithoutluns[$counter]['tid'] = $value[0];
                    preg_match("/name:(.*)/", $value[1], $result);
                    $volumeswithoutluns[$counter]['name'] = $result[1];
                    $counter++;
                }
            }

            if (empty($volumes)) {
                return 2;
            }

            $volumes = array_values($volumes);

            $counter=0;
            foreach ($volumes as $value) {
                $var[$counter][0]['tid'] = $value[0];

                preg_match("/name:(.*)/", $value[1], $result);
                $var[$counter][0]['name'] = $result[1];

                for ($i=2; $i < count($value); $i=$i+7) {
                    preg_match("/lun:([0-9].*)/", $value[$i], $result);
                    $var[$counter][$counter+$i]['lun'] = $result[1];

                    preg_match("/state:([0-9].*)/", $value[$i+1], $result);
                    $var[$counter][$counter+$i]['state'] = $result[1];

                    preg_match("/iotype:(.*)/", $value[$i+2], $result);
                    $var[$counter][$counter+$i]['iotype'] = $result[1];

                    preg_match("/iomode:(.*)/", $value[$i+3], $result);
                    $var[$counter][$counter+$i]['iomode'] = $result[1];

                    preg_match("/blocks:(.*)/", $value[$i+4], $result);
                    $var[$counter][$counter+$i]['blocks'] = $result[1];

                    preg_match("/blocksize:(.*)/", $value[$i+5], $result);
                    $var[$counter][$counter+$i]['blocksize'] = $result[1];

                    preg_match("/path:(.*)/", $value[$i+6], $result);
                    $var[$counter][$counter+$i]['path'] = $result[1];
                }

                $counter++;
            }

            // Correct index
            for ($i=0; $i < count($var); $i++) {
                $var[$i] = array_values($var[$i]);
            }

            $table = array(
                0 => "name",
                1 => "tid",
                2 => "path",
                3 => "lun",
                4 => "state",
                5 => "iotype",
                6 => "blocks",
                7 => "blocksize",
                8 => "iomode"
            );

            $return[0] = $table;
            $return[1] = $var;
            if (!empty($volumeswithoutluns)) {
                $return[2] = $volumeswithoutluns;
            }

            return $return;
        }
    }

?>