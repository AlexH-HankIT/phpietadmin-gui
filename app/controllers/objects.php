<?php
    class objects extends Controller {
        public function __construct() {
            $this->create_models();
            $this->check_loggedin($this->session);
        }

        public function index() {
            $this->view('header');
            $this->view('menu');

            $data['type'] = $this->database->get_object_types();
            $data['objects'] = $this->database->get_all_objects();

            $this->view('objects/table', $data);

            $this->view('footer', $this->std->get_service_status());
        }

        public function add() {

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
                echo "Nothing to do";
            }
        }

        public function edit() {

        }
    }
?>