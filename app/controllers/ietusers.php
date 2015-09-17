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
            $this->view('usertable', $this->base_model->database->get_all_users());
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
                $_POST['username'] = str_replace(' ', '', $_POST['username']);
                $_POST['password'] = str_replace(' ', '', $_POST['password']);
                $user = $this->model('Ietuser', $_POST['username']);
                $user->add_user_to_db($_POST['password']);
                echo json_encode($user->logging->get_action_result());
            }
        }

        public function delete_from_db() {
            if (isset($_POST['username']) && !empty($_POST['username'])) {
                $user = $this->model('Ietuser', $_POST['username']);
                $user->delete_user_from_db();
                echo json_encode($user->logging->get_action_result());
            }
        }
    }
