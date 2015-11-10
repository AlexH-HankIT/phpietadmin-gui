<?php namespace phpietadmin\app\controllers;
use phpietadmin\app\core;

    class Ietusers extends core\BaseController {
        /**
         *
         * Display table with users
         *
         * @return      void
         *
         */
        public function index() {
            // get all all username, passwords and ids from database and turn them over to the view
            $this->view('ietUserTable', $this->base_model->database->get_all_users());
        }

        /**
         *
         * Adds a user to the database
         *
         * @return      void
         *
         */
        public function add_to_db() {
            if (isset($_POST['username'], $_POST['password']) && !$this->base_model->std->mempty($_POST['username'], $_POST['password'])) {
                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                $username = str_replace(' ', '', $username);
                $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
                $password = str_replace(' ', '', $password);

                $user = $this->model('Ietuser', $username);
                $user->add_user_to_db($password);
                echo json_encode($user->logging->get_action_result());
            }
        }

        public function delete_from_db() {
            if (!empty($_POST['username'])) {
                $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                $username = str_replace(' ', '', $username);

                $user = $this->model('Ietuser', $username);
                $user->delete_user_from_db();
                echo json_encode($user->logging->get_action_result());
            }
        }
    }
