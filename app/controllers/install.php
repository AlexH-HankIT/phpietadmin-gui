<?php
namespace app\controllers;

use app\core;

require_once MODEL_DIR . '/functions.php';

class install extends core\BaseController {
    private $dbExists = false;

   public function __construct() {
        // only calls this if the database does not exist and the version file status="new"
        // if database exists throw exception for security reasons
        // also if status="installed"
        if (file_exists(DB_FILE)) {
            $this->dbExists = true;
        }
    }

    public function index() {
        $data = array(
            'database' => $this->dbExists
        );

        // show welcome page
        $this->view('install/welcome', $data);
    }

    public function database() {
        if ($this->dbExists === false) {
            // create database
            exec('sqlite3 ' . DB_FILE . ' < ' . INSTALL_DIR . '/database.new.sql', $output, $code);

            echo json_encode(array(
                'output' => $output,
                'code' => $code,
            ));
        }
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

            // Update version file
            $versionFile = getVersionFile();
            $versionFile['status'] = 'installed';
            file_put_contents(VERSION_FILE, json_encode($versionFile));

            echo json_encode($user->logging->get_action_result());
        }
    }
}