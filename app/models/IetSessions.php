<?php
    class IetSessions {
        public function getProcSessions() {
            require_once 'Database.php';
            $database = new Database();

            if (file_exists($database->get_config('proc_sessions'))) {
                $return = file_get_contents($database->get_config('proc_sessions'));
                if (empty($return)) {
                    return 2;
                } else {
                    return $return;
                }
            } else {
                return 1;
            }
        }

        public function getIetSessions() {
            // Read content
            $data = $this->getProcSessions();

            // If data contains error code, return it
            if (is_int($data)) {
                return $data;
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
                // All arrays with less than two rows don't contain interesting data
                if (count($value) > 2) {
                    $sessions[$counter] = $value;
                }
                $counter++;
            }

            // Abort and return if file is empty
            if (empty($sessions)) {
                return 2;
            }


            $counter=0;
            foreach ($sessions as $value) {
                // No regex here, because explode() already deleted 'tid'
                $var[$counter][0]['tid'] = $value[0];

                preg_match("/name:(.*)/", $value[1], $result);
                $var[$counter][0]['name'] = $result[1];

                for ($i=2; $i < count($value); $i=$i+7) {
                    preg_match("/sid:(.*)/", $value[$i], $result);
                    $var[$counter][$counter+$i]['sid'] = $result[1];

                    preg_match("/initiator:(.*)/", $value[$i+1], $result);
                    $var[$counter][$counter+$i]['initiator'] = $result[1];

                    preg_match("/cid:([0-9].*)/", $value[$i+2], $result);
                    $var[$counter][$counter+$i]['cid'] = $result[1];

                    preg_match("/ip:(.*)/", $value[$i+3], $result);
                    $var[$counter][$counter+$i]['ip'] = $result[1];

                    preg_match("/state:(.*)/", $value[$i+4], $result);
                    $var[$counter][$counter+$i]['state'] = $result[1];

                    preg_match("/hd:(.*)/", $value[$i+5], $result);
                    $var[$counter][$counter+$i]['hd'] = $result[1];

                    preg_match("/dd:(.*)/", $value[$i+6], $result);
                    $var[$counter][$counter+$i]['dd'] = $result[1];
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
                2 => "sid",
                3 => "initiator",
                4 => "cid",
                5 => "ip",
                6 => "state",
                7 => "hd",
                8 => "dd"
            );

            $return[0] = $table;
            $return[1] = $var;
            $return['title'] = "Iet sessions";

            return $return;
        }
    }

?>