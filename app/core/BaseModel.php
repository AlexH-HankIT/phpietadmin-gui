<?php
namespace app\core;

use app\models,
    app\models\logging;

class BaseModel {
    public $database;
    public $logging;
    public $std;

    public function __construct() {
        $registry = Registry::getInstance();

        // get dependencies for models
        try {
            $this->database = $registry->get('database');
            $this->logging = $registry->get('logging');
            $this->std = $registry->get('std');
        } catch (\Exception $e) {
            die('<h1>' . $e->getMessage() . '</h1>');
        }
    }
}