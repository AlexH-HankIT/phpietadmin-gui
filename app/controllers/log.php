<?php namespace phpietadmin\app\controllers;
use phpietadmin\app\core;

    class Log extends core\BaseController {
        public function show($param) {
            if ($param == 'action') {
                $data = $this->base_model->std->tail($this->base_model->database->get_config('log_base')['value'] . '/' . $this->base_model->database->get_config('action_log')['value']);
                if ($data !== false) {
                    $this->view('table', array(
                            'title' => 'Action',
                            'heading' => array(
                                'timestamp',
                                'ip',
                                'browser agent',
                                'message',
                                'status',
                                'type',
                                'method'
                            ),
                            'body' => array_reverse($data)
                        )
                    );
                } else {
                    $this->view('message', array('message' => 'The action log file is empty!', 'type' => 'danger'));
                }
            } else if ($param == 'access') {
                $data = $this->base_model->std->tail($this->base_model->database->get_config('log_base')['value'] . '/' . $this->base_model->database->get_config('access_log')['value']);
                if ($data !== false) {
                    $this->view('table', array(
                            'title' => 'Access',
                            'heading' => array(
                                'timestamp',
                                'user',
                                'ip',
                                'browser agent',
                                'message',
                                'status',
                                'type',
                                'method'
                            ),
                            'body' => array_reverse($data)
                        )
                    );
                } else {
                    $this->view('message', array('message' => 'The access log file is empty!', 'type' => 'danger'));
                }
            } else {
                $this->view('message', array('message' => 'Invalid url', 'type' => 'warning'));
            }
        }
    }