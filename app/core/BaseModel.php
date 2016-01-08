<?php
namespace app\core;

use app\models,
    app\models\logging;

/**
 * This classed provides basic functions, which are used in almost every model.
 *
 * @package app\core
 * @throws \Exception if the selected registry element is not found
 */
class BaseModel {
    /** @var object $database Object for database access */
    public $database;

    /** @var object $logging Object for logging (read and write) */
    public $logging;

    public function __construct() {
        $registry = Registry::getInstance();

        // get dependencies for models
        try {
            $this->database = $registry->get('database');
            $this->logging = $registry->get('logging');
        } catch (\Exception $e) {
            die('<h1>' . $e->getMessage() . '</h1>');
        }
    }
}