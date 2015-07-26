<?php
    class config extends Controller {
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

        /**
         *
         * Displays the lvm config menu
         *
         * @return      void
         *
         */
        public function lvm() {
            $result = $this->database->query('select option, value, description from config where editable_via_gui=1 and category=2');
            $data = $this->std->fetchdata($result);
            $this->view('config/configtable', $data);
        }

        /**
         *
         * Displays the ietd config menu
         *
         * @return      void
         *
         */
        public function iet() {
            $result = $this->database->query('select option, value, description from config where editable_via_gui=1 and category=1');
            $data = $this->std->fetchdata($result);
            $this->view('config/configtable', $data);
        }

        /**
         *
         * Displays other config options
         *
         * @return      void
         *
         */
        public function misc() {
            $result = $this->database->query('select option, value, description from config where editable_via_gui=1 and category=3');
            $data = $this->std->fetchdata($result);
            $this->view('config/configtable', $data);

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