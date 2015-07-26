<?php
    class Ietusers extends Controller {
        /**
         *
         * Display table with users
         *
         * @return      void
         *
         */
        public function index() {
            // get all all username, passwords and ids from database and turn them over to the view
            $data = $this->database->get_all_users();

            $this->view('usertable', $data);
        }

        /**
         *
         * Check if username is already taken
         *
         * @return      void
         *
         */
        public function check_username_already_in_use() {
            $data = $this->database->get_all_usernames();

            if (is_array($data)) {
                $result = array_search($_POST['username'], $data);

                if ($result === false) {
                    echo "false";
                } else {
                    echo "true";
                }
            } else {
                echo "false";
            }
        }

        /**
         *
         * Adds a user to the database
         *
         * @return      void
         *
         */
        public function addusertodb() {
            if (isset($_POST['username']) && isset($_POST['password'])) {
                $this->database->add_ietuser($_POST['username'], $_POST['password']);
            }
        }

        public function deleteuserfromdb() {
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $id = intval($_POST['id']);

                // Check if user is in use
                $data = $this->database->get_ietuser($id);
                $return[0] = $this->std->check_if_file_contains_value($this->database->get_config('ietd_config_file'), 'IncomingUser ' . $data['username']);
                $return[1] = $this->std->check_if_file_contains_value($this->database->get_config('ietd_config_file'), 'OutgoingUser ' . $data['username']);

                if (!$return[0] && !$return[1]) {
                    $return = $this->database->delete_ietuser($id);
                    if ($return !== 0) {
                        echo "Failed";
                    } else {
                        echo "Success";
                    }
                } else {
                    echo "In use!";
                }
            } else {
                echo "Can't do anything!";
            }
        }
    }
