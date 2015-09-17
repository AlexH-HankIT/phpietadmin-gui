<?php use phpietadmin\app\models;

// require the class
require_once __DIR__ . '/registry.php';

$user = new models\User('admin');

$user->add('Aichach78');

print_r($user->logging->get_action_result());