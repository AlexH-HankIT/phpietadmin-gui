<?php
namespace app\controllers;

use app\core,
    app\models;

class Auth extends core\BaseController {
    /**
     *
     * Handles the user login
     *
     * @return     void
     *
     */
    public function login() {
        if (isset($_POST['username'], $_POST['password1'])) {
            // filter user input
            $username = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
            $password1 = filter_input(INPUT_POST, 'password1', FILTER_SANITIZE_STRING);

            // create session object
            $session = $this->model('Session', $username);

            // login user
            $return = $session->login($password1);

            if ($return === true) {
                if (models\Misc::IsXHttpRequest() === true) {
                    echo json_encode(array(
                        'url' => WEB_PATH . '/dashboard',
                        'status' => 'success'
                    ));
                } else {
                    header('Location: ' . WEB_PATH . '/dashboard');
                }
                die();
            } else {
                if (models\Misc::IsXHttpRequest() === true) {
                    echo json_encode(array(
                        'message' => 'Wrong username or password!',
                        'status' => 'failure'
                    ));
                } else {
                    $this->view('message', 'Wrong username or password!');
                    header('refresh:2;url=' . WEB_PATH . '/auth/login');
                }
                die();
            }
        } else {
            $this->view('login');
        }
    }

    /**
     *
     * Handles the logout
     *
     * @return      void
     *
     */
    public function logout() {
        $session = $this->model('Session');
        $session->logout();
        header('Location: ' . WEB_PATH . '/auth/login');
        die();
    }
}