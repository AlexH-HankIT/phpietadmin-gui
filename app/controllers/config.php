<?php
    class config extends Controller {
        public function user() {
            $this->view('config/user');
        }

        public function lvm() {
            $result = $this->database->query('select option, value, description from config where editable_via_gui=1 and category=2');
            $data = $this->std->fetchdata($result);
            $this->view('config/configtable', $data);
        }

        public function iet() {
            $result = $this->database->query('select option, value, description from config where editable_via_gui=1 and category=1');
            $data = $this->std->fetchdata($result);
            $this->view('config/configtable', $data);
        }

        public function misc() {
            $result = $this->database->query('select option, value, description from config where editable_via_gui=1 and category=3');
            $data = $this->std->fetchdata($result);
            $this->view('config/configtable', $data);

        }

        public function editloginuser() {
            if (isset($_POST['pwhash'])) {
                $return = $this->database->edit_login_user($_POST['pwhash']);
                if ($return !== 0) {
                    echo "Failed";
                } else {
                    echo "Success";
                }
            }
        }

        public function edit() {
            if(isset($_GET["value"]) && isset($_GET['option'])) {
                // If $code is not 0, it means, the option doesn't exist, or some other error occured
                if ($this->database->return_last_error() !== 0) {
                    echo "Failed";
                    // $data == 1 means, the option contains a path, therefore we check if the file exists
                } else if ($this->database->ispath($_GET['option']) == 1){
                    if (!file_exists($_GET["value"])) {
                        echo "Failed";
                    }
                } else {
                    if ($this->database->set_config($_GET['option'], $_GET["value"]) !== 0) {
                        echo "Failed";
                    } else {
                        echo "Success";
                    }
                }
            } else {
                die();
            }
        }
    }
?>