<?php namespace phpietadmin\app\controllers;
use phpietadmin\app\core,
    phpietadmin\app\models;

    class connection extends core\BaseController {
        /**
         *
         * echos a service status
         *
         * @return      void
         *
         */
       public function status() {
           if (isset($_POST['servicename'])) {
               $service = $this->model('Service', $_POST['servicename']);
               $service->action('status');
               echo json_encode($service->logging->get_action_result());
           }
       }

        /**
         *
         * check if session is expired
         *
         * @return      void
         *
         */
       public function check_session_expired() {
           // if the session is expired the client will get no output
           // else we will return true here
           echo true;
       }

        /**
         *
         * check if server is still reachable
         *
         * @return      void
         *
         */
       public function check_server_online() {
           echo true;
       }
    }