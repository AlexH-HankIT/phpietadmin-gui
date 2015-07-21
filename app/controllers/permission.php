<?php
    class permission extends Controller {
        public function addrule() {
            if (isset($_POST['iqn']) && isset($_POST['type']) && isset($_POST['id'])) {
                // Get value of object with $id
                $value = $this->database->get_object_value(intval(['id']));

                // Check for type
                if ($_POST['type'] == "initiator") {
                    $file = $this->database->get_config('ietd_init_allow');
                } else if ($_POST['type'] == "target") {
                    $file = $this->database->get_config('ietd_target_allow');
                } else {
                    die();
                }

                $data = $this->ietpermissions->get_iet_allow($file, $_POST['iqn']);

                if (!is_int($data) && isset($data[0]) && !empty($data[0])) {
                    $key = array_search($value, $data[0]);
                }

                if (isset($key) && is_int($key)) {
                    echo 'The object with value ' . htmlspecialchars($value) . ' is already added!';
                } else {
                    if (isset($file) && !empty($file)) {
                        $return = $this->ietpermissions->add_object_to_iqn($_POST['iqn'], $value, $file);
                        if ($return !== 0) {
                            echo "Failed";
                        } else {
                            echo "Success";
                        }
                    } else {
                        echo "Failed";
                    }
                }
            } else {
                $data['objects'] = $this->database->get_all_objects();
                $data['targets'] = $this->ietadd->get_targets();

                if ($data['targets'] == 3) {
                    $this->view('message', "Error - No targets available!");
                } else {
                    $this->view('permissions/addrule', $data);
                }
            }
        }

        public function deleterule() {
            if (isset($_POST['iqn']) && !isset($_POST['value'])) {
                if (isset($_POST['ruletype'])) {
                    if ($_POST['ruletype'] == 'initiators.allow') {
                        $file = $this->database->get_config('ietd_init_allow');
                    } else if ($_POST['ruletype'] == 'targets.allow') {
                        $file = $this->database->get_config('ietd_target_allow');
                    }
                } else {
                    $file = $this->database->get_config('ietd_init_allow');
                }

                $data = $this->ietpermissions->get_iet_allow($file, $_POST['iqn']);

                if (isset($data[0])) {
                    $data = $data[0];

                    for ($i=0; $i<count($data)-1; $i++) {
                        $value = $this->database->get_object_by_value($data[$i]);

                        if (isset($value) && !empty($value)) {
                            $objects[$i] = $value;
                        } else {
                            $orphans[$i] = $data[$i];
                        }
                    }

                    unset($data);
                    if (isset($objects) && !empty($objects)) {
                        $objects = array_values($objects);
                        $data['deleteruleobjectstable'] = $objects;
                    }

                    if (isset($orphans) && !empty($orphans)) {
                        $orphans = array_values($orphans);
                        $data['deleteruleorphanedtable'] = $orphans;
                    }

                    if (isset($data)) {
                        $this->view('permissions/deleterule', $data);
                    } else {
                        $this->view('message', 'No rules set for this target!');
                    }
                } else {
                    $this->view('message', 'No rules set for this target!');
                }
            } else if (isset($_POST['iqn']) && isset($_POST['value'])) {
                if ($_POST['ruletype'] == 'initiators.allow') {
                    $file = $this->database->get_config('ietd_init_allow');
                } else if ($_POST['ruletype'] == 'targets.allow') {
                    $file = $this->database->get_config('ietd_target_allow');
                } else {
                    $file = $this->database->get_config('ietd_init_allow');
                }

                if (isset($file) && !empty($file)) {
                    $return = $this->ietpermissions->delete_object_from_iqn($_POST['iqn'], $_POST['value'], $file);
                    if ($return !== 0) {
                        echo "Failed";
                    } else {
                        echo "Success";
                    }
                } else {
                    echo "Failed";
                }
            }
        }

        public function adduser() {
            if (isset($_POST['iqn']) && isset($_POST['type']) && isset($_POST['id'])) {
                if ($_POST['type'] == 'Incoming') {
                    $type = 'IncomingUser';
                } else if ($_POST['type'] == 'Outgoing') {
                    $type = 'OutgoingUser';
                } else {
                    echo "The type value is invalid!";
                    die();
                }

                // Get username and password
                $data = $this->database->get_ietuser(intval($_POST['id']));

                // Get current tid of target
                $tid = $this->ietadd->get_tid($_POST['iqn']);

                // Check if user is already added
                $user = $this->ietdelete->get_configured_iet_users($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm'), $tid);

                // Check if user contains an error code
                if (!is_int($user)) {
                    foreach ($user as $key => $value) {
                        $usernames[$key] = $value[1];
                    }
                    $key = array_search($data['username'], $usernames);
                }

                if(isset($key) && $key === false) {
                    echo 'User is ' . htmlspecialchars($data['username']) . ' already there!';
                } else {
                    // Add user to daemon
                    $return = $this->exec->add_user_to_daemon($tid, $type, $data['username'], $data['password']);

                    if ($return != 0) {
                        echo 'Could not add user ' . htmlspecialchars($data['username']) . ' to target ' . htmlspecialchars($_POST['iqn']) .'Server said:' . htmlspecialchars($return[0]);
                    } else {
                        $option = $type . ' ' . $data['username'] . ' ' . $data['password'];
                        $return = $this->ietadd->add_option_to_iqn_in_file($_POST['iqn'], $this->database->get_config('ietd_config_file'), $option);

                        if ($return !== 0) {
                            if ($return == 1) {
                                echo 'The user was added to the daemon, but not to the config file, because it\'s read only.';
                            } else if ($return == 3) {
                                echo 'The user was added to the daemon, but not to the config file, because the target isn\'t there.';
                            } else {
                                echo 'The user was added to the daemon, but not to the config file. Reason is unkown.';
                            }
                        } else {
                            echo "Success";
                        }
                    }
                }
            } else {
                $data['targets'] = $this->ietadd->get_targets();
                $data['user'] = $this->database->get_all_usernames(true);

                if ($data['targets'] == 3) {
                    $this->view('message', "Error - No targets available!");
                } else {
                    $this->view('permissions/adduser', $data);
                }
            }
        }

        public function deleteuser() {
            if (isset($_POST['iqn']) && !isset($_POST['user']) && !isset($_POST['type'])) {
                // get tid to iqn
                $tid = $this->ietadd->get_tid($_POST['iqn']);

                // get all users for tid
                $users = $this->ietdelete->get_configured_iet_users($this->database->get_config('sudo') . ' ' . $this->database->get_config('ietadm'), $tid);

                if ($users == 3) {
                    $this->view('message', 'No users set for this target!');
                } else {
                    $this->view('permissions/deleteuser', $users);
                }
            } else if(isset($_POST['user']) && isset($_POST['type']) && isset($_POST['iqn'])) {
                // get type
                if ($_POST['type'] == 'IncomingUser') {
                    $type = 'IncomingUser';
                } else if ($_POST['type'] == 'OutgoingUser') {
                    $type = 'OutgoingUser';
                } else {
                    echo "The type value is invalid!";
                    die();
                }

                // get tid to iqn
                $tid = $this->ietadd->get_tid($_POST['iqn']);

                // delete user from daemon
                $return = $this->exec->delete_user_from_daemon($tid, $type, $_POST['user']);

                if ($return !== 0) {
                    echo 'Could not delete user ' . htmlspecialchars($_POST['user']) . ' from target ' . htmlspecialchars($_POST['iqn']) . 'Server said:' . htmlspecialchars($return[0]);
                } else {
                    // if successful delete uesr from config file
                    // get exact line with user
                    $data = $this->database->get_user_by_name($_POST['user']);
                    $return = $this->ietdelete->delete_option_from_iqn($_POST['iqn'], $_POST['type'] . ' ' . $_POST['user'] . ' ' . $data['password'], $this->database->get_config('ietd_config_file'));

                    if ($return !== 0) {
                        if ($return == 1) {
                            echo 'The user was deleted from the daemon, but not from the config file, because it\'s read only.';
                        } else if ($return == 3) {
                            echo 'The user was deleted from the daemon, but not from the config file, because the target isn\'t there.';
                        } else {
                            echo 'The user was deleted from the daemon, but not from the config file. Reason is unkown.';
                        }
                    } else {
                        echo true;
                    }
                }
            }
        }

        public function adddisuser() {
            if (isset($_POST['id']) && isset($_POST['type'])) {
                // get type
                if ($_POST['type'] == 'Incoming') {
                    $type = 'IncomingUser';
                } else if ($_POST['type'] == 'Outgoing') {
                    $type = 'OutgoingUser';
                } else {
                    echo "The type value is invalid!";
                    die();
                }

                // Get username and password
                $data = $this->database->get_ietuser(intval($_POST['id']));

                // Check if user is already added
                $user = $this->ietdelete->get_configured_iet_users($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm'));

                // Check if user contains an error code
                if (!is_int($user)) {
                    foreach ($user as $key => $value) {
                        $usernames[$key] = $value[1];
                    }
                    if (empty($usernames)) {
                        $key = false;
                    } else {
                        $key = array_search($data['username'], $usernames);
                    }
                } else {
                    $key = false;
                }

                if($key !== false) {
                    echo 'User is ' . htmlspecialchars($data['username']) . ' already there!';
                } else {
                    // add user to daemon and config file
                    $return = $this->exec->add_discovery_user_to_daemon($type, $data['username'], $data['password']);

                    if ($return !== 0) {
                        echo 'Could not add user ' . htmlspecialchars($_POST['user']) . 'Server said:' . htmlspecialchars($return[0]);
                    } else {
                        $return = $this->ietadd->add_global_option_to_file($this->database->get_config('ietd_config_file'), $type . ' ' . $data['username'] . ' ' . $data['password']);

                        if ($return !== 0) {
                            if ($return == 1) {
                                echo 'The user was added to the daemon, but not to the config file, because it\'s read only.';
                            } else if ($return == 4) {
                                echo 'User ' . htmlspecialchars($data['username']) . ' is already there!';
                            } else {
                                echo 'The user was added to the daemon, but not to the config file. Reason is unkown.';
                            }
                        } else {
                            echo true;
                        }
                    }
                }
            } else  {
                $data['user'] = $this->database->get_all_usernames(true);
                $this->view('permissions/adddisuser', $data);
            }
        }

        public function deletedisuser() {
            if (isset($_POST['type']) && isset($_POST['username'])) {
                // get type
                if ($_POST['type'] == 'IncomingUser') {
                    $type = 'IncomingUser';
                } else if ($_POST['type'] == 'OutgoingUser') {
                    $type = 'OutgoingUser';
                } else {
                    echo "The type value is invalid!";
                    die();
                }

                // delete user from daemon
                $return = $this->exec->delete_discovery_user_from_daemon($type, $_POST['username']);

                if ($return !== 0) {
                    echo 'Could not delete user ' . htmlspecialchars($_POST['username']) . 'Server said:' . htmlspecialchars($return[0]);
                } else {
                    // delete user from config file
                    $data = $this->database->get_user_by_name($_POST['username']);
                    $return = $this->ietdelete->delete_global_option_from_file($type . ' ' . $_POST['username'] . ' ' . $data['password'], $this->database->get_config('ietd_config_file'));

                    if ($return !== 0) {
                        if ($return == 1) {
                            echo 'The user was deleted from the daemon, but not from the config file, because it\'s read only.';
                        } else if ($return == 3) {
                            echo 'The user was deleted from the daemon, but not from the config file because it wasn\'t there!';
                        } else {
                            echo 'The user was deleted from the daemon but not from the config file. The reason is unkown.';
                        }
                    } else {
                        echo true;
                    }
                }
            } else {
                $user = $this->ietdelete->get_configured_iet_users($this->database->get_config('sudo') . " " . $this->database->get_config('ietadm'));

                if ($user == 3) {
                    $this->view('message', 'Error - No users configured!');
                } else {
                    $this->view('permissions/deletedisuser', $user);
                }
            }
        }
    }
?>