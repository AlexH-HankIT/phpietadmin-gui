<?php
namespace app\controllers;

use app\core;

class Lvm extends core\BaseController {
    public function add() {
        if (isset($_POST['vg'], $_POST['name'], $_POST['size'])) {
            $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_NUMBER_FLOAT);
            $vg = filter_input(INPUT_POST, 'vg', FILTER_SANITIZE_STRING);
            $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

            $lv = $this->model('lvm\lv\Lv', $vg, $name);
            $lv->add_lv(floatval($size));
            echo json_encode($lv->logging->get_action_result());
        } else {
            $vgs = $this->model('lvm\vg\Vg', false);
            $data = $vgs->get_vg();

            if ($data !== false) {
                $this->view('lvm/add', $data);
            } else {
                $this->view('message', array('message' => 'Error - No volume groups found', 'type' => 'danger', 'container' => false));
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
                    $this->view('message', array('message' => 'Error - No volume groups found', 'type' => 'danger', 'container' => false));
                }
                break;
            case 'extent':
                if (isset($_POST['lv'], $_POST['vg'], $_POST['size'])) {
                    $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_NUMBER_FLOAT);
                    $vg_name = filter_input(INPUT_POST, 'vg', FILTER_SANITIZE_STRING);
                    $lv_name = filter_input(INPUT_POST, 'lv', FILTER_SANITIZE_STRING);

                    $lv = $this->model('lvm\lv\Lv', $vg_name, $lv_name);
                    $lv->extend_lv($size);

                    // remap lun on target to update the size
                    if (isset($_POST['remap']) && $_POST['remap'] === "true") {
                        $target = $this->model('target\Target', false);

                        $path = '/dev/' . $vg_name . '/' . $lv_name;
                        // check if lv is a lun
                        if ($target->is_lun($path) !== false) {
                            $iqn = $target->getIqnForLun($path);
                            $target = $this->model('target\Target', $iqn);
                            $target->reattachLun($path);
                        } else {
                            // do nothing
                        }
                    }

                    echo json_encode($lv->logging->get_action_result());
                } else if (isset($_POST['lv'], $_POST['vg'])) {
                    $lv_name = filter_input(INPUT_POST, 'lv', FILTER_SANITIZE_STRING);
                    $vg_name = filter_input(INPUT_POST, 'vg', FILTER_SANITIZE_STRING);

                    $lv = $this->model('lvm\lv\Lv', $vg_name, $lv_name);
                    $data['lv'] = $lv->get_lv(false);

                    if ($data['lv'] !== false) {
                        $vg = $this->model('lvm\vg\Vg', $vg_name);
                        $data['vg'] = $vg->get_vg();

                        if ($data['vg'][0]['VFree'] <= 2) {
                            $this->view('message', array('message' => 'Error - The volume group ' . $data['vg'][0]['VG'] . ' is too small!', 'type' => 'warning', 'container' => false));
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
                    $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_NUMBER_FLOAT);
                    $vg_name = filter_input(INPUT_POST, 'vg', FILTER_SANITIZE_STRING);
                    $lv_name = filter_input(INPUT_POST, 'lv', FILTER_SANITIZE_STRING);

                    $lv = $this->model('lvm\lv\Lv', $vg_name, $lv_name);
                    $lv->reduce_lv($size);
                    echo json_encode($lv->logging->get_action_result());
                } else if (isset($_POST['lv'], $_POST['vg'])) {
                    $vg_name = filter_input(INPUT_POST, 'vg', FILTER_SANITIZE_STRING);
                    $lv_name = filter_input(INPUT_POST, 'lv', FILTER_SANITIZE_STRING);

                    $lv = $this->model('lvm\lv\Lv', $vg_name, $lv_name);
                    $data['lv'] = $lv->get_lv(false);
                    if ($data['lv'] !== false) {
                        $vg = $this->model('lvm\vg\Vg', $vg_name);
                        $data['vg'] = $vg->get_vg();

                        if ($data['lv'][0]['LSize'] <= 1) {
                            $this->view('message', array('message' => 'Error - The logical volume ' . $data['vg'][0]['VG'] . ' is too small!', 'type' => 'warning', 'container' => false));
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
                            $vg_name = filter_input(INPUT_POST, 'vg', FILTER_SANITIZE_STRING);
                            $lv_name = filter_input(INPUT_POST, 'lv', FILTER_SANITIZE_STRING);
                            $size = filter_input(INPUT_POST, 'size', FILTER_SANITIZE_NUMBER_FLOAT);

                            $lv = $this->model('lvm\lv\Lv', $vg_name, $lv_name);
                            $lv->snapshot_lv($size);
                            echo json_encode($lv->logging->get_action_result());
                        } else if (isset($_POST['lv'], $_POST['vg'])) {
                            $vg_name = filter_input(INPUT_POST, 'vg', FILTER_SANITIZE_STRING);
                            $lv_name = filter_input(INPUT_POST, 'lv', FILTER_SANITIZE_STRING);

                            $lv = $this->model('lvm\lv\Lv', $vg_name, $lv_name);
                            $data['lv'] = $lv->get_lv();
                            if ($data['lv'] !== false) {
                                $vg = $this->model('lvm\vg\Vg', $vg_name);
                                $data['vg'] = $vg->get_vg();
                                if (floatval($data['vg'][0]['VFree']) > 1.1) {
                                    $this->view('lvm/add_snapshot', $data);
                                } else {
                                    $this->view('message', array('message' => 'Error - Volume group is to small for new snapshots!', 'type' => 'warning', 'container' => false));
                                }
                            } else {
                                // lv doesn't exist
                            }
                        }
                    } else if ($param2 === 'delete') {
                        if (isset($_POST['vg'], $_POST['snapshot'])) {
                            $vg_name = filter_input(INPUT_POST, 'vg', FILTER_SANITIZE_STRING);
                            $snapshot_name = filter_input(INPUT_POST, 'snapshot', FILTER_SANITIZE_STRING);

                            $lv = $this->model('lvm\lv\Lv', $vg_name, $snapshot_name);
                            $lv->remove_lv();
                            echo json_encode($lv->logging->get_action_result());
                        } else if (isset($_POST['lv'], $_POST['vg'])) {
                            $lv_name = filter_input(INPUT_POST, 'lv', FILTER_SANITIZE_STRING);
                            $vg_name = filter_input(INPUT_POST, 'vg', FILTER_SANITIZE_STRING);

                            $lv = $this->model('lvm\lv\Lv', $vg_name, $lv_name);
                            $data['lv'] = $lv->get_snapshot();

                            if ($data['lv'] !== false) {
                                $this->view('lvm/delete_snapshot', $data);
                            } else {
                                $this->view('message', array('message' => 'Error - No snapshots found!', 'type' => 'warning', 'container' => false));
                            }
                        }
                    }
                }
                break;
            case 'rename':
                if (isset($_POST['lv'], $_POST['vg'], $_POST['name'])) {
                    $lv_name = filter_input(INPUT_POST, 'lv', FILTER_SANITIZE_STRING);
                    $vg_name = filter_input(INPUT_POST, 'vg', FILTER_SANITIZE_STRING);
                    $new_name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);

                    $lv = $this->model('lvm\lv\Lv', $vg_name, $lv_name);
                    $lv->rename_lv($new_name);
                    echo json_encode($lv->logging->get_action_result());
                } else if (isset($_POST['lv'], $_POST['vg'])) {
                    $lv_name = filter_input(INPUT_POST, 'lv', FILTER_SANITIZE_STRING);
                    $vg_name = filter_input(INPUT_POST, 'vg', FILTER_SANITIZE_STRING);

                    $lv = $this->model('lvm\lv\Lv', $vg_name, $lv_name);
                    $data['lv'] = $lv->get_lv();
                    $this->view('lvm/rename', $data);
                }
                break;
            case 'delete':
                if (isset($_POST['lv'], $_POST['vg'], $_POST['delete'])) {
                    $lv_name = filter_input(INPUT_POST, 'lv', FILTER_SANITIZE_STRING);
                    $vg_name = filter_input(INPUT_POST, 'vg', FILTER_SANITIZE_STRING);

                    $lv = $this->model('lvm\lv\Lv', $vg_name, $lv_name);
                    $lv->remove_lv();
                    echo json_encode($lv->logging->get_action_result());
                } else if (isset($_POST['lv'], $_POST['vg'])) {
                    $lv_name = filter_input(INPUT_POST, 'lv', FILTER_SANITIZE_STRING);
                    $vg_name = filter_input(INPUT_POST, 'vg', FILTER_SANITIZE_STRING);

                    $lv = $this->model('lvm\lv\Lv', $vg_name, $lv_name);
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
