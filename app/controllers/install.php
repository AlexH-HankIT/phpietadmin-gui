<?php
namespace app\controllers;

use app\core;

class Install extends core\BaseController {
    public function __construct() {
        // only calls this if the database does not exist and the version file status="new"
        // if database exists throw exception for security reasons
        // also if status="installed"
    }

    public function index() {
        // show welcome page
        $this->view('install/welcome');
    }

    public function database() {
        // create database
        exec('sqlite3 /tmp/test' . INSTALL_DIR . '/database.new.sql', $output, $code);
        // show results
        // output json array
        // provide help in case of error
    }

    public function user() {
        // create first user
        // echo path to script for auth code generation
        // provide help in case of error
        // set version file status="installed"
    }
}

// Also save version in database, so the application knows when to update it