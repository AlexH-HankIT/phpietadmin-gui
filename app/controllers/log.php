<?php
    class Log extends Controller {
        public function config() {

        }

        public function show($param) {
            if ($param == 'action') {
                $data = $this->std->tail('/var/log/phpietadmin/phpietadmin_action.log');
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
                        'body' => $data
                    );

                    $this->view('table', $view);
                } else {
                    $this->view('message', array('message' => 'The action log file is empty!', 'type' => 'danger'));
                }
            } else if ($param == 'access') {
                $data = $this->std->tail('/var/log/phpietadmin/phpietadmin_access.log');
                if ($data !== false) {
                    $view = array(
                        'title' => 'Access',
                        'heading' => array(
                            'timestamp',
                            'ip',
                            'browser agent',
                            'session id',
                            'command',
                            'message',
                            'method'
                        ),
                        'body' => $data
                    );

                    $this->view('table', $view);
                } else {
                    $this->view('message', array('message' => 'The access log file is empty!', 'type' => 'danger'));
                }
            } else if ($param == 'debug') {
                $data = $this->std->tail('/var/log/phpietadmin/phpietadmin_debug.log');
                if ($data !== false) {
                    $view = array(
                        'title' => 'Debug',
                        'heading' => array(
                            'timestamp',
                            'ip',
                            'browser agent',
                            'session id',
                            'command',
                            'message'
                        ),
                        'body' => $data
                    );
                    $this->view('table', $view);
                } else {
                    $this->view('message', array('message' => 'The debug log file is empty!', 'type' => 'danger'));
                }
            } else if ($param == 'syslog') {
                // ToDo
            } else if ($param == 'php') {
                // ToDo
            } else {
                $this->view('message', array('message' => 'Invalid url', 'type' => 'warning'));
            }
        }
    }