<?php
    class Service extends Controller {
        public function index() {
            if (isset($_POST['servicename'])) {
                $servicename = escapeshellarg($_POST['servicename']);
                $sudo = $this->database->get_config('sudo');
                $service = $this->database->get_config('service');
                if (isset($_POST['start'])) {
                    $command = escapeshellcmd($sudo . ' ' . $service . ' ' . $servicename .  ' start');
                } else if (isset($_POST['stop'])) {
                    $command = escapeshellcmd($sudo . ' ' . $service . ' ' . $servicename . ' stop');
                } else if (isset($_POST['restart'])) {
                    $command = escapeshellcmd($sudo . ' ' . $service . ' ' . $servicename . ' restart');
                }

                if (empty($command)) {
                    echo 'Invalid command!';
                } else {
                    echo htmlspecialchars(shell_exec($command));
                }
            } else {
                $this->view('services/overview', $this->database->get_services());
            }
        }

        public function add() {
            if (isset($_POST['servicename'])) {
                if (isset($_POST['action'])) {
                    // $_POST['action'] == 'enable' <-- enable service
                    // $_POST['action'] == 'disable' <-- disable service
                    // $_POST['action'] == 'delete' <-- delete service
                    // $_POST['action'] == 'add' <-- add service
                    if ($_POST['action'] == 'enable') {
                        echo htmlspecialchars(($this->database->change_service($_POST['servicename'], 'enabled', '1')));
                    } else if ($_POST['action'] == 'disable') {
                        echo htmlspecialchars($this->database->change_service($_POST['servicename'], 'enabled', '0'));
                    } else if ($_POST['action'] == 'delete') {
                        echo htmlspecialchars($this->database->delete_service($_POST['servicename']));
                    } else if ($_POST['action'] == 'add') {
                        echo htmlspecialchars($this->database->add_service($_POST['servicename']));
                    } else if ($_POST['action'] == 'edit') {
                        if (isset($_POST['newvalue'])) {
                            echo htmlspecialchars(($this->database->change_service($_POST['servicename'], 'name', $_POST['newvalue'])));
                        } else {
                            echo "Missing value";
                        }
                    } else {
                        echo "Invalid action";
                    }
                }
            } else {
                $this->view('services/add', $this->database->get_services(true));
            }
        }

        public function check_service_already_exists() {
            if (isset($_POST['servicename'])) {
                foreach ($this->database->get_services(true) as $key => $value) {
                    $servicenames[$key] = $value['name'];
                }

                if (empty($servicenames)) {
                    echo 'false';
                } else {
                    // if the action is edit, we check the new value
                    if ($_POST['action'] == 'edit') {
                        $service = $_POST['newvalue'];
                    } else if ($_POST['action'] == 'add') {
                        $service = $_POST['servicename'];
                    } else {
                        echo 'Invalid action';
                        die();
                    }

                    $return = array_search($service, $servicenames);

                    if ($return !== false) {
                        echo 'true';
                    } else {
                        echo 'false';
                    }
                }
            }
        }

        public function hold() {
            if (isset($_POST['action'])) {
                sleep(5);
                if ($_POST['action'] == 'reboot') {
                    shell_exec($this->database->get_config('sudo') . ' ' . $this->database->get_config('shutdown') . ' --reboot now');
                } else if ($_POST['action'] == 'shutdown') {
                    shell_exec($this->database->get_config('sudo') . ' ' . $this->database->get_config('shutdown') . ' --poweroff now');
                } else {
                    echo "Invalid action";
                }
            }
        }
    }
?>