<?php
    class Ietusers extends Controller {
        public function index() {
            // get all all username, passwords and ids from database and turn them over to the view
            $data = $this->database->get_all_users();

            $this->view('usertable', $data);
        }

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

        public function addusertodb() {
            if (isset($_POST['type']) && isset($_POST['username']) && isset($_POST['password'])) {
                $this->database->add_ietuser($_POST['type'], $_POST['username'], $_POST['password']);
            }
        }

        public function deleteuserfromdb() {
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $id = intval($_POST['id']);
                $return = $this->database->delete_ietuser($id);
                if ($return !== 0) {
                    echo "Failed";
                } else {
                    echo "Success";
                }
            } else {
                echo "Can't do anything!";
            }
        }

        public function addusertotarget() {

        }

        public function deleteuserfromtarget() {

        }

    }
?>