<?php
use phpietadmin\app\models\target;

// require the class
require_once __DIR__ . '/../../autoloader.php';

// Create object
// if iqn doesn't exist, it will be created
$target = new target\Target('iqn.2014-12.com.example.iscsi:test1');

$data = $target->get_acls();

print_r($data);