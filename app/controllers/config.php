<?php
    class config extends Controller {
        public function vg() {
            $this->view('config/vg');
        }

        /**
         *
         * Displays the phpietadmin user config menu
         *
         * @return      void
         *
         */
        public function user() {
            $this->view('config/user');
        }

        public function show($param) {
            if ($param == 'lvm') {
                $data = $this->database->get_config_by_category('lvm');
            } else if ($param == 'iet') {
                $data = $this->database->get_config_by_category('iet');
            } else if ($param == 'misc') {
                $data = $this->database->get_config_by_category('misc');
            } else if ($param == 'bin') {
                $data = $this->database->get_config_by_category('bin');
            } else if ($param == 'logging') {
                $data = $this->database->get_config_by_category('logging');
            } else {
                $this->view('message', array('message' => 'Invalid url', 'type' => 'warning'));
            }

            if (isset($data) && !empty($data) && $data !== false) {
                $this->view('config/configtable', $data);
            }
        }

        /**
         *
         * Changes the phpietadmin login user password
         *
         * @return      void
         *
         */
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

        /**
         *
         * Edit a config option
         *
         * @return      void
         *
         */
        public function edit() {
            if(isset($_GET["value"], $_GET['option'])) {
                // $data == 1 means, the option contains a path, therefore we check if the file exists
                if ($this->database->ispath($_GET['option']) == 1) {
                    if (!file_exists($_GET["value"])) {
                        echo "Failed";
                    } else {
                        if ($this->database->set_config($_GET['option'], $_GET["value"]) !== 0) {
                            echo "Failed";
                        } else {
                            echo "Success";
                        }
                    }
                }
            }
        }
    }