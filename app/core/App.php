<?php namespace phpietadmin\app\core;
use phpietadmin\app\controllers,
	phpietadmin\app\models\logging,
	phpietadmin\app\models;

    Class App {
        protected $controller_object;
        protected $controller_name = 'phpietadmin\\app\\controllers\\dashboard';
        protected $method = 'index';
        protected $params = [];

        public function __construct() {
            array_filter($_POST, array($this, 'sanitize'));
            array_filter($_GET, array($this, 'sanitize'));

            $url = $this->parseUrl();

            if(file_exists(__DIR__ . '/../controllers/' .  $url[0] . '.php')) {
				$this->controller_name = 'phpietadmin\\app\\controllers\\' . $url[0];
				unset($url[0]);
            }

			$registry = Registry::getInstance();
			$registry->set('database', new models\Database());
			$registry->set('logging', new logging\Logging());
			$registry->set('std',  new models\Std());

            $this->controller_object = new $this->controller_name;
			$this->controller_object->base_model = new BaseModel();

            // auth controller is accessible without authentication
            if ($this->controller_name !== 'phpietadmin\app\controllers\auth') {
				$session = $this->controller_object->model('Session');

                if($session->checkLoggedIn($this->controller_name) !== true) {
                    // if user is not logged in redirect him and stop execution
                    if ($this->controller_object->base_model->std->IsXHttpRequest()) {
                        echo false;
                        die();
                    } else {
                        header("Location: /phpietadmin/auth/login");
                        die();
                    }
                }
            }

            // If request is no ajax, display header, menu and footer
            if (!$this->controller_object->base_model->std->IsXHttpRequest() && $this->controller_name !== 'phpietadmin\app\controllers\auth') {
                $this->controller_object->view('header', $this->controller_object->base_model->std->get_dashboard_data());
                $this->controller_object->view('menu');
            }

            $continue = true;
            if(isset($url[1])) {
                if(method_exists($this->controller_object, $url[1])) {
                    $this->method = $url[1];
                    unset($url[1]);
                } else {
                    if ($this->controller_object->base_model->std->IsXHttpRequest() === true) {
                        http_response_code(404);
                        echo 'Method ' . htmlspecialchars($url[1]) . ' doesn\'t exist!';
                    } else {
                        $this->controller_object->view('messsage', 'Method ' . $url[1] . ' doesn\'t exist!');
                    }
                    $continue = false;
                }
            } else {
                // if $url[1] is not set, the browser will most likely call the index function
                if(method_exists($this->controller_object, 'index')) {
                    $this->method = 'index';
                } else {
                    if ($this->controller_object->base_model->std->IsXHttpRequest() === true) {
                        http_response_code(404);
                        echo 'Method ' . htmlspecialchars($this->method) . ' doesn\'t exist!';
                    } else {
                        $this->controller_object->view('messsage', 'Method ' . $this->method . ' doesn\'t exist!');
                    }
                    $continue = false;
                }
            }

            $this->params = $url ? array_values($url) : [];

            // only load the main application if no error occurred
            if ($continue === true) {
                call_user_func_array([$this->controller_object, $this->method], $this->params);
            }

            if (!$this->controller_object->base_model->std->IsXHttpRequest() && $this->controller_name !== 'phpietadmin\app\controllers\auth') {
                $this->controller_object->view('footer');
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