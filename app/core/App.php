<?php
namespace app\core;

use app\controllers,
    app\models\logging,
    app\models;

require_once MODEL_DIR . '/misc.php';

class App {
    protected $controllerObject;
    protected $controllerName = 'app\\controllers\\dashboard';
    protected $method = 'index';
    protected $params = [];
    protected $url;
    protected $installed;

    public function __construct() {
        array_filter($_POST, array($this, 'sanitize'));
        array_filter($_GET, array($this, 'sanitize'));
        $this->url = $this->parseUrl();

        if (!file_exists(DB_FILE) || getVersionFile()['status'] === 'new') {
            $this->installed = false;
        } else {
            $this->installed = true;
        }
    }

    public function app() {
        if (file_exists(CONTROLLER_DIR . '/' . $this->url[0] . '.php')) {
            $this->controllerName = 'app\\controllers\\' . $this->url[0];
            unset($this->url[0]);
        }

        if ($this->installed === false) {
            if ($this->controllerName !== 'app\\controllers\\install') {
                header('Location: ' . WEB_PATH . '/install');
                $this->controllerName = 'app\\controllers\\install';
            }
        } else {
            if ($this->controllerName === 'app\\controllers\\install') {
                die('<h1>Already installed</h1>');
            }
        }

        $this->controllerObject = new $this->controllerName;

        // Do not use $this->installed here, because it is also false if the database exists
        // but the version file has status="new"
        if (file_exists(DB_FILE)) {
            $this->setupRegistry();
        }

        $this->checkAuth();

        if ($this->installed === true) {
            $this->showHeader();
        }

        // If the method is not found throw an exception, display an error message and exit the application
        try {
            if (isset($this->url[1])) {
                if (method_exists($this->controllerObject, $this->url[1])) {
                    $this->method = $this->url[1];
                    unset($this->url[1]);
                } else {
                    if (isXHttpRequest() === true) {
                        http_response_code(404);
                        echo 'Method ' . htmlspecialchars($this->url[1]) . ' doesn\'t exist!';
                    } else {
                        http_response_code(404);
                        $this->controllerObject->view('message', 'Method ' . $this->url[1] . ' doesn\'t exist!');
                    }
                    throw new \Exception();
                }
            } else {
                // if $url[1] is not set, the browser will most likely call the index function
                if (method_exists($this->controllerObject, 'index')) {
                    $this->method = 'index';
                } else {
                    if (isXHttpRequest() === true) {
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

        $this->params = $this->url ? array_values($this->url) : [];
        call_user_func_array([$this->controllerObject, $this->method], $this->params);

        if ($this->installed === true) {
            $this->showFooter();
        }
    }

    private function parseUrl() {
        if (isset($_GET['url'])) {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }

    private function setupRegistry() {
        $registry = Registry::getInstance();
        $registry->set('database', new models\Database());
        $registry->set('logging', new logging\Logging());
        $registry->set('std', new models\Std());
        $this->controllerObject->baseModel = new BaseModel();
    }

    // Sanitize user input
    // Unlikely that this does something useful
    // but it's a welcome addition
    private function sanitize(&$value) {
        $value = addslashes(strip_tags(trim($value)));
    }

    private function showFooter() {
        if (!isXHttpRequest() && $this->controllerName !== 'app\controllers\auth' && $this->controllerName !== 'app\controllers\install') {
            $this->controllerObject->view('footer');
        }
    }

    private function showHeader() {
        // If request is no ajax, display header, menu and footer
        if (!isXHttpRequest() && $this->controllerName !== 'app\controllers\auth' && $this->controllerName !== 'app\controllers\install') {
            $this->controllerObject->view('header', get_dashboard_data());
            $this->controllerObject->view('menu');
        }
    }

    private function checkAuth() {
        // auth controller is accessible without authentication
        if ($this->controllerName !== 'app\controllers\auth' && $this->controllerName !== 'app\controllers\install') {
            $session = $this->controllerObject->model('Session');

            if ($session->checkLoggedIn($this->controllerName) !== true) {
                // if user is not logged in redirect him and stop execution
                if (isXHttpRequest()) {
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