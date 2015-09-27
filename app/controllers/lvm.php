<?php namespace phpietadmin\app\controllers;
use phpietadmin\app\core;

class Lvm extends core\BaseController {
    public function add() {
        if (isset($_POST['vg'], $_POST['name'], $_POST['size'])) {
            $lv = $this->model('lvm\lv\Lv', $_POST['vg'], $_POST['name']);
            $lv->add_lv($_POST['size']);
            echo json_encode($lv->logging->get_action_result());
        } else {
            $vgs = $this->model('lvm\vg\Vg', false);
            $data = $vgs->get_vg();

            if ($data !== false) {
                $this->view('lvm/add', $data);
            } else {
                // no volume groups found
            }
        }
    }

    public function configure($param1 = false, $param2 = false) {
        switch ($param1) {
            case false:
                $lv = $this->model('lvm\lv\Lv', false, false);
                $data = $lv->get_lv(false);

                if ($data !== false) {
                    $this->view('lvm/menu', $data);
                } else {
                    // no logical volumes
                    // display error here
                }
                break;
            case 'extent':
                if (isset($_POST['lv'], $_POST['vg'], $_POST['size'], $_POST['remap'])) {
                    $lv = $this->model('lvm\lv\Lv', $_POST['vg'], $_POST['lv']);
                    $lv->extend_lv($_POST['size']);

                    if ($_POST['remap'] === true) {
                        $target = $this->model('target\Target', '');

                        // check if lv is a lun
                        if ($target->is_lun($_POST['lv']) !== false) {
                            // get iqn somehow
                            // create target object with iqn
                            // detach and add lun
                        } else {
                            // do nothing
                        }
                    }
                    echo json_encode($lv->logging->get_action_result());
                } else if (isset($_POST['lv'], $_POST['vg'])) {
                    $lv = $this->model('lvm\lv\Lv', $_POST['vg'], $_POST['lv']);
                    $data['lv'] = $lv->get_lv(false);

                    if ($data['lv'] !== false) {
                        $vg = $this->model('lvm\vg\Vg', $_POST['vg']);
                        $data['vg'] = $vg->get_vg();

                        if ($data['vg'][0]['VFree'] <= 2) {
                            $this->view('message', array('message' => 'Error - The volume group ' . $data['vg'][0]['VG'] . ' is too small!', 'type' => 'warning'));
                        } else {
                            $this->view('lvm/extend', $data);
                        }
                    } else {
                        // lv doesn't exist
                    }
                }
                break;
            case 'shrink':
                if (isset($_POST['lv'], $_POST['vg'], $_POST['size'])) {
                    $lv = $this->model('lvm\lv\Lv', $_POST['vg'], $_POST['lv']);
                    $lv->reduce_lv($_POST['size']);
                    echo json_encode($lv->logging->get_action_result());
                } else if (isset($_POST['lv'], $_POST['vg'])) {
                    $lv = $this->model('lvm\lv\Lv', $_POST['vg'], $_POST['lv']);
                    $data['lv'] = $lv->get_lv(false);
                    if ($data['lv'] !== false) {
                        $vg = $this->model('lvm\vg\Vg', $_POST['vg']);
                        $data['vg'] = $vg->get_vg();

                        if ($data['lv'][0]['LSize'] <= 1) {
                            $this->view('message', array('message' => 'Error - The logical volume ' . $data['vg'][0]['VG'] . ' is too small!', 'type' => 'warning'));
                        } else {
                            $this->view('lvm/shrink', $data);
                        }
                    } else {
                        // lv doesn't exist
                    }
                }
                break;
            case 'snapshot':
                if ($param2 !== false) {
                    if ($param2 === 'add') {
                        if (isset($_POST['lv'], $_POST['vg'], $_POST['size'])) {
                            $lv = $this->model('lvm\lv\Lv', $_POST['vg'], $_POST['lv']);
                            $lv->snapshot_lv($_POST['size']);
                            echo json_encode($lv->logging->get_action_result());
                        } else if (isset($_POST['lv'], $_POST['vg'])) {
                            $lv = $this->model('lvm\lv\Lv', $_POST['vg'], $_POST['lv']);
                            $data['lv'] = $lv->get_lv();
                            if ($data['lv'] !== false) {
                                $vg = $this->model('lvm\vg\Vg', $_POST['vg']);
                                $data['vg'] = $vg->get_vg();
                                if (floatval($data['vg'][0]['VFree']) > 1.1) {
                                    $this->view('lvm/add_snapshot', $data);
                                } else {
                                    $this->view('message', array('message' => 'Error - Volume group is to small for new snapshots!', 'type' => 'warning'));
                                }
                            } else {
                                // lv doesn't exist
                            }
                        }
                    } else if ($param2 === 'delete') {
                        if (isset($_POST['vg'], $_POST['snapshot'])) {
                            $lv = $this->model('lvm\lv\Lv', $_POST['vg'], $_POST['snapshot']);
                            $lv->remove_lv();
                            echo json_encode($lv->logging->get_action_result());
                        } else if (isset($_POST['lv'], $_POST['vg'])) {
                            $lv = $this->model('lvm\lv\Lv', $_POST['vg'], $_POST['lv']);
                            $data['lv'] = $lv->get_snapshot();

                            if ($data['lv'] !== false) {
                                $this->view('lvm/delete_snapshot', $data);
                            } else {
                                $this->view('message', array('message' => 'Error - No snapshots found!', 'type' => 'warning'));
                            }
                        }
                    }
                }
                break;
            case 'rename':
				if (isset($_POST['lv'], $_POST['vg'], $_POST['name'])) {
					$lv = $this->model('lvm\lv\Lv', $_POST['vg'], $_POST['lv']);
					$lv->rename_lv($_POST['name']);
					echo json_encode($lv->logging->get_action_result());
				} else if (isset($_POST['lv'], $_POST['vg'])) {
					$lv = $this->model('lvm\lv\Lv', $_POST['vg'], $_POST['lv']);
					$data['lv'] = $lv->get_lv();
					$this->view('lvm/rename', $data);
				}
                break;
            case 'delete':
				if (isset($_POST['lv'], $_POST['vg'], $_POST['delete'])) {
					$lv = $this->model('lvm\lv\Lv', $_POST['vg'], $_POST['lv']);
					$lv->remove_lv();
					echo json_encode($lv->logging->get_action_result());
				} else if (isset($_POST['lv'], $_POST['vg'])) {
					$lv = $this->model('lvm\lv\Lv', $_POST['vg'], $_POST['lv']);
					$data['lv'] = $lv->get_lv();
					$this->view('lvm/delete', $data);
				}
                break;
            default:
                // invalid url
                break;
        }
    }
}