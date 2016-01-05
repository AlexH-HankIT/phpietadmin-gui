<?php
namespace app\core;

use app\controllers,
    app\models\logging,
    app\models;

/**
 * Entry point for this application. All requests are handled here.
 * @package app\core
 * @throws \Exception if the version file is invalid
 */
class App {
    /** @var object $controllerObject Contains the object of the called controller */
    protected $controllerObject;

    /** @var string $controllerName Contains the name of the called controller, it's initialized with a default value */
    protected $controllerName = 'app\\controllers\\dashboard';

    /** @var string $method Contains the name of the called method, it's initialized with a default value */
    protected $method = 'index';

    /** @var array $params Contains an array with all parameters */
    protected $params = [];

    /** @var array $url Contains the url using the parseUrl() function */
    protected $url;

    public function __construct() {
        array_filter($_POST, array($this, 'sanitize'));
        array_filter($_GET, array($this, 'sanitize'));
        $this->url = $this->parseUrl();
    }

    /**
     * Create the database. Check if it's already there, otherwise you will delete all data ;-)
     *
     * @return void
     */
    private function installDb() {
        exec('sqlite3 ' . DB_FILE . ' < ' . INSTALL_DIR . '/database.new.sql', $output, $code);

        if ($code !== 0) {
            die('<h1>Unable to create database</h1>');
        }
    }

    /**
     * Check if the database has the same version as the application. If not update it using the
     * database.update.sql file.
     * Nothing will be done, if the application is in development mode.
     *
     * @throws \Exception if the version file is invalid
     * @return void
     */
    private function updateDb() {
        // check if db update is necessary
        $version = $this->controllerObject->baseModel->database->get_config('version');

        // If version is false, the database has no version entry, therefore version 0.6.2 or older is used
        // Which means we have to update the database anyway
        if ($version !== false) {
            $version['value'] = str_replace('.', '', $version['value']);
        }

        try {
            $versionFile = models\Misc::getVersionFile();
        } catch (\Exception $e) {
            die('<h1>'  . $e->getMessage() . '</h1>');
        }

        if ($version === false || $versionFile['version'] > $version['value']) {
            if (MODE !== 'dev') {
                exec('sqlite3 ' . DB_FILE . ' < ' . INSTALL_DIR . '/database.update.sql');
            }
        }
    }

    /**
     * This method start the application. It creates necessary objects, handles authentication
     * and outputs the application header + menu if the request isn't an ajax
     *
     * @throws \Exception if the called controller or method is not found
     * @return void
     */
    public function app() {
        if (file_exists(CONTROLLER_DIR . '/' . ucfirst($this->url[0]) . '.php')) {
            $this->controllerName = 'app\\controllers\\' . $this->url[0];
            unset($this->url[0]);
        }

        $this->controllerObject = new $this->controllerName;

        if (!file_exists(DB_FILE)) {
            $this->installDb();
            $this->setupRegistry();
        } else {
            $this->setupRegistry();
            $this->updateDb();
        }

        $this->checkAuth();
        $this->showHeader();

        // If the method is not found throw an exception, display an error message and exit the application
        try {
            if (isset($this->url[1])) {
                if (method_exists($this->controllerObject, $this->url[1])) {
                    $this->method = $this->url[1];
                    unset($this->url[1]);
                } else {
                    if (models\Misc::isXHttpRequest() === true) {
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
                    if (models\Misc::isXHttpRequest() === true) {
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
            die();
        }

        $this->params = $this->url ? array_values($this->url) : [];
        call_user_func_array([$this->controllerObject, $this->method], $this->params);
    }

    /**
     * Split the url for application routing
     * @return array
     */
    private function parseUrl() {
        if (isset($_GET['url'])) {
            return $url = explode('/', filter_var(rtrim($_GET['url'], '/'), FILTER_SANITIZE_URL));
        }
    }

    /**
     * Fill the registry with all necessary models and
     * instantiated a baseModel, to access the models through the
     * controllerObject
     *
     * @throws \Exception
     * @return void
     */
    private function setupRegistry() {
        $registry = Registry::getInstance();
        $registry->set('database', new models\Database());
        $registry->set('logging', new logging\Logging());
        $this->controllerObject->baseModel = new BaseModel();
    }

    /**
     * Sanitize user input
     * Unlikely that this does something useful
     * but it's a welcome addition
     *
     * @param $value array
     * @return void
     */
    private function sanitize(&$value) {
        $value = addslashes(strip_tags(trim($value)));
    }

    /**
     * Output application header and menu, if the user is authenticated and
     * the request is not ajax
     *
     * @return void
     */
    private function showHeader() {
        // If request is no ajax, display header, menu and footer
        if (!models\Misc::isXHttpRequest() && $this->controllerName !== 'app\controllers\auth') {
            $this->controllerObject->view('header', models\Misc::get_dashboard_data());
            $this->controllerObject->view('menu');
        }
    }

    /**
     * Validate if the user is correctly authenticated
     *
     * @return void
     */
    private function checkAuth() {
        // auth controller is accessible without authentication
        if ($this->controllerName !== 'app\controllers\auth') {
            $session = $this->controllerObject->model('Session');

            if ($session->checkLoggedIn($this->controllerName) !== true) {
                // if user is not logged in redirect him and stop execution
                if (models\Misc::isXHttpRequest()) {
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