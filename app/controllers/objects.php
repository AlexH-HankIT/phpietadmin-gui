<?php
    class objects extends Controller {
        public function index() {
            $data['type'] = $this->database->get_object_types();
            $data['objects'] = array_reverse($this->database->get_all_objects());
            $this->view('objecttable', $data);
        }

        public function add() {
            if (isset($_POST['type'], $_POST['name'], $_POST['value']) && !$this->std->mempty($_POST['type'], $_POST['name'], $_POST['value'])) {
                // delete whitespaces from the user input
                $_POST['type'] = str_replace(' ', '', $_POST['type']);
                $_POST['name'] = str_replace(' ', '', $_POST['name']);
                $_POST['value'] = str_replace(' ', '', $_POST['value']);

                $data = $this->database->get_all_objects();
                try {
                    if (is_array($data)) {
                        if ($this->std->recursive_array_search($_POST['value'], $data) !== false) {
                            throw new exception('value');
                        } else if($this->std->recursive_array_search($_POST['name'], $data) !== false) {
                            throw new exception('name');
                        }
                    }
                    if ($this->database->add_object($_POST['type'], $_POST['name'], $_POST['value']) != 0) {
                        echo json_encode(array('code' => 6, 'message' => 'DB error'));
                    } else {
                        echo json_encode(array('code' => 0, 'message' => 'Success'));
                    }
                } catch(Exception $e) {
                    // send the error with the field where the error should be displayed to the client
                    echo json_encode(array('code' => 4, 'field' => $e->getMessage()));
                }
            }
        }

        public function delete() {
            if (!empty($_POST['id'])) {
                $return = $this->database->delete_object(intval($_POST['id']));
                if ($return !== 0) {
                    echo json_encode(array('code' => $return, 'message' => 'Database error!'));
                } else {
                    echo json_encode(array('code' => 0));
                }
            }
        }
    }