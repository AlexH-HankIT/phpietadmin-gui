<?php
    class Overview extends Controller {
        public function disks() {
            $disk = $this->generic_model('Disks');

            $data = $disk->get_disks();

            if (!empty($data) && $data !== false) {
                $this->view('table', $data);
            } else {
                $this->view('message', 'Error - No block devices available!');
            }
        }

        public function iet($param) {
            $targets = $this->target_model();
            $data = $targets->return_target_data();

            $view['data'] = $data;
            $data = $targets->get_result();

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
                $model = $this->pv_model();
                $data = $model->get_pv();
                $title = 'Physical volumes';
            } else if ($param == 'vg') {
                $model = $this->vg_model();
                $data = $model->get_vg();
                $title = 'Volume groups';
            } else if ($param == 'lv') {
                $model = $this->lv_model(false, false);
                $data = $model->get_lv();
                $title = 'Logical volumes';
            } else {
                $this->view('message', array('message' => 'Invalid url', 'type' => 'warning'));
            }

            if (!empty($data)) {
                if ($data !== false) {
                    $this->view('table', array('title' => $title, 'heading' => array_keys($data[0]), 'body' => $data));
                } else {
                    $data = $model->get_result();
                    $this->view('message', array('message' => $data['message'], 'type' => 'danger'));
                }
            }
        }
    }