<?php
    class Ietdeletetarget {
        // Define global vars
        var $database;
        var $std;

        public function __construct() {
            $this->create_models();
        }

        private function create_models() {
            // Create other need models in this model
            require_once 'Database.php';
            require_once 'Std.php';
            $this->database = new Database();
            $this->std = new Std();
        }

        public function parse_data($IQN_ID) {
            $volumes = file_get_contents($this->database->get_config('proc_volumes'));

            if (empty($volumes)) {
                return 2;
            } else {
                preg_match_all("/name:(.*)/", $volumes, $a_name);
                preg_match_all("/tid:([0-9].*?) /", $volumes, $a_tid);
                preg_match_all("/path:(.*)/", $volumes, $a_paths);

                $IQN = $IQN_ID - 1 ;
                $TID = $a_tid[1][$IQN];
                $NAME = $a_name[1][$IQN];
                $PATH = $a_paths[1][$IQN];

                return $data = array  (
                    0 => $TID,
                    1 => $NAME,
                    2 => $PATH
                );
            }
        }

        public function delete_from_config_file($data) {
            $this->std->deleteLineInFile($this->database->get_config('ietd_init_allow'), "$data[1]");
            $this->std->deleteLineInFile($this->database->get_config('ietd_config_file'), $data[1]);
            $this->std->deleteLineInFile($this->database->get_config('ietd_config_file'), $data[2]);
        }

        public function get_names() {
            $volumes = file_get_contents($this->database->get_config('proc_volumes'));

            if (empty($volumes)) {
                return 2;
            } else {
                preg_match_all("/name:(.*)/", $volumes, $a_name);
                return $a_name;
            }
        }
    }
?>