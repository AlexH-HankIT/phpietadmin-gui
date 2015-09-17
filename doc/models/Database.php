<?php use phpietadmin\app\models;

// require the class
require_once __DIR__ . '/../../app/models/autoloader.php';

$database = new models\Database();

$data = $database->get_config('iqn');

print_r($data);

// output:
/*
Array
(
    [option] => iqn
    [value] => iqn.2014-12.com.example.iscsi
    [type] => generic
    [category] => iet
    [description] => Names of the iscsi targets
    [field] => input
    [editable_via_gui] => 1
    [optioningui] => IQN
)
*/