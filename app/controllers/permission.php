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
                $this->view('permissions/addrule', $data);
            }
        }
    }
?>