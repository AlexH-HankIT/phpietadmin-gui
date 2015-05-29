<?php
    class Std {
        public function exec_and_return($command) {
            exec($command . " 2>&1", $status, $result);

            if ($result != 0) {
                return $status;
            } else {
                return 0;
            }
        }

        public function get_service_status() {
            require_once 'Database.php';
            $database = new Database;

            exec($database->get_config('sudo') . " " . $database->get_config('service') . " " . $database->get_config('servicename') . " status", $status, $result);
            $return[0] = $status;
            $return[1] = $result;
            return $return;
        }

        public function explode_array_by_space($array) {
            // Explode arrays by space
            $counter = 0;
            foreach ($array as $value) {
                $data[$counter] = explode(' ', $value);
                $counter++;
            }
            return $data;
        }

        public function IsXHttpRequest() {
            if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
                return true;
            } else {
                return false;
            }
        }

        // From here: https://gist.github.com/branneman/951847
        // Thanks to branneman
        // array_search function with partial match
        public function array_find($needle, array $haystack) {
            foreach ($haystack as $key => $value) {
                if (false !== stripos($value, $needle)) {
                    return true;
                }
            }
            return false;
        }
    }
?>