<?php
require_once __DIR__ . '/../app/core/const.inc.php';
require_once MODEL_DIR . '/Misc.php';

use app\models,
    app\core;

// Register autoloader
spl_autoload_register('app\models\Misc::loader');

if (is_writable(DB_FILE)) {
    $registry = core\Registry::getInstance();
    $registry->set('database', new models\Database());
    $registry->set('logging', new models\logging\Logging());

    $userModel = new app\models\User();
    $users = $userModel->returnData();

    if ($users === false) {
        die("No users found!\n");
    } else {
        $id = '';
        do {
            // list user
            foreach ($users as $user) {
                echo $user['user_id'] . ' ' . $user['username'] . "\n";
            }

            echo "Please enter the id of the user, whose password should be reset: \n";
            $id = readline("ID: ");

            // Validate
            if (!is_numeric($id)) {
                $id = '';
            } else {
                $key = app\models\Misc::recursiveArraySearch(intval($id), $users);

                if ($key !== false) {
                    if (isset($users[$key])) {
                        echo "Warning - password will be visible!\n";
                        $password1 = readline("New password: ");
                        if (!empty($password1)) {
                            $password2 = readline("Repeat: ");
                            if ($password1 === $password2) {
                                $user = new app\models\User($users[$key]['username']);
                                $user->change($password1);
                                echo $user->logging->get_action_result()['message'] . "\n";
                            } else {
                                die("Passwords do not match!\n");
                            }
                        } else {
                            die("Password cannot be empty!\n");
                        }
                    } else {
                        $id = '';
                    }
                } else {
                    $id = '';
                }
            }
        } while (empty($id));
    }
} else {
    die("Database not found or not writable!");
}