<?php namespace phpietadmin\app\core;
	use phpietadmin\app\models,
		phpietadmin\app\models\logging;

class BaseModel {
	public $database;
	public $logging;
	public $std;

	public function __construct() {
		$registry = Registry::getInstance();

		// get dependencies for models
		$this->database = $registry->get('database');
		$this->logging = $registry->get('logging');
		$this->std = $registry->get('std');
	}
}