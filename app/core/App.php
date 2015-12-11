<?php
namespace app\core;

use app\controllers,
    app\models\logging,
    app\models;

class App {
    protected $controllerObject;
    protected $controllerName = 'app\\controllers\\dashboard';
    protected $method = 'index';
    protected $params = [];

    public function __construct() {
        array_filter($_POST, array($this, 'sanitize'));
        array_filter($_GET, array($this, 'sanitize'));

        $url = $this->parseUrl();

        if (file_exists(CONTROLLER_DIR . '/' . $url[0] . '.php')) {
            $this->controllerName = 'app\\controllers\\' . $url[0];
            unset($url[0]);
        }

        $registry = Registry::getInstance();
        $registry->set('database', new models\Database());
        $registry->set('logging', new logging\Logging());
        $registry->set('std', new models\Std());

        $this->controllerObject = new $this->controllerName;
        $this->controllerObject->baseModel = new BaseModel();

        $this->checkAuth();
        $this->showHeader();

        // If the method is not found throw an exception, display an error message and exit the application
        try {
            if (isset($url[1])) {
                if (method_exists($this->controllerObject, $url[1])) {
                    $this->method = $url[1];
                    unset($url[1]);
                } else {
                    if ($this->controllerObject->baseModel->std->IsXHttpRequest() === true) {
                        http_response_code(404);
                        echo 'Method ' . htmlspecialchars($url[1]) . ' doesn\'t exist!';
                    } else {
                        http_response_code(404);
                        $this->controllerObject->view('message', 'Method ' . $url[1] . ' doesn\'t exist!');
                    }
                    throw new \Exception();
                }
            } else {
                // if $url[1] is not set, the browser will most likely call the index function
                if (method_exists($this->controllerObject, 'index')) {
                    $this->method = 'index';
                } else {
                    if ($this->controllerObject->baseModel->std->IsXHttpRequest() === true) {
                        http_response_code(404);
                        echo 'Method ' . htmlspecialchars($this->method) . ' doesn\'t exist!';
                    } else {
                        http_response_code(404);
                        $this->controllerObject->view('message', 'Method ' . $this->method . ' doesn\'t exist!');
                    }
                    throw new \Exception();
                }
            }
        } catch (\Exception $e) {
            $this->showFooter();
            die();
        }

        $this->params = $url ? array_values($url) : [];
        call_user_func_array([$this->controllerObject, $this->method], $this->params);
        $this->showFooter();
    }

    public function parseUrl() {
        if (isset($_GET['url'])) {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }

    // Sanitize user input
    // Unlikely that this does something useful
    // but it's a welcome addition
    protected function sanitize(&$value) {
        $value = addslashes(strip_tags(trim($value)));
    }

    protected function showFooter() {
        if (!$this->controllerObject->baseModel->std->IsXHttpRequest() && $this->controllerName !== 'app\controllers\auth') {
            $this->controllerObject->view('footer');
        }
    }

    protected function showHeader() {
        // If request is no ajax, display header, menu and footer
        if (!$this->controllerObject->baseModel->std->IsXHttpRequest() && $this->controllerName !== 'app\controllers\auth') {
            $this->controllerObject->view('header', $this->controllerObject->baseModel->std->get_dashboard_data());
            $this->controllerObject->view('menu');
        }
    }

    protected function checkAuth() {
        // auth controller is accessible without authentication
        if ($this->controllerName !== 'app\controllers\auth') {
            $session = $this->controllerObject->model('Session');

            if ($session->checkLoggedIn($this->controllerName) !== true) {
                // if user is not logged in redirect him and stop execution
                if ($this->controllerObject->baseModel->std->IsXHttpRequest()) {
                    echo false;
                    die();
                } else {
                    header('Location: ' . WEB_PATH . '/auth/login');
                    die();
                }
            }
        }
    }
}