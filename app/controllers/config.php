<?php
    class config extends Controller {
        public function fetchdata($result) {
            $counter=0;
            while ($row = $result->fetchArray(SQLITE3_NUM)) {
                $data[$counter] = $row;
                $counter++;
            }

            return $data;
        }

        public function index() {
            $this->view('config/menu');
            $this->view('message', "Please select a category");
        }

        public function user() {
            $this->view('config/menu');
            $this->view('config/user');
        }

        public function lvm() {
            $this->view('config/menu');

            $result = $this->database->query('select option, value, description from config where editable_via_gui=1 and category=2');

            $data=$this->fetchdata($result);

            $this->view('config/configtable', $data);
        }

        public function iet() {
            $this->view('config/menu');

            $result = $this->database->query('select option, value, description from config where editable_via_gui=1 and category=1');

            $data=$this->fetchdata($result);
            $this->view('config/configtable', $data);
        }

        public function misc() {
            $this->view('config/menu');

            $result = $this->database->query('select option, value, description from config where editable_via_gui=1 and category=3');

            $data=$this->fetchdata($result);
            $this->view('config/configtable', $data);

        }

        public function editloginuser() {
            if (isset($_POST['pwhash'])) {
                $return = $this->database->edit_login_user($_POST['pwhash']);
                if ($return != 0) {
                    echo "Failed";
                } else {
                    echo "Success";
                }
            }
        }

        public function edit() {
            $database = $this->model('Database');

            if(isset($_GET["value"]) && isset($_GET['option'])) {
                $data = $database->ispath($_GET['option']);
                $code = $database->return_last_error();
                // If $code is not 0, it means, the option doesn't exist, or some other error occured
                if ($code ==! 0) {
                    echo "Failed";
                    // $data == 1 means, the option contains a path, therefore we check if the file exists
                } else if ($data == 1){
                    if (!file_exists($_GET["value"])) {
                        echo "Failed";
                    }
                } else {
                    $code = $database->set_config($_GET['option'], $_GET["value"]);
                    if ($code ==! 0) {
                        echo "Failed";
                    } else {
                        echo "Success";
                    }
                }
            } else {
                exit();
            }
        }
    }
?>