<?php
    class Std {
        public function exec_and_return($command) {
            exec($command, $status, $result);

            if ($result != 0) {
                return $status;
            } else {
                return 0;
            }
        }

        public function deleteLineInFile($file, $string) {
            $i=0;$array=array();
            $read = fopen($file, "r") or die("can't open the file $file");
            while(!feof($read)) {
                $array[$i] = fgets($read);
                ++$i;
            }
            fclose($read);
            $write = fopen($file, "w") or die("can't open the file $file");
            foreach($array as $a) {
                if(!strstr($a,$string)) fwrite($write,$a);
            }
            fclose($write);
        }
    }
?>