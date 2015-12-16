<?php
namespace app\controllers;

use app\core;

class Install extends core\BaseController {
    private $db_temp = '/tmp/test';

    public function __construct() {
        // only calls this if the database does not exist and the version file status="new"
        // if database exists throw exception for security reasons
        // also if status="installed"
        $user = $this->model('User', false);
        $versionFile = $this->baseModel->std->getVersionFile();

        if (file_exists($this->db_temp)) {
            throw new \Exception();
        } else if ($user->returnData() !== false) {
            throw new \Exception();
        } else if ($versionFile['status'] !== 'new') {
            throw new \Exception();
        }
    }

    public function index() {
        // show welcome page
        $this->view('install/welcome');
    }

    public function database() {
        // create database
        exec('sqlite3 ' . $this->db_temp . ' < ' . INSTALL_DIR . '/database.new.sql', $output, $code);

        echo json_encode(array(
            'output' => $output,
            'code' => $code,
        ));
    }

    public function user() {
        // create first user
        // echo path to script for auth code generation
        // provide help in case of error
        // set version file status="installed"
        if (isset($_POST['password1'], $_POST['password2'], $_POST['authCode'], $_POST['username'])) {
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $authCode = filter_input(INPUT_POST, 'authCode', FILTER_SANITIZE_STRING);
            $password1 = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING);
            $password2 = filter_input(INPUT_POST, 'password2', FILTER_SANITIZE_STRING);
            $user = $this->model('User', $username);
            $user->addFirstUser($authCode, $password1, $password2);
            echo json_encode($user->logging->get_action_result());
        }
    }
}