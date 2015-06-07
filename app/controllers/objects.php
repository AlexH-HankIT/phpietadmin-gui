<?php
    class objects extends Controller {
        public function index() {
            $data['type'] = $this->database->get_object_types();
            $data['objects'] = $this->database->get_all_objects();

            $this->view('objecttable', $data);
        }

        public function add() {
            if (isset($_POST['type']) && isset($_POST['name']) && isset($_POST['value'])) {
                $this->database->add_object($_POST['type'], $_POST['name'], $_POST['value']);
            }
        }

        public function delete() {
            if (isset($_POST['id']) && !empty($_POST['id'])) {
                $id = intval($_POST['id']);
                $return = $this->database->delete_object($id);
                if ($return !== 0) {
                    echo "Failed";
                } else {
                    echo "Success";
                }
            } else {
                echo "Can't do anything!";
            }
        }

        public function edit() {

        }

        public function checkvalueexists() {
            if (isset($_POST['check']) && $_POST['check'] == "duplicated" && isset($_POST['value'])) {
                $data = $this->database->get_all_object_values();

                if (is_array($data)) {
                    $result = array_search($_POST['value'], $data);
                    if (!$result) {
                        echo "false";
                    } else {
                        echo "true";
                    }
                } else {
                    echo "false";
                }
            }
        }
    }
?>