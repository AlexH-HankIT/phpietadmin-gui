<?php namespace phpietadmin\app\controllers;
use phpietadmin\app\core,
    phpietadmin\app\models;

    class Service extends core\BaseController {
        public function index() {
			$data = $this->baseModel->database->get_services();
			if ($data === 0) {
                $this->view('message', array('message' => 'Error - No services available!', 'type' => 'warning'));
			} else {
				$this->view('services/overview', $data);
			}
        }

		public function change_service_state() {
			if (isset($_POST['servicename']) && !empty($_POST['servicename'])) {
				$service_name = filter_input(INPUT_POST, 'servicename', FILTER_SANITIZE_STRING);

				if (isset($_POST['start'])) {
					$service = $this->model('Service', $service_name);
					$service->action('start');
					echo json_encode($service->logging->get_action_result());
				} else if (isset($_POST['stop'])) {
                    $service = $this->model('Service', $service_name);
					$service->action('stop');
					echo json_encode($service->logging->get_action_result());
				} else if (isset($_POST['restart'])) {
                    $service = $this->model('Service', $service_name);
					$service->action('restart');
					echo json_encode($service->logging->get_action_result());
				}
			}
		}

		public function add() {
            if (isset($_POST['servicename'], $_POST['action']) && !$this->baseModel->std->mempty($_POST['servicename'], $_POST['action'])) {
				$service_name = filter_input(INPUT_POST, 'servicename', FILTER_SANITIZE_STRING);

                switch($_POST['action']) {
                    case 'enable':
                        $service = $this->model('Service', $service_name);
                        $service->change_in_database('enable');
                        echo json_encode($service->logging->get_action_result());
                        break;
                    case 'disable';
                        $service = $this->model('Service', $service_name);
                        $service->change_in_database('disable');
                        echo json_encode($service->logging->get_action_result());
                        break;
                    case 'delete':
                        $service = $this->model('Service', $service_name);
                        $service->delete_from_db();
                        echo json_encode($service->logging->get_action_result());
                        break;
                    case 'add':
                        $service = $this->model('Service', $service_name);
                        $service->add_to_db();
                        echo json_encode($service->logging->get_action_result());
                        break;
                    case 'edit':
                        if (isset($_POST['newvalue'])) {
							$new_value = filter_input(INPUT_POST, 'newvalue', FILTER_SANITIZE_STRING);
                            $service = $this->model('Service', $service_name);
                            $service->rename_in_database($new_value);
                            echo json_encode($service->logging->get_action_result());
                        }
						break;
                }
            } else {
                $data = $this->baseModel->database->get_services(true);
                if ($data !== 0) {
                    $this->view('services/add', $data);
                } else {
                    $this->view('message', array('message' => 'Error - No services available!', 'type' => 'warning'));
                }
            }
		}

        public function hold() {
            if (isset($_POST['action'])) {
                sleep(5);
                if ($_POST['action'] === 'reboot') {
                    shell_exec($this->baseModel->database->get_config('shutdown')['value'] . ' --reboot now');
                } else if ($_POST['action'] === 'shutdown') {
                    shell_exec($this->baseModel->database->get_config('shutdown')['value'] . ' --poweroff now');
                } else {
                    echo "Invalid action";
                }
            }
        }
    }