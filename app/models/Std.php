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

            exec($database->get_config('sudo') . " " . $database->get_config('service') . " " . $database->get_config('servicename') . " status", $status, $result);
            $return[0] = $status;
            $return[1] = $result;
            return $return;
        }

        public function addlineafterpattern($pattern, $file, $data) {
            $lines = file( $file , FILE_IGNORE_NEW_LINES );
            $key = array_search($pattern, $lines);
            $lines[$key+1] .= "\n" . $data;
            file_put_contents( $file , implode( "\n", $lines ) );
        }

        public function add_line_to_file($line, $file) {
            if (!file_exists($file)) {
                return 1;
            } else if (!is_writeable($file)) {
                return 1;
            } else {
                file_put_contents($file, $line, FILE_APPEND | LOCK_EX);
            }
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
    }
?>