<?php namespace phpietadmin\app\controllers;
use phpietadmin\app\core,
	phpietadmin\app\models as models;

    class targets extends core\BaseController {
        public function addtarget() {
            if (!empty($_POST['name'])) {
                $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
                $target = $this->model('target\Target', $this->base_model->database->get_config('iqn')['value'] . ':' . $name);
                $target->add();
                echo json_encode($target->logging->get_action_result());
            } else {
                $this->view('targets/addTarget', $this->base_model->database->get_config('iqn')['value'] . ':');
            }
        }

        public function adddisuser() {
            if (isset($_POST['id'], $_POST['type'])) {
                $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
                $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

                if ($type === 'Incoming') {
                    $type = 'IncomingUser';
                } else if ($type === 'Outgoing') {
                    $type = 'OutgoingUser';
                } else {
                    echo "The type value is invalid!";
                    die();
                }
                $target = $this->model('target\Target');
                $target->add_user($id, true, $type);
                echo json_encode($target->logging->get_action_result());
            } else {
                $data = $this->base_model->database->get_all_usernames(true);

                if ($data != 0) {
                    $this->view('targets/add_dis_user', $data);
                } else {
                    $this->view('message', array('message' => 'Error - No user available!', 'type' => 'warning'));
                }
            }
        }

        public function deletedisuser() {
            if (isset($_POST['type'], $_POST['id'])) {
                $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
                $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

                $target = $this->model('target\Target');
                $target->delete_user($id, true, $type);
                echo json_encode($target->logging->get_action_result());
            } else {
                $target = $this->model('target\Target');
                $data = $target->get_user(true);
                if ($data !== false) {
                    $this->view('targets/delete_dis_user', $data);
                } else {
                    $this->view('message', array('message' => 'Error - No user available!', 'type' => 'warning'));
                }
            }
        }

		public function configure($iqn = false, $function = false) {
            switch ($function) {
                case 'maplun':
                    $this->mapLun($iqn);
                    break;
                case 'deletelun':
                    $this->deleteLun($iqn);
                    break;
                case 'addrule':
                    $this->addrule($iqn);
                    break;
                case 'deleterule':
                    $this->deleterule($iqn);
                    break;
				case 'adduser':
					$this->adduser($iqn);
					break;
				case 'deleteuser':
					$this->deleteuser($iqn);
					break;
				case 'session':
					$this->session($iqn);
					break;
                case 'menu':
                    $this->menu($iqn);
                    break;
                default:
                    $this->select($iqn);
            }
		}

        /*
         * Display the iqn selector
         */
        private function select($iqn) {
            $targets = $this->model('target\Target', false);
            $configureTargetData['targets'] = $targets->return_target_data();
            if ($iqn !== false) {
                // Check if iqn is really existing
                $target = $this->model('target\Target', $iqn);
                if ($target->target_status !== true) {
                    $iqn = false;
                }
            }
            $configureTargetData['iqn'] = $iqn;
            $this->view('targets/configureTargetSelect', $configureTargetData);
        }

        /*
         * Display the iqn menu
         */
        private function menu($iqn) {
            if ($iqn !== false) {
                $target = $this->model('target\Target', $iqn);
                if ($target->target_status === true) {
                    $this->view('targets/configureTargetMenu', $iqn);
                }
            }
        }

		private function mapLun($iqn) {
            if (isset($_POST['type'], $_POST['mode'], $_POST['path']) && !$this->base_model->std->mempty($_POST['type'], $_POST['mode'], $_POST['path'])) {
                $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
                $mode = filter_input(INPUT_POST, 'mode', FILTER_SANITIZE_STRING);
                $path = filter_input(INPUT_POST, 'path', FILTER_SANITIZE_STRING);

                $target = $this->model('target\Target', $iqn);

                if ($target->target_status !== false) {
                    $target->add_lun($path, $mode, $type);
                    echo json_encode($target->logging->get_action_result());
                } else {
                    $this->view('message', array('message' => 'The target does not exist!', 'type' => 'danger'));
                }
            } else {
                $targets = $this->model('target\Target', false);
                $lv = $this->model('lvm\lv\Lv', false, false);
                $unused_lun = $lv->get_unused_lun($targets->return_all_used_lun());
                if (!empty($unused_lun) && $unused_lun !== false) {
                    $this->view('targets/maplun', $unused_lun);
                } else {
                    $this->view('message', array('message' => 'Error - No logical volumes available!', 'type' => 'warning'));
                }
            }
		}

        private function deleteLun($iqn) {
            if (isset($_POST['path'])) {
                $path = filter_input(INPUT_POST, 'path', FILTER_SANITIZE_STRING);

                // delete lun with id
                $target = $this->model('target\Target', $iqn);

                $target->detach_lun($path, true);
                echo json_encode($target->logging->get_action_result());
            } else {
                // fetch data via target model
                $target = $this->model('target\Target', $iqn);
                $data = $target->return_target_data();

                if ($target->target_status !== false) {
                    if (isset($data['lun'])) {
                        // display lun for iqn
                        $this->view('targets/deleteLun', $data);
                    } else {
                        $this->view('message', array('message' => 'Error - No lun available!', 'type' => 'warning'));
                    }
                } else {
                    $this->view('message', array('message' => 'The target does not exist!', 'type' => 'danger'));
                }
            }
        }

        private function addrule($iqn) {
            if (isset($_POST['type'], $_POST['id'])) {
                $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
                $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

                $target = $this->model('target\Target', $iqn);
                $target->add_acl($id, $type);
                echo json_encode($target->logging->get_action_result());
            } else {
                $data = $this->base_model->database->get_all_objects();

                if ($data === 3) {
                    $this->view('message', array('message' => 'Error - No objects available!', 'type' => 'warning'));
                } else {
                    $this->view('targets/addRule', $data);
                }
            }
        }

        private function deleterule($iqn) {
            if (isset($_POST['value'], $_POST['ruleType'])) {
                $value = filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING);
                $rule_type = filter_input(INPUT_POST, 'ruleType', FILTER_SANITIZE_STRING);

                $target = $this->model('target\Target', $iqn);
                $target->delete_acl($value, $rule_type);
                echo json_encode($target->logging->get_action_result());
            } else if (isset($_POST['ruleType'])){
                // display body here
                $target = $this->model('target\Target', $iqn);

                if ($_POST['ruleType'] === 'targets') {
                    $data = $target->get_acls('targets');
                    if ($data !== false) {
                        // delete the iqn
                        unset($data[0]);
                        // display target type
                        $this->view('targets/deleteRule', $data);
                    } else {
                        $this->view('message', array('message' => 'Error - No target acl available!', 'type' => 'warning'));
                    }
                } else if ($_POST['ruleType'] === 'initiators') {
                    $data = $target->get_acls('initiators');
                    if ($data !== false) {
                        // delete the iqn
                        unset($data[0]);
                        // display initiator acl as default
                        $this->view('targets/deleteRule', $data);
                    } else {
                        $this->view('message', array('message' => 'Error - No initiator acl available!', 'type' => 'warning'));
                    }
                }
            } else {
                $this->view('targets/deleteRuleControl');
            }
        }

		private function adduser($iqn) {
			if (isset($_POST['type'], $_POST['id'])) {
				$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
				$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);

				$target = $this->model('target\Target', $iqn);
				$target->add_user($id, false, $type);
				echo json_encode($target->logging->get_action_result());
			} else {
				$data = $this->base_model->database->get_all_usernames(true);
				if ($data != 0) {
					$this->view('targets/addUser', $data);
				} else {
					$this->view('message', array('message' => 'Error - No user available!', 'type' => 'warning'));
				}
			}
		}

		private function deleteuser($iqn) {
			if (isset($_POST['id'], $_POST['type'])) {
				$id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
				$type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);

				$target = $this->model('target\Target', $iqn);
				$target->delete_user($id, false, $type);
				echo json_encode($target->logging->get_action_result());
			} else {
				$target = $this->model('target\Target', $iqn);

				$data = $target->get_user();

				if ($data == 3 || $data == false) {
					$this->view('message', array('message' => 'Error - No users set for this target!', 'type' => 'warning'));
				} else {
					$this->view('targets/deleteUser', $data);
				}
			}
		}

		private function session($iqn) {
			if (isset($_POST['sid'])) {
				$sid = filter_input(INPUT_POST, 'sid', FILTER_SANITIZE_STRING);
				$target = $this->model('target\Target', $iqn);

				if ($target->target_status !== false) {
					$target->disconnect_session($sid);
					echo json_encode($target->logging->get_action_result());
				} else {
					$this->view('message', array('message' => 'The target does not exist!', 'type' => 'danger'));
				}
			} else {
				$target = $this->model('target\Target', $iqn);
				$data = $target->return_target_data();

				if (isset($data['session'])) {
					$view['heading'] = array_keys($data['session'][0]);
					$view['body'] = $data['session'];

					$this->view('targets/session', $view);
				} else {
					$this->view('message', array('message' => 'Error - The target has no open sessions!', 'type' => 'warning'));
				}
			}
		}

        /*public function configure($param1 = false, $param2 = false) {
            $targets = $this->model('target\Target', false);
            $data = $targets->return_target_data();

            if ($data !== false) {
                if ($param1 === false) {
                    $this->view('targets/configuretarget', $data);
                } else if ($param1 == 'maplun') {
                    if (isset($_POST['target'], $_POST['type'], $_POST['mode'], $_POST['path']) && !$this->base_model->std->mempty($_POST['target'], $_POST['type'], $_POST['mode'], $_POST['path'])) {
                        $target = filter_input(INPUT_POST, 'target', FILTER_SANITIZE_STRING);
                        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
                        $mode = filter_input(INPUT_POST, 'mode', FILTER_SANITIZE_STRING);
                        $path = filter_input(INPUT_POST, 'path', FILTER_SANITIZE_STRING);

                        $target = $this->model('target\Target', $target);

                        if ($target->target_status !== false) {
                            $target->add_lun($path, $mode, $type);
                            echo json_encode($target->logging->get_action_result());
                        } else {
                            $this->view('message', array('message' => 'The target does not exist!', 'type' => 'danger'));
                        }
                    } else {
                        $lv = $this->model('lvm\lv\Lv', false, false);
                        $unused_lun = $lv->get_unused_lun($targets->return_all_used_lun());
                        if (!empty($unused_lun) && $unused_lun !== false) {
                            $this->view('targets/maplun', $unused_lun);
                        } else {
                            $this->view('message', array('message' => 'Error - No logical volumes available!', 'type' => 'warning'));
                        }
                    }
                } else if ($param1 == 'deletelun') {
                    if (isset($_POST['iqn'], $_POST['path'])) {
                        $iqn = filter_input(INPUT_POST, 'iqn', FILTER_SANITIZE_STRING);
                        $path = filter_input(INPUT_POST, 'path', FILTER_SANITIZE_STRING);

                        // delete lun with id
                        $target = $this->model('target\Target', $iqn);

                        $target->detach_lun($path, true);
                        echo json_encode($target->logging->get_action_result());
                    } else if (isset($_POST['iqn'])) {
                        $iqn = filter_input(INPUT_POST, 'iqn', FILTER_SANITIZE_STRING);

                        // fetch data via target model
                        $target = $this->model('target\Target', $iqn);
                        $data = $target->return_target_data();

                        if ($target->target_status !== false) {
                            if (isset($data['lun'])) {
                                // display lun for iqn
                                $this->view('targets/deletelun', $data);
                            } else {
                                $this->view('message', array('message' => 'Error - No lun available!', 'type' => 'warning'));
                            }
                        } else {
                            $this->view('message', array('message' => 'The target does not exist!', 'type' => 'danger'));
                        }
                    }
                } else if ($param1 == 'adduser') {
                    if (isset($_POST['type'], $_POST['id'], $_POST['iqn'])) {
                        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
                        $iqn = filter_input(INPUT_POST, 'iqn', FILTER_SANITIZE_STRING);
                        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);

                        $target = $this->model('target\Target', $iqn);
                        $target->add_user($id, false, $type);
                        echo json_encode($target->logging->get_action_result());
                    } else if (isset($_POST['iqn'])) {
                        $data = $this->base_model->database->get_all_usernames(true);
                        if ($data != 0) {
                            $this->view('targets/adduser', $data);
                        } else {
                            $this->view('message', array('message' => 'Error - No user available!', 'type' => 'warning'));
                        }
                    }
                } else if ($param1 == 'deleteuser') {
                    if (isset($_POST['id'], $_POST['type'], $_POST['iqn'])) {
                        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
                        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
                        $iqn = filter_input(INPUT_POST, 'iqn', FILTER_SANITIZE_STRING);

                        $target = $this->model('target\Target', $iqn);
                        $target->delete_user($id, false, $type);
                        echo json_encode($target->logging->get_action_result());
                    } else if (isset($_POST['iqn'])) {
                        $iqn = filter_input(INPUT_POST, 'iqn', FILTER_SANITIZE_STRING);

                        $target = $this->model('target\Target', $iqn);

                        $data = $target->get_user();

                        if ($data == 3 || $data == false) {
                            $this->view('message', array('message' => 'Error - No users set for this target!', 'type' => 'warning'));
                        } else {
                            $this->view('targets/deleteuser', $data);
                        }
                    }
                } else if ($param1 == 'deletetarget') {
                    if (isset($_POST['iqn'], $_POST['delete_acl'], $_POST['force'])) {
                        $iqn = filter_input(INPUT_POST, 'iqn', FILTER_SANITIZE_STRING);
                        $target = $this->model('target\Target', $iqn);
                        $target->delete_target(boolval($_POST['force']), boolval($_POST['delete_acl']));
                        echo json_encode($target->logging->get_action_result());
                    } else if (isset($_POST['iqn'])) {
                        $iqn = filter_input(INPUT_POST, 'iqn', FILTER_SANITIZE_STRING);
                        $target = $this->model('target\Target', $iqn);
                        $this->view('targets/delete_target', $target->return_target_data());
                    }
                } else if ($param1 == 'deletesession') {
                    if (isset($_POST['iqn'], $_POST['sid'])) {
                        $iqn = filter_input(INPUT_POST, 'iqn', FILTER_SANITIZE_STRING);
                        $sid = filter_input(INPUT_POST, 'sid', FILTER_SANITIZE_STRING);

                        $target = $this->model('target\Target', $iqn);

                        if ($target->target_status !== false) {
                            $target->disconnect_session($sid);
                            echo json_encode($target->logging->get_action_result());
                        } else {
                            $this->view('message', array('message' => 'The target does not exist!', 'type' => 'danger'));
                        }
                    } else if (isset($_POST['iqn'])) {
                        $iqn = filter_input(INPUT_POST, 'iqn', FILTER_SANITIZE_STRING);

                        $target = $this->model('target\Target', $iqn);
                        $data = $target->return_target_data();

                        if (isset($data['session'])) {
                            $view['heading'] = array_keys($data['session'][0]);
                            $view['body'] = $data['session'];

                            $this->view('targets/sessiondelete', $view);
                        } else {
                            $this->view('message', array('message' => 'Error - The target has no open sessions!', 'type' => 'warning'));
                        }
                    }
                } else if ($param1 == 'settings') {
                    if (isset($_POST['option'], $_POST['newvalue'], $_POST['iqn'])) {
						$target = $this->model('target\Target', filter_input(INPUT_POST, 'iqn', FILTER_SANITIZE_STRING));
						$target->add_setting($option = filter_input(INPUT_POST, 'option', FILTER_SANITIZE_STRING), filter_input(INPUT_POST, 'newvalue', FILTER_SANITIZE_STRING));
						echo json_encode($target->logging->get_action_result());
                    } else if (isset($_POST['iqn'])) {
						$target = $this->model('target\Target', filter_input(INPUT_POST, 'iqn', FILTER_SANITIZE_STRING));
						$this->view('targets/settings_table', $target->get_all_settings());
                    }
                } else if ($param1 == 'addrule') {
                    if (isset($_POST['iqn'], $_POST['type'], $_POST['id'])) {
                        $iqn = filter_input(INPUT_POST, 'iqn', FILTER_SANITIZE_STRING);
                        $type = filter_input(INPUT_POST, 'type', FILTER_SANITIZE_STRING);
                        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

                        $target = $this->model('target\Target', $iqn);
                        $target->add_acl($id, $type);
                        echo json_encode($target->logging->get_action_result());
                    } else {
                        $data = $this->base_model->database->get_all_objects();

                        if ($data === 3) {
                            $this->view('message', array('message' => 'Error - No objects available!', 'type' => 'warning'));
                        } else {
                            $this->view('targets/addrule', $data);
                        }
                    }
				} else if ($param1 == 'deleterule') {
					if (isset($_POST['iqn'], $_POST['value'], $_POST['rule_type'])) {
                        $iqn = filter_input(INPUT_POST, 'iqn', FILTER_SANITIZE_STRING);
                        $value = filter_input(INPUT_POST, 'value', FILTER_SANITIZE_STRING);
                        $rule_type = filter_input(INPUT_POST, 'rule_type', FILTER_SANITIZE_STRING);

						$target = $this->model('target\Target', $iqn);
						$target->delete_acl($value, $rule_type);
						echo json_encode($target->logging->get_action_result());
					} else if (isset($_POST['iqn'])) {
                        $iqn = filter_input(INPUT_POST, 'iqn', FILTER_SANITIZE_STRING);

						// display body here
						$target = $this->model('target\Target', $iqn);

                        if ($param2 == 'targets') {
                            $data = $target->get_acls('targets');
                            if ($data !== false) {
                                // delete the iqn
                                unset($data[0]);
                                // display target type
                                $this->view('targets/delete_rule', $data);
                            } else {
                                $this->view('message', array('message' => 'Error - No target acl available!', 'type' => 'warning'));
                            }
                        } else if ($param2 == 'initiators') {
                            $data = $target->get_acls('initiators');
                            if ($data !== false) {
                                // delete the iqn
                                unset($data[0]);
                                // display initiator acl as default
                                $this->view('targets/delete_rule', $data);
                            } else {
                                $this->view('message', array('message' => 'Error - No initiator acl available!', 'type' => 'warning'));
                            }
                        } else if ($param2 == 'control') {
                            // display control
                            $this->view('targets/delete_rule_control');
                        } else {
                            http_response_code(404);
                            echo 'Invalid url';
                        }
					}
                } else {
                    http_response_code(404);
                    echo 'Invalid url';
                }
            } else {
                $this->view('message', array('message' => 'Error - No targets available!', 'type' => 'warning'));
            }
        }*/
    }