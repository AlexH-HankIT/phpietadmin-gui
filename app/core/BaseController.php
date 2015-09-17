<?php namespace phpietadmin\app\core;
    class BaseController {
        // Define global vars
        public $base_model;

        // arg1 = model name with namespace from phpietadmin\app\models\
        // arg2 = arg1 for model
        // arg 3 = arg 2 for model
        public function model() {
            $arg = func_get_args();
            $model = 'phpietadmin\\app\\models\\' . $arg[0];

            if (func_num_args() === 3) {
                return new $model($arg[1], $arg[2]);
            } else if (func_num_args() === 2) {
                return new $model($arg[1]);
            } else {
                return new $model();
            }
        }

        public function view($view, $data = []) {
            if (file_exists(__DIR__ . '/../../app/views/' . $view . '.php')) {
                require_once __DIR__ . '/../../app/views/' . $view . '.php';
            } else if (file_exists(__DIR__ . '/../app/views/' . $view . '.php')) {
                require_once __DIR__ . '/../app/views/' . $view . '.php';
            } else {
                echo 'File ' . htmlspecialchars($view) . ' not found!';
            }
        }
    }