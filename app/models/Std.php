<?php
    class Std {
        public function get_dashboard_data() {
            $data['hostname'] = file_get_contents('/etc/hostname');
            $data['phpietadminversion'] = file_get_contents('/usr/share/phpietadmin/version');
            $data['distribution'] = shell_exec('lsb_release -sd');

            $hwdata = file('/proc/cpuinfo');
            $hwdata[4] = str_replace("model", '', $hwdata[4]);
            $hwdata[4] = str_replace("name", '', $hwdata[4]);
            $data['cpu'] = str_replace(":", '', $hwdata[4]);

            $data['uptime'] = shell_exec('uptime -p');
            $data['systemstart'] = shell_exec('uptime -s');

            preg_match('/load average: (.*)/', shell_exec('uptime'), $matches);
            $data['currentload'] = $matches[1];

            $mem = file('/proc/meminfo');
            preg_match('/[0-9]+/', $mem[0], $matches);
            $data['memtotal'] = intval($matches[0] / 1024);

            preg_match('/[0-9]+/', $mem[1], $matches);
            $data['memused'] = intval($matches[0] / 1024);

            $data['systemtime'] = shell_exec('date');
            $data['kernel'] = shell_exec('uname -r');

            return $data;
        }

        public function exec_and_return($command) {
            $command = escapeshellcmd($command);
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

        public function check_if_file_contains_value($file, $value) {
            if (strpos(file_get_contents($file), $value) !== false) {
                return true;
            } else {
                return false;
            }
        }

        public function recursive_array_search($needle,$haystack) {
            foreach($haystack as $key=>$value) {
                $current_key=$key;
                if($needle===$value OR (is_array($value) && $this->recursive_array_search($needle,$value) !== false)) {
                    return $current_key;
                }
            }
            return false;
        }

        public function fetchdata($result) {
            $counter=0;
            while ($row = $result->fetchArray(SQLITE3_NUM)) {
                $data[$counter] = $row;
                $counter++;
            }
            return $data;
        }
    }
?>