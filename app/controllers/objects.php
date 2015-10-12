<?php namespace phpietadmin\app\controllers;
use phpietadmin\app\core;

    class objects extends core\BaseController {
        public function index() {
            $data['type'] = $this->base_model->database->get_object_types();
            $data['objects'] = $this->base_model->database->get_all_objects();
            $this->view('objecttable', $data);
        }

        public function add() {
            if (isset($_POST['type'], $_POST['name'], $_POST['value']) && !$this->base_model->std->mempty($_POST['type'], $_POST['name'], $_POST['value'])) {
                $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
                $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                $value = filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING);

                // delete whitespaces from the user input
                $type = str_replace(' ', '', $type);
                $name = str_replace(' ', '', $name);
                $value = str_replace(' ', '', $value);

                $data = $this->base_model->database->get_all_objects();
                try {
                    if (is_array($data)) {
                        if ($this->base_model->std->recursive_array_search($value, $data) !== false) {
                            throw new \exception('value');
                        } else if($this->base_model->std->recursive_array_search($name, $data) !== false) {
                            throw new \exception('name');
                        }
                    }

                    $return = $this->base_model->database->change(array(
                            'query' => 'INSERT INTO phpietadmin_object (type_id, value, name) VALUES ((SELECT type_id FROM phpietadmin_object_type WHERE value=:type), :value, :name)',
                            'params' => array(
                                0 => array(
                                    'name' => 'type',
                                    'value' => str_replace(' ', '', $type),
                                    'type' => SQLITE3_TEXT
                                ),
                                1 => array(
                                    'name' => 'name',
                                    'value' => str_replace(' ', '', $name),
                                    'type' => SQLITE3_TEXT
                                ),
                                2 => array(
                                    'name' => 'value',
                                    'value' => str_replace(' ', '', $value),
                                    'type' => SQLITE3_TEXT
                                )
                            )
                        )
                    );

                    //if ($this->base_model->database->add_object($_POST['type'], $_POST['name'], $_POST['value']) != 0) {
                    if ($return !== 0) {
                        echo json_encode(array('code' => 6, 'message' => 'DB error'));
                    } else {
                        echo json_encode(array('code' => 0, 'message' => 'Success'));
                    }
                } catch(\Exception $e) {
                    // send the error with the field where the error should be displayed to the client
                    echo json_encode(array('code' => 4, 'field' => $e->getMessage()));
                }
            }
        }

        public function delete() {
            if (!empty($_POST['id'])) {
                $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

                $return = $this->base_model->database->change(array(
                        'query' => 'DELETE FROM phpietadmin_object where id=:id',
                        'params' => array(
                            0 => array(
                                'name' => 'id',
                                'value' => intval($id),
                                'type' => SQLITE3_INTEGER
                            )
                        )
                    )
                );

                if ($return !== 0) {
                    echo json_encode(array('code' => $return, 'message' => 'Database error!'));
                } else {
                    echo json_encode(array('code' => 0));
                }
            }
        }
    }