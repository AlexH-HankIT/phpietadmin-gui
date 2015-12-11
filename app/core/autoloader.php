<?php
require_once 'const.inc.php';

spl_autoload_register(function ($class) {
	require_once BASE_DIR . '/' . str_replace('\\', '/', $class) . '.php';
});