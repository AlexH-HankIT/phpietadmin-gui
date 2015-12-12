<?php
namespace app\controllers;

use app\core;

class Overview extends core\BaseController {
    public function disks() {
        $disk = $this->model('Disks');
        $data = $disk->get_disks();

        if (!empty($data) && $data !== false) {
            $this->view('table', $data);
        } else {
            $this->view('message', 'Error - No block devices available!');
        }
    }

    public function iet($param) {
        $targets = $this->model('target\Target', false);
        $data = $targets->return_target_data();

        $view['data'] = $data;
        $data = $targets->logging->get_action_result();

        if ($view['data'] !== false) {
            if ($param == 'session') {
                $view['type'] = 'session';
                $this->view('targets/iettable', $view);
            } else if ($param == 'volume') {
                $view['type'] = 'volume';
                $this->view('targets/iettable', $view);
            } else {
                $this->view('message', array('message' => 'Invalid url', 'type' => 'warning'));
            }
        } else {
            $this->view('message', array('message' => $data['message'], 'type' => 'danger'));
        }
    }

    public function lvm($param) {
        if ($param == 'pv') {
            $model = $this->model('lvm\pv\Pv', false);
            $data = $model->get_pv();

            if ($data !== false) {
                foreach ($data as $volumes) {
                    $size = intval($volumes['PSize']);
                    $width = intval(($size - intval($volumes['PFree'])) * 100 / $size);
                    if ($width <= 60) {
                        $bar = 'success';
                    } else if ($width >= 81) {
                        $bar = 'danger';
                    } else if ($width >= 61) {
                        $bar = 'warning';
                    } else {
                        $bar = 'info';
                    }
                    $meta[] = array(
                        'width' => $width,
                        'type' => $bar
                    );
                }
                $heading = array_keys($data[0]);
                $title = 'Physical volumes';
            }
        } else if ($param == 'vg') {
            $model = $this->model('lvm\vg\Vg', false);
            $data = $model->get_vg();

            if ($data !== false) {
                foreach ($data as $volumes) {
                    $size = intval($volumes['VSize']);
                    $width = intval(($size - intval($volumes['VFree'])) * 100 / $size);
                    if ($width <= 60) {
                        $bar = 'success';
                    } else if ($width >= 81) {
                        $bar = 'danger';
                    } else if ($width >= 61) {
                        $bar = 'warning';
                    } else {
                        $bar = 'info';
                    }
                    $meta[] = array(
                        'width' => $width,
                        'type' => $bar
                    );
                }
                $heading = array_keys($data[0]);
                $title = 'Volume groups';
            }
        } else if ($param == 'lv') {
            $model = $this->model('lvm\lv\Lv', false, false);
            $data = $model->get_lv();

            if ($data !== false) {
                $heading = array_keys($data[0]);
                $title = 'Volume groups';
            }
        } else {
            $this->view('message', array('message' => 'Invalid url', 'type' => 'warning'));
        }

        if ($data !== false) {
            if (isset($meta)) {
                array_push($heading, 'Used');
                $view = array('title' => $title, 'heading' => $heading, 'body' => $data, 'meta' => $meta);
            } else {
                $view = array('title' => $title, 'heading' => $heading, 'body' => $data);
            }

            $this->view('lvm_table', $view);
        } else {
            $data = $model->logging->get_action_result();
            $this->view('message', array('message' => $data['message'], 'type' => 'danger'));
        }
    }
}