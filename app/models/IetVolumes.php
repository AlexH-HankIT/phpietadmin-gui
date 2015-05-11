<?php
    class IetVolumes {
        // Define global vars
        var $regex;

        public function __construct() {
            $this->create_models();
        }

        private function create_models() {
            // Create other needed models in this model
            require_once 'Regex.php';
            $this->regex = new Regex();
        }

        private function create_table() {
            return $table = array(
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
        }

        // Creates array containing all proc volumes in human-readable form
        public function getIetVolumes() {
            $var = $this->parse_proc_volumes();
            $table = $this->create_table();

            if (empty($var)) {
                return 2;
            } else {
                $return[0] = $table;
                if (isset($var[1])) {
                    $return[1] = $var[1];
                }
                if (isset($var[2])) {
                    $return[2] = $var[2];
                }

                return $return;
            }
        }

        private function explode_arrays($data) {
            // Explode array by 'tid'
            $data = array_values(array_filter(explode('tid:', $data)));

            // Explode arrays by space
            $counter = 0;
            foreach ($data as $value) {
                $data2[$counter] = explode(' ', $value);
                $counter++;
            }

            return $data2;
        }

        private function seperate_targets($data) {
            $counter=0;
            foreach ($data as $value) {
                // All arrays with less than two rows contain targets without luns
                if (count($value) > 2) {
                    $volumes[$counter] = $value;
                } else {
                    $noluns[$counter] = $value;
                }
                $counter++;
            }

            if (!empty($volumes)) {
                $return[0] = $volumes;
            } else {
                $return[0] = '';
            }

            if (!empty($noluns)) {
                $counter=0;
                foreach ($noluns as $value) {
                    $volumeswithoutluns[$counter]['tid'] = $value[0];
                    preg_match("/name:(.*)/", $value[1], $result);
                    $volumeswithoutluns[$counter]['name'] = $result[1];
                    $counter++;
                }
                $return[1] = $volumeswithoutluns;
            }

            return $return;

        }

        // Reads proc_volumes and creates an array for every target
        // Luns are also included, if available
        public function parse_proc_volumes() {
            require_once 'Ietaddtarget.php';
            $ietaddtarget = new ietaddtarget();

            // Get proc volume content
            $data = $ietaddtarget->get_proc_volume_content();

            // Abort if no data is returned
            if (empty($data)) {
                return 2;
            }

            // Replace all newlines with spaces
            $data = $this->regex->replace_newlines_with_space($data);

            $data = $this->explode_arrays($data);

            $volumes = $this->seperate_targets($data);

            if (!empty($volumes[0])) {
                $volumes[0] = array_values($volumes[0]);

                $counter = 0;
                foreach ($volumes[0] as $value) {
                    $var[$counter][0]['tid'] = $value[0];

                    preg_match("/name:(.*)/", $value[1], $result);
                    $var[$counter][0]['name'] = $result[1];

                    $count = count($value);
                    if ($count > 2) {
                        for ($i = 2; $i < $count; $i = $i + 7) {
                            preg_match("/lun:([0-9].*)/", $value[$i], $result);
                            $var[$counter][$counter + $i]['lun'] = $result[1];

                            preg_match("/state:([0-9].*)/", $value[$i + 1], $result);
                            $var[$counter][$counter + $i]['state'] = $result[1];

                            preg_match("/iotype:(.*)/", $value[$i + 2], $result);
                            $var[$counter][$counter + $i]['iotype'] = $result[1];

                            preg_match("/iomode:(.*)/", $value[$i + 3], $result);
                            $var[$counter][$counter + $i]['iomode'] = $result[1];

                            preg_match("/blocks:(.*)/", $value[$i + 4], $result);
                            $var[$counter][$counter + $i]['blocks'] = $result[1];

                            preg_match("/blocksize:(.*)/", $value[$i + 5], $result);
                            $var[$counter][$counter + $i]['blocksize'] = $result[1];

                            preg_match("/path:(.*)/", $value[$i + 6], $result);
                            $var[$counter][$counter + $i]['path'] = $result[1];
                        }
                    }

                    $counter++;
                }

                // Correct index
                for ($i = 0; $i < count($var); $i++) {
                    $var[$i] = array_values($var[$i]);
                }
                $return[1] = $var;
            }

            if (!empty($volumes[1])) {
                $return[2] = $volumes[1];
            } else {
                $return[2] = '';
            }

            return $return;

        }
    }

?>