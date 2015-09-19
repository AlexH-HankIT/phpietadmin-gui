<?php namespace phpietadmin\app\controllers;
use phpietadmin\app\core;

    class Log extends core\BaseController {
        public function show($param) {
            if ($param == 'action') {
                $data = $this->base_model->std->tail($this->base_model->database->get_config('log_base')['value'] . '/' . $this->base_model->database->get_config('action_log')['value']);
                if ($data !== false) {
                    $view = array(
                        'title' => 'Action',
                        'heading' => array(
                            'timestamp',
                            'ip',
                            'browser agent',
                            'session id',
                            'message',
                            'status',
                            'type',
                            'method'
                        ),
                        'body' => array_reverse($data)
                    );

                    $this->view('table', $view);
                } else {
                    $this->view('message', array('message' => 'The action log file is empty!', 'type' => 'danger'));
                }
            } else if ($param == 'access') {
                $data = $this->base_model->std->tail($this->base_model->database->get_config('log_base')['value'] . '/' . $this->base_model->database->get_config('access_log')['value']);
                if ($data !== false) {
                    $view = array(
                        'title' => 'Access',
                        'heading' => array(
                            'timestamp',
                            'user',
                            'ip',
                            'browser agent',
                            'session id',
                            'message',
                            'status',
                            'type',
                            'method'
                        ),
                        'body' => array_reverse($data)
                    );

                    $this->view('table', $view);
                } else {
                    $this->view('message', array('message' => 'The access log file is empty!', 'type' => 'danger'));
                }
            } else {
                $this->view('message', array('message' => 'Invalid url', 'type' => 'warning'));
            }
        }
    }