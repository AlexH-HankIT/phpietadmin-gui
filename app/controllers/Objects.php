<?php
namespace app\controllers;

use app\core;

class Objects extends core\BaseController {
    public function index() {
        $data['type'] = $this->baseModel->database->get_object_types();
        $data['objects'] = $this->baseModel->database->get_all_objects();
        $this->view('objecttable', $data);
    }

    public function add() {
        if (isset($_POST['type'], $_POST['name'], $_POST['value']) && !$this->baseModel->std->mempty($_POST['type'], $_POST['name'], $_POST['value'])) {
            $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
            $value = filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING);

            $data = $this->baseModel->database->get_all_objects();
            try {
                if (is_array($data)) {
                    if ($this->baseModel->std->recursive_array_search($name, $data) !== false) {
                        throw new \exception('name');
                    } else if ($this->baseModel->std->recursive_array_search($value, $data) !== false) {
                        throw new \exception('value');
                    }
                }

                $return = $this->baseModel->database->change(array(
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

                if ($return !== 0) {
                    echo json_encode(array('code' => 6, 'message' => 'DB error'));
                } else {
                    echo json_encode(array('code' => 0, 'message' => 'Success'));
                }
            } catch (\Exception $e) {
                // send the error with the field where the error should be displayed to the client
                echo json_encode(array('code' => 4, 'field' => $e->getMessage()));
            }
        }
    }

    public function delete() {
        if (!empty($_POST['id'])) {
            $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

            $return = $this->baseModel->database->change(array(
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
