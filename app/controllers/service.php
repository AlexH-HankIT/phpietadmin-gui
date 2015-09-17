<?php namespace phpietadmin\app\controllers;
use phpietadmin\app\core,
    phpietadmin\app\models;

    class Service extends core\BaseController {
        public function index() {
			$data = $this->base_model->database->get_services();
			if ($data === 0) {
                $this->view('message', array('message' => 'Error - No services available!', 'type' => 'warning'));
			} else {
				$this->view('services/overview', $data);
			}
        }

		public function change_service_state() {
			if (isset($_POST['servicename']) && !empty($_POST['servicename'])) {
				if (isset($_POST['start'])) {
					$service = $this->model('Service', $_POST['servicename']);
					$service->action('start');
					echo json_encode($service->logging->get_action_result());
				} else if (isset($_POST['stop'])) {
                    $service = $this->model('Service', $_POST['servicename']);
					$service->action('stop');
					echo json_encode($service->logging->get_action_result());
				} else if (isset($_POST['restart'])) {
                    $service = $this->model('Service', $_POST['servicename']);
					$service->action('restart');
					echo json_encode($service->logging->get_action_result());
				}
			}
		}

		public function add() {
            if (isset($_POST['servicename'], $_POST['action']) && !$this->std->mempty($_POST['servicename'], $_POST['action'])) {
                switch($_POST['action']) {
                    case 'enable':
                        $service = $this->model('Service', $_POST['servicename']);
                        $service->change_in_database('enable');
                        echo json_encode($service->logging->get_action_result());
                        break;
                    case 'disable';
                        $service = $this->model('Service', $_POST['servicename']);
                        $service->change_in_database('disable');
                        echo json_encode($service->logging->get_action_result());
                        break;
                    case 'delete':
                        $service = $this->model('Service', $_POST['servicename']);
                        $service->delete_from_db();
                        echo json_encode($service->logging->get_action_result());
                        break;
                    case 'add':
                        $service = $this->model('Service', $_POST['servicename']);
                        $service->add_to_db();
                        echo json_encode($service->logging->get_action_result());
                        break;
                    case 'rename':
                        if (isset($_POST['newvalue'])) {
                            $service = $this->model('Service', $_POST['servicename']);
                            $service->rename_in_database($_POST['newvalue']);
                            echo json_encode($service->logging->get_action_result());
                        }
                }
            } else {
                $data = $this->base_model->database->get_services(true);
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
                if ($_POST['action'] == 'reboot') {
                    shell_exec($this->base_model->database->get_config('sudo') . ' ' . $this->base_model->database->get_config('shutdown') . ' --reboot now');
                } else if ($_POST['action'] == 'shutdown') {
                    shell_exec($this->base_model->database->get_config('sudo') . ' ' . $this->base_model->database->get_config('shutdown') . ' --poweroff now');
                } else {
                    echo "Invalid action";
                }
            }
        }
    }