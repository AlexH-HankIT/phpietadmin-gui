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
                // delete whitespaces from the user input
                $_POST['type'] = str_replace(' ', '', $_POST['type']);
                $_POST['name'] = str_replace(' ', '', $_POST['name']);
                $_POST['value'] = str_replace(' ', '', $_POST['value']);

                $data = $this->base_model->database->get_all_objects();
                try {
                    if (is_array($data)) {
                        if ($this->base_model->std->recursive_array_search($_POST['value'], $data) !== false) {
                            throw new \exception('value');
                        } else if($this->base_model->std->recursive_array_search($_POST['name'], $data) !== false) {
                            throw new \exception('name');
                        }
                    }

                    $return = $this->base_model->database->change(array(
                            'query' => 'INSERT INTO phpietadmin_object (type_id, value, name) VALUES ((SELECT type_id FROM phpietadmin_object_type WHERE value=:type), :value, :name)',
                            'params' => array(
                                0 => array(
                                    'name' => 'type',
                                    'value' => str_replace(' ', '', $_POST['type']),
                                    'type' => SQLITE3_TEXT
                                ),
                                1 => array(
                                    'name' => 'name',
                                    'value' => str_replace(' ', '', $_POST['name']),
                                    'type' => SQLITE3_TEXT
                                ),
                                2 => array(
                                    'name' => 'value',
                                    'value' => str_replace(' ', '', $_POST['value']),
                                    'type' => SQLITE3_TEXT
                                )
                            )
                        )
                    );


                    //if ($this->base_model->database->add_object($_POST['type'], $_POST['name'], $_POST['value']) != 0) {
                    if ($return != 0) {
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
                $return = $this->base_model->database->change(array(
                        'query' => 'DELETE FROM phpietadmin_object where id=:id',
                        'params' => array(
                            0 => array(
                                'name' => 'id',
                                'value' => intval($_POST['id']),
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