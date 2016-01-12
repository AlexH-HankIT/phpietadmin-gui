<?php
namespace app\controllers;

use app\core;

class Overview extends core\BaseController {
    public function disks($format = 'default') {
        // Json for retrieval via ajax
        if ($format === 'json') {
            $disk = $this->model('Disks');
            echo $disk->get_disks('json');
        } else {
            // Breadcrumbs
            $this->view('breadcrumb', array(
                    array('href' => '#', 'text' => 'Overview'),
                    array('text' => 'Disks')
                )
            );

            // Table
            $this->view('bootstrapTable', array(
                'tableAttributes' => array(
                    "data-data-field='body'",
                    "data-url='" . WEB_PATH . "/overview/disks/json'",
                    "data-show-export='true'"
                ),
                'tableHead' => array(
                    array("heading" => "NAME", "fields" => array(
                        "data-field='NAME'"
                    )),
                    array("heading" => "MIN", "fields" => array(
                        "data-field='MIN'"
                    )),
                    array("heading" => "RM", "fields" => array(
                        "data-field='RM'"
                    )),
                    array("heading" => "SIZE", "fields" => array(
                        "data-field='SIZE'"
                    )),
                    array("heading" => "TYPE", "fields" => array(
                        "data-field='TYPE'"
                    )),
                    array("heading" => "MOUNTPOINT", "fields" => array(
                        "data-field='MOUNTPOINT'"
                    ))
                ),
                'js' => 'pages/bootstrapTable'
            ));
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
                $this->view('ietOverviewTable', $view);
            } else if ($param == 'volume') {
                $view['type'] = 'volume';
                $this->view('ietOverviewTable', $view);
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
