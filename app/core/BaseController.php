<?php
namespace app\core;

class BaseController {
    // Define global vars
    public $baseModel;

    // arg1 = model name with namespace from phpietadmin\app\models\
    // arg2 = arg1 for model
    // arg 3 = arg 2 for model
    public function model() {
        $arg = func_get_args();
        $model = 'app\\models\\' . $arg[0];

        if (func_num_args() === 3) {
            return new $model($arg[1], $arg[2]);
        } else if (func_num_args() === 2) {
            return new $model($arg[1]);
        } else {
            return new $model();
        }
    }

    public function view($view, $data = []) {
        if (file_exists(VIEW_DIR . '/' . $view . '.php')) {
            require_once VIEW_DIR . '/' . $view . '.php';
        } else {
            echo 'File ' . htmlspecialchars($view) . ' not found!';
        }
    }
}