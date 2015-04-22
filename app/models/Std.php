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

        public function deleteLineInFile($file, $string) {
            $i = 0;
            $array = array();
            $read = fopen($file, "r") or die("can't open the file $file");
            while (!feof($read)) {
                $array[$i] = fgets($read);
                ++$i;
            }
            fclose($read);
            $write = fopen($file, "w") or die("can't open the file $file");
            foreach ($array as $a) {
                if (!strstr($a, $string)) fwrite($write, $a);
            }
            fclose($write);
        }


        public function get_service_status() {
            require_once 'Database.php';
            $database = new Database;

            exec($database->getConfig('sudo') . " " . $database->getConfig('service') . " " . $database->getConfig('servicename') . " status", $status, $result);
            $return[0] = $status;
            $return[1] = $result;
            return $return;
        }

        public function check_service_status() {
            global $a_config;
            $result = $this->get_service_status();
            if ($result[1] !== 0) {
                throw new Exception("Error - Service {$a_config['iet']['servicename']} is not running.");
            }
        }

        public function addlineafterpattern($pattern, $file, $data) {
            $lines = file( $file , FILE_IGNORE_NEW_LINES );
            $key = array_search($pattern, $lines);
            $lines[$key+1] .= "\n" . $data;
            file_put_contents( $file , implode( "\n", $lines ) );
        }
    }
?>