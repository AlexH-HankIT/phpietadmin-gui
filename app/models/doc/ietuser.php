<?php use phpietadmin\app\models;

// require the class
require_once __DIR__ . '/../autoloader.php';

// Adds/deletes a user to/from the database and performs error handling and duplication checks


// Example add user
$user = new models\Ietuser('user1');
$user->add_user_to_db('password');
print_r($user->get_action_result());

// Success output:
/*
Array
(
    [message] => The user was successfully added!
    [code] => 0
    [code_type] => intern
    [method] => phpietadmin\app\models\Ietuser::add_user_to_db
)
*/

// Failure output:
/*
Array
(
    [message] => Already added!
    [code] => 4
    [code_type] => intern
    [method] => phpietadmin\app\models\Ietuser::add_user_to_db
)
*/

// Example delete user
$user = new models\Ietuser('user2');
$user->delete_user_from_db();
print_r($user->get_action_result());

// Success output:
/*
Array
(
    [message] => The user was successfully deleted!
    [code] => 0
    [code_type] => intern
    [method] => phpietadmin\app\models\Ietuser::delete_user_from_db
)
*/