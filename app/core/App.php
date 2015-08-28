<?php
	// ToDo Get rid of "controllername" var
	// ToDo Don't escape output passed to the message view

    Class App {
        protected $controller;
        protected $controllername = 'dashboard';
        protected $method = 'index';
        protected $params = [];

        public function __construct() {
            array_filter($_POST, array($this, 'sanitize'));
            array_filter($_GET, array($this, 'sanitize'));

            $url = $this->parseUrl();

            if(file_exists(__DIR__ . '/../controllers/' .  $url[0] . '.php')) {
                $this->controllername = $url[0];
                unset($url[0]);
				require_once __DIR__ .  '/../controllers/' . $this->controllername . '.php';
            }

            $this->controller = new $this->controllername;
            $this->controller->create_models();

            if ($this->controllername !== 'auth') {
                $this->controller->check_loggedin($this->controllername, $this->method);
            }

            // If request is no ajax, display header, menu and footer
            if (!$this->controller->std->IsXHttpRequest() && $this->controllername !== 'auth') {
                $this->controller->view('header', $this->controller->std->get_dashboard_data());
                $this->controller->view('menu');
            }

            $continue = true;
            if(isset($url[1])) {
                if(method_exists($this->controller, $url[1])) {
                    $this->method = $url[1];
                    unset($url[1]);
                } else {
                    if ($this->controller->std->IsXHttpRequest() === true) {
                        http_response_code(404);
                        echo 'Method ' . htmlspecialchars($url[1]) . ' doesn\'t exist!';
                    } else {
                        $this->controller->view('messsage', 'Method ' . $url[1] . ' doesn\'t exist!');
                    }
                    $continue = false;
                }
            } else {
                // if $url[1] is not set, the browser will most likely call the index function
                if(method_exists($this->controller, 'index')) {
                    $this->method = 'index';
                } else {
                    if ($this->controller->std->IsXHttpRequest() === true) {
                        http_response_code(404);
                        echo 'Method ' . htmlspecialchars($this->method) . ' doesn\'t exist!';
                    } else {
                        $this->controller->view('messsage', 'Method ' . $this->method . ' doesn\'t exist!');
                    }
                    $continue = false;
                }
            }

            $this->params = $url ? array_values($url) : [];

            // only load the main application if no error occurred
            if ($continue === true) {
                call_user_func_array([$this->controller, $this->method], $this->params);
            }

            if (!$this->controller->std->IsXHttpRequest() && $this->controllername !== 'auth') {
                $this->controller->view('footer');
            }
        }

        public function parseUrl() {
            if(isset($_GET['url'])) {
                return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
            }
        }

        // Sanitize user input
        // Unlikely that this does something useful
        // but it's a welcome addition
        protected function sanitize(&$value) {
            $value = addslashes(strip_tags(trim($value)));
        }
    }