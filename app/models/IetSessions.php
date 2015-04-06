<?php
    class IetSessions {
        public function getProcSessions()
        {
            $a_config = parse_ini_file("/home/vm/ownCloud/Work/PhpstormProjects/mvctest/app/config.ini.php", true);
            if (file_exists($a_config['iet']['proc_sessions'])) {
                return file_get_contents($a_config['iet']['proc_sessions']);
            } else {
                return 1;
            }
        }

        public function getIetSessions() {
            $sessions = $this->getProcSessions();

            // Check if even one session exists
            if (strpos($sessions, 'cid') == false && strpos($sessions, 'sid') == false) {
                return 1;
            } else {
                // Get line count
                $count = substr_count($sessions, "\n")/3;
                if ($count / 3) {
                    $a_sessions = array_filter(explode("\n", $sessions));
                    for ($i = 0; $i < count($a_sessions) - 2; $i++) {
                        if (strpos($a_sessions[$i + 2], 'cid') !== false) {
                            $a_sessions2[$i][0] = $a_sessions[$i];
                            $a_sessions2[$i][1] = $a_sessions[$i + 1];
                            $a_sessions2[$i][2] = $a_sessions[$i + 2];
                        }
                    }
                }
                // Filter empty values
                $a_sessions2 = array_values(($a_sessions2));
                for ($i=0; $i < count($a_sessions2); $i++) {
                    $a_sessions3[$i] = implode(' ', $a_sessions2[$i]);
                }
                for ($i=0; $i < count($a_sessions3); $i++) {
                    $sessions = implode("\n", $a_sessions3);
                }
                for ($b=0; $b < floor($count); $b++) {
                    preg_match_all("/name:(.*?) /", $sessions, $result);
                    $data[$b][0] = $result[1][$b];
                    preg_match_all("/tid:([0-9].*?) /", $sessions, $result);
                    $data[$b][1] = $result[1][$b];
                    preg_match_all("/sid:(.*?) /", $sessions, $result);
                    $data[$b][2] = $result[1][$b];
                    preg_match_all("/initiator:(.*?) /", $sessions, $result);
                    $data[$b][3] = $result[1][$b];
                    preg_match_all("/cid:([0-9].*?)/", $sessions, $result);
                    $data[$b][4] = $result[1][$b];
                    preg_match_all("/ip:(.*?) /", $sessions, $result);
                    $data[$b][5] = $result[1][$b];
                    preg_match_all("/state:(.*?) /", $sessions, $result);
                    $data[$b][6] = $result[1][$b];
                    preg_match_all("/hd:(.*?) /", $sessions, $result);
                    $data[$b][7] = $result[1][$b];
                    preg_match_all("/dd:(.*)/", $sessions, $result);
                    $data[$b][8] = $result[1][$b];
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

                $data2[0] = $table;
                $data2[1] = $data;

                return $data2;
            }
        }
    }

?>