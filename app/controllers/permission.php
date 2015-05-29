<?php
    class permission extends Controller {
        public function addrule() {
            if (isset($_POST['iqn']) && isset($_POST['type']) && isset($_POST['id']) && isset($_POST['ruletype'])) {
                // Get value of object with $id
                $id = intval($_POST['id']);
                $value = $this->database->get_object_value($id);

                if ($_POST['ruletype'] == "allow") {
                    if ($_POST['type'] == "initiator") {
                        $file = $this->database->get_config('ietd_init_allow');
                    } else if ($_POST['type'] == "target") {
                        $file = $this->database->get_config('ietd_target_allow');
                    }
                } else if ($_POST['ruletype'] == "deny") {
                    if ($_POST['type'] == "initiator") {
                        $file = $this->database->get_config('ietd_init_deny');
                    }
                }

                if (isset($file) && !empty($file)) {
                    $return = $this->ietpermissions->add_object_to_iqn($_POST['iqn'], $value, $file);
                    if ($return !== 0) {
                        echo "Failed";
                    } else {
                        echo "Success";
                    }
                } else {
                    echo "Failed";
                }
            } else {
                $data['objects'] = $this->database->get_all_objects();
                $data['targets'] = $this->ietadd->get_targets();

                if ($data['targets'] !== 0) {
                    $this->view('message', "Error - No targets available!");
                } else {
                    $this->view('permissions/addrule', $data);
                }


            }
        }

        public function deleterule() {
            if (isset($_POST['iqn']) && isset($_POST['ruletype']) && !isset($_POST['value'])) {
                if ($_POST['ruletype'] == 'initiators.allow') {
                    $file = $this->database->get_config('ietd_init_allow');
                } else if ($_POST['ruletype'] == 'initiators.deny') {
                    $file = $this->database->get_config('ietd_init_deny');
                } else if ($_POST['ruletype'] == 'targets.allow') {
                    $file = $this->database->get_config('ietd_target_allow');
                }

                $data = $this->ietpermissions->get_iet_allow($file, $_POST['iqn']);

                if (isset($data[0])) {
                    $data = $data[0];

                    for ($i=0; $i<count($data)-1; $i++) {
                        $value = $this->database->get_object_by_value($data[$i]);

                        if (isset($value) && !empty($value)) {
                            $objects[$i] = $value;
                        } else {
                            $orphans[$i] = $data[$i];
                        }
                    }

                    unset($data);
                    if (isset($objects) && !empty($objects)) {
                        $objects = array_values($objects);
                        $data['deleteruleobjectstable'] = $objects;
                    }

                    if (isset($orphans) && !empty($orphans)) {
                        $orphans = array_values($orphans);
                        $data['deleteruleorphanedtable'] = $orphans;
                    }

                    if (isset($data)) {
                        $this->view('permissions/deleteruletable', $data);
                    } else {
                        echo "false";
                    }
                } else {
                    echo "false";
                }
            } else if (isset($_POST['iqn']) && isset($_POST['value']) && isset($_POST['ruletype'])) {
                if ($_POST['ruletype'] == 'initiators.allow') {
                    $file = $this->database->get_config('ietd_init_allow');
                } else if ($_POST['ruletype'] == 'initiators.deny') {
                    $file = $this->database->get_config('ietd_init_deny');
                } else if ($_POST['ruletype'] == 'targets.allow') {
                    $file = $this->database->get_config('ietd_target_allow');
                }

                if (isset($file) && !empty($file)) {
                    $return = $this->ietpermissions->delete_object_from_iqn($_POST['iqn'], $_POST['value'], $file);
                    if ($return !== 0) {
                        echo "Failed";
                    } else {
                        echo "Success";
                    }
                } else {
                    echo "Failed";
                }
            } else {
                $data['targets'] = $this->ietadd->get_targets();

                if ($data['targets'] !== 0) {
                    $this->view('message', "Error - No targets available!");
                } else {
                    $this->view('permissions/deleterule', $data);
                }
            }
        }
    }
?>