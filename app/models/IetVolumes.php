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
            $volumes = $this->getProcVolumes();

            if ($volumes !== 1 and $volumes !== 2) {
                for ($b = 0; $b < substr_count($volumes, "\n") / 2; $b++) {
                    preg_match_all("/name:(.*)/", $volumes, $result);
                    $data[$b][0] = $result[1][$b];
                    preg_match_all("/tid:([0-9].*?) /", $volumes, $result);
                    $data[$b][1] = $result[1][$b];
                    preg_match_all("/path:(.*)/", $volumes, $result);
                    $data[$b][2] = $result[1][$b];
                    preg_match_all("/lun:([0-9].*?)/", $volumes, $result);
                    $data[$b][3] = $result[1][$b];
                    preg_match_all("/state:([0-9].*?)/", $volumes, $result);
                    $data[$b][4] = $result[1][$b];
                    preg_match_all("/iotype:([a-z].*?) /", $volumes, $result);
                    $data[$b][5] = $result[1][$b];
                    preg_match_all("/blocks:([0-9].*?) /", $volumes, $result);
                    $data[$b][6] = $result[1][$b];
                    preg_match_all("/blocksize:([0-9].*?) /", $volumes, $result);
                    $data[$b][7] = $result[1][$b];
                }

                $table = array(
                    0 => "name",
                    1 => "tid",
                    2 => "path",
                    3 => "lun",
                    4 => "state",
                    5 => "iotype",
                    6 => "blocks",
                    7 => "blocksize"
                );

                $data2[0] = $table;
                $data2[1] = $data;

                return $data2;
            } else {
                return 1;
            }
        }
    }

?>