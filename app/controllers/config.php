<?php namespace phpietadmin\app\controllers;
use phpietadmin\app\core;

    class config extends core\BaseController {
		/**
		 *
		 * Displays the phpietadmin user config menu
		 *
		 * @param	$param1 string
		 * @return      void
		 *
		 */
		public function user($param1 = 'show') {
			switch ($param1) {
				case 'show':
					$users = $this->model('User');
					$data = $users->returnData();

					if ($data !== false) {
						$this->view('config/user_table', $data);
					} else {
						$this->view('message', array('message' => 'No user available!', 'type' => 'warning'));
					}
					break;
				case 'delete':
					if (isset($_POST['username'])) {
                        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
						$user = $this->model('User', $username);
						$user->delete();
						echo json_encode($user->logging->get_action_log());
					}
					break;
				case 'add':
					if (isset($_POST['username'], $_POST['password'])) {
                        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
						$user = $this->model('User', $username);
						$user->add($password);
						echo json_encode($user->logging->get_action_log());
					}
					break;
				case 'change':
					if (isset($_POST['username'], $_POST['row'], $_POST['value'])) {
                        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                        $value = filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING);
						$user = $this->model('User', $username);
						$user->change($value);
						echo json_encode($user->logging->get_action_log());
					}
					break;
				default:
					$this->view('message', array('message' => 'Invalid url', 'type' => 'warning'));
			}
		}

		public function show($param) {
			switch ($param) {
				case 'iet':
					$data = $this->base_model->database->get_config_by_category('iet');
					break;
				case 'misc':
					$data = $this->base_model->database->get_config_by_category('misc');
					break;
				case 'bin':
					$data = $this->base_model->database->get_config_by_category('bin');
					break;
				case 'logging':
					$data = $this->base_model->database->get_config_by_category('logging');
					break;
				default:
					$this->view('message', array('message' => 'Invalid url', 'type' => 'warning'));
			}

			if (isset($data) && !empty($data) && $data !== false) {
				$this->view('config/configtable', $data);
			}
		}

		public function edit_config() {
			if (isset($_POST['option'], $_POST['value'])) {
                $option = filter_input(INPUT_POST, 'option', FILTER_SANITIZE_STRING);
                $value = filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING);
				$config = $this->model('Config', $option);
				$config->change_config('value', $value);
				echo json_encode($config->logging->get_action_result());
			}
		}
	}