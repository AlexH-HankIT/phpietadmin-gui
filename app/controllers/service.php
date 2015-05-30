<?php
    class Service extends Controller {
        public function index() {
            if (isset($_POST['start'])) {
                $command = escapeshellcmd($this->database->get_config('sudo') . " " . $this->database->get_config('service') . " " . $this->database->get_config('servicename') . " start");
            } else if (isset($_POST['stop'])) {
                $command = escapeshellcmd($this->database->get_config('sudo') . " " . $this->database->get_config('service') . " " . $this->database->get_config('servicename') . " stop");
            } else if (isset($_POST['restart'])) {
                $command = escapeshellcmd($this->database->get_config('sudo') . " " . $this->database->get_config('service') . " " . $this->database->get_config('servicename') . " restart");
            }

            if (isset($command) && !empty($command)) {
                $output = shell_exec($command);
                $this->view('service', $output);
            } else {
                $this->view('service');
            }

            $return = $this->std->get_service_status();

            if ($return[1] != 0) {
                $this->view('message', "Service is not running!");
            } else {
                $this->view('message', "Service is running!");
            }
        }

        public function status() {
            $this->view('ietdstatus',$this->std->get_service_status());
        }

    }
?>