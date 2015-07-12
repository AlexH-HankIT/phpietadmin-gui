<?php
    class connection extends Controller {
       public function index() {

       }

       public function status() {
           if (isset($_POST['servicename'])) {
               $return = $this->std->get_service_status($_POST['servicename']);
               echo $return[1];
           } else {
               echo 'Undefined service name';
           }
       }

       public function check_session_expired() {
           // if the session is expired the function check_loggedin in core/Controller.php will return false
           // else we will return true here
           echo 'true';
       }

       public function check_server_online() {
           echo 'alive';
       }
    }
?>