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
					$this->view('config/userConfigMenu', $users->returnData());
					break;
				case 'delete':
					if (isset($_POST['username'])) {
                        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
						$user = $this->model('User', $username);
						$user->delete();
						echo json_encode($user->logging->get_action_result());
					}
					break;
				case 'add':
					if (isset($_POST['username'], $_POST['password'])) {
                        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                        $password = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
						$user = $this->model('User', $username);
						$user->add($password);
						echo json_encode($user->logging->get_action_result());
					}
					break;
				case 'change':
					if (isset($_POST['username'], $_POST['value'])) {
                        $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
                        $value = filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING);
						$user = $this->model('User', $username);
						$user->change($value);
						echo json_encode($user->logging->get_action_result());
					}
					break;
				default:
					$this->view('message', array('message' => 'Invalid url', 'type' => 'warning'));
			}
		}

		public function show($param) {
			switch ($param) {
				case 'iet':
					$data = $this->baseModel->database->get_config_by_category('iet');
					break;
				case 'misc':
					$data = $this->baseModel->database->get_config_by_category('misc');
					break;
				case 'bin':
					$data = $this->baseModel->database->get_config_by_category('bin');
					break;
				case 'logging':
					$data = $this->baseModel->database->get_config_by_category('logging');
					break;
				case 'backup':
					$data = $this->baseModel->database->get_config_by_category('backup');
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

		public function release() {
			if (isset($_POST['release'])) {
				if ($_POST['release'] === 'stable') {
					$config = $this->model('Config', 'releaseCheck');
					$config->change_config('value', 'stable');
					$data = $this->baseModel->database->get_config('stableReleaseUrl');
					echo file_get_contents($data['value']);
				} else if ($_POST['release'] === 'beta') {
					$config = $this->model('Config', 'releaseCheck');
					$config->change_config('value', 'beta');
					$data = $this->baseModel->database->get_config('betaReleaseUrl');
					echo file_get_contents($data['value']);
				}
			} else {
				try {
					$versionFile = $this->baseModel->std->getVersionFile();
					$data['installedVersion'] = $versionFile['version'];
					$data['installedRelease'] = $versionFile['release'];
					$data['release'] = $this->baseModel->database->get_config('releaseCheck')['value'];
					$this->view('config/releases', $data);
				} catch(\Exception $e) {
					$this->view('message', array('message' => $e->getMessage(), 'type' => 'danger'));
				}
			}
		}

		public function checkUpdate() {
			$release = $this->baseModel->database->get_config('releaseCheck')['value'];
			if ($release === 'stable') {
				echo file_get_contents($this->baseModel->database->get_config('stableReleaseUrl')['value']);
			} else {
				echo file_get_contents($this->baseModel->database->get_config('betaReleaseUrl')['value']);
			}
		}
	}