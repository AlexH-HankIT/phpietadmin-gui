<?php
namespace app\controllers;

use app\core;

class Install extends core\BaseController {
    private $db_temp = '/tmp/test';

    public function __construct() {
        // only calls this if the database does not exist and the version file status="new"
        // if database exists throw exception for security reasons
        // also if status="installed"
        if (file_exists($this->db_temp)) {
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
    }
}