<?php
require_once __DIR__ . '/../app/core/const.inc.php';
require_once MODEL_DIR . '/Misc.php';

// Register autoloader
spl_autoload_register('app\models\Misc::loader');

if (is_writable(DB_FILE)) {
    $database = new app\models\Database();
    $users = $database->get_phpietadmin_user();

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
            $key = app\models\Misc::recursive_array_search(intval($id), $users);

            if ($key !== false) {
                if (isset($users[$key])) {
                    $password1 = readline("New password: ");

                    if (!empty($password1)) {
                        $password2 = readline("Repeat: ");
                        if (!empty($password2)) {
                            if ($password1 === $password2) {
                                $password = password_hash($password1, PASSWORD_BCRYPT);
                                $database->updatePhpietadminUserPassword($password, $users[$key]['username']);
                            } else {
                                // error
                            }
                        } else {
                            // error
                        }
                    } else {
                        // error
                    }
                } else {
                    $id = '';
                }
            } else {
                $id = '';
            }
        }
    } while (empty($id));

} else {
    // error
}